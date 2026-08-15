<?php
// Core bootstrap and security helpers. PHP 7.0 compatible.
$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(0, '/', '', $isHttps, true);
    session_start();
    // PHP 7.0 has no SameSite option in session_set_cookie_params().
    // Re-issue the session cookie with SameSite=Lax when possible.
    $cookie = session_name() . '=' . session_id() . '; Path=/; HttpOnly; SameSite=Lax';
    if ($isHttps) $cookie .= '; Secure';
    header('Set-Cookie: ' . $cookie, false);
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
if ($isHttps) header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

$configFile=__DIR__.'/../config.php';
if(file_exists($configFile)) $config=require $configFile; else $config=require __DIR__.'/../config.example.php';
define('SITE_NAME',isset($config['site_name']) ? $config['site_name'] : 'TACTICAL BLOG');
define('DATA_DIR',__DIR__.'/../data');
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function load_json($file,$default=array()){$path=DATA_DIR.'/'.$file;if(!is_file($path)) return $default;$raw=file_get_contents($path);$data=json_decode($raw,true);return is_array($data)?$data:$default;}
function save_json($file,$data){if(!is_dir(DATA_DIR)) mkdir(DATA_DIR,0755,true);$tmp=DATA_DIR.'/'.$file.'.tmp';file_put_contents($tmp,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),LOCK_EX);rename($tmp,DATA_DIR.'/'.$file);}
function admin(){return !empty($_SESSION['admin']);}
function require_admin(){if(!admin()){header('Location: login.php');exit;}}
function csrf_token(){if(empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32));return $_SESSION['csrf_token'];}
function csrf_field(){return '<input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">';}
function verify_csrf(){
    $sessionToken=isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
    $postToken=isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if(!hash_equals($sessionToken,$postToken)){http_response_code(403);exit('CSRF VALIDATION FAILED');}
}
function site_settings(){return load_json('settings.json',array('site_name'=>SITE_NAME,'tagline'=>'个人记录 / 技术 / 游戏 / 日常','announcement'=>'SYSTEM ONLINE'));}
