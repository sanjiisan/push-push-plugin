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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '8MbkrJQ4');

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
define('AUTH_KEY',         '.l]=$RJa{7>dvE9CGyK-yAT5XNkmMT76m{_{/&+S0SE2wy}[`R8n?n@U3/I!UY}>');
define('SECURE_AUTH_KEY',  '#4J+xlBB<?+r#r&$R2E9x F{x1&mROv#zB2CmIX$f&LsuP$~rc]9u88Gj.JSo+0k');
define('LOGGED_IN_KEY',    'T|hIIdIt0.IR1/m*@jWxYX,[v0{p[?Gzp=ou$ 9!x]h{0apB!:^E(=+>C()(pvRE');
define('NONCE_KEY',        'PwBiee_</1Uew*0N-bG~ RZZy7d>e}dnW[/1-|qpN,$Fkh4C#EH-KdQPzcq$k:>D');
define('AUTH_SALT',        'B<sPm}HD;NYpev_BLG=|M[#,z@OS,N^h[y{H!IS`BjVeb_kr&L3{t-|?QAX;|88p');
define('SECURE_AUTH_SALT', '%ng![<$T4-Fj=1LxqjGF:,eeG]rEJWZxw9lF}U<Y+8Xfp#?y;CsF=f@s$5XO%lnj');
define('LOGGED_IN_SALT',   './L?20+l]{2Y]ujW6l~n l_x*M`W6qQ;e`nPa[TZXsH4}I2GlSFv=E=;G8nG%wF[');
define('NONCE_SALT',       'R1!zfUpv=t[&fW!G/:aOoiHpp-VbSoH:^PST:=$UG}Bz=rt9-A}v[v=9@V??O7;^');

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
