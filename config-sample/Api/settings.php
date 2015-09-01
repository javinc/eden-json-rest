<?php //-->

$host = 'http://api.ejr.dev';

return array(
	'url_root' => $host,
	'cdn_root' => '',
	'i18n' => 'en_US',
	'eden_debug' => true,
    'uac' => true,
	'debug_mode' => E_ALL,
	'server_timezone' => 'Asia/Manila',
	'default_page' => 'index',
	'jwt' => array(
		'key' => 'whatthefucklogic',
		'algo' => array('HS256'),
		'payload' => array(
			'iss' => $host,
            'aud' => 'http://ejr.dev',
            'iat' => 1356999524,
            'nbf' => 1357000000)));
