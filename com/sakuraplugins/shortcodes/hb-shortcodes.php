<?php

/**
* shortocdes manager
*/
class HBShortcodeManager
{

	//shortcodes
	public function registerShortcodes() {
		add_shortcode('hbmd-search', array($this, 'heartBeatMaterialDesignSearch'));
	}

	//material design search form
	public function heartBeatMaterialDesignSearch($atts, $content = null) {
		//extract(shortcode_atts(array('show_logo' => 'true'), $atts));		
		?>
		<form role="search" method="get" class="hbmd-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="hbmd-form-group">      
				<input class="hbmd-form-input" type="text" required>
				<span class="highlight"></span>
				<span class="bar"></span>
				<label>Name</label>
			</div>
		</form>		
		<?php
		return '';
	}
	
}
?>