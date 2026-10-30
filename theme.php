<?php
require_once __DIR__.'/includes/cms.php';
header('Content-Type: text/css; charset=UTF-8');
$defaults=content_theme_defaults();$colors=[];
foreach($defaults as $key=>$fallback){$value=content_value($key,'site');$colors[$key]=preg_match('/^#[0-9a-fA-F]{6}$/',$value)?strtolower($value):$fallback;}
if($colors===$defaults){echo '/* Default logo-led website palette. */';exit;}
$p=$colors['theme_primary'];$a=$colors['theme_accent'];$b=$colors['theme_background'];
?>
:root{
--brand-primary:<?= $p ?>;
--brand-accent:<?= $a ?>;
--brand-background:<?= $b ?>;
--brand-surface:color-mix(in srgb,<?= $p ?> 4%,<?= $b ?>);
--brand-tint:color-mix(in srgb,<?= $p ?> 9%,<?= $b ?>);
--brand-border:color-mix(in srgb,<?= $p ?> 15%,<?= $b ?>);
}
