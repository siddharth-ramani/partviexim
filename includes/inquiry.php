<?php
require_once __DIR__.'/data.php';
cms_session();header('Cache-Control: no-store, private');
$inquiryError='';$inquirySuccess=$_SESSION['inquiry_success']??'';unset($_SESSION['inquiry_success']);
function inquiry_value(string $key):string{return e(is_string($_POST[$key]??null)?$_POST[$key]:'');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        csrf_check();
        if(!empty($_POST['website']))throw new RuntimeException('Your inquiry could not be accepted. Please contact us directly.');
        $data=[];
        foreach(['name'=>150,'email'=>254,'phone'=>40,'country'=>100,'company'=>200,'product'=>100,'quantity'=>6000] as $key=>$max){
            $value=$_POST[$key]??'';
            if(!is_string($value)||mb_strlen(trim($value))>$max)throw new RuntimeException('Please check the length of your '.$key.' field.');
            $data[$key]=trim($value);
        }
        foreach(['name','email','product','quantity'] as $key)if($data[$key]==='')throw new RuntimeException('Please fill in all required fields.');
        if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))throw new RuntimeException('Please enter a valid email address.');
        $choices=['general'=>content_value('general_inquiry','contact')];foreach($products as $p)$choices[$p['slug']]=$p['name'];
        if(!isset($choices[$data['product']]))throw new RuntimeException('Please choose an available product.');
        $data['product']=$choices[$data['product']];
        $key='inquiry:'.hash('sha256',$_SERVER['REMOTE_ADDR']??'unknown');
        if(rate_limited($key,5,3600))throw new RuntimeException('You have sent several inquiries recently. Please try again later or contact us by email.');
        $db=cms_db();$db->prepare('INSERT INTO inquiries(data,created_at) VALUES(?,?)')->execute([json_encode($data,JSON_THROW_ON_ERROR),gmdate('Y-m-d H:i:s')]);
        $id=$db->lastInsertId();
        $_SESSION['inquiry_success']=strtr(content_value('success_message','contact'),['{id}'=>(string)$id]);
        $_SESSION['csrf']=bin2hex(random_bytes(32));
        header('Location: contact#inquiry-form',true,303);exit;
    }catch(Throwable $ex){
        if(http_response_code()!==403)http_response_code(422);
        error_log('Inquiry submission: '.$ex->getMessage());
        $inquiryError=get_class($ex)===RuntimeException::class?$ex->getMessage():'We could not save your inquiry. Please try again or contact us directly.';
    }
}
