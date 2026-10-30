<?php
declare(strict_types=1);

require_once __DIR__ . '/site-url.php';

function seo_absolute(string $path): string {
    return preg_match('~^https?://~i', $path) ? $path : seo_base() . ltrim($path, '/');
}

$seoSlug = $seoPath ?? $currentPage['slug'] ?? 'index';
$canonicalUrl = seo_base() . ($seoSlug === 'index' ? '' : $seoSlug);
$isSeoError=http_response_code()>=400;
$seoNoIndex=!empty($noIndex)||$isSeoError||seo_is_preview();
$socialSource=media_url(isset($productDetail)?$productDetail['image']:(isset($categoryDetail)?$categoryDetail['image']:content_value('hero_main_image','index')));
$socialImage=seo_absolute($socialSource);
$socialSize=is_file(__DIR__.'/../'.$socialSource)?@getimagesize(__DIR__.'/../'.$socialSource):false;
$organization = [
    '@type' => 'Organization', '@id' => seo_base() . '#organization',
    'name' => $siteName, 'url' => seo_base(),
    'logo' => seo_absolute(media_url(setting('logo'))),
    'email' => setting('email'), 'telephone' => setting('phone'),
    'address' => setting('address'),
    'contactPoint' => ['@type' => 'ContactPoint', 'contactType' => 'sales', 'email' => setting('email'), 'telephone' => setting('phone')],
    'sameAs' => array_values(array_filter([setting('facebook'), setting('instagram'), setting('linkedin')])),
];
$structuredData = ['@context' => 'https://schema.org', '@graph' => [
    $organization,
    ['@type' => 'WebSite', '@id' => seo_base() . '#website', 'url' => seo_base(), 'name' => $siteName, 'publisher' => ['@id' => seo_base() . '#organization']],
    ['@type' => 'WebPage', '@id' => $canonicalUrl . '#webpage', 'url' => $canonicalUrl,
     'name' => $pageTitle . ' | ' . $siteName, 'description' => $pageDescription,
     'inLanguage' => 'en', 'isPartOf' => ['@id' => seo_base() . '#website']],
]];
$structuredData['@graph'][2]['@type'] = isset($productDetail) ? 'ItemPage' : (isset($categoryDetail) ? 'CollectionPage' : match ($seoSlug) {
    'about' => 'AboutPage', 'contact' => 'ContactPage', 'products', 'certificates' => 'CollectionPage', default => 'WebPage'
});
if (isset($productDetail)) {
    $breadcrumbId = $canonicalUrl . '#breadcrumb';
    $structuredData['@graph'][2]['breadcrumb'] = ['@id' => $breadcrumbId];
    $structuredData['@graph'][] = ['@type' => 'BreadcrumbList', '@id' => $breadcrumbId, 'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => content_value('nav_home','site'), 'item' => seo_base()],
        ['@type' => 'ListItem', 'position' => 2, 'name' => content_value('copy_c78c14c55d','products'), 'item' => seo_base() . 'products'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $productDetail['name'], 'item' => $canonicalUrl],
    ]];
} elseif ($seoSlug === 'products' || isset($categoryDetail)) {
    $items = [];
    foreach (($categoryProducts??$products) as $position => $item) $items[] = ['@type' => 'ListItem', 'position' => $position + 1, 'name' => $item['name'], 'url' => seo_base() . 'products/' . $item['slug']];
    $structuredData['@graph'][2]['mainEntity'] = ['@type' => 'ItemList', 'itemListElement' => $items];
}
if(!isset($productDetail)&&$seoSlug!=='index'&&!$isSeoError){
    $breadcrumbId=$canonicalUrl.'#breadcrumb';
    $structuredData['@graph'][2]['breadcrumb']=['@id'=>$breadcrumbId];
    $structuredData['@graph'][]=['@type'=>'BreadcrumbList','@id'=>$breadcrumbId,'itemListElement'=>[
        ['@type'=>'ListItem','position'=>1,'name'=>content_value('nav_home','site'),'item'=>seo_base()],
        ['@type'=>'ListItem','position'=>2,'name'=>$currentPage['data']['title']??$pageTitle,'item'=>$canonicalUrl],
    ]];
}
if(isset($productDetail)){
    $productId=$canonicalUrl.'#product';$structuredData['@graph'][2]['mainEntity']=['@id'=>$productId];
    $properties=[];foreach($productDetail['specifications']??[] as $label=>$value)$properties[]=['@type'=>'PropertyValue','name'=>$label,'value'=>$value];
    $properties[]=['@type'=>'PropertyValue','name'=>'Packaging','value'=>$productDetail['packing']];
    $structuredData['@graph'][]=['@type'=>'Product','@id'=>$productId,'name'=>$productDetail['name'],'description'=>$productDetail['description'],'image'=>$socialImage,'category'=>$productDetail['category'],'countryOfOrigin'=>['@type'=>'Country','name'=>$productDetail['origin']],'additionalProperty'=>$properties,'mainEntityOfPage'=>['@id'=>$canonicalUrl.'#webpage']];
}
?>
    <?php if(!$isSeoError): ?><link rel="canonical" href="<?= e($canonicalUrl) ?>"><?php endif; ?>
    <meta name="robots" content="<?= $seoNoIndex ? 'noindex, follow' : 'index, follow, max-image-preview:large' ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_IN">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:title" content="<?= e($pageTitle . ' | ' . $siteName) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($socialImage) ?>">
    <?php if(str_starts_with($socialImage,'https://')): ?><meta property="og:image:secure_url" content="<?= e($socialImage) ?>"><?php endif; ?>
    <?php if($socialSize): ?><meta property="og:image:type" content="<?= e($socialSize['mime']) ?>"><meta property="og:image:width" content="<?= (int)$socialSize[0] ?>"><meta property="og:image:height" content="<?= (int)$socialSize[1] ?>"><?php endif; ?>
    <meta property="og:image:alt" content="<?= e(isset($productDetail) ? $productDetail['name'] : (isset($categoryDetail)?$categoryDetail['name']:$siteName . ' — agricultural exports')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle . ' | ' . $siteName) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription) ?>">
    <meta name="twitter:image" content="<?= e($socialImage) ?>">
    <meta name="twitter:image:alt" content="<?= e(isset($productDetail)?$productDetail['name']:$siteName.' agricultural ingredients') ?>">
    <link rel="sitemap" type="application/xml" href="<?= e(seo_base() . 'sitemap.xml') ?>">
    <?php if(!$isSeoError): ?><script type="application/ld+json"><?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) ?></script><?php endif; ?>
