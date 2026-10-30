<?php
require_once __DIR__.'/../includes/cms.php';
cms_session();
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store, private');header('X-Content-Type-Options: nosniff');header('X-Frame-Options: DENY');header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
function admin_user(): ?array {
    if(empty($_SESSION['admin']))return null;
    $r=cms_db()->query('SELECT * FROM admins WHERE id=1')->fetch();
    if(!$r || ($_SESSION['admin_version']??0)!==$r['version'] || time()-($_SESSION['admin_seen']??0)>3600){unset($_SESSION['admin']);return null;}
    $_SESSION['admin_seen']=time();return $r;
}
function admin_require(): array { $u=admin_user();if(!$u){header('Location: login.php');exit;}return $u; }
function admin_csrf(): string {return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">';}
function admin_redirect(string $path,string $message=''): void {if($message)$_SESSION['flash']=$message;header('Location: '.$path);exit;}
function post_text(string $key,int $max=20000,bool $required=false): string {
    $v=$_POST[$key]??'';if(!is_string($v))throw new RuntimeException('Invalid form value.');$v=trim($v);
    if(mb_strlen($v)>$max)throw new RuntimeException('The '.$key.' field is too long.');
    if($required&&$v==='')throw new RuntimeException('Please complete the '.$key.' field.');return $v;
}
function field(string $key,string $label,$value='',string $type='text',bool $required=false,string $hint=''): void {
    echo '<div class="field"><label for="'.e($key).'">'.e($label).($required?' <span aria-hidden="true">*</span>':'').'</label>';
    if($type==='textarea')echo '<textarea id="'.e($key).'" name="'.e($key).'" rows="5"'.($required?' required':'').'>'.e($value).'</textarea>';
    else echo '<input id="'.e($key).'" name="'.e($key).'" type="'.e($type).'" value="'.e($value).'"'.($required?' required':'').($type==='password'?' autocomplete="new-password"':'').'>';
    if($hint)echo '<small>'.e($hint).'</small>';echo '</div>';
}
function upload_field(string $key,string $label,string $value='',bool $pdf=false,int $pdfLimit=12): void {
    $webpAllowed=$key!=='favicon'||function_exists('imagecreatefromwebp');
    echo '<div class="field upload-field"><label for="'.e($key).'">'.e($label).'</label>';
    if($value){if(!$pdf)echo '<img class="asset-preview" src="../'.e($value).'" alt="Current '.e($label).'">';else echo '<a class="text-link" href="../'.e($value).'" target="_blank" rel="noopener">View current PDF ↗</a>';}
    echo '<input id="'.e($key).'" type="file" name="'.e($key).'" accept="'.($pdf?'application/pdf':($webpAllowed?'image/png,image/jpeg,image/webp':'image/png,image/jpeg')).'"><small>'.($pdf?'PDF, up to '.$pdfLimit.' MB.':($webpAllowed?'JPG, PNG or WebP, up to 6 MB.':'JPG or PNG, up to 6 MB.')).' Leave empty to keep the current file.</small></div>';
}
function admin_top(string $title,string $section,string $subtitle=''): void {
    $flash=$_SESSION['flash']??'';unset($_SESSION['flash']);
    $links=['dashboard'=>['Overview','index.php'],'products'=>['Products','index.php?section=products'],'categories'=>['Categories','index.php?section=categories'],'certificates'=>['Certificates','index.php?section=certificates'],'pages'=>['Pages & appearance','index.php?section=pages'],'settings'=>['Website settings','index.php?section=settings'],'values'=>['Core values','index.php?section=values'],'benefits'=>['Why choose us','index.php?section=benefits'],'inquiries'=>['Inquiry inbox','index.php?section=inquiries'],'help'=>['How to manage','index.php?section=help'],'account'=>['My account','index.php?section=account']];
    ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title><?php echo e($title); ?> | Parthvi Admin</title><?php require __DIR__ . '/../includes/favicon.php'; ?><link rel="stylesheet" href="admin.css?v=<?php echo filemtime(__DIR__.'/admin.css'); ?>"><script src="admin.js?v=<?php echo filemtime(__DIR__.'/admin.js'); ?>" defer></script></head><body>
    <a class="skip" href="#workspace">Skip to content</a><div class="admin-shell"><aside class="sidebar" id="sidebar"><a class="admin-brand" href="index.php"><span class="brand-symbol">P<span>↗</span></span><span>Parthvi Exim<small>WEBSITE MANAGER</small></span></a><p class="nav-label">WORKSPACE</p><nav aria-label="Admin navigation"><?php foreach($links as $k=>$l):?><a class="side-link <?php echo $section===$k?'active':''; ?>" href="<?php echo e($l[1]); ?>"<?php echo $section===$k?' aria-current="page"':''; ?>><span class="nav-dot" aria-hidden="true"></span><?php echo e($l[0]); ?></a><?php endforeach;?></nav><div class="sidebar-bottom"><a href="../" target="_blank" rel="noopener">View website ↗</a><form action="logout.php" method="post"><?php echo admin_csrf(); ?><button class="logout" type="submit">Sign out</button></form></div></aside>
    <div class="main-shell"><header class="topbar"><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="sidebar">☰ <span>Menu</span></button><span class="topbar-label">Your website, in one place</span><div class="account-chip"><span>A</span> Administrator</div></header><main id="workspace" class="workspace"><div class="page-heading"><div><p class="kicker">PARTHVI EXIM / <?php echo e(strtoupper($section)); ?></p><h1><?php echo e($title); ?></h1><p><?php echo e($subtitle); ?></p></div><a class="button secondary" href="../" target="_blank" rel="noopener">View website ↗</a></div><?php if($flash):?><div class="notice success" role="status"><?php echo e($flash); ?></div><?php endif;
}
function admin_bottom():void {echo '</main><footer class="admin-footer">Parthvi Exim · Website management</footer></div></div></body></html>';}

function category_field(string $value):void {
    echo '<div class="field"><label for="category">Product category *</label><input id="category" name="category" list="category-options" value="'.e($value).'" required><datalist id="category-options">';
    foreach(cms_records('categories',false) as $r)echo '<option value="'.e($r['data']['name']).'">';
    echo '</datalist><small>Choose an existing category, or type a new name to create one. Manage names and images in Categories.</small></div>';
}
