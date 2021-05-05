<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4Z8s1X5AxCMwlQN9cXdYPfW9Sy+prdS5maSLjVvOVohbmHbXR+QgEKLmPOUeg21Ug8E3AyPyJN7GV3j+/o20uw==');
define('SECURE_AUTH_KEY',  '/bCpFIaJrfL3p7DCpyNGVdlIz5AuojzM71gO2F9l1gJMbS8O4rznESg0IhteSQcThl9NlKL787oWfXDW2lL3gw==');
define('LOGGED_IN_KEY',    'BOESl3QQFhH0/A07x79Xj935HwWytejiwXluAp9sXcKyTXoYOSS5bPcDLlsU1+SPOce62na14in+PNKFKuw4Ag==');
define('NONCE_KEY',        '3A2rSG/yolmL/5urlmxO0FSQ5PwwGORD3/2WXehdIK8RsAQYWZHuYqKMv/TBZcIckB8AGDZSQVjG+lv+TsogGw==');
define('AUTH_SALT',        'XK8o79ubo2n/xP/2aTB11LpSYwIxBF9KyNnXB3fuj/C1h0pztRVK+zUkyp2fM+bboTJ1ECz6VETN6FTrvw4Q1w==');
define('SECURE_AUTH_SALT', 'NKfgxnftplzGhlx0ueRLhq1vE+MXymZjta/iYEY3nAjCb4Sc28W7iIYtzrsS7Dph3S1Yg4BcKpf3v3NNEKLkFw==');
define('LOGGED_IN_SALT',   '4I7X1vRUNRTBD++qjoWKfgPegZ7bgvXeC9a6d1P8cWoXvp4tI1kpT/44KSsAYwkcliDCP/wHDxFrY/H1XQbuCA==');
define('NONCE_SALT',       'hUv0tRrwPbYcwB6DH/3gl7a0R1DKxpe1RK2P0agYMhngZNeBDv1OwPcLClPQTgj2A4HuJ1UrQZaxmFBLRMihUw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
