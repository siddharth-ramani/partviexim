<?php
// Upgrade only legacy fallback descriptions; retain custom search titles/descriptions.
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/../includes/cms.php';
$updates=[
 'index'=>['Parthvi Exim exports premium Indian agricultural commodities including Isabgul Psyllium Husk and Jeera Cumin Seeds.','Source Indian dehydrated onion, garlic, peanuts, spices, psyllium husk and natural ingredients from Parthvi Exim. Explore the catalogue and request an export quotation.'],
 'products'=>['Parthvi Exim products include Isabgul Psyllium Husk and Jeera Cumin Seeds for global buyers.','Browse dehydrated onion, garlic, peanuts, peanut butter, Indian spices, moringa and psyllium husk. View product forms, specifications and packaging.'],
 'contact'=>['Contact Parthvi Exim for Isabgul Psyllium Husk and Jeera Cumin Seeds export inquiries.','Contact Parthvi Exim for Indian ingredient specifications, packaging, quotations and export inquiries.'],
];
foreach($updates as $slug=>[$old,$new]){$page=cms_page($slug);$d=$page['data'];if(($d['description']??'')!==$old)continue;$d['description']=$new;$d['_updated_at']=gmdate('c');$q=cms_db()->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=? AND version=?');$q->execute([json_encode($d,JSON_THROW_ON_ERROR),$slug,$page['version']]);if(!$q->rowCount())throw new RuntimeException('Page changed during SEO update: '.$slug);echo $slug." fallback description refreshed.\n";}
