<?php
$iconBase = cms_base();
$faviconSettings = cms_settings_row()['data'];
$customFavicon = $faviconSettings['favicon'] ?? '';
$hasCustomFavicon = preg_match('~^assets/uploads/[a-f0-9]+\.png$~D', $customFavicon)
    && is_file(__DIR__ . '/../' . $customFavicon);
if ($hasCustomFavicon): ?>
<link rel="icon" type="image/png" href="<?= e($iconBase . $customFavicon) ?>">
<link rel="shortcut icon" type="image/png" href="<?= e($iconBase . $customFavicon) ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= e($iconBase . $customFavicon) ?>">
<?php else: ?>
<link rel="icon" type="image/svg+xml" href="<?= e($iconBase) ?>assets/icons/favicon.svg?v=1">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e($iconBase) ?>assets/icons/favicon-32.png?v=1">
<link rel="shortcut icon" href="<?= e($iconBase) ?>favicon.ico?v=1">
<link rel="apple-touch-icon" sizes="180x180" href="<?= e($iconBase) ?>assets/icons/apple-touch-icon.png?v=1">
<?php endif; ?>
