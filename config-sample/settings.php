<?php //-->

$host = 'http://api.ejr.dev';
$appHost = 'http://ejr.dev';

return array(
	'url_root' => $host,
	'app_root' => $appHost,
	'cdn_root' => '',
	'i18n' => 'en_US',
	'eden_debug' => true,
	'uac' => true,
	'debug_mode' => E_ALL,
	'server_timezone' => 'Asia/Manila',
	'default_page' => 'index',
	'mail' => array(
		'ses' => array(),
		'smtp' => array(
				'default' => true,
				'host' => 'smtp.live.com',
				'user' => 'dev@chiligarlic.com',
				'pass' => '{pass}',
				'name' => 'Mashdrop.com')),
	'jwt' => array(
		'key' => 'whatthefucklogic',
		'algo' => array('HS256'),
		'payload' => array(
			'iss' => $host,
            'aud' => $appHost,
            'iat' => 1356999524,
            'nbf' => 1357000000)));
