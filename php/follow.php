<?php

header('Content-type:text/plain; charset=UTF-8');

require_once("/home/u262578317/public_html/php/twitteroauth.php");

date_default_timezone_set("Asia/Tokyo");

class AccessTokenAuthentication {
	/*
	 * Get the access token.
	 *
	 * @param string $grantType	Grant type.
	 * @param string $scopeUrl	 Application Scope URL.
	 * @param string $clientID	 Application client ID.
	 * @param string $clientSecret Application client ID.
	 * @param string $authUrl	  Oauth Url.
	 *
	 * @return string.
	 */
	function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl){
		try {
			//Initialize the Curl Session.
			$ch = curl_init();
			//Create the request Array.
			$paramArr = array (
				 'grant_type'	=> $grantType,
				 'scope'		 => $scopeUrl,
				 'client_id'	 => $clientID,
				 'client_secret' => $clientSecret
			);
			//Create an Http Query.//
			$paramArr = http_build_query($paramArr);
			//Set the Curl URL.
			curl_setopt($ch, CURLOPT_URL, $authUrl);
			//Set HTTP POST Request.
			curl_setopt($ch, CURLOPT_POST, TRUE);
			//Set data to POST in HTTP "POST" Operation.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
			//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//Execute the  cURL session.
			$strResponse = curl_exec($ch);
			//Get the Error Code returned by Curl.
			$curlErrno = curl_errno($ch);
			if($curlErrno){
				$curlError = curl_error($ch);
				throw new Exception($curlError);
			}
			//Close the Curl Session.
			curl_close($ch);
			//Decode the returned JSON string.
			$objResponse = json_decode($strResponse);
			//print_r($strResponse);
			/*if ($objResponse->error){
				throw new Exception($objResponse->error_description);
			}*/
			return $objResponse->access_token;
		} catch (Exception $e) {
			echo "Exception-".$e->getMessage();
		}
	}
}

/*
 * Class:HTTPTranslator
 *
 * Processing the translator request.
 */
Class HTTPTranslator {
	/*
	 * Create and execute the HTTP CURL request.
	 *
	 * @param string $url		HTTP Url.
	 * @param string $authHeader Authorization Header string.
	 * @param string $postData   Data to post.
	 *
	 * @return string.
	 *
	 */
	function curlRequest($url, $authHeader) {
		//Initialize the Curl Session.
		$ch = curl_init();
		//Set the Curl url.
		curl_setopt ($ch, CURLOPT_URL, $url);
		//Set the HTTP HEADER Fields.
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
		//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
		//Execute the  cURL session.
		$curlResponse = curl_exec($ch);
		//Get the Error Code returned by Curl.
		$curlErrno = curl_errno($ch);
		if ($curlErrno) {
			$curlError = curl_error($ch);
			throw new Exception($curlError);
		}
		//Close a cURL session.
		curl_close($ch);
		return $curlResponse;
	}
}

function doTranslation($inputStr) {
	//Client ID of the application.
	$clientID	   = "HakataTranslate";
	//Client Secret key of the application.
	$clientSecret = "MmqNW1kLnVB78NXnlbTwzFG+XzvRAHh60YM3I9srHhI=";
	//OAuth Url.
	$authUrl	  = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
	//Application Scope Url
	$scopeUrl	 = "http://api.microsofttranslator.com";
	//Application grant type
	$grantType	= "client_credentials";

	//Create the AccessTokenAuthentication object.
	$authObj	  = new AccessTokenAuthentication();
	//Get the Access token.
	$accessToken  = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
	//Create the authorization Header string.
	$authHeader = "Authorization: Bearer ". $accessToken;

	//Set the params.//
	$fromLanguage = "ja";
	$toLanguage   = "en";
	$contentType  = 'text/plain';
	$category	 = 'general';
	
	$params = "text=".urlencode($inputStr)."&to=".$toLanguage."&from=".$fromLanguage;
	$translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
	
	//Create the Translator Object.
	$translatorObj = new HTTPTranslator();
	
	//Get the curlResponse.
	$curlResponse = $translatorObj->curlRequest($translateUrl, $authHeader);
	
	//Interprets a string of XML into an object.
	$xmlObj = simplexml_load_string($curlResponse);
	foreach((array)$xmlObj[0] as $val){
		$translatedStr = $val;
	}
	return $translatedStr;
}


/*
 * Create and execute the HTTP CURL request.
 * 
 * @param string $url		HTTP Url.
 * @param string $authHeader Authorization Header string.
 * @param string $postData   Data to post.
 *
 * @return string.
 *
 */
