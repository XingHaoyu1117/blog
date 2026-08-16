<?php
http_response_code(404);
require __DIR__.'/inc/bootstrap.php';
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>404 - NOT FOUND</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__.'/inc/header.php'; ?>
<main class="container">
<div class="card">
<h1>[ 404 ]</h1>
<p>> ERROR: 请求的资源不存在。</p>
<p>> 你访问的页面可能已被移动、删除或从未存在。</p>
<p><a href="index.php">>> 返回首页</a></p>
</div>
</main>
<?php include __DIR__.'/inc/footer.php'; ?>
</body>
</html>