<?php
//�o�͕����̎擾
$setstring = $_GET['setstring'];
if ($setstring == '') {
  $setstring = $_POST['setstring'];
}

//�摜�̍쐬
$img = imagecreate(100, 20);

imageantialias($img, true);

//�F�̍쐬�i�w�i�F�j
$backcol = imagecolorallocate($img, 160, 160, 160);
//�w�i�F��h��
imagefilledrectangle($img, 0, 0, 200, 80, $backcol);

//�F�̍쐬�i�����j
$col = imagecolorallocate($img, 255, 255, 255);
//����������
imagestring($img, 5, 10, 4, $setstring, $col);

//�摜�o��
header("Content-type: image/png");
header("Cache-control: no-cache");
imagepng($img);

//�摜�̏����i�������̉���j
imagedestroy($img);
