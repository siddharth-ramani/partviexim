<div class="notice info"><strong>Your changes go live after saving.</strong> Use “View saved page” to check the result. Images left empty during editing keep their current files.</div>
<div class="page-grid">
<?php $guides=[
 ['Products','Add or edit products, descriptions, grades, specifications and photos. Choose a category and tick “Feature this product on Home” to promote it. Save as Draft to hide it.','products'],
 ['Categories','Change the category name, card image and order. Renaming a category updates all its products. A draft category hides its products from the public website. Move products first if you want to delete a category.','categories'],
 ['Page text & images','Open Pages & appearance, choose a page and expand the section you want. Edit headings, button text, founder profile and images. Section visibility switches hide content without deleting it.','pages'],
 ['Company & contact details','Update your business name, logo, favicon, email, phone, social profiles, company introduction, mission and vision. These details are shared across the website.','settings'],
 ['Catalogue & colours','Open Pages & appearance → Shared content & appearance. Replace the catalogue PDF, change menu/footer labels, manage the enquiry banner or update brand colours.','pages&slug=site'],
 ['Certificates','Upload the certificate PDF and an optional preview image. You can reorder documents or hide them as drafts. Existing PDF links are kept when you replace a file.','certificates'],
 ['Buyer inquiries','Open the inbox, search for the buyer, review requirements and reply by email. Mark an inquiry In progress or Closed to keep your inbox organised.','inquiries'],
 ['Login & password','Change your email or password in My account. Enter your current password to confirm. Password changes sign out other sessions.','account'],
];foreach($guides as [$title,$body,$link]): ?><article class="panel"><h2><?= e($title) ?></h2><p><?= e($body) ?></p><a class="button secondary" href="index.php?section=<?= e($link) ?>">Open editor →</a></article><?php endforeach; ?>
</div>
