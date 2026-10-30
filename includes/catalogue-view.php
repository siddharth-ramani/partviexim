<?php
// Shared product presentation for the home and catalogue pages.
function product_card(array $product): void { ?>
<article class="catalogue-card" data-category="<?= e($product['category']) ?>" data-search="<?= e(strtolower($product['name'].' '.$product['category'].' '.$product['short'])) ?>">
    <a class="product-photo" href="products/<?= e($product['slug']) ?>" tabindex="-1" aria-hidden="true"><img loading="lazy" decoding="async" width="400" height="400" src="<?= e(media_url($product['image'])) ?>" srcset="<?= e(media_srcset($product['image'])) ?>" sizes="(max-width:575px) 45vw, (max-width:991px) 45vw, 280px" alt="<?= e($product['name']) ?>"></a>
    <div class="catalogue-card-body"><span class="eyebrow"><?= e($product['category']) ?></span><h3><a href="products/<?= e($product['slug']) ?>"><?= e($product['name']) ?></a></h3><p><?= e($product['short']) ?></p><a class="card-link" href="products/<?= e($product['slug']) ?>"><?= content_text('product_link_label','site') ?> <span aria-hidden="true">↗</span></a></div>
</article>
<?php }
function catalogue_cta(): void { if(!content_on('show_cta','site'))return; ?>
<section class="fresh-cta"><div class="container"><div><span class="eyebrow dark"><?= content_text('copy_075efbca80', 'site') ?></span><h2><?= content_text('copy_9ab3a01287', 'site') ?><br><?= content_text('copy_02edeb90b4', 'site') ?></h2><p><?= content_text('copy_106986fa82', 'site') ?></p></div><a href="<?= content_text('cta_link','site') ?>" class="btn btn-primary"><?= content_text('copy_9a56902bc1', 'site') ?> <span aria-hidden="true">↗</span></a></div></section>
<?php }
