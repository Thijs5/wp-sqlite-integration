<?php
/**
 * This file must be placed in the directory
 * 
 *   wordpress/wp-content/db.php
 * 
 * @package SQLite Integration
 * @version 1.0
 * @author Kojima Toshiyasu, Justin Adie
 *
 */

function pdo_log_erro($message, $data = null) {
  
  if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin') !== false) {
    $admin_dir = '';
  } else {
    $admin_dir = 'wp-admin/';
  }
  die(<<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>WordPress &rsaquo; Error</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="{$admin_dir}install.css" type="text/css" />
</head>
<body>
  <h1 id="logo"><img alt="WordPress" src="{$admin_dir}images/wordpress-logo.png" /></h1>
  <p>$message</p>
  <p>$data</p>
</body>
<html>

HTML
);
}

if (version_compare( PHP_VERSION, '5.2.4', '<')) {
  pdo_log_erro(__('PHP version on this server is too old.'), sprinf(__("Your server is running PHP version %d but this version of WordPress requires at least 5.2.4"), phpversion()));
}

if (!extension_loaded('pdo')) {
  pdo_log_erro(__('PHP PDO Extension is not loaded.'), __('Your PHP installation appears to be missing the PDO extension which is required for this version of WordPress.'));
}

if (!extension_loaded('pdo_sqlite')) {
  pdo_log_erro(__('PDO Driver for SQLite is missing.'), __('Your PHP installtion appears not to have the right PDO drivers loaded. These are required for this version of WordPress and the type of database you have specified.'));
}

/**
 * Notice:
 * Your scripts have the permission to create directories or files on your server.
 * If you write in your wp-config.php like below, we take these definitions.
 * define('DB_DIR', '/full_path_to_the_database_directory/');
 * define('DB_FILE', 'database_file_name');
 */
if (defined('WP_PLUGIN_DIR')) {
  define('PDODIR', WP_PLUGIN_DIR . '/sqlite-integration/');
} else {
  if (defined('WP_CONTENT_DIR')) {
    define('PDODIR', WP_CONTENT_DIR . '/plugins/sqlite-integration/');
  } else {
    define('PDODIR', ABSPATH . 'wp-content/plugins/sqlite-integration/');
  }
}

if (defined('DB_DIR')) {
  if (substr(DB_DIR, -1, 1) != '/') {
    define('FQDBDIR', DB_DIR . '/');
  } else {
    define('FQDBDIR', DB_DIR);
  }
} else {
  if (defined('WP_CONTENT_DIR')) {
    define('FQDBDIR', WP_CONTENT_DIR . '/database/');
  } else {
    define('FQDBDIR', ABSPATH . 'wp-content/database/');
  }
}

if ( defined('DB_FILE' )) {
  define('FQDB', FQDBDIR . DB_FILE);
} else {
  define('FQDB', FQDBDIR . '.ht.sqlite');
}

if (version_compare(PHP_VERSION, '5.3', '<')) {
  define('UDF_FILE', PDODIR . 'functions-5-2.php');
} elseif (version_compare(PHP_VERSION, '5.3', '>=')) {
  define('UDF_FILE', PDODIR . 'functions.php');
}

require_once PDODIR . 'pdodb.class.php';
?>