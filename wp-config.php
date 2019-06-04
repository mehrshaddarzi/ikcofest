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
define( 'DB_NAME', 'ikcofest' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'za*fJ#R8Cv!)Pl=GZt6S-6)>lZ>adsxSD9wsG-N?6OjY_UJ`c![a$.~a`aWpaZ }' );
define( 'SECURE_AUTH_KEY',  ']yoqmQ<pW<sTsU5: d92Z&fsBA{WGfXyV4IDv;Pa=1(mu-}(W4-`sW/LAXJSef)%' );
define( 'LOGGED_IN_KEY',    '*NUv(U~XmcDjLu*9K0z$41vIxS~D|8x@@=9<gdzdVAi_cRcY6fFV.{ }@)}XYupG' );
define( 'NONCE_KEY',        'P0E8lN9w8K;+@d3c^*WZd}R&mJVw~o]EyNW$R`7*mCJ~hU>2yy^3P~l&{@&;Yzlh' );
define( 'AUTH_SALT',        'Rp-sd(i2E]8OH`Ez-REtnML*NQ&avI8>5Ua$3bh^WHh3orM-[Fe)xpDKMkyWpuNB' );
define( 'SECURE_AUTH_SALT', 'YE>z.]u>%;!vC2~d%GvXKM^wGd($hn>IF%DhV8&![c`:C(O^E/wv0ky=awjnrPqo' );
define( 'LOGGED_IN_SALT',   'TT&O8%PU`uO?_Y+u`)WAW$Z*:4II6Zi)4JODx<31pVj-NM-@q Jm!uhq1ht.IOdH' );
define( 'NONCE_SALT',       '[RXj-j;y-^9(=h!NVXheVJ732nUwG.oUEn= x_bcBe6rjr[.E(6Hv:xz*4SPog/M' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ik_';


/* FRAMEWORK OPTION CONFIG */
define('WP_HOME','http://localhost/ikcofest.ir');
define('WP_SITEURL','http://localhost/ikcofest.ir');
define ('WP_CONTENT_FOLDERNAME', 'public');
define ('WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME) ;
define ('WP_CONTENT_URL', WP_SITEURL.'/'.WP_CONTENT_FOLDERNAME);
define ('WP_PLUGIN_DIR', ABSPATH  . 'addone');
define ('WP_PLUGIN_URL', WP_SITEURL.'/addone');
define( 'PLUGINDIR', ABSPATH . 'addone' );
define( 'UPLOADS', ''.'file' );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );
define( 'SCRIPT_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
