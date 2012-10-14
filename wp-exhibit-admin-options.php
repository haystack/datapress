<?php 
// mt_options_page() displays the page content for the Test Options submenu
function exhibit_options_page() {
	// variables for the field and option names 
	    $opt_name = 'google_map_api_key';
	    $hidden_field_name = 'mt_submit_hidden';
	    $data_field_name = 'google_map_api_key';
	
	    $et_opt_name = 'datapress_et_phone_home';
	    $et_opt_field_name = 'send_statistics';

	    // Read in existing option value from database
	    $google_map_api_key = get_option( $opt_name );
		$et_phone_home = get_option($et_opt_name);

	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( $_POST[ $hidden_field_name ] == 'Y' ) {
	        // Read their posted value
	        $google_map_api_key = $_POST[ $data_field_name ];
			$et_phone_home = ("YES" === $_POST[ $et_opt_field_name ]) ? "Y" : "N" ;
			
	        // Save the posted value in the database
	        update_option( $opt_name, $google_map_api_key );
	        update_option( $et_opt_name, $et_phone_home );

	        // Put an options updated message on the screen

	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
	<?php

	    }

	    // Now display the options editing screen

	    echo '<div class="wrap">';

	    // header

	    echo "<h2>" . __( 'DataPress Options', 'mt_trans_domain' ) . "</h2>";

	    // options form

	    ?>

	<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

	<h3><?php _e("Google Maps API Key", 'mt_trans_domain' ); ?></h3> 
	<input type="text" style="width: 350px;" name="<?php echo $data_field_name; ?>" value="<?php echo $google_map_api_key; ?>" size="20">(<a href="http://code.google.com/apis/maps/signup.html">Get a Google Maps Key</a>)<br />
	<p>This key allows your Exhibits to access to Google Map functionality.</p>

	<h3><?php _e("Participate in Usage Study", 'mt_trans_domain' ); ?></h3>
	<p><input type="checkbox" style="width: 350px;" name="<?php echo $et_opt_field_name; ?>" <?php echo (($et_phone_home == "Y") ? "checked" : "" ) ?> value="YES" /> <b>Help Research!</b></p>
	<p>The <a href="http://haystack.csail.mit.edu">Haystack Research Group</a> at <a href="http://www.mit.edu">MIT</a> <a href="http://csail.mit.edu/">CSAIL</a> is studying whether tools like Datapress will help promote data publishing on the web.<br />
	If the above box is checked, Datapress will ping MIT every time one of your DataPress Exhibits is viewed so that we can count the number of viewers. 
	<br />Learn more at the <a href="http://projects.csail.mit.edu/datapress">Datapress Website</a>.</p>

	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
	</p>

	</form>
	</div>

	<?php
}
?>
