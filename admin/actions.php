<?php
function save_admin_action(string $section,array $user):void {
    csrf_check();$db=cms_db();$action=post_text('action',40,true);
    if($action==='settings'){
        $row=cms_settings_row();$data=$row['data'];
        foreach(['site_name','tagline','email','phone','address','whatsapp','facebook','instagram','linkedin','about_text','about_details','mission','vision','motive','footer_text'] as $k)$data[$k]=post_text($k,20000,in_array($k,['site_name','email','phone','address']));
        if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))throw new RuntimeException('Enter a valid contact email.');
        if(!preg_match('/^\+?[0-9 ()-]{7,25}$/',$data['phone']))throw new RuntimeException('Enter a valid phone number.');
        foreach(['whatsapp','facebook','instagram','linkedin'] as $k){if($data[$k]!==''&&(!filter_var($data[$k],FILTER_VALIDATE_URL)||!in_array(strtolower(parse_url($data[$k],PHP_URL_SCHEME)),['https','http'],true)))throw new RuntimeException('Enter a valid https:// URL for '.$k.'.');}
        foreach(['logo','favicon'] as $key){$upload=cms_upload($key,$key==='favicon'?'favicon':'image');if($upload)$data[$key]=$upload;}
        $data=cms_mark_modified($row['data'],$data);
        $q=$db->prepare('UPDATE settings SET data=?,version=version+1 WHERE id=1 AND version=?');$q->execute([json_encode($data,JSON_THROW_ON_ERROR),(int)($_POST['version']??0)]);
        if(!$q->rowCount())throw new RuntimeException('Settings changed in another tab. Reload before saving.');
        cms_activity('Website settings updated');admin_redirect('index.php?section=settings','Website settings saved. Your changes are live on the website.');
    }
    if($action==='page'){
        $slug=post_text('slug',60,true);$page=cms_page($slug);if(!$page['version'])throw new RuntimeException('Page not found.');
        $data=$page['data'];$data['title']=post_text('title',150,true);$data['description']=post_text('description',400,true);
        $data['seo_title']=post_text('seo_title',150);$data['seo_description']=post_text('seo_description',400);
        foreach($data['fields'] as $key=>&$field){
            $type=$field['type']??(!empty($field['multiline'])?'textarea':'text');$input='copy_'.$key;
            if(in_array($type,['image','pdf'],true)){$upload=cms_upload($input,$type==='pdf'?'pdf':'image',$type==='pdf'?32:12);if($upload)$field['value']=$upload;continue;}
            $value=post_text($input,20000,!empty($field['required']));
            if($type==='toggle')$value=isset($_POST[$input])&&$_POST[$input]==='1'?'1':'0';
            if($type==='color'&&!preg_match('/^#[0-9a-fA-F]{6}$/',$value))throw new RuntimeException('Choose a valid colour for '.$field['label'].'.');
            if($type==='number'&&(filter_var($value,FILTER_VALIDATE_INT)===false||(int)$value<1||(int)$value>24))throw new RuntimeException('Featured product count must be between 1 and 24.');
            if($type==='link'&&!content_valid_link($value))throw new RuntimeException('Use a page path or a full https:// link for '.$field['label'].'.');
            $field['value']=$value;
        }unset($field);
        $data=cms_mark_modified($page['data'],$data);
        $q=$db->prepare('UPDATE pages SET data=?,version=version+1 WHERE slug=? AND version=?');$q->execute([json_encode($data,JSON_THROW_ON_ERROR),$slug,(int)($_POST['version']??0)]);
        if(!$q->rowCount())throw new RuntimeException('This page changed in another tab. Reload before saving.');
        cms_activity($data['title'].' page updated');admin_redirect('index.php?section=pages&slug='.urlencode($slug),'Page content saved.');
    }
    if($action==='record'||$action==='delete_record'){
        $type=post_text('type',30,true);if(!in_array($type,['products','categories','certificates','values','benefits'],true))throw new RuntimeException('Invalid content type.');
        $id=(int)($_POST['id']??0);$old=null;
        if($id){$q=$db->prepare('SELECT * FROM records WHERE id=? AND type=?');$q->execute([$id,$type]);$old=$q->fetch();if(!$old)throw new RuntimeException('Item not found.');}
        $version=(int)($_POST['version']??0);
        if($action==='delete_record'){
            if(!$old)throw new RuntimeException('Item not found.');
            if($type==='categories'){ $name=json_decode($old['data'],true)['name'];foreach(cms_records('products',false) as $product)if($product['data']['category']===$name)throw new RuntimeException('Move the products to another category before deleting this category. You can hide the category using draft status.'); }
            $q=$db->prepare('DELETE FROM records WHERE id=? AND type=? AND version=?');$q->execute([$id,$type,$version]);
            if(!$q->rowCount())throw new RuntimeException('This item changed. Reload and try again.');
            cms_touch_content_pages($type);
            cms_activity(ucfirst($type).' item deleted');admin_redirect('index.php?section='.$type,'Item deleted. Uploaded files were retained for existing document links.');
        }
        $data=$old?json_decode($old['data'],true):[];
        if($type==='products'){
            $data['seo_title']=post_text('seo_title',150);$data['seo_description']=post_text('seo_description',400);
            foreach(['name','slug','category','short','description','origin','packing'] as $k)$data[$k]=post_text($k,$k==='description'?20000:3000,true);
            if(!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$data['slug'])||strlen($data['slug'])>100)throw new RuntimeException('URL anchor must use lowercase letters, numbers and single hyphens.');
            foreach(cms_records('products',false) as $r){if((int)$r['id']!==$id&&($r['data']['slug']===$data['slug']||in_array($data['slug'],$r['data']['previous_slugs']??[],true)))throw new RuntimeException('Another product already uses that URL anchor.');}
            if($old){$oldData=json_decode($old['data'],true);if($oldData['slug']!==$data['slug'])$data['previous_slugs']=array_values(array_unique(array_merge($data['previous_slugs']??[],[$oldData['slug']])));$data['previous_slugs']=array_values(array_filter($data['previous_slugs']??[],fn($slug)=>$slug!==$data['slug']));}
            $data['featured']=isset($_POST['featured']);
            $data['grades']=cms_lines(post_text('grades'));$data['applications']=cms_lines(post_text('applications'));$data['specifications']=[];
            foreach(cms_lines(post_text('specifications')) as $line){$pair=explode(':',$line,2);if(count($pair)!==2||trim($pair[0])===''||trim($pair[1])==='')throw new RuntimeException('Complete both the specification name and value, or remove the empty row.');$data['specifications'][trim($pair[0])]=trim($pair[1]);}
            $path=cms_upload('image','image');if($path){$data['image']=$path;$d=getimagesize(__DIR__.'/../'.$path);$data['imageWidth']=$d[0];$data['imageHeight']=$d[1];}
            if(empty($data['image']))throw new RuntimeException('Please upload a product image.');
         }elseif($type==='categories'){
            $data['name']=post_text('name',150,true);$data['description']=post_text('description',3000);
            foreach(cms_records('categories',false) as $r)if((int)$r['id']!==$id&&mb_strtolower($r['data']['name'])===mb_strtolower($data['name']))throw new RuntimeException('A category already has this name.');
            $data['slug']=post_text('slug',100)?:cms_unique_category_slug($data['name'],$id);
            if(!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/',$data['slug']))throw new RuntimeException('Use lowercase English letters, numbers and hyphens for the category URL.');
            foreach(cms_records('categories',false) as $cat)if((int)$cat['id']!==$id&&(($cat['data']['slug']??'')===$data['slug']||in_array($data['slug'],$cat['data']['previous_slugs']??[],true)))throw new RuntimeException('Another category already uses that URL.');
            if($old){$oldData=json_decode($old['data'],true);if(!empty($oldData['slug'])&&$oldData['slug']!==$data['slug'])$data['previous_slugs']=array_values(array_unique(array_merge($data['previous_slugs']??[],[$oldData['slug']])));$data['previous_slugs']=array_values(array_filter($data['previous_slugs']??[],fn($slug)=>$slug!==$data['slug']));}
            foreach(['heading','intro','buying_notes','seo_title','seo_description'] as $key)$data[$key]=post_text($key,$key==='buying_notes'?12000:3000);
            $path=cms_upload('image','image');if($path)$data['image']=$path;$data['image']=$data['image']??'';
        }elseif($type==='certificates'){
            foreach(['title','label','description'] as $k)$data[$k]=post_text($k,3000,true);
            $path=cms_upload('file','pdf');if($path)$data['file']=$path;
            $path=cms_upload('preview','image');if($path)$data['preview']=$path;
            if(empty($data['file']))throw new RuntimeException('Please upload the certificate PDF.');
            $data['preview']=$data['preview']??'';
        }else{
            $data['title']=post_text('title',180,true);$data['text']=post_text('text',4000,true);
        }
        $pos=filter_var($_POST['position']??0,FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>9999]]);if($pos===false)throw new RuntimeException('Display order must be between 0 and 9999.');
        $published=isset($_POST['published'])?1:0;
        $before=$old?json_decode($old['data'],true):[];
        $contentChanged=!$old||(int)$old['position']!==$pos||(int)$old['published']!==$published||cms_content_changed($before,$data);
        $data=cms_mark_modified($before,$data,$contentChanged);
        $db->beginTransaction();
        try {
        if($id){$q=$db->prepare('UPDATE records SET data=?,position=?,published=?,version=version+1 WHERE id=? AND version=?');$q->execute([json_encode($data,JSON_THROW_ON_ERROR),$pos,$published,$id,$version]);if(!$q->rowCount())throw new RuntimeException('This item changed in another tab. Reload before saving.');}
        else{$q=$db->prepare('INSERT INTO records(type,data,position,published) VALUES(?,?,?,?)');$q->execute([$type,json_encode($data,JSON_THROW_ON_ERROR),$pos,$published]);}
        if($type==='categories'&&$old){$before=json_decode($old['data'],true)['name'];if($before!==$data['name'])foreach(cms_records('products',false) as $p)if($p['data']['category']===$before){$pd=$p['data'];$pd['category']=$data['name'];$pd['_updated_at']=gmdate('c');$db->prepare('UPDATE records SET data=?,version=version+1 WHERE id=?')->execute([json_encode($pd,JSON_THROW_ON_ERROR),$p['id']]);}}
        if($type==='products'){ $found=false;foreach(cms_records('categories',false) as $cat)if($cat['data']['name']===$data['category'])$found=true;if(!$found)$db->prepare("INSERT INTO records(type,data,position,published) VALUES('categories',?,?,1)")->execute([json_encode(['name'=>$data['category'],'slug'=>cms_unique_category_slug($data['category']),'image'=>$data['image'],'description'=>'','heading'=>$data['category'],'intro'=>'','buying_notes'=>'','seo_title'=>'','seo_description'=>''],JSON_THROW_ON_ERROR),count(cms_records('categories',false))]); }
        if($contentChanged)cms_touch_content_pages($type);
        $db->commit();
        } catch(Throwable $e){if($db->inTransaction())$db->rollBack();throw $e;}
        cms_activity(($id?'Updated ':'Added ').($data['name']??$data['title']));admin_redirect('index.php?section='.$type,'Item saved. '.($published?'Published on your website.':'Saved as a draft.'));
    }
    if($action==='inquiry'){
        $status=post_text('status',20,true);if(!in_array($status,['New','In progress','Closed'],true))throw new RuntimeException('Invalid status.');
        $q=$db->prepare('UPDATE inquiries SET status=? WHERE id=?');$q->execute([$status,(int)($_POST['id']??0)]);
        admin_redirect('index.php?section=inquiries&id='.(int)$_POST['id'],'Inquiry status updated.');
    }
    if($action==='delete_inquiry'){
        $db->prepare('DELETE FROM inquiries WHERE id=?')->execute([(int)($_POST['id']??0)]);admin_redirect('index.php?section=inquiries','Inquiry deleted.');
    }
    if($action==='account'){
        $email=post_text('email',254,true);$current=post_text('current_password',200,true);$new=post_text('new_password',200);$confirm=post_text('confirm_password',200);
        if(!password_verify($current,$user['password']))throw new RuntimeException('Current password is incorrect.');
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))throw new RuntimeException('Enter a valid login email.');
        if($new!==''&&(strlen($new)<8||$new!==$confirm))throw new RuntimeException('Use at least 8 characters and match the confirmation password.');
        $hash=$new!==''?password_hash($new,PASSWORD_DEFAULT):$user['password'];
        $db->prepare('UPDATE admins SET email=?,password=?,version=version+1 WHERE id=1')->execute([$email,$hash]);
        session_regenerate_id(true);$_SESSION['admin_version']=$user['version']+1;
        cms_activity('Admin login details updated');admin_redirect('index.php?section=account','Login details updated. Other sessions have been signed out.');
    }
    throw new RuntimeException('Unknown action.');
}
