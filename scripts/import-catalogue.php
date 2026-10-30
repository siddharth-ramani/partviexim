<?php
// Run once after taking a database backup. Safe to re-run; matches products by slug.
// PARTHVI_DB_NAME / PARTHVI_DB_USER / PARTHVI_DB_HOST override connection settings.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__.'/../includes/cms.php';
$db=cms_db();
$products=json_decode(file_get_contents(__DIR__.'/../database/catalogue-2026.json'),true,512,JSON_THROW_ON_ERROR);
$settings=cms_settings_row()['data'];
$settings=array_replace($settings,[
 'tagline'=>'Indian ingredients. Global possibilities.',
 'address'=>'Plot No. 3, Jay Shree Industrial Estate, Delad, Olpad, Surat, Gujarat, 394540, India',
 'about_text'=>'Parthvi Exim connects global buyers with high-quality Indian agricultural ingredients. Based in Gujarat, we work with trusted farmers, processors and suppliers to source cumin, psyllium husk, dehydrated onion and garlic, peanuts, and other natural ingredients.',
 'about_details'=>'Our company specializes in premium cumin seeds and psyllium husk, with a wider range of dehydrated vegetables, peanuts, peanut butter, moringa powder and cardamom for food businesses and international buyers.\n\nWe focus on careful sourcing, cleaning, processing and packing to preserve product quality, aroma and consistency. From choosing a suitable product to discussing packing and coordinating export documentation, we work with buyers throughout the sourcing process.\n\nOur focus is on building long-term relationships through competitive pricing, dependable communication and reliable shipment coordination. At Parthvi Exim, we export trust and the rich heritage of Indian agriculture.',
 'mission'=>'To deliver products that meet international food safety and purity expectations, build transparent and ethical relationships with buyers, and support local farming communities through responsible sourcing.',
 'vision'=>'To become a globally recognized name for quality in agricultural exports, connecting international markets with premium Indian spices, natural fibre and food ingredients.',
 'motive'=>'To promote the richness of Indian agricultural products in international markets while creating value through pure, natural, properly processed ingredients and reliable service.',
 'footer_text'=>'From Gujarat to global markets. Your sourcing partner for dehydrated onion and garlic, peanuts, spices and natural Indian ingredients.'
]);
$settings['about_details']=str_replace('\\n',"\n",$settings['about_details']);
$db->beginTransaction();
try {
 $existing=[];foreach(cms_records('products',false) as $r)$existing[$r['data']['slug']]=$r['id'];
 foreach($products as $i=>$p){
  $json=json_encode($p,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
  if(isset($existing[$p['slug']]))$db->prepare('UPDATE records SET data=?,position=?,version=version+1 WHERE id=?')->execute([$json,$i,$existing[$p['slug']]]);
  else $db->prepare("INSERT INTO records(type,data,position,published) VALUES('products',?,?,1)")->execute([$json,$i]);
 }
 $db->prepare('UPDATE settings SET data=?,version=version+1 WHERE id=1')->execute([json_encode($settings,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
 foreach(['index','products','about','contact'] as $slug){
  $page=cms_page($slug)['data'];
  $descriptions=['index'=>'Source Indian dehydrated onion, garlic, peanuts, spices, psyllium husk and natural ingredients from Parthvi Exim. Explore our catalogue and request an export quotation.','products'=>'Browse our Indian ingredient collection by category. Discover product forms, specifications, packaging and applications from the Parthvi Exim catalogue.','about'=>'Meet Parthvi Exim, a Gujarat-based sourcing and export partner for Indian agricultural ingredients.','contact'=>'Contact Parthvi Exim for product specifications, packaging, quotations and export inquiries.'];
  $titles=['index'=>'Indian Ingredients, Global Possibilities','products'=>'Our Product Collection','about'=>'About Parthvi Exim','contact'=>'Contact Our Team'];
  $page['seo_title']=$titles[$slug];$page['seo_description']=$descriptions[$slug];
  $db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($page,JSON_THROW_ON_ERROR),$slug]);
 }
 $db->commit();echo count($products)." catalogue products imported.\n";
} catch(Throwable $e){$db->rollBack();throw $e;}
// Keep the fresh-install seed aligned with the client catalogue.
$seed=require __DIR__.'/../includes/seed.php';$seed['settings']=$settings;$seed['records']['products']=$products;
file_put_contents(__DIR__.'/../includes/seed.php',"<?php\n// Initial content for a fresh installation.\nreturn ".var_export($seed,true).";\n");
