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

/**
 * Database connection information is automatically provided.
 * There is no need to set or change the following database configuration
 * values:
 *   DB_HOST
 *   DB_NAME
 *   DB_USER
 *   DB_PASSWORD
 *   DB_CHARSET
 *   DB_COLLATE
 */

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         'c8u*aN{X3p<Bd,VibZU|iaCx#0oRmO}5G{+>3C]ZiSZ$~Av!$rjBBg]:xfZTw2GJ');
define('SECURE_AUTH_KEY',  '{ND_)tOu2C7dHd}+ZdmUOgq|?j8#KS0y7#PY~#JH{-pa!s!J#XZ_(s5*u)hF_:6L');
define('LOGGED_IN_KEY',    'L#TnR>?p_2KY1|QSjFYH:NgtLKIDe2:G<11QJE3)~?kL8PsA@.zfmXY~%@J_F*z9');
define('NONCE_KEY',        'E}?T[#hHja7?Y]hImgOpdP#Lf?f]CBY{!)A#9Xeg;N#ZZo0tuvqB9;pIT,MD=~=C');
define('AUTH_SALT',        'Q8KNv?T+il(di_2%!CJHJA+z)bL^e[54>jY)@?yN~Zg(gQMe~oLv!^~<IlU++=[r');
define('SECURE_AUTH_SALT', 'Z+$=77yTp}~*8h8M]Wac!NqPxJEYFg#{Q-a3p4<m@xj#)4zz4%?*nKIgVLGlL:Ag');
define('LOGGED_IN_SALT',   'CLV=~GAoke%)7FnBL)_rkf.(U.ReHZ%cre9@54vC2P-_19a}$;A<Qm3~+~$h(8l|');
define('NONCE_SALT',       'y>L.>~e-CJFMqF%leR?vg]Ch?iCfom;kN+B]lx$S-K5,5ss<9?gtEGg?pn~ALEpo');

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
if ( ! defined( 'WP_DEBUG') ) {
	define('WP_DEBUG', false);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
