<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}


define('AUTH_KEY',         '723nMFP/IrRRgAlUZ393BDcL6NQnaTIvDLFyJAeTQ+otc9UMeqFF21KdeGxLorGXqdvY701JG8o2H0o/14XCnQ==');
define('SECURE_AUTH_KEY',  'T6o1HsYS65nh++KoCuVAEaC14Un5fOlgCwX3Xnrf5PZ+WGqXs9vWDFXvumFAFGABTFdBVzIZ0VZJ8Y8maKk+Ww==');
define('LOGGED_IN_KEY',    '2HslSgIC0OkhFfVPZomz265FpY9fbpVkkL3vy7hokQsxYl+1yH9LU1oTJIG531dGfgQnsPDVg40Q/dNFrAz4rA==');
define('NONCE_KEY',        '+l0yUfKbjZknaOv0feEdiWcEvwAn1wM/t6RyrCc5cn2G3m50YMfVMM6C/B1w8NxCxTNZvnfMIFwvIhAhqH0aVQ==');
define('AUTH_SALT',        'YBFA+1h3o0mzlMfBc8s6xeGZR/OOkkrUcPNJv8Pu4l5O/0WXhugtryUzif7NTy99OuGek8q+jhcut77NBWSbCQ==');
define('SECURE_AUTH_SALT', 'pCXAoE8K9IqijvtMwXMPFP0eZksvU2oDQzXwnvHr2kvJsSFt0M5hA+rIXMxb4kMdKaFsgxeOMSYbEVl1yr7rXA==');
define('LOGGED_IN_SALT',   'xKnsuLobsVvacrxKZV+xB1iT89qdUWlLfMLG/kqDeOtKsEa7c0CwHQBVARxWq0pMuuxv+J/slkOXY4rLtZSvWg==');
define('NONCE_SALT',       'JCBvgGpcY0g+K/RS+geXgnOiFF00xUj40PeMI+GFff6qWaIP3MJvW8GKZ67cmOkPUZd7dxLCkpmEtunFrxb5fA==');
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
