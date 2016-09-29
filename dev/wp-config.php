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
define('DB_NAME', 'mealblvd_imark_dev');

/** MySQL database username */
define('DB_USER', 'mealblvd_imk_dev');

/** MySQL database password */
define('DB_PASSWORD', 'ztFApOu9P*}[');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '`DO*r)!}[gb~AbSO7-*2Wh.a{u/?;g/E^CaT81TnW#/a4+smUBpzC^m!y!k])OaB');
define('SECURE_AUTH_KEY',  's0 VpoCcQU#r-(muV8<9{ch|Jgp)Mv.4jqM2;qGmHTf2wzu~,{c2e:OiN .?uJ1D');
define('LOGGED_IN_KEY',    'c6Yat<g#ESRl|&Y}0h{ KAgA,@AOVvSCY*Wd}b~|Yl=ei3_`YBbdE|~xb.-foicE');
define('NONCE_KEY',        '^,CQ(SBlSg!agcU&[n*<g)CDr(I{1.:BySah4(E%__r}{&%lWiY{(0&MfX#s_iOb');
define('AUTH_SALT',        'voO>q~pB*R~=:mW#t[N8@ziM|V-~n01V^Pl ?~(v2sXPXkB7$uao51ov0aUi:]K)');
define('SECURE_AUTH_SALT', 'n /(zysdLjO@ikI5o,W>_E-`G]M^#occ!6FdoSc DV6w``Fd7uPhqmD=@B}Onhe)');
define('LOGGED_IN_SALT',   'U8W2lqjtkdfJHy.]XL+^BJ.tf~@en Bq GxOi)S3OrJl:c7efD-u=EDYmB(TF{4^');
define('NONCE_SALT',       'tI/0B)vzNP>A?n4B@LA:z.S`$}{X0:lT=Sf:z24,<r3AhL.>:zb6dA90G/. pJw~');

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
