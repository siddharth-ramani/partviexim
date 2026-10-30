<?php
require_once __DIR__.'/includes/data.php';
$slug=is_string($_GET['slug']??null)?$_GET['slug']:'';$categoryDetail=null;$categoryProducts=[];
foreach($productCategories as $name=>$entry){
    if(($entry['data']['slug']??'')===$slug){$categoryDetail=$entry['data'];$categoryProducts=$entry['products'];break;}
    if(in_array($slug,$entry['data']['previous_slugs']??[],true)){header('Location: '.cms_base().category_url($name),true,301);exit;}
}
if(!$categoryDetail){require __DIR__.'/404.php';exit;}
if(count($categoryProducts)===1){header('Location: '.cms_base().'products/'.$categoryProducts[0]['slug'],true,301);exit;}
if(basename(parse_url($_SERVER['REQUEST_URI']??'',PHP_URL_PATH)??'')==='category.php'){header('Location: '.cms_base().'categories/'.$categoryDetail['slug'],true,301);exit;}
$useSiteBase=true;$seoPath='categories/'.$categoryDetail['slug'];$currentPage=cms_page('products');$currentPage['slug']=$seoPath;
$currentPage['data']['title']=$categoryDetail['name'];$currentPage['data']['seo_title']=trim($categoryDetail['seo_title']??'')?:$categoryDetail['name'].' Products';
$currentPage['data']['description']=$categoryDetail['intro']?:$categoryProducts[0]['short'];$currentPage['data']['seo_description']=trim($categoryDetail['seo_description']??'')?:$currentPage['data']['description'];
$categoryDetail['image']=$categoryDetail['image']?:$categoryProducts[0]['image'];
require __DIR__.'/includes/header.php';require __DIR__.'/includes/catalogue-view.php';
?>
<section class="page-hero"><div class="container"><nav class="product-breadcrumb" aria-label="Breadcrumb"><a href="./"><?= content_text('nav_home','site') ?></a><span>/</span><a href="products"><?= content_text('copy_c78c14c55d','products') ?></a><span>/</span><span aria-current="page"><?= e($categoryDetail['name']) ?></span></nav><div class="row align-items-center g-4"><div class="col-lg-8"><span class="eyebrow"><?= content_text('category_kicker','products') ?></span><h1><?= e($categoryDetail['heading']?:$categoryDetail['name']) ?></h1><p><?= e($categoryDetail['intro']?:$categoryProducts[0]['short']) ?></p><a class="btn btn-primary" href="contact?product=general#inquiry-form"><?= content_text('category_quote','products') ?> ↗</a></div><div class="col-lg-4"><img class="category-hero-image" fetchpriority="high" decoding="async" width="400" height="400" src="<?= e(media_url($categoryDetail['image'])) ?>" alt="<?= e($categoryDetail['name']) ?>"></div></div></div></section>
<section class="section-pad"><div class="container"><div class="section-head"><span class="eyebrow dark"><?= content_text('category_range_label','products') ?></span><h2><?= content_format('category_range_heading',['category'=>$categoryDetail['name']],'products') ?></h2></div><div class="catalogue-grid"><?php foreach($categoryProducts as $p)product_card($p); ?></div></div></section>
<?php if(trim($categoryDetail['buying_notes']??'')!==''): ?><section class="section-pad surface-section"><div class="container category-buying-guide"><div><span class="eyebrow dark"><?= content_text('category_guide_label','products') ?></span><h2><?= content_text('category_guide_heading','products') ?></h2></div><div><?php foreach(preg_split('/\R{2,}/',$categoryDetail['buying_notes']) as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?><div class="category-guide-links"><a href="certificates"><?= content_text('category_certificates','products') ?> ↗</a><a href="about"><?= content_text('category_about','products') ?> ↗</a></div></div></div></section><?php endif; ?>
<?php catalogue_cta();require __DIR__.'/includes/footer.php'; ?>
