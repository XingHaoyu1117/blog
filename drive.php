<?php
require __DIR__ . '/inc/bootstrap.php';

$root = __DIR__ . '/uploads';
if (!is_dir($root)) {
    @mkdir($root, 0755, true);
}

$requestedDir = isset($_GET['dir']) ? (string)$_GET['dir'] : '';
$sub = '';
if ($requestedDir !== '' && $requestedDir !== '.' && $requestedDir !== '..' &&
    preg_match('/^[a-zA-Z0-9_-][a-zA-Z0-9._-]*$/', $requestedDir)) {
    $sub = $requestedDir;
}
$dir = $root . ($sub !== '' ? '/' . $sub : '');
$rootReal = realpath($root);
$dirReal = realpath($dir);
if (!$rootReal || !$dirReal || strpos($dirReal, rtrim($rootReal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR) !== 0 ||
    !is_dir($dirReal) || is_link($dir)) {
    $sub = '';
    $dir = $root;
}

$query = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$files = array();
if (is_dir($dir)) {
    foreach (array_diff(scandir($dir), array('.', '..')) as $name) {
        $path = $dir . '/' . $name;
        if (!is_file($path) || is_link($path)) {
            continue;
        }
        if ($query !== '' && stripos($name, $query) === false) {
            continue;
        }
        $files[] = array('name' => $name, 'size' => filesize($path), 'time' => filemtime($path));
    }
}

usort($files, function ($a, $b) {
    if ($a['time'] === $b['time']) {
        return 0;
    }
    return ($a['time'] > $b['time']) ? -1 : 1;
});

function human_size($bytes)
{
    $units = array('B', 'KB', 'MB', 'GB');
    $index = 0;
    while ($bytes >= 1024 && $index < 3) {
        $bytes /= 1024;
        $index++;
    }
    return round($bytes, 1) . ' ' . $units[$index];
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TACTICAL DRIVE</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<main class="container">
    <section class="hero card">
        <div class="status blink">● STORAGE ONLINE</div>
        <h1>TACTICAL DRIVE</h1>
        <p>PERSONAL FILE STORAGE // <?=count($files)?> FILES</p>
    </section>
    <div class="card">
        <form class="search" method="get">
            <?php if ($sub !== ''): ?><input type="hidden" name="dir" value="<?=e($sub)?>"><?php endif; ?>
            <input name="q" value="<?=e($query)?>" placeholder="SEARCH FILES...">
            <button type="submit">SEARCH</button>
            <?php if ($query !== ''): ?><a href="drive.php<?=($sub !== '' ? '?dir=' . urlencode($sub) : '')?>">CLEAR</a><?php endif; ?>
        </form>
        <h2>&gt;&gt; FILE INDEX</h2>
        <?php if (!$files): ?>
            <p><?=($query !== '' ? 'NO MATCHING FILES' : 'NO FILES AVAILABLE')?></p>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <?php $fileUrl = ($sub !== '' ? $sub . '/' : '') . $file['name']; ?>
                <div class="file-row">
                    <div>
                        <a href="download.php?file=<?=urlencode($fileUrl)?>">&gt; <?=e($file['name'])?></a>
                        <small><?=human_size($file['size'])?> // <?=date('Y-m-d H:i', $file['time'])?></small>
                    </div>
                    <a class="download-btn" href="download.php?file=<?=urlencode($fileUrl)?>">DOWNLOAD</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
