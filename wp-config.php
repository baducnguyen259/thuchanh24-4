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
define( 'DB_NAME', 'thuchanh24' );

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
define( 'AUTH_KEY',         'Rofff6(53[aVz`[c*6C|z2$E8dXQ:$=2>yC#YnL`^rIjK?M)fHS?3hHnD[d4;A_|' );
define( 'SECURE_AUTH_KEY',  '2A|MT)s8n.g$!T%]okr&`[C{-ni?<nNVOi+qG*Spx;x2wP2 3!u}I)Td 5VzM$xd' );
define( 'LOGGED_IN_KEY',    'TzEe*zw`d.ZZR9 ~-2G?Rdr{nt4(4!jl!lm BG.@wSBie,G|6cIC*|pZl>1zi47n' );
define( 'NONCE_KEY',        'huT9^aOxq2c]%Rsaab!sL!iZb?9>4Tx^HTfZ^AK*2obI=T|l@vFMu[K^tvEP`1^x' );
define( 'AUTH_SALT',        'eK>?{OIA-.d6kE`{! cV|zAa +{9U4FX=xOd^w47W:|?LQ:12zSh62V@0x-9ct_T' );
define( 'SECURE_AUTH_SALT', 'hV s!:=4z`h|FznS,]z]]m>3a mpGhTtXvMc)PZTXv0~Xx<l:atm#J(X0fqYaMgv' );
define( 'LOGGED_IN_SALT',   '@4]ckm/.}!p?un1gT~gwn*i1H. -`#x0k[=I8YCV3pq %eI85@A3|LIgJO LaV4B' );
define( 'NONCE_SALT',       '^rK73m?-_ID0u8g4i&~$`AF?E{wVIXw7y@XOqiJ6~2)S/0/.m;|jbm84fP:O#XsQ' );

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
