<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/media.php';
require_once __DIR__ . '/seo-defaults.php';

function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
// Stored timestamps remain UTC so existing records retain their correct instant.
function cms_india_time(string $utc, string $format = 'd M Y, h:i A'): string {
    $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $utc, new DateTimeZone('UTC'));
    return $date ? $date->setTimezone(new DateTimeZone('Asia/Kolkata'))->format($format) : $utc;
}
function cms_db(): PDO {
    static $db;
    if ($db instanceof PDO) return $db;
    if (!extension_loaded('pdo_mysql')) throw new RuntimeException('Enable PHP PDO MySQL on your hosting.');
    $config = require __DIR__ . '/../config/database.php';
    $db = new PDO('mysql:host='.$config['host'].';port='.$config['port'].';dbname='.$config['name'].';charset=utf8mb4', $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ]);
    $db->exec("SET time_zone = '+00:00'");
    return $db;
}
function cms_settings_row(): array { $r=cms_db()->query('SELECT * FROM settings WHERE id=1')->fetch();$r['data']=json_decode($r['data'],true,512,JSON_THROW_ON_ERROR);return $r; }
function cms_records(string $type, bool $public=true): array {
    $q=cms_db()->prepare('SELECT * FROM records WHERE type=?'.($public?' AND published=1':'').' ORDER BY position,id');$q->execute([$type]);
    $items=[];foreach($q as $r){$r['data']=json_decode($r['data'],true,512,JSON_THROW_ON_ERROR);$items[]=$r;}return $items;
}
function cms_page(string $slug): array {
    $q=cms_db()->prepare('SELECT * FROM pages WHERE slug=?');$q->execute([$slug]);$r=$q->fetch();
    if(!$r) return ['slug'=>$slug,'data'=>['fields'=>[]],'version'=>0];
    $r['data']=json_decode($r['data'],true,512,JSON_THROW_ON_ERROR);return $r;
}
function setting(string $key): string { global $siteSettings; return (string)($siteSettings[$key]??''); }
function page_text(string $key): string { global $currentPage; return e($currentPage['data']['fields'][$key]['value']??''); }
function cms_session(): void {
    if(session_status()===PHP_SESSION_ACTIVE)return;
    ini_set('session.use_strict_mode','1');ini_set('session.use_only_cookies','1');
    session_name('parthvi_session');
    session_set_cookie_params(['lifetime'=>0,'path'=>cms_base(),'secure'=>!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off','httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
function cms_base(): string {
    $path=str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']??'/'));
    if(basename($path)==='admin')$path=dirname($path);
    return rtrim($path,'/.').'/';
}
function csrf_token(): string { cms_session();if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf']; }
function csrf_check(): void {
    cms_session();if(!is_string($_POST['csrf']??null)||!hash_equals(csrf_token(),$_POST['csrf'])){http_response_code(403);throw new RuntimeException('This form has expired. Refresh the page and try again.');}
}
function rate_limited(string $key,int $limit,int $seconds): bool {
    $db=cms_db();$db->beginTransaction();
    try {
        $now=time();
        // The unique key locks this bucket even for concurrent first requests.
        $db->prepare('INSERT INTO rate_limits (`key`,attempts,expires) VALUES (?,0,?) ON DUPLICATE KEY UPDATE `key`=VALUES(`key`)')->execute([$key,$now+$seconds]);
        $q=$db->prepare('SELECT attempts,expires FROM rate_limits WHERE `key`=? FOR UPDATE');$q->execute([$key]);$row=$q->fetch();
        $attempts=(int)$row['expires']<=$now?0:(int)$row['attempts'];
        $expires=(int)$row['expires']<=$now?$now+$seconds:(int)$row['expires'];
        if($attempts>=$limit){$db->commit();return true;}
        $db->prepare('UPDATE rate_limits SET attempts=?,expires=? WHERE `key`=?')->execute([$attempts+1,$expires,$key]);
        $db->commit();return false;
    }catch(Throwable $e){if($db->inTransaction())$db->rollBack();throw $e;}
}
function cms_activity(string $message): void { cms_db()->prepare('INSERT INTO activity(message,created_at) VALUES(?,?)')->execute([$message,gmdate('Y-m-d H:i:s')]); }
function cms_upload(string $field,string $kind,int $pdfLimit=12): ?string {
    $f=$_FILES[$field]??null;if(!$f||$f['error']===UPLOAD_ERR_NO_FILE)return null;
    if(!is_int($f['error'])||$f['error']!==UPLOAD_ERR_OK)throw new RuntimeException('The upload failed. Check your file size and try again.');
    $max=$kind==='pdf'?$pdfLimit*1024*1024:6*1024*1024;
    if($f['size']>$max)throw new RuntimeException($kind==='pdf'?'PDFs must be '.$pdfLimit.' MB or smaller.':'Images must be 6 MB or smaller.');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $dir=__DIR__.'/../assets/uploads/';$name=bin2hex(random_bytes(16));
    if($kind==='pdf'){
        if($mime!=='application/pdf'||file_get_contents($f['tmp_name'],false,null,0,5)!=='%PDF-')throw new RuntimeException('Please upload a valid PDF document.');
        $name.='.pdf';if(!move_uploaded_file($f['tmp_name'],$dir.$name))throw new RuntimeException('Uploads folder is not writable.');
    }else{
        if(!in_array($mime,['image/jpeg','image/png','image/webp'],true))throw new RuntimeException('Use a JPG, PNG or WebP image.');
        $size=getimagesize($f['tmp_name']);if(!$size||$size[0]>6000||$size[1]>6000||$size[0]*$size[1]>16000000)throw new RuntimeException('Image dimensions are too large (maximum 16 megapixels and 6000px per side).');
        if($mime==='image/webp'&&!function_exists('imagecreatefromwebp')){
            if($kind==='favicon')throw new RuntimeException('Use a PNG or JPG for the browser icon on this server.');
            $name.='.webp';
            if(!move_uploaded_file($f['tmp_name'],$dir.$name))throw new RuntimeException('Uploads folder is not writable.');
            return 'assets/uploads/'.$name;
        }
        $image=imagecreatefromstring(file_get_contents($f['tmp_name']));if(!$image)throw new RuntimeException('The image could not be read.');
        if($kind==='favicon'){
            $canvas=imagecreatetruecolor(180,180);imagealphablending($canvas,false);imagesavealpha($canvas,true);
            imagefill($canvas,0,0,imagecolorallocatealpha($canvas,0,0,0,127));
            $scale=min(180/$size[0],180/$size[1]);$w=max(1,(int)round($size[0]*$scale));$h=max(1,(int)round($size[1]*$scale));
            imagecopyresampled($canvas,$image,(int)((180-$w)/2),(int)((180-$h)/2),0,0,$w,$h,$size[0],$size[1]);
            imagedestroy($image);$image=$canvas;
        }
        imagesavealpha($image,true);$name.='.png';$ok=imagepng($image,$dir.$name);imagedestroy($image);if(!$ok)throw new RuntimeException('Uploads folder is not writable.');
    }
    if ($kind !== 'pdf') media_generate('assets/uploads/'.$name);
    return 'assets/uploads/'.$name;
}
function cms_lines(string $text): array { return array_values(array_filter(array_map('trim',preg_split('/\R/',$text)),fn($x)=>$x!=='')); }

require_once __DIR__.'/content.php';

function cms_touch_content_pages(string $type): void {
    $pages=match($type){'products','categories'=>['index','products'],'certificates'=>['certificates'],'values','benefits'=>['about'],default=>[]};
    if(!$pages)return;
    $q=cms_db()->prepare("UPDATE pages SET data=JSON_SET(data, '$._updated_at', ?),version=version+1 WHERE slug IN (".implode(',',array_fill(0,count($pages),'?')).')');
    $q->execute(array_merge([gmdate('c')],$pages));
}
function cms_content_changed(array $before,array $after): bool {
    unset($before['_updated_at'],$after['_updated_at']);return $before!==$after;
}
function cms_mark_modified(array $before,array $after,bool $force=false): array {
    if($force||cms_content_changed($before,$after))$after['_updated_at']=gmdate('c');
    return $after;
}