function curlRequest($url, $authHeader, $postData=''){
	//Initialize the Curl Session.
	$ch = curl_init();
	//Set the Curl url.
	curl_setopt ($ch, CURLOPT_URL, $url);
	//Set the HTTP HEADER Fields.
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
	//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
	//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
	if($postData) {
		//Set HTTP POST Request.
		curl_setopt($ch, CURLOPT_POST, TRUE);
		//Set data to POST in HTTP "POST" Operation.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	}
	//Execute the  cURL session. 
	$curlResponse = curl_exec($ch);
	//Get the Error Code returned by Curl.
	$curlErrno = curl_errno($ch);
	if ($curlErrno) {
		$curlError = curl_error($ch);
		throw new Exception($curlError);
	}
	//Close a cURL session.
	curl_close($ch);
	return $curlResponse;
}

$account = array();
$since = array();

$hakatashi = array( "hakatashi", "hakatashi_A", "hakatashi_B", "hakatashi_O");

$handle = fopen( "/home/u262578317/public_html/php/id.txt", "r");
while( !feof($handle) ) {
	array_push( $account, fgets( $handle ) );
}

fclose( $handle );

$str = file_get_contents( "/home/u262578317/public_html/php/follow.txt" );
$follow = intval($str);

$str = file_get_contents( "/home/u262578317/public_html/php/follow_ik.txt" );
$follow_ik = intval($str);

$str = file_get_contents( "/home/u262578317/public_html/php/since_search.txt" );
$since_search = intval($str);

$str = file_get_contents( "/home/u262578317/public_html/php/percent.txt" );
$since_percent = intval($str);

$handle_s = fopen( "/home/u262578317/public_html/php/since.txt", "r");
while( !feof($handle_s) ) {
	array_push( $since, str_replace(array("\r\n","\n","\r"), '', fgets( $handle_s )) );
}
fclose( $handle_s );

$minute = (int)(floor(time()/60))%60;
$hour = (int)(floor(time()/60/60)+9)%24;

$times = (int)(floor(($minute+1)%60/5));

$time_str = date("(U) F j, Y, G:i:s");

file_put_contents( "/home/u262578317/public_html/php/log.txt", "last exec: $time_str; hour: $hour; minute: $minute; times: $times" );

$consumer_key = "1tO1osf89YC0LZ2j1K2ZQQ";
$consumer_secret = "63PgScXldmOP9eqSTRr3ZNKVLX7OwFORQWd2mly2Oc";
$access_token = "1243903304-MobpaZVAiKtARyg3Z92nXWMV9GDe5X7Tl9bASRm";
$access_token_secret = "ZlJTGeadg8ju3FQhvb52xUY3mXoGen0Mxagis7eIo";

$access_token_ik = "594728279-rFK33eJGWghdW9X9kQxbduNRC9swgSP0zuGs5uks";
$access_token_secret_ik = "DS1B7hpYMUwm2WP1OTSdVRYgW4WsGSUWBaLuWYmMFsQ";

$consumer_key_en = "jWGTzT6eVPi2adGGo0Bw";
$consumer_secret_en = "WdYW3csjySePXnOABHeKTxFv75hDdXrFrbx8rSmYw4";
$access_token_en = "1304451745-ke0Ajnf9EyieRw3YlGqZHMoBK7GIWeZuGTXmGDI";
$access_token_secret_en = "DDSnjrBV9gXiDHtAjOrGQEIE39EmYD0flkuTzNnJDs";

$consumer_key_per = "YJuiCtwEFcaqP2wHYAhe1A";
$consumer_secret_per = "pBK9Qct1vnkZlBx2rNPtkVZClV17xvPibLGSosWI8U";
$access_token_per = "128424456-LsoLsHJJwPcnENN20pll9ty2tnKHAwL8mDPLgdFo";
$access_token_secret_per = "oSTpXXtFXdTBpJGlf95Ywvx6cHXUk1ntf86YWOE8E88";

$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
$to_ik = new TwitterOAuth($consumer_key,$consumer_secret,$access_token_ik,$access_token_secret_ik);
$to_en = new TwitterOAuth($consumer_key_en,$consumer_secret_en,$access_token_en,$access_token_secret_en);
$to_per = new TwitterOAuth($consumer_key_per,$consumer_secret_per,$access_token_per,$access_token_secret_per);

//自動フォロー(hakatashi_O)

if ( $times % 3 == 0 ) {
	if ( $follow < count($account)) {
		$user = $account[$follow];
		$user = str_replace(array("\r\n","\n","\r"), '', $user);
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/friendships/create.json","POST",array("user_id"=>$user,"follow"=>"true"));
		$result = json_decode($req);
		echo "\n\n#### Follow by hakatashi_O ####\n\n";
		var_dump($result);
	}

	if ( $follow >= 4*24*4 && $follow-4*24*4 < count($account)) {
		$user = $account[$follow-4*24*4];
		$user = str_replace(array("\r\n","\n","\r"), '', $user);
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/friendships/destroy.json","POST",array("user_id"=>$user));
		$result = json_decode($req);
		echo "\n\n#### Unfollow by hakatashi_O ####\n\n";
		var_dump($result);
	}
	
	file_put_contents( "/home/u262578317/public_html/php/follow.txt", $follow+1 );
}

//自動フォロー(ikenoue21)

if ( $follow_ik+1 < count($account)) {
	$user = $account[$follow_ik];
	$user = str_replace(array("\r\n","\n","\r"), '', $user);
	$req = $to_ik->OAuthRequest("https://api.twitter.com/1.1/friendships/create.json","POST",array("user_id"=>$user,"follow"=>"true"));
	$result = json_decode($req);
	echo "\n\n#### Follow by ikenoue21 ####\n\n";
	var_dump($result);
}

if ( $follow_ik-4*24*8 >= 0 && $follow_ik-4*24*8 < count($account)) {
	$user = $account[$follow_ik-4*24*8];
	$user = str_replace(array("\r\n","\n","\r"), '', $user);
	$req = $to_ik->OAuthRequest("https://api.twitter.com/1.1/friendships/destroy.json","POST",array("user_id"=>$user));
	$result = json_decode($req);
	echo "\n\n#### Unfollow by ikenoue21 ####\n\n";
	var_dump($result);
}

$user = $account[$follow_ik%1350];
$user = str_replace(array("\r\n","\n","\r"), '', $user);
$req = $to_ik->OAuthRequest("https://api.twitter.com/1.1/friendships/destroy.json","POST",array("user_id"=>$user));
$result = json_decode($req);
echo "\n\n#### Total Unfollow by ikenoue21 ####\n\n";
var_dump($result);

file_put_contents( "/home/u262578317/public_html/php/follow_ik.txt", $follow_ik+1 );

//「博多市」を含むツイートをふぁぼる

$setting = array("q"=>"博多市 OR 博多氏 OR はかたし OR hakatashi","result_type"=>"recent","since_id"=>$since_search,"count"=>100,"include_entities"=>"false");
$req = $to->OAuthRequest("https://api.twitter.com/1.1/search/tweets.json","GET",$setting);
$result = json_decode($req);
echo "\n\n#### Search Setting ####\n\n";
print_r($setting);
echo "\n\n#### Search Result ####\n\n";
var_dump($result);

foreach ($result->statuses as $status) {
	if ( !preg_match("/__int/", $status->user->screen_name ) && !preg_match("/hakatashi/", $status->user->screen_name ) && !preg_match("/臨床実験場/", $status->text ) && !preg_match("/@hakatashi/", $status->text ) ) {
		$req = $to->OAuthRequest("https://api.twitter.com/1.1/favorites/create.json","POST",array("id"=>$status->id_str));
		$result = json_decode($req);
		echo "\n\n#### Favoriting Result ####\n\n";
		var_dump($result);
	}
	if ( intval($status->id_str) > $since_search ) $since_search = intval($status->id_str);
}

file_put_contents( "/home/u262578317/public_html/php/since_search.txt", $since_search );

//自動診断メーカー

if ($times == 0) {
	if ($hour%4==0) {
		$ch1 = curl_init("http://shindanmaker.com/c/list?mode=pickup");
	} else {
		$ch1 = curl_init("http://shindanmaker.com/c/list");
	}

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
	
	$req = $to->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>$tweet_ent));
	$result = json_decode($req);
	echo "\n\n#### Shindan Result ####\n\n";
	var_dump($result);
}

