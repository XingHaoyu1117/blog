<?php
// Shared bootstrap and security helpers. Keep syntax compatible with PHP 7.0.
$isHttps = !empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off';
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params(0, '/', '', $isHttps, true);
    session_start();

    // PHP 7.0 does not expose SameSite through session_set_cookie_params().
    $cookie = session_name() . '=' . session_id() . '; Path=/; HttpOnly; SameSite=Lax';
    if ($isHttps) {
        $cookie .= '; Secure';
    }
    header('Set-Cookie: ' . $cookie, false);
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

$configFile = __DIR__ . '/../config.php';
if (is_file($configFile)) {
    $config = require $configFile;
} else {
    $config = require __DIR__ . '/../config.example.php';
}
if (!is_array($config)) {
    $config = array();
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', isset($config['site_name']) ? $config['site_name'] : 'TACTICAL BLOG');
}
if (!defined('DATA_DIR')) {
    define('DATA_DIR', __DIR__ . '/../data');
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function data_file_path($file)
{
    $file = basename((string)$file);
    if ($file === '' || $file === '.' || $file === '..') {
        return false;
    }
    return DATA_DIR . DIRECTORY_SEPARATOR . $file;
}

function load_json($file, $default = array())
{
    $path = data_file_path($file);
    if ($path === false || !is_file($path) || !is_readable($path)) {
        return $default;
    }

    $handle = @fopen($path, 'rb');
    if ($handle === false) {
        return $default;
    }

    $raw = '';
    if (flock($handle, LOCK_SH)) {
        $raw = stream_get_contents($handle);
        flock($handle, LOCK_UN);
    }
    fclose($handle);

    if ($raw === false || $raw === '') {
        return $default;
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

function save_json($file, $data)
{
    $path = data_file_path($file);
    if ($path === false) {
        return false;
    }

    if (!is_dir(DATA_DIR) && !@mkdir(DATA_DIR, 0755, true) && !is_dir(DATA_DIR)) {
        return false;
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }

    // A stable sidecar lock prevents concurrent writers from replacing each other's data.
    $lockHandle = @fopen($path . '.lock', 'c');
    if ($lockHandle === false) {
        return false;
    }

    $saved = false;
    if (flock($lockHandle, LOCK_EX)) {
        $tmp = $path . '.' . uniqid('tmp_', true);
        $bytes = @file_put_contents($tmp, $json, LOCK_EX);
        if ($bytes !== false && $bytes === strlen($json) && @rename($tmp, $path)) {
            $saved = true;
        }
        if (is_file($tmp)) {
            @unlink($tmp);
        }
        flock($lockHandle, LOCK_UN);
    }

    fclose($lockHandle);
    return $saved;
}

function admin()
{
    return !empty($_SESSION['admin']);
}

function require_admin()
{
    if (!admin()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf()
{
    $sessionToken = isset($_SESSION['csrf_token']) ? (string)$_SESSION['csrf_token'] : '';
    $postToken = isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : '';
    if ($sessionToken === '' || $postToken === '' || !hash_equals($sessionToken, $postToken)) {
        http_response_code(403);
        exit('CSRF VALIDATION FAILED');
    }
}

function site_settings()
{
    return load_json('settings.json', array(
        'site_name' => SITE_NAME,
        'tagline' => '个人记录 / 技术 / 游戏 / 日常',
        'announcement' => 'SYSTEM ONLINE'
    ));
}
