<?php
// Router for the isolated PHP development-server test run only.
$root=dirname(__DIR__);$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
if(str_contains($path,'..')){http_response_code(404);exit;}
$file=$root.$path;
if(preg_match('~^/products/([a-z0-9]+(?:-[a-z0-9]+)*)$~',$path,$match)){$file=$root.'/product.php';$_GET['slug']=$match[1];}
if(preg_match('~^/categories/([a-z0-9]+(?:-[a-z0-9]+)*)$~',$path,$match)){$file=$root.'/category.php';$_GET['slug']=$match[1];}
if($path==='/robots.txt')$file=$root.'/robots.php';
if($path==='/sitemap.xml')$file=$root.'/sitemap.php';
if(is_dir($file))$file.='/index.php';
elseif(!pathinfo($file,PATHINFO_EXTENSION))$file.='.php';
if(!is_file($file)&&!is_dir($file))$file=$root.'/404.php';
if(is_file($file)&&pathinfo($file,PATHINFO_EXTENSION)==='php'){
    $_SERVER['SCRIPT_NAME']=substr($file,strlen($root));$_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];require $file;return true;
}
return false;
