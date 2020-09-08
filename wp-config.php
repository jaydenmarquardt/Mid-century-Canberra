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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'jaydenu1_uni' );

/** MySQL database username */
define( 'DB_USER', 'jaydenu1_wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ST!!n6vAru)V' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'y7y_4L@)Mkx7?h+/>60uN72xfo5@x0=FT/WPjyF_pSH$tD@c+%I)%@s$ahZ1g^MI' );
define( 'SECURE_AUTH_KEY',  'WY?QakYta({;C&~a|LSH1(bNeXzI}n29M7~XUSU=fb2)iK6?3p!I[EzH|jS %q}D' );
define( 'LOGGED_IN_KEY',    ' 1|!ypig28=yK[YyrpKyzlD9Aa}{o|Wz)$F4?v,R2^ho<)ac1[#XD{0>+$X_t+q+' );
define( 'NONCE_KEY',        '~s>{Cc[F]O2sYQaJx jg+2w;|sW}U`=,#^h&7s+tXjtajk2%6952z4kavPD.xqkF' );
define( 'AUTH_SALT',        '^Xgyt** bQS l/xKqfo&-qjhoMk)DdcFb8Whd=B$~m|hbeS0#EZaviPa)EK$ml#s' );
define( 'SECURE_AUTH_SALT', 'MaR]$)*4={b55I4r[Vi@j9ZC}[2@@Hb/6t^,Mt1BK#%n_7vc^PSQ$G9{5{jx`tPn' );
define( 'LOGGED_IN_SALT',   'J12%HP89uj/@y^wvIB#mX4Bk)U:CLTg77r[]ETHx0?8en>Z`B:!yr:WT10$OQ~OG' );
define( 'NONCE_SALT',       'wz-F4DI5.=ab?Q8t/-%*x,m.8:IBU;m/6A@$?7d:h`YXDF}AnTae<4C6~8_y/Qdy' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
