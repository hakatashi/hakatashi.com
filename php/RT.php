<?php

// twitteroauth.phpを読み込む。パスはあなたが置いた適切な場所に変更してください
require_once("twitteroauth.php");

$filename = "since.txt";
$str = file_get_contents( $filename );

$since = intval($str);

// Consumer keyの値
$consumer_key = "jup9czfMTcjuCv36NRYAOg";
// Consumer secretの値
$consumer_secret = "JzSKOucMSs6UdNizRu3ZN9xdE5vNTLaEyE5Duq3ld7M";
// Access Tokenの値
$access_token = "1227233078-UwfUZ3blTvey3A1gwGQyrIhEYHkmGw1uhwrFL8y";
// Access Token Secretの値
$access_token_secret = "igqRnPWQRGAzmhGFxUe2nGxFmR4kSDgpyd3EJgzqdT4";
// Access Tokenの値
$access_token2 = "1227334933-TB3yLSHuQIpXgqoUMV0IEQeyFYkY7LAyHj98fyA";
// Access Token Secretの値
$access_token_secret2 = "Xk6uhfMYy8RH5CweJwnjwppWahL8WDcKozAfofMjo";

// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
// OAuthオブジェクト生成
$to2 = new TwitterOAuth($consumer_key,$consumer_secret,$access_token2,$access_token_secret2);

$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/user_timeline.json","GET",array("screen_name"=>"tehutehuaple","count"=>"200"));

// Twitterから返されたJSONをデコードする
$result = json_decode($req);

// foreachで呟きの分だけループする
foreach($result as $status){
	$status_id = $status->id_str; // 呟きのステータスID
	$text = $status->text; // 呟き
	$user_id = $status->user->id_str; // ID（数字）
	$screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
	$name = $status->user->name; // ユーザーの名前（HNなど）
	if (preg_match("/^RT/", $text)) {
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/destroy/".$status_id.".json","POST",array());
	}
}

$req = $to2->OAuthRequest("https://api.twitter.com/1.1/statuses/user_timeline.json","GET",array("screen_name"=>"tehutehuappie","count"=>"200"));

// Twitterから返されたJSONをデコードする
$result = json_decode($req);

// foreachで呟きの分だけループする
foreach($result as $status){
	$status_id = $status->id_str; // 呟きのステータスID
	$text = $status->text; // 呟き
	$user_id = $status->user->id_str; // ID（数字）
	$screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
	$name = $status->user->name; // ユーザーの名前（HNなど）
	if (preg_match("/^RT/", $text)) {
		$req = $to2->OAuthRequest("https://api.twitter.com/1.1/statuses/destroy/".$status_id.".json","POST",array());
	}
}

// home_timelineの取得。TwitterからXML形式が返ってくる
$req = $to->OAuthRequest("https://api.twitter.com/1.1/search/tweets.json","GET",array("q"=>"#rtした人全員フォローする OR #rtした人フォローする OR #rtした人で気になる人フォローする OR #rtしたひと全員フォローする OR #rtした人を全員フォローする","result_type"=>"recent","count"=>"20"));

// Twitterから返されたJSONをデコードする
$result = json_decode($req);

//echo "<pre>";
//var_dump($result);

$i = 0;

// foreachで呟きの分だけループする
foreach($result->statuses as $status){
	$status_id = $status->id_str; // 呟きのステータスID
	$text = $status->text; // 呟き
	$user_id = $status->user->id_str; // ID（数字）
	$screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
	$name = $status->user->name; // ユーザーの名前（HNなど）
	$uri = "https://api.twitter.com/1.1/statuses/retweet/".$status_id.".json";
	if ($i%2==0) {
		$req = $to->OAuthRequest($uri,"POST",array());
	} else {
		$req = $to2->OAuthRequest($uri,"POST",array());
	}
	$i++;
}

// home_timelineの取得。TwitterからXML形式が返ってくる
$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/user_timeline.json","GET",array("count"=>"200","screen_name"=>"tehutehuapple","since_id"=>"$since","include_rts"=>"false"));

// Twitterから返されたJSONをデコードする
$result = json_decode($req);

// foreachで呟きの分だけループする
foreach($result as $status){
	$status_id = $status->id_str; // 呟きのステータスID
	$text = $status->text; // 呟き
	$user_id = $status->user->id_str; // ID（数字）
	$screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
	$name = $status->user->name; // ユーザーの名前（HNなど）
	if (!preg_match("/@/", $text)) {
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>"$text"));
		$req = $to2->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>"$text"));
	}
	if (intval($status_id)>$since) $since = intval($status_id);
}

$file = fopen( $filename, "w" );
file_put_contents( $filename, $since );

echo "<pre>";
$userlist = "";
$i = 0;
$flag = 0;

while($flag != 2 || $i == 100) {
	$temp = $i+1;
	$req = $to->OAuthRequest("https://api.twitter.com/1.1/users/search.json","GET",array("q"=>"リフォロー100%","count"=>"20","page"=>"$temp"));
	$result = json_decode($req);
	foreach($result as $status){
		if (!($userlist === "")) $userlist .= ",";
		$userlist .= $status->id_str; // ステータスID
	}
	$req1 = $to->OAuthRequest("https://api.twitter.com/1.1/friendships/lookup.json","GET",array("user_id"=>"$userlist"));
	$result1 = json_decode($req1);
	$req2 = $to2->OAuthRequest("https://api.twitter.com/1.1/friendships/lookup.json","GET",array("user_id"=>"$userlist"));
	$result2 = json_decode($req2);
	$accept1 = array();
	$accept2 = array();
	$accept = array();
	foreach($result1 as $status){
		if ($status->connections[0] === "none") array_push($accept1, $status->id_str);
	}
	print_r($accept1);
	foreach($result2 as $status){
		if ($status->connections[0] === "none") array_push($accept2, $status->id_str);
	}
	print_r($accept2);
	foreach($accept1 as $tmpusr) {
		if (array_search($tmpusr,$accept2) !== FALSE) array_push($accept, $tmpusr);
	}
	print_r($accept);
	foreach($accept as $tmpusr) {
		if ($flag == 0) {
			$to->OAuthRequest("https://api.twitter.com/1.1/friendships/create.json","POST",array("user_id"=>"$tmpusr","follow"=>"true"));
			$flag = 1;
			echo "followed ".$tmpusr."<br />";
			continue;
		}
		if ($flag == 1) {
			$to2->OAuthRequest("https://api.twitter.com/1.1/friendships/create.json","POST",array("user_id"=>"$tmpusr","follow"=>"true"));
			$flag = 2;
			echo "followed ".$tmpusr."<br />";
			break;
		}
	}
	$i++;
}

?>