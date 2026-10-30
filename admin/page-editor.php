<?php
$labels=['index'=>'Home page','about'=>'About us','products'=>'Products page','certificates'=>'Certificates page','contact'=>'Contact page','privacy-policy'=>'Privacy policy','terms-and-conditions'=>'Terms & conditions','site'=>'Shared content & appearance'];
$slug=is_string($_GET['slug']??null)?$_GET['slug']:'';
if(!$slug): ?>
<div class="notice info">Choose a page below. Open a section, edit its text or replace an image, then click <strong>Save changes</strong>. Your saved changes appear on the website immediately.</div>
<div class="page-grid"><?php foreach($labels as $key=>$label):$row=cms_page($key);if(!$row['version'])continue; ?>
<article class="panel page-card"><span class="page-icon" aria-hidden="true">▤</span><h2><?= e($label) ?></h2><p><?= e($key==='site'?'Menu labels, footer, brand colours, catalogue PDF and the shared enquiry banner.':($key==='index'?'Hero images, headings, featured products, ordering steps and section visibility.':($key==='about'?'Company page, founder photo, founder story and section visibility.':'Page headings, introductions, labels and search engine details.'))) ?></p><div><a class="button" href="index.php?section=pages&amp;slug=<?= e($key) ?>">Edit <?= e($key==='site'?'shared content':'page') ?></a><a class="text-link" href="../<?= $key==='index'||$key==='site'?'':e($key) ?>" target="_blank" rel="noopener">View saved page ↗</a></div></article>
<?php endforeach; ?></div>
<?php return;endif;
$row=cms_page($slug);if(!$row['version']||!isset($labels[$slug])){echo '<div class="notice error">Page not found.</div>';return;}
$d=$row['data'];$groups=[];foreach($d['fields'] as $k=>$f)$groups[$f['group']??'Page content'][$k]=$f;
?>
<a class="back-link" href="index.php?section=pages">← Choose another page</a>
<div class="editor-intro panel"><div><h2><?= e($labels[$slug]) ?></h2><p>Open the section you want to change. Plain text is safe to edit; no HTML or coding is needed.</p></div><a class="button secondary" href="../<?= in_array($slug,['index','site'])?'':e($slug) ?>" target="_blank" rel="noopener">View saved page ↗</a></div>
<?php if(in_array($slug,['index','about'])): ?><div class="notice info">Company introduction, mission and vision are in <a href="index.php?section=settings#company-content">Company details</a>. Product photos and featured choices are in <a href="index.php?section=products">Products</a>. Category names and card images are in <a href="index.php?section=categories">Categories</a>.</div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="editor-form content-editor" data-dirty-form>
<?= admin_csrf() ?><input type="hidden" name="action" value="page"><input type="hidden" name="slug" value="<?= e($slug) ?>"><input type="hidden" name="version" value="<?= e($row['version']) ?>"><input type="hidden" name="title" value="<?= e($d['title']) ?>"><input type="hidden" name="description" value="<?= e($d['description']) ?>">
<nav class="editor-jumps" aria-label="Page sections"><?php $n=0;foreach($groups as $group=>$fields): ?><a href="#content-group-<?= $n++ ?>"><?= e($group) ?></a><?php endforeach; ?></nav>
<?php $n=0;foreach($groups as $group=>$fields): ?>
<details class="panel content-group" id="content-group-<?= $n ?>" <?= $n++===0?'open':'' ?>><summary><span><?= e($group) ?></span><small><?= count($fields) ?> editable items</small></summary><div class="group-fields">
<?php foreach($fields as $k=>$f):$key='copy_'.$k;$type=$f['type']??(!empty($f['multiline'])?'textarea':'text');$value=old_value($key,$f['value']);
if($type==='toggle' && $_SERVER['REQUEST_METHOD']==='POST')$value=isset($_POST[$key])?'1':'0';
if(in_array($type,['image','pdf'])):upload_field($key,$f['label'],$f['value'],$type==='pdf',$type==='pdf'?32:12);
elseif($type==='toggle'): ?><label class="toggle-field"><input type="checkbox" name="<?= e($key) ?>" value="1" <?= $value==='1'?'checked':'' ?>><span><strong><?= e($f['label']) ?></strong><small>Turn off to hide this section or feature without deleting its content.</small></span></label>
<?php else:field($key,$f['label'],$value,$type==='link'?'text':$type,!empty($f['required']),$type==='link'?'Use a page such as contact or products, or a full https:// URL.':($type==='color'?'Choose a colour. Use dark primary and accent colours for readable text.':($type==='number'?'Choose between 1 and 24 products.':(!empty($f['multiline'])?'Line breaks are supported. Plain text only.':''))));endif;endforeach; ?>
</div></details>
<?php endforeach; ?>
<?php if($slug!=='site'): ?><details class="panel content-group"><summary><span>Search engine details</span><small>Optional</small></summary><div class="group-fields"><?php field('seo_title','Search title',old_value('seo_title',$d['seo_title']??''),'text',false,'Business name is added automatically.');field('seo_description','Search description',old_value('seo_description',$d['seo_description']??''),'textarea',false,'A short description of this page for search results.');?></div></details><?php else: ?><input type="hidden" name="seo_title" value=""><input type="hidden" name="seo_description" value=""><?php endif; ?>
<div class="form-actions sticky-actions"><span class="save-state" data-save-state>No unsaved changes</span><button class="button" type="submit">Save changes</button><a class="button secondary" href="index.php?section=pages">Back to pages</a></div>
</form>
