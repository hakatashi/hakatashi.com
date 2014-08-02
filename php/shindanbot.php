<?php

header('Content-type:text/plain; charset=UTF-8');

require_once("/home/u262578317/public_html/php/twitteroauth.php");

// Consumer keyの値
$consumer_key = "BEbgsj6Ol532mzXWNkEQ";
// Consumer secretの値
$consumer_secret = "QqG4L21gnvGTaYBE4T28yE1ueyGJ5ESCh6maZWNaA5g";
// Access Tokenの値
$access_token = "1473735499-9rzKD7BlU1Zer75xnKCaM5QQX5VyIEmIxgNvo1P";
// Access Token Secretの値
$access_token_secret = "0nLT5sPbecwEN06WIDR6z05O4YCci8jdBPD041vHPlA";

// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

$list = array();

$ch1 = curl_init("http://tr.twipple.jp/data/famous_ranking/recent.js");

curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_HEADER, 0);

$result = curl_exec($ch1);

curl_close($ch1);

preg_match_all( "/\"keywords\":\[\"(.*?)\"\]/i", $result, $match);

$i=0;

foreach($match[1] as $item) {
	$parse = explode("\",\"",$item,2);
	if ($i<10) array_push( $list, $parse[0] );
	$i++;
}

$ch1 = curl_init("http://tr.twipple.jp/data/ranking/recent.js");

curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_HEADER, 0);

$result = curl_exec($ch1);

curl_close($ch1);

preg_match_all( "/\"word\":\"(.*?)\"/i", $result, $match);

$i=0;

foreach($match[1] as $item) {
	if (!preg_match("/rt/i",$item) && !preg_match("/#/i",$item) && !preg_match("/&/i",$item)) {
		if ($i<20) array_push( $list, $item );
	}
	$i++;
}

$ch1 = curl_init("http://shindanmaker.com/c/list?mode=hot");

curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_HEADER, 0);

$result = curl_exec($ch1);
curl_close($ch1);

preg_match_all( "/<a class=\"list_title\" href=\"\/(.*?)\">/i", $result, $match);

$id = $match[1][rand(0,39)];

$ch = curl_init("http://shindanmaker.com/".$id);

curl_setopt($ch, CURLOPT_POST, 1);

$post = "u=".$list[rand(0,count($list)-1)];

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

$result = curl_exec($ch);
curl_close($ch);

$result = str_replace(array("\r\n","\n","\r"), '<br>', $result);

preg_match( "/data-text=\"(.*?)\"/i", $result, $match);

$shindan = str_replace( '<br>', "\r\n", $match[1]);

$tweet_ent = $shindan." (id=".$id.")";

$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>$tweet_ent));
$result = json_decode($req);
echo "\n\n#### Shindan Result ####\n\n";
var_dump($result);