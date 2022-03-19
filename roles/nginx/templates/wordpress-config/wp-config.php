<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '{{ wp_db_name }}');

/** MySQL database username */
define('DB_USER', '{{ wp_db_user }}');

/** MySQL database password */
define('DB_PASSWORD', '{{ wp_db_password }}');

/** MySQL hostname */
define('DB_HOST', '{{ wp_db_host }}');

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

define('AUTH_KEY',         '@lLVlj3@i:bo,FmYS?f?N2|vynC>og{|`@|3T.);w@zz}<&DLiK@/[r-g+2T|7n+');
define('SECURE_AUTH_KEY',  'a-qwh9O=07+p3G~hcX7V]A460.C&IUK2G2t^4xIT~eRFZ^ycRDTqbYU/Q?Tue@5_');
define('LOGGED_IN_KEY',    'R>hJy?D 4;9oT|oSoxFr3y$0; (|4=$B-#G&,=T-?BF#]|-P4GL`*Hn+4=nt*]Uu');
define('NONCE_KEY',        'EMVVe7..@XZE*)~>7%Yq2M<`ho_nBK2on3i|m7c~l<fJC*c4@:=6(e+`,YZxFj@y');
define('AUTH_SALT',        '[8Bx;& @aBp1],z8LVc [+=t>mVk$N;fgBHJ~L^B4bE+g&Y3Pr[t^94vq-{q6$-z');
define('SECURE_AUTH_SALT', '?_GJxKA|/a(@R4RKN53iP!8:wMKDt}dLHZtC?Ep2D#HcN/$S84-?v3yM}EM;=|*f');
define('LOGGED_IN_SALT',   'a68Im8&mw%=Ee2IbS+<yhR@u+s@HyhSD<JnjTuJ%0d-Y2hP*JBAPs(t8oA2-;Do<');
define('NONCE_SALT',       '{k}/>a_:z*SMhdP^.EG>glJCZ0NhDve*A1W*+ M.O2Qg-_g[-1%ZQ0L]Cg(Y/L6b');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/** Disable Automatic Updates Completely */
define( 'AUTOMATIC_UPDATER_DISABLED', {{auto_up_disable}} );

/** Define AUTOMATIC Updates for Components. */
define( 'WP_AUTO_UPDATE_CORE', {{core_update_level}} );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
// define( 'WP_DEBUG', True );
// define( 'WP_DEBUG_LOG', True );
