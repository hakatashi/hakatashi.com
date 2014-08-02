<?php
//出力文字の取得
$setstring = $_GET['setstring'];
if ($setstring == '') {
  $setstring = $_POST['setstring'];
}

//画像の作成
$img = imagecreate(100, 20);

imageantialias($img, true);

//色の作成（背景色）
$backcol = imagecolorallocate($img, 160, 160, 160);
//背景色を塗る
imagefilledrectangle($img, 0, 0, 200, 80, $backcol);

//色の作成（文字）
$col = imagecolorallocate($img, 255, 255, 255);
//文字を書く
imagestring($img, 5, 10, 4, $setstring, $col);

//画像出力
header("Content-type: image/png");
header("Cache-control: no-cache");
imagepng($img);

//画像の消去（メモリの解放）
imagedestroy($img);
