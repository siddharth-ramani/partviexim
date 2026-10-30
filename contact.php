<?php
require_once __DIR__.'/includes/inquiry.php';
$pageTitle = 'Contact us';
$pageDescription = 'Contact Parthvi Exim for Indian ingredient specifications, packaging, quotations and export inquiries.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero contact-hero">
    <div class="container">
        <span class="eyebrow"><?= content_text('copy_2ace936824', 'contact') ?></span>
        <h1><?php echo page_text('h1_1'); ?></h1>
        <p><?= content_text('copy_3e58d71c59', 'contact') ?></p>
    </div>
</section>

<section class="section-pad contact-page">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <form class="inquiry-form" id="inquiry-form" data-sending-label="<?= content_text('sending_label','contact') ?>" action="contact" method="post">
                    <h2><?php echo page_text('h2_1'); ?></h2>
<input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
<?php if ($inquiryError): ?><div class="alert alert-danger" role="alert"><?php echo e($inquiryError); ?></div><?php endif; ?>
<?php if ($inquirySuccess): ?><div class="alert alert-success" role="status"><?php echo e($inquirySuccess); ?></div><?php endif; ?>
<div class="inquiry-trap" aria-hidden="true"><label for="website"><?= content_text('copy_8f2659d046', 'contact') ?></label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
                    <p id="inquiry-help"><?= content_text('copy_782084b905', 'contact') ?></p>
                    <p class="small"><?= content_text('copy_d7797f3377', 'contact') ?> <a href="privacy-policy"><?= content_text('privacy_label','site') ?></a> <?= content_text('copy_9dd83651dc', 'contact') ?></p>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label" for="inquiry-name"><?= content_text('copy_1591c15531', 'contact') ?></label><input class="form-control" type="text" name="name" id="inquiry-name" value="<?php echo inquiry_value('name'); ?>" autocomplete="name" required></div>
                        <div class="col-md-6"><label class="form-label" for="inquiry-email"><?= content_text('copy_db67894b8f', 'contact') ?></label><input class="form-control" type="email" name="email" id="inquiry-email" value="<?php echo inquiry_value('email'); ?>" autocomplete="email" required></div>
                        <div class="col-md-6"><label class="form-label" for="inquiry-phone"><?= content_text('copy_412d7b1bc6', 'contact') ?></label><input class="form-control" type="tel" name="phone" id="inquiry-phone" value="<?php echo inquiry_value('phone'); ?>" autocomplete="tel"></div>
                        <div class="col-md-6"><label class="form-label" for="inquiry-country"><?= content_text('copy_0d65f6c2b9', 'contact') ?></label><input class="form-control" type="text" name="country" id="inquiry-country" value="<?php echo inquiry_value('country'); ?>" autocomplete="country-name"></div>
                        <div class="col-12">
                            <label class="form-label" for="inquiry-product"><?= content_text('copy_15023507e9', 'contact') ?></label>
                            <select class="form-select" name="product" id="inquiry-product" required>
                                <option value=""><?= content_text('copy_a3f44c1511', 'contact') ?></option><option value="general" <?php echo ($_POST['product'] ?? $_GET['product'] ?? '') === 'general' ? 'selected' : ''; ?>><?= content_text('general_inquiry','contact') ?></option>
                                <?php foreach ($products as $product) : ?>
                                    <option value="<?php echo e($product['slug']); ?>" <?php echo (($_POST['product'] ?? $_GET['product'] ?? '') === $product['slug']) ? 'selected' : ''; ?>><?php echo e($product['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label" for="inquiry-company"><?= content_text('copy_7250ecc8f9', 'contact') ?></label><input class="form-control" type="text" name="company" id="inquiry-company" value="<?php echo inquiry_value('company'); ?>" autocomplete="organization"></div>
                        <div class="col-12"><label class="form-label" for="inquiry-quantity"><?= content_text('copy_780ec2478b', 'contact') ?></label><textarea class="form-control" name="quantity" id="inquiry-quantity" rows="4" required placeholder="<?= content_text('quantity_placeholder','contact') ?>"><?php echo inquiry_value('quantity'); ?></textarea></div>
                        <div class="col-12"><button class="btn btn-primary" type="submit"><?= content_text('copy_20818a7541', 'contact') ?></button></div>
                    </div>
                    <p id="inquiry-status" class="small mt-3 mb-0" role="status"></p>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="contact-panel">
                    <span class="eyebrow dark"><?= content_text('copy_1a9350a605', 'contact') ?></span>
                    <h2><?php echo page_text('h2_2'); ?></h2>
                    <div class="contact-line">
                        <strong><?= content_text('copy_05acd41fce', 'contact') ?></strong>
                        <a href="tel:<?php echo e(preg_replace('/[^+0-9]/', '', setting('phone'))); ?>"><?php echo e(setting('phone')); ?></a>
                    </div>
                    <div class="contact-line">
                        <strong><?= content_text('copy_beb8fe7e51', 'contact') ?></strong>
                        <a href="<?php echo e(setting('whatsapp')); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(setting('phone')); ?></a>
                    </div>
                    <div class="contact-line">
                        <strong><?= content_text('copy_8d5e4d2062', 'contact') ?></strong>
                        <a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a>
                    </div>
                    <div class="contact-line">
                        <strong><?= content_text('copy_a7548a97aa', 'contact') ?></strong>
                        <span><?php echo e(setting('address')); ?></span>
                    </div>
                    <div class="social-row">
                        <?php if (setting('facebook')): ?><a href="<?php echo e(setting('facebook')); ?>" target="_blank" rel="noopener noreferrer">Facebook</a><?php endif; ?>
                        <?php if (setting('instagram')): ?><a href="<?php echo e(setting('instagram')); ?>" target="_blank" rel="noopener noreferrer">Instagram</a><?php endif; ?>
                        <?php if (setting('linkedin')): ?><a href="<?php echo e(setting('linkedin')); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
