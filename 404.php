<?php
http_response_code(404);
header('X-Robots-Tag: noindex, follow');
require_once __DIR__.'/includes/data.php';
$useSiteBase=true;$noIndex=true;
$currentPage=['slug'=>'not-found','data'=>['title'=>'Page not found','description'=>'The requested page could not be found. Explore the current Parthvi Exim collection.']];
require __DIR__.'/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow"><?= content_text('page_missing_small','site') ?></span><h1><?= content_text('page_missing_heading','site') ?></h1><p><?= content_text('page_missing_copy','site') ?></p><a class="btn btn-primary" href="products"><?= content_text('page_missing_button','site') ?></a></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
