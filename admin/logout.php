<?php
require __DIR__.'/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}
csrf_check();$_SESSION=[];session_regenerate_id(true);admin_redirect('login.php');
