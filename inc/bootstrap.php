<?php
session_start();
$configFile=__DIR__.'/../config.php';
if(file_exists($configFile)) $config=require $configFile; else $config=require __DIR__.'/../config.example.php';
define('SITE_NAME',$config['site_name'] ?? 'TACTICAL BLOG');
define('DATA_DIR',__DIR__.'/../data');
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function load_json($file,$default=[]){$path=DATA_DIR.'/'.$file;if(!is_file($path)) return $default;$raw=file_get_contents($path);$data=json_decode($raw,true);return is_array($data)?$data:$default;}
function save_json($file,$data){if(!is_dir(DATA_DIR)) mkdir(DATA_DIR,0755,true);$tmp=DATA_DIR.'/'.$file.'.tmp';file_put_contents($tmp,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),LOCK_EX);rename($tmp,DATA_DIR.'/'.$file);}
function admin(){return !empty($_SESSION['admin']);}
function require_admin(){if(!admin()){header('Location: login.php');exit;}}
