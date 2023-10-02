<?php

namespace DgoraWcas\Engines\TNTSearchMySQL\Indexer;

class WPDBSecond extends WPDB {
	public static function maybe_init() {
		if ( isset( self::$db ) ) {
			return;
		}

		$dbuser     = defined( 'DB_USER' ) ? DB_USER : '';
		$dbpassword = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
		$dbname     = defined( 'DB_NAME' ) ? DB_NAME : '';
		$dbhost     = defined( 'DB_HOST' ) ? DB_HOST : '';

		self::$db = new \wpdb( $dbuser, $dbpassword, $dbname, $dbhost );
	}
}
