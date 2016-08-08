<?php

/**
* shortocdes manager
*/
class HBShortcodeManager
{

	//shortcodes
	public function registerShortcodes() {
		add_shortcode('hbmd-search', array($this, 'heartBeatMaterialDesignSearch'));
		add_shortcode('hb-search', array($this, 'heartBeatSearch'));
	}

	//material design search form
	public function heartBeatMaterialDesignSearch($atts, $content = null) {
		extract(shortcode_atts(array('max_results' => 5, 'placeholder' => 'Search'), $atts));
		?>
		<div class="hbmd-form-ui">
			<form role="search" method="get" class="hbmd-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="hbmd-group">      
					<input class="hbmd-search-input" type="text" required>
					<span class="bar"></span>
					<label><?php echo $placeholder;?></label>
				</div>
			</form>		
		</div>
		<?php
		return '';
	}

	//heartbeat simple search form
	public function heartBeatSearch($atts, $content = null) {
		extract(shortcode_atts(array('max_results' => 5, 'placeholder' => 'Search'), $atts));
		?>
		<div class="hb-form-ui">
			<form role="search" method="get" class="hb-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input class="hb-search-input" type="text" name="search" placeholder="<?php echo $placeholder;?>">
			</form>		
		</div>
		<?php
		return '';
	}	
	
}
?>