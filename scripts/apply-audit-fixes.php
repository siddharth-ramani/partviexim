<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__.'/../includes/cms.php';
$db=cms_db();$db->beginTransaction();
try {
 $s=cms_settings_row()['data'];$s['address']='Plot No. 3, Jayshree Industrial Estate, Delad, Olpad, Surat, Gujarat, 394540, India';
 $db->prepare('UPDATE settings SET data=?,version=version+1 WHERE id=1')->execute([json_encode($s,JSON_THROW_ON_ERROR)]);
 $page=cms_page('index')['data'];$page['fields']['h1_1']['multiline']=true;$page['fields']['h1_1']['label']='Homepage hero heading (one line per row)';$page['fields']['h1_1']['value']="Goodness from\nour soil.\nFor your world.";
 $db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($page,JSON_THROW_ON_ERROR),'index']);
 $privacy=cms_page('privacy-policy')['data'];$privacy['fields']['p_2']['value']='Last updated: 28 October 2026';
 $privacy['fields']['p_8']['value']='This website does not currently include advertising trackers, analytics scripts, buyer accounts, or an online checkout. Essential session cookies protect the inquiry form and admin sign-in. Styling and navigation resources are served with the website. Hosting systems may maintain technical access logs for operation and security.';
 $db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=?')->execute([json_encode($privacy,JSON_THROW_ON_ERROR),'privacy-policy']);
 $db->commit();echo "Address, editable homepage heading and resource description corrected.\n";
} catch(Throwable $e){$db->rollBack();throw $e;}
$seed=require __DIR__.'/../includes/seed.php';$seed['settings']=$s;
foreach(['index','privacy-policy'] as $slug)if(isset($seed['pages'][$slug]))$seed['pages'][$slug]=cms_page($slug)['data'];
file_put_contents(__DIR__.'/../includes/seed.php',"<?php\n// Initial content for a fresh installation.\nreturn ".var_export($seed,true).";\n");
