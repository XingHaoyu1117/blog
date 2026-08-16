<?php
require __DIR__.'/../inc/bootstrap.php';
require_admin();
$posts = load_json('posts.json', []);
$id = $_GET['id'] ?? '';
$editing = null;
foreach ($posts as $p) { if ((string)$p['id'] === (string)$id) { $editing = $p; break; } }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = array_values(array_filter(array_map('trim', explode(',', $_POST['tags'] ?? ''))));
    if ($title === '' || $content === '') die('INPUT REQUIRED');
    if ($editing) {
        foreach ($posts as &$p) {
            if ((string)$p['id'] === (string)$editing['id']) { $p['title']=$title; $p['content']=$content; $p['tags']=$tags; }
        }
        unset($p);
    } else {
        $maxId = 0;
        foreach ($posts as $post) { $postId=(int)($post['id'] ?? 0); if ($postId>$maxId) $maxId=$postId; }
        $posts[]=['id'=>$maxId+1,'title'=>$title,'content'=>$content,'tags'=>$tags,'date'=>date('Y-m-d'),'views'=>0];
    }
    save_json('posts.json', $posts);
    header('Location: posts.php');
    exit;
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>EDIT LOG</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container">
<div class="card">
<h1>[ <?=$editing ? 'EDIT' : 'NEW'?> LOG ]</h1>
<form method="post">
<?=csrf_field()?>
<input name="title" value="<?=e($editing['title'] ?? '')?>" placeholder="TITLE">
<input name="tags" value="<?=e(implode(',', $editing['tags'] ?? []))?>" placeholder="TAGS (comma separated)">
<textarea name="content" placeholder="CONTENT"><?=e($editing['content'] ?? '')?></textarea>
<button>SAVE</button>
</form>
<p><a href="posts.php">BACK</a></p>
</div>
</main>
</body>
</html>