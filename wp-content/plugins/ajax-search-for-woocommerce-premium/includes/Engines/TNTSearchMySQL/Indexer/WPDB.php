<?php

namespace DgoraWcas\Engines\TNTSearchMySQL\Indexer;

class WPDB {
	/** @var \wpdb */
	public static $db;

	/** @var bool */
	private static $inTransaction = false;

	public static function maybe_init() {
		global $wpdb;

		if ( isset( self::$db ) ) {
			return;
		}

		self::$db = $wpdb;
	}

	/**
	 * Clear saved queries
	 *
	 * Saving indexer SQL queries consumes a lot of memory.
	 *
	 * @return void
	 */
	private static function clear_saved_queries() {
		if ( defined( 'DGWT_WCAS_INDEXER_ALLOW_SAVEQUERIES' ) && DGWT_WCAS_INDEXER_ALLOW_SAVEQUERIES ) {
			return;
		}

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
			self::$db->queries = [];
		}
	}

	/**
	 * @throws WPDBException
	 */
	public static function delete( $table, $where, $where_format = null ) {
		self::maybe_init();

		$result = self::$db->delete( $table, $where, $where_format );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function get_results( $query = null, $output = OBJECT ) {
		self::maybe_init();

		$result = self::$db->get_results( $query, $output );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function get_row( $query = null, $output = OBJECT, $y = 0 ) {
		self::maybe_init();

		$result = self::$db->get_row( $query, $output, $y );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function get_var( $query = null, $x = 0, $y = 0 ) {
		self::maybe_init();

		$result = self::$db->get_var( $query, $x, $y );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function insert( $table, $data, $format = null ) {
		self::maybe_init();

		$result = self::$db->insert( $table, $data, $format );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function query( string $query ) {
		self::maybe_init();

		$result = self::$db->query( $query );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	/**
	 * @throws WPDBException
	 */
	public static function update( $table, $data, $where, $format = null, $where_format = null ) {
		self::maybe_init();

		$result = self::$db->update( $table, $data, $where, $format, $where_format );
		if ( ! empty( self::$db->last_error ) ) {
			throw new WPDBException( sprintf( 'Database error "%1$s" for query: "%2$s"', self::$db->last_error, trim( preg_replace( '/\s+/', ' ', self::$db->last_query ) ) ) );
		}

		self::clear_saved_queries();

		return $result;
	}

	public static function prepare( $query, ...$args ) {
		self::maybe_init();

		return self::$db->prepare( $query, $args );
	}

	/**
	 * Start transaction
	 *
	 * @return void
	 */
	public static function start_transaction() {
		self::maybe_init();

		if ( ! self::$inTransaction ) {
			self::$db->query( 'START TRANSACTION' );
			self::$inTransaction = true;
		}
	}

	/**
	 * Commit transaction
	 *
	 * @return void
	 */
	public static function commit() {
		self::maybe_init();

		if ( self::$inTransaction ) {
			self::$db->query( 'COMMIT' );
			self::$inTransaction = false;
		}
	}

	/**
	 * Rollback transaction
	 *
	 * @return void
	 */
	public static function rollback() {
		self::maybe_init();

		if ( self::$inTransaction ) {
			self::$db->query( 'ROLLBACK' );
			self::$inTransaction = false;
		}
	}
}
