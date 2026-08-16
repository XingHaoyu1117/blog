<?php
require __DIR__.'/../inc/bootstrap.php';
require_admin();

$dir = __DIR__.'/../uploads';

if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$msg = '';

$allowed = array(
    'zip','pdf','txt','jpg','jpeg','png','gif','webp','mp3','mp4','doc','docx','xls','xlsx','ppt','pptx','7z','rar'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if (isset($_POST['delete'])) {
        $f = basename($_POST['delete']);
        $p = $dir . '/' . $f;
        if (is_file($p)) { unlink($p); $msg = 'DELETE OK'; }
        else { $msg = 'FILE NOT FOUND'; }
    }
    elseif (isset($_FILES['file'])) {
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $error = $_FILES['file']['error'];
            switch ($error) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $msg = 'UPLOAD ERROR: FILE TOO LARGE'; break;
                case UPLOAD_ERR_PARTIAL:
                    $msg = 'UPLOAD ERROR: UPLOAD INTERRUPTED'; break;
                case UPLOAD_ERR_NO_FILE:
                    $msg = 'NO FILE SELECTED'; break;
                default:
                    $msg = 'UPLOAD ERROR: ' . $error; break;
            }
        } else {
            $name = basename($_FILES['file']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $max = 100 * 1024 * 1024;
            if (!in_array($ext, $allowed, true)) {
                $msg = 'FILE TYPE NOT ALLOWED';
            }
            elseif ($_FILES['file']['size'] > $max) {
                $msg = 'FILE TOO LARGE (100MB)';
            }
            else {
                $safe = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
                $target = $dir . '/' . $safe;
                if (file_exists($target)) {
                    $base = pathinfo($safe, PATHINFO_FILENAME);
                    $extension = pathinfo($safe, PATHINFO_EXTENSION);
                    $i = 1;
                    do {
                        $newName = $extension !== '' ? $base . '_' . $i . '.' . $extension : $base . '_' . $i;
                        $target = $dir . '/' . $newName;
                        $i++;
                    } while (file_exists($target));
                    $safe = $newName;
                }
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) $msg = 'UPLOAD OK: ' . $safe;
                else $msg = 'UPLOAD FAILED';
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
<input type="file" name="file" required>
<br><br>
<button type="submit">UPLOAD</button>
</form>
<p>MAX FILE SIZE: 100 MB</p>
<h3>&gt;&gt; STORAGE INDEX</h3>
<?php
$files = array_diff(scandir($dir), array('.', '..'));
foreach ($files as $f):
    $p = $dir . '/' . $f;
    if (!is_file($p)) continue;
?>
<div class="file-row">
<div>
<a href="../download.php?file=<?=urlencode($f)?>"><?=e($f)?></a>
<small><?=round(filesize($p) / 1024 / 1024, 2)?> MB</small>
</div>
<form method="post" onsubmit="return confirm('DELETE FILE?')">
<?=csrf_field()?>
<input type="hidden" name="delete" value="<?=e($f)?>">
<button type="submit">DELETE</button>
</form>
</div>
<?php endforeach; ?>
<p>
<a href="dashboard.php">← BACK</a>
|
<a href="../drive.php" target="_blank">OPEN DRIVE</a>
</p>
</div>
</main>
</body>
</html>