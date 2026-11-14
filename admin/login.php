<?php
require __DIR__ . '/../inc/bootstrap.php';

if (admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$now = time();
$window = 600;
$maxAttempts = 5;
$remoteAddress = isset($_SERVER['REMOTE_ADDR']) ? (string)$_SERVER['REMOTE_ADDR'] : 'unknown';
$throttleKey = hash('sha256', $remoteAddress);
$throttleFile = DATA_DIR . '/login_throttle.json';
$lockFile = DATA_DIR . '/login_throttle.lock';

if (!is_dir(DATA_DIR)) {
    @mkdir(DATA_DIR, 0755, true);
}

$lockHandle = @fopen($lockFile, 'c');
if ($lockHandle === false || !flock($lockHandle, LOCK_EX)) {
    http_response_code(503);
    exit('LOGIN TEMPORARILY UNAVAILABLE');
}

$attemptsData = array();
if (is_file($throttleFile)) {
    $rawAttempts = @file_get_contents($throttleFile);
    $decodedAttempts = json_decode((string)$rawAttempts, true);
    if (is_array($decodedAttempts)) {
        $attemptsData = $decodedAttempts;
    }
}

// Expired entries are removed while the shared throttle lock is held.
foreach ($attemptsData as $key => $record) {
    if (!is_array($record) || !isset($record['started']) || $now - (int)$record['started'] >= $window) {
        unset($attemptsData[$key]);
    }
}

$current = isset($attemptsData[$throttleKey]) ? $attemptsData[$throttleKey] : array('started' => $now, 'attempts' => 0);
$locked = isset($current['attempts']) && (int)$current['attempts'] >= $maxAttempts;

$saveAttempts = function ($data) use ($throttleFile) {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    $temporaryFile = $throttleFile . '.' . uniqid('tmp_', true);
    $written = @file_put_contents($temporaryFile, $json, LOCK_EX);
    if ($written === false || $written !== strlen($json) || !@rename($temporaryFile, $throttleFile)) {
        if (is_file($temporaryFile)) {
            @unlink($temporaryFile);
        }
        return false;
    }
    return true;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($locked) {
        http_response_code(429);
        $error = 'TOO MANY LOGIN ATTEMPTS. TRY AGAIN IN 10 MINUTES.';
    } else {
        verify_csrf();
        $username = trim(isset($_POST['user']) ? (string)$_POST['user'] : '');
        $password = isset($_POST['pass']) ? (string)$_POST['pass'] : '';
        $expectedUser = isset($config['admin_user']) ? (string)$config['admin_user'] : 'admin';
        $passwordHash = isset($config['admin_password_hash']) ? (string)$config['admin_password_hash'] : '';

        if (hash_equals($expectedUser, $username) && $passwordHash !== '' && password_verify($password, $passwordHash)) {
            unset($attemptsData[$throttleKey]);
            $saveAttempts($attemptsData);
            flock($lockHandle, LOCK_UN);
            fclose($lockHandle);

            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: index.php');
            exit;
        }

        if (!isset($attemptsData[$throttleKey])) {
            $attemptsData[$throttleKey] = array('started' => $now, 'attempts' => 0);
        }
        $attemptsData[$throttleKey]['attempts'] = (int)$attemptsData[$throttleKey]['attempts'] + 1;
        if (!$saveAttempts($attemptsData)) {
            http_response_code(503);
            $error = 'LOGIN RATE LIMIT STORAGE FAILED. TRY AGAIN LATER.';
        } else {
            $error = 'ACCESS DENIED';
        }
    }
}

flock($lockHandle, LOCK_UN);
fclose($lockHandle);
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ADMIN LOGIN</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container">
    <div class="card">
        <h1>[ ADMIN TERMINAL ]</h1>
        <?php if ($error !== ''): ?><p class="status"><?=e($error)?></p><?php endif; ?>
        <form method="post">
            <?=csrf_field()?>
            <input name="user" placeholder="USER" autocomplete="username" required>
            <input name="pass" type="password" placeholder="PASSWORD" autocomplete="current-password" required>
            <button type="submit">EXECUTE</button>
        </form>
    </div>
</main>
</body>
</html>
