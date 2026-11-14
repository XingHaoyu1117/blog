<?php
require __DIR__ . '/inc/bootstrap.php';

$posts = load_json('posts.json', array());
$id = isset($_GET['id']) ? (string)$_GET['id'] : '';
$post = null;
$postIndex = null;

foreach ($posts as $index => $item) {
    if (isset($item['id']) && (string)$item['id'] === $id) {
        $post = $item;
        $postIndex = $index;
        break;
    }
}

if ($post !== null && $postIndex !== null) {
    if (!isset($_SESSION['post_viewed']) || !is_array($_SESSION['post_viewed'])) {
        $_SESSION['post_viewed'] = array();
    }

    $now = time();
    foreach ($_SESSION['post_viewed'] as $viewedId => $viewedAt) {
        if ($now - (int)$viewedAt >= 1800) {
            unset($_SESSION['post_viewed'][$viewedId]);
        }
    }

    if (!isset($_SESSION['post_viewed'][$id])) {
        $posts[$postIndex]['views'] = (int)(isset($post['views']) ? $post['views'] : 0) + 1;
        if (save_json('posts.json', $posts)) {
            $post = $posts[$postIndex];
            $_SESSION['post_viewed'][$id] = $now;
        }
    }
}

?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e(isset($post['title']) ? $post['title'] : 'DATA NOT FOUND')?></title>
<meta name="description" content="<?=e(isset($post['title']) ? $post['title'] : 'TACTICAL BLOG article')?>">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<main class="container">
    <article class="card">
        <?php if ($post !== null): ?>
            <div class="post-meta">[ DATA LOG ] <?=e(isset($post['date']) ? $post['date'] : '')?> // VIEWS <?=e(isset($post['views']) ? $post['views'] : 0)?></div>
            <h1><?=e(isset($post['title']) ? $post['title'] : '')?></h1>
            <?php if (!empty($post['tags']) && is_array($post['tags'])): ?>
                <div class="tags">
                    <?php foreach ($post['tags'] as $postTag): ?>
                        <a href="index.php?tag=<?=urlencode($postTag)?>">#<?=e($postTag)?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="article-content"><?=nl2br(e(isset($post['content']) ? $post['content'] : ''))?></div>
        <?php else: ?>
            <h1>DATA NOT FOUND</h1>
            <p><a href="index.php">&lt; RETURN TO BASE</a></p>
        <?php endif; ?>
    </article>
</main>
<?php include __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
