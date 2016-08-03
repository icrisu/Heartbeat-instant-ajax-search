<?php

class HeartBeatHeader
{
	public static function render() {
		$appetitInfo = get_plugin_data(HEARTBEAT_FILE, $markup = true, $translate = true );
		?>
		<div class="sakura-admin-header">
			<img src="<?php echo HEARTBEAT_ADMIN_URI . '/img/admin-logo.png'; ?>" alt="logo" />
			<p class="sakura-admin-header-info">Version <?php echo $appetitInfo['Version']?></p>
		</div>
		<?php
	}
}

?>