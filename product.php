<?php
require_once __DIR__ . '/includes/data.php';
$slug = is_string($_GET['slug'] ?? null) ? $_GET['slug'] : '';
$productDetail = null;
foreach ($products as $candidate) if ($candidate['slug'] === $slug) { $productDetail = $candidate; break; }
if (!$productDetail) {
    foreach($products as $candidate)if(in_array($slug,$candidate['previous_slugs']??[],true)){header('Location: '.cms_base().'products/'.$candidate['slug'],true,301);exit;}

    http_response_code(404);
    header('X-Robots-Tag: noindex');
    $useSiteBase = true;
    $noIndex = true;
    $seoPath = 'products';
    $currentPage = ['slug'=>'product-not-found','data'=>['title'=>'Product not found','description'=>'This product is unavailable. Explore our current ingredient collection.']];
    require __DIR__ . '/includes/header.php';
    echo '<section class="page-hero"><div class="container"><span class="eyebrow">'.content_text('not_found_small','site').'</span><h1>'.content_text('not_found_heading','site').'</h1><p>'.content_text('not_found_copy','site').'</p><a class="btn btn-primary" href="products">'.content_text('not_found_button','site').'</a></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$currentPage = cms_page('products');
if (basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '') === 'product.php') {
    header('Location: ' . cms_base() . 'products/' . $productDetail['slug'], true, 301);
    exit;
}
$currentPage['data']['title'] = $productDetail['name'] . ' Exporter';
$currentPage['data']['seo_title'] = $productDetail['seo_title'] ?? $currentPage['data']['title'];
$currentPage['data']['description'] = 'Explore ' . $productDetail['name'] . ' from ' . $productDetail['origin'] . '. View available grades, packaging and applications. Contact ' . $siteName . ' for export inquiries.';
$currentPage['data']['seo_description'] = $productDetail['seo_description'] ?? $currentPage['data']['description'];
$seoPath = 'products/' . $productDetail['slug'];
require __DIR__ . '/products.php';
