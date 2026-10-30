<?php require_once __DIR__ . '/data.php';
$pageTitle=$currentPage['data']['title']??$pageTitle??$siteName;
$pageDescription=$currentPage['data']['description']??$pageDescription??'';
$pageTitle=trim($currentPage['data']['seo_title']??'') ?: seo_page_title($currentPage['slug']??'index',$pageTitle);
$pageDescription=trim($currentPage['data']['seo_description']??'') ?: $pageDescription;
require_once __DIR__.'/site-url.php';
if(!empty($noIndex)||http_response_code()>=400||seo_is_preview())header('X-Robots-Tag: noindex, follow');
$skipTarget = (isset($productDetail) || !empty($useSiteBase)) ? (parse_url($_SERVER['REQUEST_URI'] ?? cms_base(), PHP_URL_PATH) ?? cms_base()) : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (isset($productDetail) || !empty($useSiteBase)): ?><base href="<?= e(cms_base()) ?>"><?php endif; ?>
    <title><?php echo e($pageTitle . ' | ' . $siteName); ?></title><?php require __DIR__ . '/favicon.php'; ?>
    <meta name="description" content="<?php echo e($pageDescription); ?>">
    <?php require __DIR__ . '/seo.php'; ?>
    <?php $priorityImage=isset($productDetail)?$productDetail['image']:(isset($categoryDetail)?$categoryDetail['image']:(($currentPage['slug']??'')==='index'&&content_on('show_hero','index')?content_value('hero_main_image','index'):''));if($priorityImage): ?><link rel="preload" as="image" fetchpriority="high" href="<?= e(media_url($priorityImage)) ?>" <?php if(($currentPage['slug']??'')==='index' && media_srcset($priorityImage)): ?>imagesrcset="<?= e(media_srcset($priorityImage)) ?>" imagesizes="(max-width:575px) 245px, (max-width:991px) 310px, 340px"<?php endif; ?>><?php endif; ?>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
    <link rel="stylesheet" href="assets/css/refresh.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/refresh.css'); ?>">
    <?php if(content_has_custom_theme()): ?><link rel="stylesheet" href="theme.php?v=<?= e(cms_page('site')['version']) ?>"><?php endif; ?>
    <?php if(content_value('google_verification','site')!==''): ?><meta name="google-site-verification" content="<?= content_text('google_verification','site') ?>"><?php endif; ?>
</head>
<body>
<a class="skip-link" href="<?= e($skipTarget) ?>#main-content">Skip to content</a>
<header class="site-header sticky-top">
    <div class="top-strip">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span><?php echo e(setting('tagline')); ?></span>
            <div class="social-icons" aria-label="Social media">
                <?php if (setting('facebook')): ?><a href="<?php echo e(setting('facebook')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h3V4h-3c-3 0-5 2-5 5v2H6v4h3v7h4v-7h3l1-4h-4V9c0-.7.3-1 1-1Z"/></svg></a><?php endif; ?>
                <?php if (setting('linkedin')): ?><a href="<?php echo e(setting('linkedin')); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8H2v14h4V8Zm.3-4.3A2.3 2.3 0 1 0 1.7 3.7a2.3 2.3 0 0 0 4.6 0ZM22 14c0-4.2-2.2-6.2-5.2-6.2-2.4 0-3.5 1.3-4.1 2.2V8H9v14h3.9v-7c0-1.8.3-3.6 2.6-3.6s2.4 2.1 2.4 3.7V22H22v-8Z"/></svg></a><?php endif; ?>
                <?php if (setting('instagram')): ?><a href="<?php echo e(setting('instagram')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H7Zm5 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm5-1.2a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Z"/></svg></a><?php endif; ?>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="./">
                <img decoding="async" width="1860" height="845" class="site-logo" src="<?php echo e(media_url(setting('logo'))); ?>" alt="<?php echo e(setting('site_name')); ?>">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link <?php echo activeClass('index.php'); ?>" href="./"><?= content_text('nav_home', 'site') ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo activeClass('about.php'); ?>" href="about"><?= content_text('nav_about', 'site') ?></a></li>
                    <li class="nav-item dropdown products-dropdown">
                        <button class="nav-link dropdown-toggle <?php echo activeClass('products.php'); ?>" id="productsMenuToggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-controls="productsMenu"><?= content_text('nav_products', 'site') ?></button>
                        <ul class="dropdown-menu products-menu" id="productsMenu" aria-labelledby="productsMenuToggle">
                            <li class="products-menu-all"><a class="dropdown-item" href="products"><?= content_text('copy_fc3c3526ef', 'site') ?> <span aria-hidden="true">↗</span></a></li>
                            <?php foreach (array_keys($productCategories) as $category) : ?>
                                <li><a class="dropdown-item" href="<?= e(category_url($category)) ?>"><?= e($category) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link <?php echo activeClass('certificates.php'); ?>" href="certificates"><?= content_text('nav_certificates', 'site') ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo activeClass('contact.php'); ?>" href="contact"><?= content_text('nav_contact', 'site') ?></a></li>
                </ul>
                <a class="btn btn-sm btn-primary ms-lg-3 nav-inquiry" href="contact"><?= content_text('copy_bbaec07aaf', 'site') ?></a>
            </div>
        </div>
    </nav>
</header>
<main id="main-content" tabindex="-1">
