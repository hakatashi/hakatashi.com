<?php
 
// OAuth�̂�����
$consumer_key = 'TUi3SAhPofLOJAy4Q4wGg';
$consumer_secret = 'hKGcecTIYX2HQKBaLQucLg6C8vxmFJLpY6UbVNQfw';
$oauth_token = '128424456-tsI3AyChnwKRywNBetamqOhBFUGBaK75Kpd9QSs';
$oauth_token_secret = 'ZLFPMw5XEgC8qVBa5bbX3Tb9ZDnTvvf7cG3ruVRFNXY';
 
// API��URL
$url = 'https://stream.twitter.com/1.1/statuses/filter.json';
 
// ���N�G�X�g�̃��\�b�h
$method = 'GET';
 
// �p�����[�^
$post_parameters = array(
);
$get_parameters = array(
    'locations' => '132.2,29.9,146.2,39.0,138.4,33.5,146.1,46.20',
);
$oauth_parameters = array(
    'oauth_consumer_key' => $consumer_key,
    'oauth_nonce' => microtime(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_token' => $oauth_token,
    'oauth_version' => '1.0',
);
 
// ���������
$a = array_merge($oauth_parameters, $post_parameters, $get_parameters);
ksort($a);
$base_string = implode('&', array(
    rawurlencode($method),
    rawurlencode($url),
    rawurlencode(http_build_query($a, '', '&', PHP_QUERY_RFC3986))
));
$key = implode('&', array(rawurlencode($consumer_secret), rawurlencode($oauth_token_secret)));
$oauth_parameters['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_string, $key, true));
 
 
// �ڑ����f�[�^�擾
// $fp = stream_socket_client("ssl://stream.twitter.com:443/"); �ł��悢
$fp = fsockopen("ssl://stream.twitter.com", 443);
if ($fp) {
    fwrite($fp, "GET " . $url . ($get_parameters ? '?' . http_build_query($get_parameters) : '') . " HTTP/1.0\r\n"
                . "Host: stream.twitter.com\r\n"
                . 'Authorization: OAuth ' . http_build_query($oauth_parameters, '', ',', PHP_QUERY_RFC3986) . "\r\n"
                . "\r\n");
    while (!feof($fp)) {
        echo fgets($fp);
    }
    fclose($fp);
}