<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
require_once(dirname(__FILE__) . '/com/sakuraplugins/models/settings-model.php');
require_once(dirname(__FILE__) . '/com/sakuraplugins/models/index-model.php');
require_once(dirname(__FILE__) . '/com/sakuraplugins/models/search-terms-model.php');

$settingsModel = new HBSettingsModel();
$settingsModel->destroy();

$indexModel = new IndexModel();
$indexModel->destroy();

$searchModel = new HBSearchTermsModel();
$searchModel->destroy();
?>