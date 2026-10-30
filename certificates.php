<?php
$pageTitle = 'Certificates';
$pageDescription = 'View and download Parthvi Exim GST, Importer-Exporter Code (IEC), Udyam registration certificates, and FSSAI license.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero certificates-hero">
    <div class="container">
        <span class="eyebrow"><?php echo page_text('span_1'); ?></span>
        <h1><?php echo page_text('h1_1'); ?></h1>
        <p><?php echo page_text('p_1'); ?></p>
    </div>
</section>

<section class="section-pad certificates-page">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($certificates as $certificate) : ?>
            <div class="col-md-6">
                <article class="certificate-card certificate-document">
                    <a class="certificate-preview" href="<?php echo e($certificate['file']); ?>" target="_blank" rel="noopener noreferrer" aria-label="View <?php echo e($certificate['title']); ?> PDF (opens in a new tab)">
                        <?php if ($certificate['preview']): ?><img loading="lazy" decoding="async" src="<?php echo e(media_url($certificate['preview'])); ?>" alt="<?= e('First page of '.$siteName.' '.$certificate['title']) ?>"><?php else: ?><span class="certificate-placeholder"><?= content_text('copy_c698a3be1d', 'certificates') ?><span><?= content_text('copy_41d08b2d95', 'certificates') ?></span></span><?php endif; ?>
                    </a>
                    <div class="certificate-details">
                        <span class="eyebrow dark"><?php echo e($certificate['label']); ?> · PDF</span>
                        <h2><?php echo e($certificate['title']); ?></h2>
                        <p><?php echo e($certificate['description']); ?></p>
                        <div class="certificate-actions">
                            <a class="btn btn-primary" href="<?php echo e($certificate['file']); ?>" target="_blank" rel="noopener noreferrer" aria-label="View <?php echo e($certificate['title']); ?> PDF (opens in a new tab)"><?= content_text('view_pdf_label','certificates') ?></a>
                            <a class="btn btn-outline-primary" href="<?php echo e($certificate['file']); ?>" download aria-label="Download <?php echo e($certificate['title']); ?>"><?= content_text('download_label','certificates') ?></a>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="certificate-support text-center mt-5">
            <h2><?php echo page_text('h2_1'); ?></h2>
            <p><?php echo page_text('p_2'); ?></p>
            <a href="contact" class="btn btn-primary"><?= content_text('copy_ba515d58e3', 'certificates') ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
