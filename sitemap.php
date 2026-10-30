<?php
declare(strict_types=1);
require_once __DIR__.'/includes/site-url.php';
require_once __DIR__.'/includes/cms.php';
header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: no-cache');
$base=seo_base();
$sitemapProducts=content_visible_products(array_column(cms_records('products'),'data'));
$sitemapCategories=content_categories($sitemapProducts);
$settings=cms_settings_row()['data'];$shared=cms_page('site')['data'];
function sitemap_xml(string $value):string{return htmlspecialchars($value,ENT_XML1|ENT_QUOTES,'UTF-8');}
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<?php foreach(seo_public_paths() as $path):$page=cms_page($path===''?'index':$path)['data'];$modified=seo_modified($page,$settings,$shared); ?>
    <url><loc><?= sitemap_xml($base.$path) ?></loc><?php if($modified): ?><lastmod><?= sitemap_xml($modified) ?></lastmod><?php endif; ?></url>
<?php endforeach; ?>
<?php foreach($sitemapCategories as $entry):$item=$entry['data'];if(count($entry['products'])<2||empty($item['slug']))continue;$modified=seo_modified($item,$settings,$shared); ?>
    <url><loc><?= sitemap_xml($base.'categories/'.$item['slug']) ?></loc><?php if($modified): ?><lastmod><?= sitemap_xml($modified) ?></lastmod><?php endif; ?></url>
<?php endforeach; ?>
<?php foreach($sitemapProducts as $item):$modified=seo_modified($item,$settings,$shared); ?>
    <url><loc><?= sitemap_xml($base.'products/'.$item['slug']) ?></loc><?php if($modified): ?><lastmod><?= sitemap_xml($modified) ?></lastmod><?php endif; ?><image:image><image:loc><?= sitemap_xml($base.media_url($item['image'])) ?></image:loc></image:image></url>
<?php endforeach; ?>
</urlset>
