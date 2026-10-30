</main>
<footer class="footer-section">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <a class="footer-brand" href="./">
                    <img loading="lazy" decoding="async" width="1860" height="845" class="footer-logo" src="<?php echo e(media_url(setting('logo'))); ?>" alt="<?php echo e(setting('site_name')); ?>">
                </a>
                <p><?php echo e(setting('footer_text')); ?></p>
            </div>
            <div class="col-sm-6 col-lg-3">
                <h3><?= content_text('copy_bc03ff76bb', 'site') ?></h3>
                <a href="about"><?= content_text('copy_5d9d147310', 'site') ?></a>
                <a href="products"><?= content_text('copy_40024478c2', 'site') ?></a>
                <?php $catalogueFile=content_value('catalogue_file','site');if(content_on('show_catalogue','site') && is_file(__DIR__.'/../'.$catalogueFile)): ?><a href="<?= e($catalogueFile) ?>" download><?= content_text('catalogue_label','site') ?> (<?= number_format(filesize(__DIR__.'/../'.$catalogueFile)/1048576,1) ?> MB)</a><?php endif; ?>
                <a href="certificates"><?= content_text('copy_2f1c13920c', 'site') ?></a>
                <a href="contact"><?= content_text('copy_e642947ca3', 'site') ?></a>
            </div>
            <div class="col-sm-6 col-lg-4">
                <h3><?= content_text('copy_d4cf0af9a0', 'site') ?></h3>
                <p><?= content_text('footer_phone','site') ?> <a class="d-inline" href="tel:<?php echo e(preg_replace('/[^+0-9]/', '', setting('phone'))); ?>"><?php echo e(setting('phone')); ?></a></p>
                <p><?= content_text('footer_whatsapp','site') ?> <a class="d-inline" href="<?php echo e(setting('whatsapp')); ?>" target="_blank" rel="noopener noreferrer"><?php echo e(setting('phone')); ?></a></p>
                <p><?= content_text('footer_email','site') ?> <a class="d-inline" href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a></p>
                <p><?= content_text('footer_address','site') ?> <?php echo e(setting('address')); ?></p>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; <?php echo date('Y'); ?> <?php echo e(setting('site_name')); ?>. <?= content_text('copyright_text','site') ?></span>
            <span class="developer-credit"><?= content_text('copy_1ecfe0874c', 'site') ?> <a href="<?= content_text('developer_url','site') ?>" target="_blank" rel="noopener noreferrer"><?= content_text('copy_17c3c47391', 'site') ?></a></span>
            <nav class="footer-legal" aria-label="Legal information">
                <a href="privacy-policy"<?php echo basename($_SERVER['PHP_SELF']) === 'privacy-policy.php' ? ' aria-current="page"' : ''; ?>><?= content_text('privacy_label','site') ?></a>
                <a href="terms-and-conditions"<?php echo basename($_SERVER['PHP_SELF']) === 'terms-and-conditions.php' ? ' aria-current="page"' : ''; ?>><?= content_text('terms_label','site') ?></a>
            </nav>
        </div>
    </div>
</footer>
<?php if (setting('whatsapp') && content_on('show_whatsapp','site')): ?>
<a class="whatsapp-bubble" href="<?php echo e(setting('whatsapp')); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e('Chat with '.$siteName.' on WhatsApp (opens in a new tab)') ?>">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20.52 3.48A11.9 11.9 0 0 0 12.04 0C5.44 0 .07 5.37.07 11.97c0 2.11.55 4.17 1.6 5.99L0 24l6.2-1.63a11.95 11.95 0 0 0 5.83 1.49h.01C18.64 23.86 24 18.49 24 11.9c0-3.2-1.24-6.2-3.48-8.42ZM12.04 21.84a9.9 9.9 0 0 1-5.05-1.38l-.36-.21-3.68.96.98-3.59-.23-.37a9.93 9.93 0 0 1-1.52-5.28c0-5.48 4.46-9.94 9.95-9.94a9.86 9.86 0 0 1 7.03 2.91 9.86 9.86 0 0 1 2.91 7.03c0 5.48-4.46 9.94-9.93 9.94Zm5.45-7.44c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.8-1.49-1.78-1.66-2.08-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.09 4.49.71.31 1.27.49 1.7.63.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35Z"/></svg>
    <span class="whatsapp-tooltip" aria-hidden="true"><?= content_text('copy_108154ceb7', 'site') ?></span>
</a>
<?php endif; ?>
<script src="assets/js/bootstrap.bundle.min.js" defer></script>
<script src="assets/js/site.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/site.js'); ?>" defer></script>
</body>
</html>
