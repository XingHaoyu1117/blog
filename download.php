<?php
require __DIR__ . '/inc/bootstrap.php';

if (isset($_GET['file']) && $_GET['file'] !== '') {
    $name = str_replace('\\', '/', (string)$_GET['file']);
    $parts = explode('/', $name);
    $safeParts = array();

    foreach ($parts as $part) {
        if ($part === '' || $part === '.' || $part === '..' || strpos($part, chr(0)) !== false) {
            http_response_code(404);
            exit('FILE NOT FOUND');
        }
        $safeParts[] = $part;
    }

    $safe = implode('/', $safeParts);
    $base = realpath(__DIR__ . '/uploads');
    $path = realpath(__DIR__ . '/uploads/' . $safe);
    $prefix = $base ? rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '';

    if ($safe === '' || !$base || !$path || strpos($path, $prefix) !== 0 || !is_file($path) || is_link($path)) {
        http_response_code(404);
        exit('FILE NOT FOUND');
    }

    $file = basename($path);
    $asciiName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file);
    if ($asciiName === '' || $asciiName === '.' || $asciiName === '..') {
        $asciiName = 'download';
    }

    header('Content-Type: application/octet-stream');
    header('X-Content-Type-Options: nosniff');
    header('Content-Length: ' . filesize($path));
    header("Content-Disposition: attachment; filename=\"" . $asciiName . "\"; filename*=UTF-8''" . rawurlencode($file));
    readfile($path);
    exit;
}

$uploadDir = __DIR__ . '/uploads';
$files = array();
if (is_dir($uploadDir)) {
    foreach (array_diff(scandir($uploadDir), array('.', '..')) as $entry) {
        $fullPath = $uploadDir . '/' . $entry;
        if (is_file($fullPath) && !is_link($fullPath)) {
            $files[] = array('name' => $entry, 'size' => filesize($fullPath), 'time' => filemtime($fullPath));
        }
    }
}

function formatSize($bytes)
{
    $units = array('B', 'KB', 'MB', 'GB');
    $index = 0;
    while ($bytes >= 1024 && $index < count($units) - 1) {
        $bytes /= 1024;
        $index++;
    }
    return round($bytes, 2) . ' ' . $units[$index];
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>DOWNLOAD</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<main class="container">
    <div class="card">
        <h1>[ DOWNLOAD ]</h1>
        <?php if (empty($files)): ?>
            <p>&gt; 暂无可用文件。</p>
        <?php else: ?>
            <p>&gt; 共 <?=count($files)?> 个文件可下载：</p>
            <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="download.php?file=<?=urlencode($file['name'])?>">
                        [<?=date('Y-m-d', $file['time'])?>] <?=e($file['name'])?>
                    </a>
                    <span style="opacity:0.6;"> — <?=formatSize($file['size'])?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
