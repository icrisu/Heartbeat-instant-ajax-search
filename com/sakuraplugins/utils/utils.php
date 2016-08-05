<?php
/**
* utils
*/
class HBeatUtils
{

	//enque required fonts
	public static function enqueFontsFrom($fonts){
		$protocol = is_ssl() ? 'https' : 'http';
		for ($i=0; $i < sizeof($fonts); $i++) { 
			wp_enqueue_style($fonts[$i]['key'], $protocol.$fonts[$i]['resource']);
		}
	}	
}

?>