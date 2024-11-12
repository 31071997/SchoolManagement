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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'school_management' );

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
define( 'AUTH_KEY',         '*])F8vNjC;3FQtx.+=cpC4(zMvs*$&MG%sX5ScB#?fb6sL0GO~Cq~hoJzx2Vx=)^' );
define( 'SECURE_AUTH_KEY',  ')}5#lWZ:&NIf|#,j `FhA_8<ja-W)Lnyhux[;Qj9|EIp6ia9NzrJVsudM5v~)6#A' );
define( 'LOGGED_IN_KEY',    '6VgIdahxXBS=3@oBC%kEl]7l?z52[D8]Z=9RC2kJ:un=KmK-RBvh175I!{) YSt9' );
define( 'NONCE_KEY',        '>iZLL3=_mAY%K8fA%ZMFFBMWgFZlQvDR%kN<-noEyz!Yu ;!mA_^L:,JV7sT;*$&' );
define( 'AUTH_SALT',        '!,awNEQ?=GJ^D2qs){T%qwQ&9^v&b.d9]XI)uv&qQk|un~g My#1V}p6-dj{Kg(_' );
define( 'SECURE_AUTH_SALT', '!4|U0^T~`ot{Q%f+OYM=/`g+}G@X&KJ>~=#2Hh8k%r2:40sLvUszi7rXo 9y(~.M' );
define( 'LOGGED_IN_SALT',   '9~% cnO?nQv|NFtsY>C`1q[HAq=|*G5UlKS@k|IeQYu;y^R`g*+=r(%zXf!O9<Pn' );
define( 'NONCE_SALT',       '|wHf-NwG[#)mg2rRWem!Bv0g$?dE|ooSRI!6;PczM/DhZa8mI<}<KQZ;DIny qY-' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
