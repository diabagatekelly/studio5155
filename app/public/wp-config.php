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
if(file_exists(dirname(__FILE__) . '/local.php')) {
	define( 'DB_NAME', 'local' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );
} else {
	//**LIve database (netfirms) */
	define( 'DB_NAME', 'studio5155org_mauj_jjdelnmelhrym' );
	define( 'DB_USER', 'kgvdfvpbofmvaees' );
	define( 'DB_PASSWORD', 'babyAadam#01' );
	define( 'DB_HOST', 'localhost' );
}

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ytuVWkOILAJ+hQ9ptXjkK5aYUftHQJAyD81XUhlx+RSKb1+M56Q9OmyGWxV7fmTq8XTT24M1WFhAdyEuzFfrEQ==');
define('SECURE_AUTH_KEY',  'ZHhF7OPxAYw3ONpHzm1RlbaZ//TqD7sX90/qZ+5epeK5sUaXguxWF3ux3F2DgdfBx1Nmw10AnB/Yr7KApTPCaw==');
define('LOGGED_IN_KEY',    'FiEzYPjKzmGkQTfJnDi6thFQ9jQz59FgQ213ssQgVapy5cFxUcNl6jYGe7A0APP6TuCBcIR5OvkSjy6ogFnUqA==');
define('NONCE_KEY',        'XjsRGiMRvYxVN//HdB6EIiweUK+dICPKCRudM1ws10lXg012oNu4HYKRQZc5GnZRjTvd6RkA2sOHEKZtAIBbSw==');
define('AUTH_SALT',        'wEkQOHU4r5re9/ZnUSarh2axiCVCXC0hOmnDhDcPu8DQwwQfEhJqQGYohMwoc9Ozi9DLF7vOxYTn2OZX5DRCMw==');
define('SECURE_AUTH_SALT', 'gLAXKmPUAadcCnFsG1fPI99g646IbCclE/ITKGQxeb/R0LOiRwF8Z3hsBcYQJmPQbWPFxrlxTlMTTl9D6GTyGQ==');
define('LOGGED_IN_SALT',   '1J/WJ6bblS69JBUV6FwD6XKiUWWipOK7mV7MW0wU6ipdgMRhxI/aWWq4nOWoPPjsG7RmpPFEvJG0pbFj0ApDOw==');
define('NONCE_SALT',       'ev48Fquu7J9l6wlYnz+spP7i2X7W+11kOViTnMat7cL5ROzaj1Ng2yf/awL/f5DPfA6B6JnfOYhYByaEzCDqTA==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
