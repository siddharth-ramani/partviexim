<?php
require_once __DIR__.'/cms.php';
try {
    $siteSettings=cms_settings_row()['data'];
    $currentPage=cms_page(pathinfo(basename($_SERVER['PHP_SELF']??'index.php'),PATHINFO_FILENAME));
    $products=array_column(cms_records('products'),'data');
    $products=content_visible_products($products);
    $productCategories=content_categories($products);
    $certificates=array_column(cms_records('certificates'),'data');
    $coreValues=array_column(cms_records('values'),'data');
    $whyChooseUs=array_column(cms_records('benefits'),'data');
} catch(Throwable $ex) {
    error_log($ex->getMessage());http_response_code(503);
    exit('Website setup is incomplete. Please check MySQL configuration and import the supplied database.');
}
$siteName=setting('site_name');$siteTagline=setting('tagline');
$aboutText=setting('about_text');$aboutDetails=setting('about_details');
$missionText=setting('mission');$visionText=setting('vision');$motiveText=setting('motive');
function activeClass($page){$actual=basename($_SERVER['PHP_SELF']);return $actual===$page||(in_array($actual,['product.php','category.php'],true)&&$page==='products.php')?'active':'';}