//パーセント解釈

if (0 <= $since_percent && $since_percent < 100) {
	$next_notice = ( mktime( 0, 0, 0, 4, 1, 2017 ) - mktime( 0, 0, 0, 4, 1, 2013 ) ) / 100 * ($since_percent + 1) + mktime( 0, 0, 0, 4, 1, 2013 );
	$current_time = time();
	if ( $current_time >= $next_notice ) {
		$text = "大学生活の".($since_percent+1)."%が終了しました (".date("Y/m/d H:i:s", $next_notice)." GMT+9)";
		$req = $to_per->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>$text));
		$result = json_decode($req);
		echo "\n\n#### Percentage Result ####\n\n";
		var_dump($result);
		file_put_contents( "/home/u262578317/public_html/php/percent.txt", $since_percent+1 );
	}
}

//英語翻訳

for ($i=0;$i<4;$i++) {
	$setting = array("screen_name"=>$hakatashi[$i],"since_id"=>$since[$i],"count"=>200,"exclude_replies"=>"true","include_rts"=>"false");
	$req = $to_en->OAuthRequest("https://api.twitter.com/1.1/statuses/user_timeline.json","GET",$setting);
	$result = json_decode($req);
	echo "\n\n#### Tweet Translation Setting ####\n\n";
	print_r($setting);
	echo "\n\n#### Tweet to Translate ####\n\n";
	var_dump($result);

	foreach ($result as $status) {
		$trans = doTranslation($status->text);
		if (strlen($trans)>140) {
			$trans = substr($trans, 0, 139) . "…";
		}
		$req = $to_en->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=>$trans));
		$result = json_decode($req);
		echo "\n\n#### Translation Result ####\n\n";
		var_dump($result);
		if ( intval($status->id_str) > intval($since[$i]) ) $since[$i] = $status->id_str;
	}
}

$handle_s = fopen("/home/u262578317/public_html/php/since.txt", 'w');
for ($i=0;$i<4;$i++) {
	fwrite($handle_s,$since[$i]."\n");
}
fclose( $handle_s );