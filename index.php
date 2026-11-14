<?php
require __DIR__ . '/inc/bootstrap.php';

$settings = site_settings();
$posts = load_json('posts.json', array());
$query = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$tag = isset($_GET['tag']) ? trim((string)$_GET['tag']) : '';

if ($query !== '' || $tag !== '') {
    $posts = array_values(array_filter($posts, function ($post) use ($query, $tag) {
        $title = isset($post['title']) ? (string)$post['title'] : '';
        $content = isset($post['content']) ? (string)$post['content'] : '';
        $matches = ($query === '' || stripos($title . ' ' . $content, $query) !== false);
        if ($matches && $tag !== '') {
            $tags = isset($post['tags']) && is_array($post['tags']) ? $post['tags'] : array();
            $matches = in_array($tag, $tags, true);
        }
        return $matches;
    }));
}

function blog_excerpt($text, $length)
{
    $text = (string)$text;
    if (function_exists('mb_substr') && function_exists('mb_strlen')) {
        return mb_strlen($text, 'UTF-8') > $length ? mb_substr($text, 0, $length, 'UTF-8') . '...' : $text;
    }
    if (preg_match('/^(.{0,' . (int)$length . '})/us', $text, $matches)) {
        $excerpt = $matches[1];
        return strlen($excerpt) < strlen($text) ? $excerpt . '...' : $excerpt;
    }
    return substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($settings['site_name'])?></title>
<meta name="description" content="<?=e(isset($settings['tagline']) ? $settings['tagline'] : '个人博客与文件存储')?>">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<main class="container">
    <section class="hero card">
        <div class="status blink">● ONLINE</div>
        <h1><?=e($settings['site_name'])?></h1>
        <p><?=e(isset($settings['tagline']) ? $settings['tagline'] : '')?></p>
        <p class="announcement">&gt; <?=e(isset($settings['announcement']) ? $settings['announcement'] : '')?></p>
    </section>
    <form class="search" method="get">
        <?php if ($tag !== ''): ?><input type="hidden" name="tag" value="<?=e($tag)?>"><?php endif; ?>
        <input name="q" value="<?=e($query)?>" placeholder="SEARCH DATA LOGS...">
        <button type="submit">SEARCH</button>
    </form>
    <h2 class="section-title">&gt;&gt; DATA LOGS</h2>
    <?php if (!$posts): ?><div class="card">NO DATA FOUND</div><?php endif; ?>
    <?php foreach (array_reverse($posts) as $post): ?>
        <?php $content = isset($post['content']) ? $post['content'] : ''; ?>
        <article class="card">
            <div class="post-meta">LOG-<?=e(isset($post['id']) ? $post['id'] : '')?> // <?=e(isset($post['date']) ? $post['date'] : '')?> // VIEWS <?=e(isset($post['views']) ? $post['views'] : 0)?></div>
            <a href="post.php?id=<?=urlencode(isset($post['id']) ? $post['id'] : '')?>"><div class="post-title">&gt; <?=e(isset($post['title']) ? $post['title'] : '')?></div></a>
            <div><?=e(blog_excerpt($content, 120))?></div>
            <?php if (!empty($post['tags']) && is_array($post['tags'])): ?>
                <div class="tags">
                    <?php foreach ($post['tags'] as $postTag): ?>
                        <a href="?tag=<?=urlencode($postTag)?>">#<?=e($postTag)?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</main>
<?php include __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
