<?php
require __DIR__.'/inc/bootstrap.php';
$dir=__DIR__.'/uploads';
if(!is_dir($dir)) mkdir($dir,0755,true);
$files=[];
foreach(array_diff(scandir($dir),['.','..']) as $f){$path=$dir.'/'.$f;if(is_file($path))$files[]=['name'=>$f,'size'=>filesize($path),'time'=>filemtime($path)];}
usort($files,fn($a,$b)=>$b['time']<=>$a['time']);
function human_size($n){$u=['B','KB','MB','GB'];$i=0;while($n>=1024&&$i<3){$n/=1024;$i++;}return round($n,1).' '.$u[$i];}
?><!doctype html><html lang="zh-CN"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>TACTICAL DRIVE</title><link rel="stylesheet" href="assets/style.css"></head><body><?php include __DIR__.'/inc/header.php'; ?><main class="container"><section class="hero card"><div class="status blink">● STORAGE ONLINE</div><h1>TACTICAL DRIVE</h1><p>PERSONAL FILE STORAGE // <?=count($files)?> FILES</p></section><div class="card"><h2>&gt;&gt; FILE INDEX</h2><?php if(!$files): ?><p>NO FILES AVAILABLE</p><?php else: foreach($files as $f): ?><div class="file-row"><div><a href="download.php?file=<?=urlencode($f['name'])?>">&gt; <?=e($f['name'])?></a><small><?=human_size($f['size'])?> // <?=date('Y-m-d H:i',$f['time'])?></small></div><a class="download-btn" href="download.php?file=<?=urlencode($f['name'])?>">DOWNLOAD</a></div><?php endforeach;endif;?></div></main><?php include __DIR__.'/inc/footer.php'; ?></body></html>
