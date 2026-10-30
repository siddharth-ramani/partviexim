<?php
require_once __DIR__.'/includes/site-url.php';
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: no-cache');
echo "User-agent: *\n";
if(seo_is_preview()) { echo "Disallow: /\n"; exit; }
$prefix=rtrim(parse_url(seo_base(),PHP_URL_PATH)??'','/');
echo 'Allow: '.$prefix."/assets/\n";
foreach(['admin','includes','config','database','scripts','tests'] as $path)echo 'Disallow: '.$prefix.'/'.$path."/\n";
echo "\nSitemap: ".seo_base()."sitemap.xml\n";
