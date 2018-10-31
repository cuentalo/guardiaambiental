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
define('DB_NAME', 'wordpress-db');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', 'admin2018');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~~U5606-.|jk:R=NvD=4N2Vs(53cESG~<N^Yq_P66C@/`,*>HZT :|Xs[0qlZ-J9');
define('SECURE_AUTH_KEY',  'hmZE^^@=AF)R9rv&*> gy$A~{jY`5U8z)}<rqi~q1W=pz4|>Fp64K+yTZE0Kez},');
define('LOGGED_IN_KEY',    'XGe)h53G>HudQsyK;/K1MOCXr:RK^zZ6ZR@*6F%dxEB9N ddNG$~`k|leB9bP_v&');
define('NONCE_KEY',        'BCPb/6HAN4[+,XL|zlX8TSNiQ@7q<Kv6AV,;Ym],j($ZJmzbcVfunZuPvs1,!Gf|');
define('AUTH_SALT',        'nEoJADY*E0>,d^b9[TwjTy3;liEQzP/%|U;)7iW|BRECS.-3P8r+4=^f4-Av-YF(');
define('SECURE_AUTH_SALT', 'UK{Z>&_j|s)6Jzihx>&?p-O*+ hMoHTxGJ$zJEX+)Pa<|i|oQdutOA{c%;|$gdz7');
define('LOGGED_IN_SALT',   '%R-=w9>uN-po_V1) i)*B6.{<9:D+j|>Zes{JM[TW2{KYe3M5SJeKxpe:, 3r0WG');
define('NONCE_SALT',       'TZ#c8Bnvs&lD^T4c6NgXR+QUf?~[}ky65aoXoDB3RST_j5p;{*_.8D/EOmp;H-PJ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
