<?php
/*
Plugin Name: HeartBeat - Instant WordPress Ajax Search
Plugin URI: http://sakuraplugins.com/
Description: HeartBeat - Instant Ajax Search for WordPress
Author: SakuraPlugins
Version: 1.0.0
Author URI: http://sakuraplugins.com/
*/

define('HEARTBEAT_ADMIN_URI', plugins_url('', __FILE__).'/resources/admin');
define('HEARTBEAT_FRONT_URI', plugins_url('', __FILE__).'/resources/front');
define('HEARTBEAT_FILE', __FILE__);

require_once(__DIR__.'/com/sakuraplugins/core.php');

$core = new HeartBeatCore();
$core->run();

?>