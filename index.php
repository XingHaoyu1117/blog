<?php
require __DIR__ . '/inc/bootstrap.php';
$posts = load_json('posts.json', [
    ['id'=>1,'title'=>'欢迎来到我的博客','content'=>'这里是第一篇日志。以后会在这里记录技术、游戏和生活。','date'=>'2026-08-14']
]);
?><!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e(SITE_NAME) ?></title><link rel="stylesheet" href="assets/style.css"></head><body><?php include __DIR__.'/inc/header.php'; ?><main class="container"><section class="hero card"><div class="status blink">● ONLINE</div><h1>TACTICAL BLOG</h1><p>个人记录 / 技术 / 游戏 / 日常</p></section><h2 class="section-title">&gt;&gt; DATA LOGS</h2><?php foreach(array_reverse($posts) as $p): ?><article class="card"><div class="post-meta">LOG-<?= e($p['id']) ?> // <?= e($p['date'] ?? '') ?></div><a href="post.php?id=<?= urlencode($p['id']) ?>"><div class="post-title">&gt; <?= e($p['title']) ?></div></a><div><?= e(mb_substr($p['content'],0,120)) ?><?= mb_strlen($p['content'])>120?'...':'' ?></div></article><?php endforeach; ?></main><?php include __DIR__.'/inc/footer.php'; ?></body></html>
