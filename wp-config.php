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
define( 'AUTH_KEY',          'UhF7k: .0K89vz1zX28*_J b9cW:Xp_j=?o36d&cs^]!W`@z|_vDRLK r|6GCvj`' );
define( 'SECURE_AUTH_KEY',   'she]vw%*`MQM%*{(S(7_10&lL67fy#136Z10nfeX<#Yv3,Y`NGMT]{*KLz:eU:uV' );
define( 'LOGGED_IN_KEY',     'TWZa?7V?8uj&[)</`^rw1W1SBaE$[:nmS#[iXek_x@J__a3HATh0i3>}SY!4z@q2' );
define( 'NONCE_KEY',         'Leb7Mx/>O{4-Zii:]hHH;BmtN3=~62Js#hMri~B8c0Dar,(x/{^5ELJ nIAwk9)a' );
define( 'AUTH_SALT',         'YQ]} 0$A0EB]D(]-]Lv<+2&C0ZWEM(%+sct]Gb7[UhmSS~u A&a`Kg|yzE`ms^A[' );
define( 'SECURE_AUTH_SALT',  'lvX(Q:!o;E|G3$kd-[RGSW`,KtZ>clS5Y16dY]?n4%z6z&1Q]|#@tj;xm0*RMLQP' );
define( 'LOGGED_IN_SALT',    '?`{q CsX14Z+U5O| _|fg^R-e s&YykRy*E1MyJbE85m>hd&^0ON{g+328~:U-(G' );
define( 'NONCE_SALT',        '[0v(l}CL3q)zrPb)(Cd%4]|3oR8!f66%#sd:~.-$ cRWaYpT=uNW+ -outq/EGWa' );
define( 'WP_CACHE_KEY_SALT', 'Fr|J8;bFv4tS7,Qd1<w~l$BdNbLX dPI6n{R,nuV%!vPBrsu5*dK&Dj@o5>1PhT?' );


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
