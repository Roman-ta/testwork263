<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testwork263' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'LrO*$Cn&Zm8|QmaIPn;<@j#^PKIcoPCy3c8Y+6/0vn7bQ,:1?Ev1uQ6A%7~yp~a]' );
define( 'SECURE_AUTH_KEY',  '.95kZ{t31G|oVLzsZ<C]M(s%s?H^tpI}_k<yshjAz$sbkg%LW^hbRE7o8?XUFWnF' );
define( 'LOGGED_IN_KEY',    'Fd1kp%`]jCv}~f}R|]@~fT[bcJ3d@SQ_&i@BvW~pYZ4j]*kPAta3r*OOjQ5mmrsl' );
define( 'NONCE_KEY',        '6ZI<t7ZDS@*USEJK3OHIrZM*+41dfSBb/)q|%9xKAG4b8Zc:.l1jy+3blZ=FMHRe' );
define( 'AUTH_SALT',        'MOw/nW=/`J*ix5jJ_g|O`Zf]u39|y>)symQa,|Y@*Fa0}!&<%fB6i3+9r+0?{Nc5' );
define( 'SECURE_AUTH_SALT', '(k1]2KBjSm.:sCqoKEGH>5gz =#ux)Xr?01]VI%RNCzFT}**U4JZ+c3p7hTX-RaG' );
define( 'LOGGED_IN_SALT',   'X[>0oM#g`Jw$jod$62wMW@;>P2w,vz>aiouUr1DoBNg-L7,rNHHpz]fqR4t<(%5K' );
define( 'NONCE_SALT',       'luuu(`T)+L?(Whk (qW)#cB+29@B-Z{U!>52$/AjUj#nFudhP ;OKnEYj Dsmmlw' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
