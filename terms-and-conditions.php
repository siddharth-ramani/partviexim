<?php
$pageTitle = 'Terms & Conditions';
$pageDescription = 'Terms for using the Parthvi Exim website, product information, certificates, and trade inquiry services.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><?php echo page_text('span_1'); ?></span>
        <h1><?php echo page_text('h1_1'); ?></h1>
        <p><?php echo page_text('p_1'); ?></p>
    </div>
</section>
<section class="section-pad surface-section">
    <div class="container legal-layout">
        <nav class="legal-nav" aria-label="Terms and conditions sections">
            <strong><?php echo page_text('strong_1'); ?></strong>
            <a href="#website">Using this website</a>
            <a href="#products">Product information</a>
            <a href="#inquiries">Inquiries &amp; orders</a>
            <a href="#documents">Content &amp; certificates</a>
            <a href="#external">External services</a>
            <a href="#contact">Questions &amp; updates</a>
        </nav>
        <article class="legal-content">
            <p class="legal-date"><?php echo page_text('p_2'); ?></p>
            <p><?php echo page_text('p_3'); ?></p>
            <section id="website">
                <h2><?php echo page_text('h2_1'); ?></h2>
                <p><?php echo page_text('p_4'); ?></p>
                <p><?php echo page_text('p_5'); ?></p>
            </section>
            <section id="products">
                <h2><?php echo page_text('h2_2'); ?></h2>
                <p><?php echo page_text('p_6'); ?></p>
                <p><?php echo page_text('p_7'); ?></p>
            </section>
            <section id="inquiries">
                <h2><?php echo page_text('h2_3'); ?></h2>
                <p><?php echo page_text('p_8'); ?></p>
                <p><?php echo page_text('p_9'); ?></p>
                <p><?php echo page_text('p_10'); ?></p>
            </section>
            <section id="documents">
                <h2><?php echo page_text('h2_4'); ?></h2>
                <p><?php echo page_text('p_11'); ?></p>
                <p><?php echo page_text('p_12'); ?></p>
            </section>
            <section id="external">
                <h2><?php echo page_text('h2_5'); ?></h2>
                <p><?php echo page_text('p_13'); ?></p>
                <p>Website information may change and occasional errors or interruptions may occur. Contact us to confirm material details before making a commercial decision. Information about handling your contact details is available in our <a href="privacy-policy">Privacy Policy</a>.</p>
            </section>
            <section id="contact">
                <h2><?php echo page_text('h2_6'); ?></h2>
                <p>For questions about these terms or a trade inquiry, email <a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a> or call <a href="tel:<?php echo e(preg_replace('/[^+0-9]/', '', setting('phone'))); ?>"><?php echo e(setting('phone')); ?></a>.</p>
                <p><?php echo page_text('p_14'); ?></p>
            </section>
            <div class="legal-related"><a href="privacy-policy">Read Privacy Policy →</a></div>
        </article>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
