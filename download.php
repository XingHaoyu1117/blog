<?php
require __DIR__.'/inc/bootstrap.php';

// 有 file 参数时执行下载
if (isset($_GET['file']) && $_GET['file'] !== '') {
    $name = str_replace('\\', '/', $_GET['file']);
    $parts = array_values(array_filter(explode('/', $name), fn($x) => $x !== '' && $x !== '.' && $x !== '..'));
    $safe = implode('/', $parts);
    $base = realpath(__DIR__.'/uploads');
    $path = realpath(__DIR__.'/uploads/'.$safe);
    if (!$safe || !$base || !$path || strpos($path, $base.DIRECTORY_SEPARATOR) !== 0 || !is_file($path)) {
        http_response_code(404);
        exit('FILE NOT FOUND');
    }
    $file = basename($path);
    header('Content-Type: application/octet-stream');
    header('Content-Length: '.filesize($path));
    header('Content-Disposition: attachment; filename="'.str_replace('"', '', $file).'"');
    readfile($path);
    exit;
}

// 无参数时显示下载文件列表页面
$uploadDir = __DIR__.'/uploads';
$files = [];
if (is_dir($uploadDir)) {
    $entries = scandir($uploadDir);
    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        $fullPath = $uploadDir.'/'.$entry;
        if (is_file($fullPath)) {
            $files[] = [
                'name' => $entry,
                'size' => filesize($fullPath),
                'time' => filemtime($fullPath),
            ];
        }
    }
}

function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2).' '.$units[$i];
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
<?php include __DIR__.'/inc/header.php'; ?>
<main class="container">
<div class="card">
<h1>[ DOWNLOAD ]</h1>
<?php if (empty($files)): ?>
<p>> 暂无可用文件。</p>
<?php else: ?>
<p>> 共 <?php echo count($files); ?> 个文件可下载：</p>
<ul>
<?php foreach ($files as $f): ?>
<li>
<a href="download.php?file=<?php echo urlencode($f['name']); ?>">
[<?php echo date('Y-m-d', $f['time']); ?>] <?php echo htmlspecialchars($f['name']); ?>
</a>
<span style="opacity:0.6;"> — <?php echo formatSize($f['size']); ?></span>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>
</main>
<?php include __DIR__.'/inc/footer.php'; ?>
</body>
</html>