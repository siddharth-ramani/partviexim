<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/../includes/cms.php';
$db=cms_db();$db->beginTransaction();
try {
 foreach(content_schema() as $slug=>$fields){
  $row=cms_page($slug);$data=$row['data'];$old=$data['fields']??[];
  foreach($fields as $key=>&$f){if(isset($old[$key]))$f['value']=$old[$key]['value'];}unset($f);
  // Preserve only fields actually rendered by the current templates.
  if(in_array($slug,['contact','certificates'],true))foreach($old as $k=>$f)if(!isset($fields[$k])&&preg_match('/^(h1_|h2_|span_|p_)/',$k))$fields[$k]=$f+['group'=>'Page content','type'=>!empty($f['multiline'])?'textarea':'text'];
  $data['fields']=$fields;
  if($slug==='site'){$data['title']='Shared website content';$data['description']='Navigation, footer, catalogue PDF, shared enquiry banner and brand colours.';}
  if($row['version'])$db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($data,JSON_THROW_ON_ERROR),$slug]);
  else $db->prepare('INSERT INTO pages(slug,data,version) VALUES(?,?,1)')->execute([$slug,json_encode($data,JSON_THROW_ON_ERROR)]);
 }
 $categories=[];foreach(cms_records('categories',false) as $r)$categories[$r['data']['name']]=true;
 $seen=[];$i=0;
 foreach(cms_records('products',false) as $r){$d=$r['data'];$name=$d['category'];
  if(!isset($categories[$name])){$cat=['name'=>$name,'image'=>$d['image'],'description'=>''];$db->prepare("INSERT INTO records(type,data,position,published) VALUES('categories',?,?,1)")->execute([json_encode($cat,JSON_THROW_ON_ERROR),$i++]);$categories[$name]=true;}
  if(!array_key_exists('featured',$d)){$d['featured']=!isset($seen[$name])&&count($seen)<8;$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($d,JSON_THROW_ON_ERROR),$r['id']]);}$seen[$name]=true;
 }
 $db->commit();echo "Website content and category editors enabled.\n";
}catch(Throwable $e){$db->rollBack();throw $e;}
