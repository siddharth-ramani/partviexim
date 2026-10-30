<?php
require_once __DIR__.'/includes/cms.php';
header('Content-Type: text/css; charset=UTF-8');
$defaults=['theme_primary'=>'#173e32','theme_accent'=>'#80572d','theme_background'=>'#fdfcf8'];$colors=[];
foreach($defaults as $key=>$fallback){$value=content_value($key,'site');$colors[$key]=preg_match('/^#[0-9a-fA-F]{6}$/',$value)?strtolower($value):$fallback;}
if($colors===$defaults){echo '/* Default website palette. */';exit;}
$p=$colors['theme_primary'];$a=$colors['theme_accent'];$b=$colors['theme_background'];
?>
:root{--navy:<?= $p ?>;--blue:<?= $p ?>;--teal:<?= $p ?>;--ink:<?= $p ?>;--white:<?= $b ?>;--surface:color-mix(in srgb,<?= $p ?> 5%,white)}
body{background:<?= $b ?>;color:<?= $p ?>}h1,h2,h3,h4,.fresh-hero h1,.page-hero h1,.text-link,.catalogue-card h3 a,.category-info h3,.detail-specs th{color:<?= $p ?>}
.top-strip,.footer-section,.editorial-about,.contact-panel,.btn-primary,.filter-chip.active,.products-menu .dropdown-item:active{background:<?= $p ?>;border-color:<?= $p ?>}
.btn-primary{--bs-btn-bg:<?= $p ?>;--bs-btn-border-color:<?= $p ?>;--bs-btn-hover-bg:<?= $p ?>;--bs-btn-hover-border-color:<?= $p ?>;--bs-btn-active-bg:<?= $p ?>}
.btn-outline-primary{--bs-btn-color:<?= $p ?>;--bs-btn-border-color:<?= $p ?>;--bs-btn-hover-bg:<?= $p ?>;--bs-btn-hover-border-color:<?= $p ?>}
.eyebrow,.eyebrow.dark,.page-hero .eyebrow,.navbar .nav-link.active,.navbar .nav-link:hover,.fresh-hero h1 em,.step-number{color:<?= $a ?>}
.fresh-hero,.page-hero,.fresh-cta,.category-tile,.catalogue-card .product-photo,.about-picture,.detail-visual{background:color-mix(in srgb,<?= $p ?> 7%,<?= $b ?>)}
.category-tile:nth-child(3n+2),.catalogue-card:nth-child(3n+2) .product-photo{background:color-mix(in srgb,<?= $a ?> 7%,<?= $b ?>)}
.category-tile:nth-child(3n),.catalogue-card:nth-child(3n) .product-photo,.hero-orbit{background:color-mix(in srgb,<?= $p ?> 12%,<?= $b ?>)}
.surface-section,.contact-page,.certificates-page{background:color-mix(in srgb,<?= $p ?> 4%,<?= $b ?>)}
.editorial-about h2,.contact-panel h2{color:#f4f0df}.editorial-about .eyebrow,.contact-panel .eyebrow,.contact-panel .eyebrow.dark{color:#d2aa70}
