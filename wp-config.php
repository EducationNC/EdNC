<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_default');

/** MySQL database username */
define('DB_USER', 'wp');

/** MySQL database password */
define('DB_PASSWORD', 'wp');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'ajP%^+EXW61B?V^tB@nikJL~DENFg)c!?MI#PtZ-WM20EfhR(eR! ~0=x|oSmx;T');
define('SECURE_AUTH_KEY',  'iJ8[wj TQz}Ts[Te?PkRVW(^K#X@w+Tx-x7P:_9lFzd7{xKTDt[%S7/4`H[l=^jV');
define('LOGGED_IN_KEY',    'q*&upiJyucCqcvv>Z_QRa0kt|q)>U#xeisf=YxelfkL3O7=vITq|m+|K0_av5^^N');
define('NONCE_KEY',        'f+>qd6q)(2Bk5*E+>TixyJKi|g6KExzm/M8toa4A*ZjmW|9TEcS[j+?}f{lM*4qp');
define('AUTH_SALT',        '<}E2xIMRS]M/u ^>J7BMD9MwfF6`e&BZ=$Od>yQ *x>=I;YgKdln,{TUf}L$_QM^');
define('SECURE_AUTH_SALT', '>~lfE D;+Y+Wk1c!_&CBh/L,^]7XnxXlC!w>6CDqeK|%?`8DN3N+d4QR$}CnV!] ');
define('LOGGED_IN_SALT',   'D:M)5?J<4rrAP%4(`[yfI(W]-4amdIJ0P<NE^!(73U@uW 2y~Ej@X8VK$tpUvR2*');
define('NONCE_SALT',       'z1hHLi];u7~m|3[Dk&e?9Z@ 4lD8Q|Ab|l3WPNd=4En1/b5bu-v40h3qiY?*xY=e');


$table_prefix = 'wp_';

define('WPLANG', '');

define( 'WP_DEBUG', true );



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
