<?php
require __DIR__ . '/../inc/bootstrap.php';
require_admin();

$dir = __DIR__ . '/../uploads';
if (!is_dir($dir)) {
    @mkdir($dir, 0755, true);
}

$msg = '';
$allowed = array('zip', 'pdf', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'mp3', 'mp4', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', '7z', 'rar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if (isset($_POST['delete'])) {
        $name = basename((string)$_POST['delete']);
        $path = $dir . '/' . $name;
        if (is_file($path) && !is_link($path)) {
            $msg = @unlink($path) ? 'DELETE OK' : 'DELETE FAILED';
        } else {
            $msg = 'FILE NOT FOUND';
        }
    } elseif (isset($_FILES['file'])) {
        $upload = $_FILES['file'];
        if (!isset($upload['error']) || is_array($upload['error'])) {
            $msg = 'UPLOAD ERROR: INVALID FILE DATA';
        } elseif ($upload['error'] !== UPLOAD_ERR_OK) {
            switch ($upload['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $msg = 'UPLOAD ERROR: FILE TOO LARGE';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $msg = 'UPLOAD ERROR: UPLOAD INTERRUPTED';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $msg = 'NO FILE SELECTED';
                    break;
                default:
                    $msg = 'UPLOAD ERROR: ' . (int)$upload['error'];
                    break;
            }
        } elseif (empty($upload['tmp_name']) || !is_uploaded_file($upload['tmp_name'])) {
            $msg = 'UPLOAD ERROR: INVALID TEMPORARY FILE';
        } else {
            $originalName = basename(str_replace('\\', '/', (string)$upload['name']));
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $maxBytes = 100 * 1024 * 1024;

            if (!in_array($extension, $allowed, true)) {
                $msg = 'FILE TYPE NOT ALLOWED';
            } elseif ((int)$upload['size'] > $maxBytes) {
                $msg = 'FILE TOO LARGE (100MB)';
            } else {
                $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                if ($safeName === '' || $safeName[0] === '.') {
                    $safeName = 'file_' . date('Ymd_His') . ($extension !== '' ? '.' . $extension : '');
                }

                $baseName = pathinfo($safeName, PATHINFO_FILENAME);
                $suffix = pathinfo($safeName, PATHINFO_EXTENSION);
                $candidate = $safeName;
                $counter = 1;
                while (file_exists($dir . '/' . $candidate)) {
                    $candidate = $baseName . '_' . $counter . ($suffix !== '' ? '.' . $suffix : '');
                    $counter++;
                }

                $target = $dir . '/' . $candidate;
                if (move_uploaded_file($upload['tmp_name'], $target)) {
                    @chmod($target, 0644);
                    $msg = 'UPLOAD OK: ' . $candidate;
                } else {
                    $msg = 'UPLOAD FAILED';
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>FILE MANAGER</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container">
<div class="card">
    <div class="status blink">● STORAGE ADMIN ONLINE</div>
    <h1>[ FILE MANAGER ]</h1>
    <?php if ($msg !== ''): ?><p class="status"><?=e($msg)?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <?=csrf_field()?>
        <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
        <input type="file" name="file" required>
        <br><br>
        <button type="submit">UPLOAD</button>
    </form>
    <p>MAX FILE SIZE: 100 MB (SERVER LIMITS ALSO APPLY)</p>
    <h3>&gt;&gt; STORAGE INDEX</h3>
    <?php
    $files = is_dir($dir) ? array_diff(scandir($dir), array('.', '..')) : array();
    foreach ($files as $fileName):
        $filePath = $dir . '/' . $fileName;
        if (!is_file($filePath) || is_link($filePath)) continue;
    ?>
    <div class="file-row">
        <div>
            <a href="../download.php?file=<?=urlencode($fileName)?>"><?=e($fileName)?></a>
            <small><?=round(filesize($filePath) / 1024 / 1024, 2)?> MB</small>
        </div>
        <form method="post" onsubmit="return confirm('DELETE FILE?')">
            <?=csrf_field()?>
            <input type="hidden" name="delete" value="<?=e($fileName)?>">
            <button type="submit">DELETE</button>
        </form>
    </div>
    <?php endforeach; ?>
    <p><a href="dashboard.php">← BACK</a> | <a href="../drive.php" target="_blank" rel="noopener">OPEN DRIVE</a></p>
</div>
</main>
</body>
</html>
