<?php
// One-time on-page upgrade. Existing later edits are retained on subsequent runs.
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/../includes/cms.php';
$categoryCopy=[
 'Dehydrated White Onion'=>['dehydrated-white-onion','Dehydrated White Onion from India','White onion flakes, chopped pieces, minced onion, granules and powder for food businesses. Compare particle sizes, moisture limits and packaging before requesting an export quotation.','Choose flakes for recipes that require visible onion pieces, or compare chopped, minced and granulated forms against your formulation. The supplied range lists flakes at 8–20 mm, chopped onion at 3–7 mm, minced onion at 1–3 mm, granules at 0.2–1 mm and powder at 80–100 mesh. Confirm your required form, pack size, quantity and destination with our team.'],
 'Dehydrated Red Onion'=>['dehydrated-red-onion','Dehydrated Red Onion from India','Explore dehydrated red onion in flakes, chopped, minced, granules and powder. Each product page includes available dimensions, packaging and applications for international ingredient buyers.','Match your recipe or production process to the required red onion cut. Flakes are listed at 8–20 mm, chopped pieces at 3–7 mm, minced onion at 1–3 mm, granules at 0.2–1 mm and powder at 80–100 mesh. Compare the individual specifications and confirm the packing material and shipment requirements before ordering.'],
 'Dehydrated Pink Onion'=>['dehydrated-pink-onion','Dehydrated Pink Onion from India','Pink onion ingredients for seasoning, sauces and prepared-food applications. Browse flakes, chopped pieces, minced onion, granules and powder with the catalogue specifications.','Start with the particle size required by your application, then choose the corresponding pink onion form. The range includes 8–20 mm flakes through to 80–100 mesh powder. Share your moisture specification, quantity, packing preference and delivery destination so the quotation can reflect your actual requirements.'],
 'Dehydrated Garlic'=>['dehydrated-garlic','Dehydrated Garlic from India','Source garlic flakes, chopped garlic, minced garlic, granules and powder for food processing, seasoning and culinary uses. View the specifications and request a bulk export quote.','Compare garlic forms by particle size: flakes at 8–20 mm, chopped garlic at 3–7 mm, minced garlic at 1–3 mm, granules at 0.2–1 mm and powder at 80–100 mesh. Individual pages show the supplied moisture limits and packing options. Confirm the required cut and packaging for your formulation before shipment.'],
 'Peanuts'=>['peanuts','Indian Peanuts for Food Businesses','Browse bold peanuts, TJ / Java peanuts, blanched peanuts, inshell peanuts and peanut dice. Compare available counts, moisture limits and packing for snack, confectionery and food-processing applications.','Choose the peanut form first: skin-on kernels, blanched whole or split peanuts, peanuts in shell, or diced ingredients. Where listed, compare kernel counts and moisture or aflatoxin specifications on the individual product page. Tell us the required form, count, quantity and packing preference to request a suitable quotation.'],
 'Indian Spices'=>['indian-spices','Cumin Seeds & Green Cardamom from India','Explore Indian cumin seeds (jeera) and green cardamom for spice blends, food service and ingredient businesses. Review available cumin qualities and discuss cardamom grades with our team.','Cumin is listed in Singapore 98%, Singapore 99%, Europe 99.5% and UK Bold Sortex qualities. Green cardamom is offered as whole green pods; request current grade and packing details. Compare the relevant product page and specify your intended application, quantity and delivery destination.'],
];
$pages=[
 'index'=>['Indian Ingredients Exporter in Gujarat, India','Explore Indian dehydrated onion, garlic, peanuts, cumin, cardamom and psyllium husk. Request product specifications and export quotes from Parthvi Exim.'],
 'products'=>['Indian Ingredients & Agro Products','Browse dehydrated onion, garlic, peanuts, spices, moringa and psyllium husk. Compare product forms, specifications and packing for bulk export inquiries.'],
 'about'=>['About Our Indian Ingredients Export Business','Meet Parthvi Exim in Surat, Gujarat. Learn about our approach to sourcing Indian ingredients and supporting international buyers with export coordination.'],
 'contact'=>['Request an Indian Ingredients Export Quote','Ask Parthvi Exim about product specifications, grades, packing and export quotations. Share your ingredient, quantity and delivery destination.'],
 'certificates'=>['FSSAI, IEC, GST & Udyam Certificates','View Parthvi Exim business registrations and food-business documents, including FSSAI, IEC, GST and Udyam certificates. Open or download the supplied PDFs.'],
];
$db=cms_db();$db->beginTransaction();
try{
 foreach(cms_records('categories',false) as $r){$d=$r['data'];if(!empty($d['_onpage_v1']))continue;$copy=$categoryCopy[$d['name']]??null;
  $d['slug']=$d['slug']??($copy[0]??cms_slug($d['name']));
  $d['heading']=$d['heading']??($copy[1]??$d['name']);$d['intro']=$d['intro']??($copy[2]??'');$d['buying_notes']=$d['buying_notes']??($copy[3]??'');
  $d['seo_title']=$d['seo_title']??($d['name'].' Exporter India');$d['seo_description']=$d['seo_description']??($copy[2]??$d['description']);$d['_onpage_v1']=true;$d['_updated_at']=gmdate('c');
  $db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);
 }
 foreach(cms_records('products',false) as $r){$d=$r['data'];if(!empty($d['_onpage_v1']))continue;
  if(empty($d['seo_title']))$d['seo_title']=$d['name'].' Exporter India';
  if(empty($d['seo_description'])){
   if(str_contains($d['category'],'Onion')||$d['category']==='Dehydrated Garlic'){
    $size=$d['specifications']['Particle size']??'';
    $d['seo_description']='Source '.strtolower($d['name']).' from India'.($size?' in '.$size.' form':'').'. View specifications, packing and food applications; request a bulk export quotation.';
   }else $d['seo_description']='Explore '.strtolower($d['name']).' from India. View available forms, applications and packing, then contact Parthvi Exim for specifications and a bulk export quote.';
  }
  $d['_onpage_v1']=true;$d['_updated_at']=gmdate('c');$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);
 }
 foreach($pages as $slug=>[$title,$description]){$row=cms_page($slug);$d=$row['data'];if(!empty($d['_onpage_v1']))continue;$d['seo_title']=$title;$d['seo_description']=$description;$d['_onpage_v1']=true;$d['_updated_at']=gmdate('c');$db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$slug]);}
 $db->commit();echo "Category content and page/product search metadata updated.\n";
}catch(Throwable $e){$db->rollBack();throw $e;}
// Refresh only recognised original Home copy; retain later client edits.
$home=cms_page('index');$d=$home['data'];$changed=false;
$copyUpdates=[
 'copy_8804a5276a'=>['Rooted in India. Ready for the world.','Indian ingredients exporter'],
 'copy_483cb724bb'=>['Carefully sourced Indian ingredients, thoughtfully prepared for global businesses. Discover our world of flavour, quality and possibility.','Dehydrated onion and garlic, peanuts, Indian spices and natural ingredients—carefully sourced in India for importers and food businesses worldwide.'],
 'copy_078473b147'=>['From everyday essentials','Explore Indian ingredients'],
 'copy_7e4eb0e94a'=>['to exceptional ingredients.','for your business.'],
];
foreach($copyUpdates as $key=>[$old,$new])if(($d['fields'][$key]['value']??'')===$old){$d['fields'][$key]['value']=$new;$changed=true;}
if($changed){$d['_updated_at']=gmdate('c');$db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),'index']);echo "Homepage ingredient focus updated.\n";}
// Replace only titles generated by the first pass with more natural product names.
foreach(cms_records('products',false) as $r){$d=$r['data'];$old=$d['name'].' Exporter India';if(($d['seo_title']??'')!==$old)continue;
 if(str_starts_with($d['category'],'Dehydrated ')&&str_contains($d['category'],'Onion'))$d['seo_title']='Dehydrated '.$d['name'];
 elseif($d['category']==='Dehydrated Garlic')$d['seo_title']='Dehydrated '.str_replace('Garlic ','',$d['name']).' Garlic';
 else $d['seo_title']=$d['name'].' Exporter from India';
 $d['_updated_at']=gmdate('c');$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);
}
foreach(cms_records('categories',false) as $r){$d=$r['data'];if(($d['seo_title']??'')!==$d['name'].' Exporter India')continue;$d['seo_title']=$d['name'].' Exporter from India';$d['_updated_at']=gmdate('c');$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);}
// Natural form names for the generated garlic titles.
$garlicNames=['Garlic Flakes'=>'Garlic Flakes','Garlic Chopped'=>'Chopped Garlic','Garlic Minced'=>'Minced Garlic','Garlic Granules'=>'Garlic Granules','Garlic Powder'=>'Garlic Powder'];
foreach(cms_records('products',false) as $r){$d=$r['data'];if(!isset($garlicNames[$d['name']]))continue;$old='Dehydrated '.str_replace('Garlic ','',$d['name']).' Garlic';if(($d['seo_title']??'')!==$old)continue;$d['seo_title']='Dehydrated '.$garlicNames[$d['name']];$d['seo_description']='Source dehydrated '.strtolower($garlicNames[$d['name']]).' from India. Compare particle sizes, moisture limits, food applications and packing for bulk export inquiries.';$d['_updated_at']=gmdate('c');$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);}
