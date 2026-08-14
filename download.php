<?php
require __DIR__.'/inc/bootstrap.php';
$name=str_replace('\\','/',$_GET['file']??'');$parts=array_values(array_filter(explode('/',$name),fn($x)=>$x!==''&&$x!=='.'&&$x!=='..'));$safe=implode('/',$parts);$base=realpath(__DIR__.'/uploads');$path=realpath(__DIR__.'/uploads/'.$safe);
if(!$safe||!$base||!$path||strpos($path,$base.DIRECTORY_SEPARATOR)!==0||!is_file($path)){http_response_code(404);exit('FILE NOT FOUND');}
$file=basename($path);header('Content-Type: application/octet-stream');header('Content-Length: '.filesize($path));header('Content-Disposition: attachment; filename="'.str_replace('"','',$file).'"');readfile($path);exit;
