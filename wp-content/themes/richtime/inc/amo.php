<?php

use AmoCRM\OAuth2\Client\Provider\AmoCRM;
use AmoCRM\Exceptions\AmoCRMApiException;

require __DIR__ . './../vendor/autoload.php';

function printError( AmoCRMApiException $e ): void {
	$errorTitle = $e->getTitle();
	$code       = $e->getCode();
	$debugInfo  = var_export( $e->getLastRequestInfo(), true );

	$error = <<<EOF
Error: $errorTitle
Code: $code
Debug: $debugInfo
EOF;

	echo '<pre>' . $error . '</pre>';
}

$provider = new AmoCRM( [
	                        'clientId'     => '86eed639-cd7b-4b6a-96e4-8a0e4b8d3c94',
	                        'clientSecret' => 'hmKjE3UlmjZvFhKhMCAod9OZnXOOskPUTpAV7vTiXKwg1acWZGZyD1eY9jTL3FmC',
	                        'redirectUri'  => 'http://www.tele134622.nichost.ru/wp-content/themes/richtime/inc/amo.php',
                        ] );

if ( isset( $_GET['code'] ) && $_GET['code'] ) {
	//Вызов функции setBaseDomain требуется для установки контектс аккаунта.
	if ( isset( $_GET['referer'] ) ) {
		$provider->setBaseDomain( $_GET['referer'] );
	}

	$token = $provider->getAccessToken( 'authorization_code', [
		'code' => $_GET['code'],
	] );

	//todo сохраняем access, refresh токены и привязку к аккаунту и возможно пользователю

	/** @var \AmoCRM\OAuth2\Client\Provider\AmoCRMResourceOwner $ownerDetails */
	$ownerDetails = $provider->getResourceOwner( $token );

	printf( 'Hello, %s!', $ownerDetails->getName() );
} else {
	echo "<pre>";
	var_dump( $provider );
	echo "</pre>";
}

