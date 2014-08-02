<?php

header('Content-type:text/plain; charset=UTF-8');

require_once("/home/u262578317/public_html/php/twitteroauth.php");

$consumer_key = "1tO1osf89YC0LZ2j1K2ZQQ";
$consumer_secret = "63PgScXldmOP9eqSTRr3ZNKVLX7OwFORQWd2mly2Oc";
$access_token = "1243903304-MobpaZVAiKtARyg3Z92nXWMV9GDe5X7Tl9bASRm";
$access_token_secret = "ZlJTGeadg8ju3FQhvb52xUY3mXoGen0Mxagis7eIo";

$str = file_get_contents( "/home/u262578317/public_html/php/follow.txt" );
$follow = intval($str);

$account = array();

$handle = fopen( "/home/u262578317/public_html/php/id.txt", "r");
while( !feof($handle) ) {
	array_push( $account, intval( fgets( $handle ) ) );
}

fclose( $handle );

$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

echo "<pre>";

$req = $to->OAuthRequest("https://api.twitter.com/1.1/friends/ids.json","GET",array("screen_name"=>"hakatashi_O","cursor"=>"-1","count"=>"5000"));
$result = json_decode($req);
echo "\n\n#### Following List ####\n\n";
var_dump($result);
foreach ($result->ids as $id) {
	if ($id < $account[$follow-4*24*4]) {
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/friendships/destroy.json","POST",array("user_id"=>$id));
		$result = json_decode($req);
		echo "\n\n#### Unfollow Result ####\n\n";
		var_dump($result);
	}
}

