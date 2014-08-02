<?php

//自動診断メーカー

//$ch1 = curl_init("http://shindanmaker.com/c/list?mode=pickup");
$ch1 = curl_init("http://shindanmaker.com/c/list");

curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_HEADER, 0);

$result = curl_exec($ch1);
curl_close($ch1);

preg_match( "/<a class=\"list_title\" href=\"\/(.*?)\">/i", $result, $match);

$id = $match[1];

$ch = curl_init("http://shindanmaker.com/".$id);

curl_setopt($ch, CURLOPT_POST, 1);

$post = "u=博多市";

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$result = curl_exec($ch);
curl_close($ch);

$result = str_replace(array("\r\n","\n","\r"), '<br>', $result);

preg_match( "/data-text=\"(.*?)\"/i", $result, $match);

$shindan = str_replace( '<br>', "\r\n", $match[1]);

$tweet_ent = $shindan." http://shindanmaker.com/".$id;

echo $tweet_ent;
