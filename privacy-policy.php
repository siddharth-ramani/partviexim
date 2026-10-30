<?php
$pageTitle = 'Privacy Policy';
$pageDescription = 'How Parthvi Exim handles website inquiries, contact information, and links to third-party services.';
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
        <nav class="legal-nav" aria-label="Privacy policy sections">
            <strong><?php echo page_text('strong_1'); ?></strong>
            <a href="#information">Information you share</a>
            <a href="#use">How information is used</a>
            <a href="#services">Website &amp; external services</a>
            <a href="#retention">Records &amp; security</a>
            <a href="#choices">Your choices</a>
            <a href="#contact">Contact us</a>
        </nav>
        <article class="legal-content">
            <p class="legal-date"><?php echo page_text('p_2'); ?></p>
            <p><?php echo page_text('p_3'); ?></p>
            <section id="information">
                <h2><?php echo page_text('h2_1'); ?></h2>
                <p><?php echo page_text('p_4'); ?></p>
                <p><?php echo page_text('p_5'); ?></p>
            </section>
            <section id="use">
                <h2><?php echo page_text('h2_2'); ?></h2>
                <p><?php echo page_text('p_6'); ?></p>
                <p><?php echo page_text('p_7'); ?></p>
            </section>
            <section id="services">
                <h2><?php echo page_text('h2_3'); ?></h2>
                <p><?php echo page_text('p_8'); ?></p>
                <p><?php echo page_text('p_9'); ?></p>
            </section>
            <section id="retention">
                <h2><?php echo page_text('h2_4'); ?></h2>
                <p><?php echo page_text('p_10'); ?></p>
                <p><?php echo page_text('p_11'); ?></p>
            </section>
            <section id="choices">
                <h2><?php echo page_text('h2_5'); ?></h2>
                <p><?php echo page_text('p_12'); ?></p>
                <p><?php echo page_text('p_13'); ?></p>
            </section>
            <section id="contact">
                <h2><?php echo page_text('h2_6'); ?></h2>
                <p>For privacy questions, requests, or concerns, email <a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a> or call <a href="tel:<?php echo e(preg_replace('/[^+0-9]/', '', setting('phone'))); ?>"><?php echo e(setting('phone')); ?></a>.</p>
                <p><?php echo page_text('p_14'); ?></p>
            </section>
            <div class="legal-related"><a href="terms-and-conditions">Read Terms &amp; Conditions →</a></div>
        </article>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
