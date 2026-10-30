<?php
declare(strict_types=1);

// Derived files only: the CMS keeps the original image and its existing path.
function media_variant(string $source): ?string {
    if (!preg_match('~^assets/(images|uploads)/[a-zA-Z0-9 _./()-]+\.(png|jpe?g|webp)$~i', $source) || str_contains($source, '..')) return null;
    $file = __DIR__ . '/../' . $source;
    if (!is_file($file)) return null;
    return 'assets/optimized/' . substr(sha1($source), 0, 16) . '-' . filemtime($file) . '-' . filesize($file) . '.webp';
}

function media_variants(string $source): array {
    $variant=media_variant($source);return $variant?[$variant,preg_replace('/\.webp$/','.png',$variant)]:[];
}
function media_url(string $source): string {
    $best=$source;$size=is_file(__DIR__.'/../'.$source)?filesize(__DIR__.'/../'.$source):PHP_INT_MAX;
    foreach(media_variants($source) as $variant)if(is_file(__DIR__.'/../'.$variant)&&filesize(__DIR__.'/../'.$variant)<$size){$best=$variant;$size=filesize(__DIR__.'/../'.$variant);}
    return $best;
}

function media_generate(string $source): void {
    $variant = media_variant($source);
    if(!$variant)return;
    $webp=function_exists('imagewebp');if(!$webp)$variant=preg_replace('/\.webp$/','.png',$variant);
    if(is_file(__DIR__.'/../'.$variant))return;
    if(str_ends_with(strtolower($source),'.webp')&&!function_exists('imagecreatefromwebp'))return;
    $directory = __DIR__ . '/../assets/optimized';
    if (!is_dir($directory) || !is_writable($directory)) return;
    $input = @imagecreatefromstring(file_get_contents(__DIR__ . '/../' . $source));
    if (!$input) return;
    $limit = stripos($source, 'logo') !== false ? 640 : 1440;
    $width = imagesx($input); $height = imagesy($input);
    $scale = min(1, $limit / max($width, $height));
    $output = imagecreatetruecolor(max(1, (int)round($width * $scale)), max(1, (int)round($height * $scale)));
    imagealphablending($output, false); imagesavealpha($output, true);
    imagecopyresampled($output, $input, 0, 0, 0, 0, imagesx($output), imagesy($output), $width, $height);
    $temporary = tempnam($directory, 'image-');
    if ($temporary !== false) {
        if(($webp?imagewebp($output,$temporary,82):imagepng($output,$temporary,9)) && filesize($temporary)<filesize(__DIR__.'/../'.$source)){chmod($temporary,0644);rename($temporary,__DIR__.'/../'.$variant);}else unlink($temporary);
    }
    imagedestroy($input); imagedestroy($output);
}
function media_srcset(string $source): string {
    $base=media_variant($source);if(!$base)return '';
    $responsive=preg_replace('/\.webp$/','-520.webp',$base);
    if(!is_file(__DIR__.'/../'.$responsive))return '';
    $size=@getimagesize(__DIR__.'/../'.$source);if(!$size)return '';
    return $responsive.' 520w, '.media_url($source).' '.$size[0].'w';
}
