<?php
require __DIR__.'/bootstrap.php';
if(admin_user())admin_redirect('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        csrf_check();
        $key='login:'.hash('sha256',$_SERVER['REMOTE_ADDR']??'unknown');
        if(rate_limited($key,8,900))throw new RuntimeException('Too many attempts. Please try again in 15 minutes.');
        $email=post_text('email',254,true);$password=post_text('password',200,true);
        $user=cms_db()->query('SELECT * FROM admins WHERE id=1')->fetch();
        if(!hash_equals(strtolower($user['email']),strtolower($email))||!password_verify($password,$user['password']))throw new RuntimeException('Email or password is incorrect.');
        session_regenerate_id(true);$_SESSION['admin']=1;$_SESSION['admin_version']=$user['version'];$_SESSION['admin_seen']=time();$_SESSION['csrf']=bin2hex(random_bytes(32));
        cms_db()->prepare('DELETE FROM rate_limits WHERE `key`=?')->execute([$key]);admin_redirect('index.php');
    }catch(Throwable $ex){$error=get_class($ex)===RuntimeException::class?$ex->getMessage():'Sign-in is temporarily unavailable.';}
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Sign in | Parthvi Admin</title><?php require __DIR__ . '/../includes/favicon.php'; ?><link rel="stylesheet" href="admin.css?v=<?php echo filemtime(__DIR__.'/admin.css'); ?>"><script src="admin.js?v=<?php echo filemtime(__DIR__.'/admin.js'); ?>" defer></script></head><body class="login-body"><main class="login-layout"><section class="login-story"><a class="admin-brand" href="../"><span class="brand-symbol">P↗</span><span>Parthvi Exim<small>WEBSITE MANAGER</small></span></a><div><p class="kicker">BUILT FOR YOUR BUSINESS</p><h1>A better way to<br>manage your website.</h1><p>Keep your products up to date, share your certificates and connect with global buyers.</p><div class="story-tags"><span>Content</span><span>Products</span><span>Inquiries</span></div></div><small>Your export business. Always moving forward.</small></section><section class="login-card"><span class="login-icon">↗</span><p class="kicker">WELCOME BACK</p><h2>Sign in to your workspace</h2><p>Enter your admin credentials to continue.</p><?php if($error):?><div class="notice error" role="alert"><?php echo e($error); ?></div><?php endif;?><form method="post"><?php echo admin_csrf();?><div class="field"><label for="email">Email address</label><input type="email" id="email" name="email" autocomplete="username" value="<?php echo e($_POST['email']??''); ?>" required></div><div class="field"><label for="password">Password</label><div class="password-wrap"><input type="password" id="password" name="password" autocomplete="current-password" required><button type="button" class="password-toggle" aria-label="Show password">Show</button></div></div><button class="button full" type="submit">Sign in →</button></form><a class="back-link" href="../">← Back to website</a></section></main></body></html>
