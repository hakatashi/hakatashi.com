<?php

header('Content-type:text/plain; charset=UTF-8');

require_once("/home/u373071153/public_html/php/twitteroauth.php");

// Consumer keyの値
$consumer_key = "ey0AOKoPla2rrMEs05m81g";
// Consumer secretの値
$consumer_secret = "gbofqMT3pEAlJXWl1NS7USBKPJLi0POkVnYPwPSYw";
// Access Tokenの値
$access_token = "1549636315-IBAjxrc7akqVBGtunLQ69GflfudZnUNHlSf7ucp";
// Access Token Secretの値
$access_token_secret = "rxbPBmgIF0oXIk4oCrPifKXtt7RZJrWKk73wU6TjnuE";

// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

$list = array();

$setting = array("q"=>"RTしたひと OR RTした人 -フォロー","result_type"=>"recent","count"=>100,"include_entities"=>"false");
$req = $to->OAuthRequest("https://api.twitter.com/1.1/search/tweets.json","GET",$setting);
$result = json_decode($req);
echo "\n\n#### Search Setting ####\n\n";
print_r($setting);
echo "\n\n#### Search Result ####\n\n";
var_dump($result);

$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/retweet/".$result->statuses[0]->id_str.".json","POST");
$result = json_decode($req);
echo "\n\n#### Favoriting Result ####\n\n";
var_dump($result);
