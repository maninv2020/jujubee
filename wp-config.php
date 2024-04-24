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
define( 'DB_NAME', 'jujubee' );

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
define( 'AUTH_KEY',         'S,~*WzLvKU^G}}F7$hYm/h^* VrHiK~ZR_YHL9~D_d;QT=`!vzdyZmI[E%pv}B#@' );
define( 'SECURE_AUTH_KEY',  'EP I-re}u48WANt,Gl3]Y3#R$G4ZJ96I,>n=7BD|@@ }H$&4`g;H(ZDTK*}r>g6d' );
define( 'LOGGED_IN_KEY',    '55X2t#))+GvB50o=n:f+gD+fE{X@mo>yb,atPK!.,3-BDQXQi]z%`p3H .nkC=XS' );
define( 'NONCE_KEY',        'W+ly)h7M1jA8tQ,*qas;Kl{,3ii<GP_?e3Pn9C>-9v#{[Fh7&`g5!jMqrC}VaH51' );
define( 'AUTH_SALT',        'v`{vrxufP8a6kPgKU{2/WidX[,D-siOJgHt64)x#UvlKVdLznm1OIU@u<hls$tsA' );
define( 'SECURE_AUTH_SALT', '<?C.R.*3n5qM2B7% iLgg>U@Qb,(~KdpH^j`dd(#Y&e.iVp{c}ji~HL_Qa.**$W|' );
define( 'LOGGED_IN_SALT',   'FsYab$]&N8vX;HGa[;Ux@Ax0X8P77-%d15oY?!(}#~oCJ^p*t5OfR 2j*&i~Iqrd' );
define( 'NONCE_SALT',       'L,Q+`_W@tKT#B]lK%0xalGO(,PK]|G_sU/}}1igCHy%@Oj-DjUlDMc1s-nNjI@q5' );

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
