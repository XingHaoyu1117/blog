<?php
require __DIR__.'/inc/bootstrap.php';
if(isset($_GET['file'])){
  $name=basename($_GET['file']);$base=realpath(__DIR__.'/uploads');$path=realpath(__DIR__.'/uploads/'.$name);
  if(!$name||!$base||!$path||dirname($path)!==$base||!is_file($path)){http_response_code(404);exit('FILE NOT FOUND');}
  header('Content-Type: application/octet-stream');header('Content-Length: '.filesize($path));header('Content-Disposition: attachment; filename="'.str_replace('"','',$name).'"');readfile($path);exit;
}
$items=load_json('downloads.json',[]);
?><!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>DOWNLOAD CENTER</title><link rel="stylesheet" href="assets/style.css"></head><body><?php include __DIR__.'/inc/header.php'; ?><main class="container"><div class="card"><h1>[ DOWNLOAD CENTER ]</h1><p><a href="drive.php">→ OPEN PERSONAL DRIVE</a></p><?php if(!$items): ?><p>暂无外部下载资源。</p><?php else: foreach($items as $x): ?><p><a href="<?=e($x['url'])?>" target="_blank" rel="noopener"><?=e($x['name'])?></a></p><?php endforeach;endif;?></div></main><?php include __DIR__.'/inc/footer.php'; ?></body></html>
