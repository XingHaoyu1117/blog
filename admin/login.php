<?php
require __DIR__.'/../inc/bootstrap.php';
if(admin()){header('Location:index.php');exit;}
$error='';
$now=time();
$window=600;
if(($_SESSION['login_window']??0)+$window<$now){$_SESSION['login_window']=$now;$_SESSION['login_attempts']=0;}
$locked=($_SESSION['login_attempts']??0)>=5;
if($_SERVER['REQUEST_METHOD']==='POST'){
    if($locked){http_response_code(429);$error='TOO MANY LOGIN ATTEMPTS. TRY AGAIN LATER.';}
    else{
        verify_csrf();
        $u=trim($_POST['user']??'');$p=$_POST['pass']??'';
        if(hash_equals((string)($config['admin_user']??'admin'),$u) && password_verify($p,$config['admin_password_hash']??'')){
            session_regenerate_id(true);$_SESSION['admin']=true;unset($_SESSION['login_attempts'],$_SESSION['login_window']);header('Location:index.php');exit;
        }
        $_SESSION['login_attempts']=($_SESSION['login_attempts']??0)+1;$error='ACCESS DENIED';
    }
}
?><!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ADMIN LOGIN</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="container"><div class="card"><h1>[ ADMIN TERMINAL ]</h1><?php if($error): ?><p><?=e($error)?></p><?php endif; ?><form method="post"><?=csrf_field()?><input name="user" placeholder="USER" autocomplete="username"><input name="pass" type="password" placeholder="PASSWORD" autocomplete="current-password"><button>EXECUTE</button></form></div></main></body></html>
