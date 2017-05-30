<?php
/**
 * Create the metaboxes for writing the case study content
 */

// Create Meta Boxes
function add_meta_boxes_fitcasestudy() {
	add_meta_box(
		'fitcase-client-info',
		__( 'Client Information', 'fitcasestudy' ),
		'meta_box_clientinfo_callback',
		'fitcasestudy',
		'normal',
		'high'
	);

	add_meta_box(
		'fitcase-client-history',
		__( 'Client History', 'fitcasestudy' ),
		'meta_box_clienthist_callback',
		'fitcasestudy',
		'normal',
		'high'
	);

	add_meta_box(
		'fitcase-client-results',
		__( 'Client Results', 'fitcasestudy' ),
		'meta_box_clientresults_callback',
		'fitcasestudy',
		'normal',
		'high'
	);

	add_meta_box(
		'fitcase-study-client-pics',
		__( 'Client Progress Photos', 'fitcasestudy' ),
		'meta_box_clientpics_callback',
		'fitcasestudy',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'add_meta_boxes_fitcasestudy' );

/*
 * Meta Box Callback for Client Info - Contains name, age, sex, and other basic information about client
 */
function meta_box_clientinfo_callback( $post )  {
	global $fitcase_post_meta;
	$fitcase_post_meta = get_post_meta( $post->ID );
	wp_nonce_field( plugin_basename( __FILE__ ), 'fitcase-nonce' );

	echo '<pre>';
	    print_r($fitcase_post_meta);
	echo '</pre>';
	?>

	<p>Please enter in the information on the client whom you wish to feature in a case story.</p>
	<p>
        <label for="fitcase_client_first_name"><?php _e( 'First Name', 'fitcasestudy' ); ?></label>
        <input type="text" name="fitcase_client_name" id="fitcase_client_name" size="25" value="<?php if ( isset( $fitcase_post_meta['fitcase_client_first_name'] ) ) echo $fitcase_post_meta['fitcase_client_first_name'][0]; ?>" />
    </p>

    <p>
        <label for="fitcase_client_first_name"><?php _e( 'Last Name', 'fitcasestudy' ); ?></label>
        <input type="text" name="fitcase_client_name" id="fitcase_client_name" size="25" value="<?php if ( isset( $fitcase_post_meta['fitcase_client_last_name'] ) ) echo $fitcase_post_meta['fitcase_client_last_name'][0]; ?>" />
    </p>

	<p>
		<label for="fitcase-client-age"><?php _e( 'Age', 'fitcasestudy' ); ?></label>
		<input type="text" name="fitcase_client_age" id="fitcase_client_age" value="<?php if ( isset( $fitcase_post_meta['fitcase_client_age'] ) ) echo $fitcase_post_meta['fitcase_client_age'][0];  ?>" size="3" maxlength="3" />
	</p>

	<p>
		<label for="fitcase_client_gender"><?php _e( 'Gender', 'fitcasestudy' ); ?></label>
		<select id="fitcase_client_gender" name="fitcase_client_gender">
			<option value="male">Male</option>
			<option value="female">Female</option>
			<option value="other">Other</option>
		</select>
	</p>

	<p>
        <label for="fitcase_client_height"><?php _e( 'Height', 'fitcasestudy' ); ?></label>
        <?php testMetric(); ?>
	</p>

	<?php
}

function meta_box_clienthist_callback( $post ) {
	global $fitcase_post_meta;

	$editor_id = 'fitcase_client_history';
	$fitcasestudy_client_history_content = '';

	if ( isset( $fitcase_post_meta['fitcase_client_history'] ) ) {
		$fitcasestudy_client_history_content = $fitcase_post_meta['fitcase_client_history'][0];
    }



	if ( $fitcasestudy_client_history_content != '' ) {
		$editor_content = $fitcasestudy_client_history_content;
	} else {
		$editor_content = '';
	}
	?>
	<p>Enter in the history of the client.</p>

	<?php
	wp_editor( $editor_content, $editor_id );
}

function meta_box_clientresults_callback( $post ) {
	echo '<h2>Client Results</h2>';
}

function meta_box_clientpics_callback( $post ) {
	$fitcase_post_meta = get_post_meta( $post->ID );
	?>

	<p>Before/After photos of client</p>

	<?php
}

/**
 * Save Meta Box Data to the database
 */
function fitcase_save_meta( $post_id, $post, $update ) {

	global $fitcase_post_meta;
	$fitcase_post_meta = get_post_meta( $post_id );
	error_log( 'fitcase_save_meta is firing');


	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		error_log( 'fitcase_save_meta: Doing Autosave');
		return;
	}

	// Verify Nonce


	if ( !current_user_can( 'edit_post', $post_id ) ) {
		error_log('fitcase_save_meta: user cannot edit posts');
		return;
	}

	if ( !current_user_can( 'edit_page' , $post_id ) ) {
		error_log( 'fitcase_save_meta: user cannot edit pages');
		return;
	}

	error_log('All Conditions are Met');

	if ( isset( $_POST[ 'fitcase_client_first_name' ] ) ) {
		$fitcase_client_name = $_POST[ 'fitcase_client_first_name' ];
		update_post_meta( $post_id, 'fitcase_client_first_name', $fitcase_client_name );
	}

	if ( isset( $_POST[ 'fitcase_client_last_name' ] ) ) {
		$fitcase_client_name = $_POST[ 'fitcase_client_last_name' ];
		update_post_meta( $post_id, 'fitcase_client_last_name', $fitcase_client_name );
	}

	if ( isset( $_POST['fitcase_client_age'] ) ) {
	    $fitcase_client_age = $_POST['fitcase_client_age'];
	    update_post_meta( $post_id, 'fitcase_client_age', $fitcase_client_age );
    }

	if ( isset( $_POST['fitcase_client_history'] ) ) {
		$fitcase_client_history = $_POST[ 'fitcase_client_history' ];
		update_post_meta( $post_id, 'fitcase_client_history', $fitcase_client_history );
	}

	error_log( 'fitcase_save_meta fired');

	error_log('$_POST Contents:' . print_r($_POST, true) );

}
add_action( 'save_post', 'fitcase_save_meta', 10, 3 );