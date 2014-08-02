<?php

require_once("twitteroauth.php");

$consumer_key = "1tO1osf89YC0LZ2j1K2ZQQ";
$consumer_secret = "63PgScXldmOP9eqSTRr3ZNKVLX7OwFORQWd2mly2Oc";
$access_token = "1243903304-MobpaZVAiKtARyg3Z92nXWMV9GDe5X7Tl9bASRm";
$access_token_secret = "ZlJTGeadg8ju3FQhvb52xUY3mXoGen0Mxagis7eIo";

$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

echo "<pre>";

$max_id=325287638091321344;
$flag=1;

while($flag==1) {
	$req = $to->OAuthRequest("https://api.twitter.com/1.1/lists/statuses.json","GET",array("slug"=>"ut2013","owner_screen_name"=>"ossan_arrow","max_id"=>$max_id,"count"=>"200","incrude_rts"=>"false"));
	$result = json_decode($req);
	var_dump($result);
	
	$flag=0;

	foreach ($result as $status) {
		if ( preg_match("/[0-9]/", $status->text) ) {
			$req = $to->OAuthRequest("https://api.twitter.com/1.1/favorites/create.json","POST",array("id"=>$status->id_str));
		}
		if ( intval($status->id_str) < $max_id ) $max_id = intval($status->id_str);
		$flag=1;
	}
}
