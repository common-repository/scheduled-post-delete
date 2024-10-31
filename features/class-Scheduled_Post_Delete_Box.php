<?php/**
 * Feature Name:	The Box
 * Version:			0.1
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

if ( ! class_exists( 'Scheduled_Post_Delete_Box' ) ) {

	class Scheduled_Post_Delete_Box extends Scheduled_Post_Delete {

		/**
		 * Tab holder
		 *
		 * @since	0.1
		 * @access	public
		 * @var		array
		 */
		public $tabs = array();
		
		/**
		 * Instance holder
		 *
		 * @since	0.1
		 * @access	private
		 * @static
		 * @var		NULL | Scheduled_Post_Delete_Box
		 */
		private static $instance = NULL;
		
		/**
		 * Method for ensuring that only one instance of this object is used
		 *
		 * @since	0.1
		 * @access	public
		 * @static
		 * @return	Scheduled_Post_Delete_Box
		 */
		public static function get_instance() {
				
			if ( ! self::$instance )
				self::$instance = new self;
				
			return self::$instance;
		}
		
		/**
		 * Setting up some data, initialize translations and start the hooks
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	is_admin, add_filter
		 * @return	void
		 */
		public function __construct() {
			
			// Options Pages are only visible in the admin area,
			// so we don't need to fire this filters
			if ( ! is_admin() )
				return;
						// Register schedule delete checkbox
			add_filter( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );						// Adds the save post hook
			add_filter( 'save_post', array( $this, 'save_meta_data' ) );		}				/**
		 * Saves the post meta
		 *
		 * @access	public
		 * @since	0.1
		 * @uses	DOING_AUTOSAVE, current_user_can, update_post_meta
		 * @return	void
		 */
		public function save_meta_data() {
		
			// Preventing Autosave, we don't want that
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;
		
			// Do we have a post
			if ( 'post' != get_post_type( $_POST[ 'ID' ] ) )
				return;
		
			// Check permissions
			if ( ! current_user_can( 'edit_posts', $_POST[ 'ID' ] ) )
				return;
		
			// Add Post Meta if there is one
			if ( ! isset( $_POST[ 'spd_scheduled_delete' ] ) )
				$_POST[ 'spd_scheduled_delete' ] = '';
				
			update_post_meta( $_POST[ 'ID' ], 'spd_scheduled_delete', $_POST[ 'spd_scheduled_delete' ] );						// Check the date			if ( 'on' == $_POST[ 'spd_scheduled_delete' ] ) {				$spd_scheduled_delete_date = $_POST[ 'spd_aa' ] . '-' . $_POST[ 'spd_mm' ] . '-' . $_POST[ 'spd_jj' ] . ' ' . $_POST[ 'spd_hh' ] . ':' . $_POST[ 'spd_mn' ] . ':' . $_POST[ 'spd_ss' ];				update_post_meta( $_POST[ 'ID' ], 'spd_scheduled_delete_date', $spd_scheduled_delete_date );			}		}				/**
		 * Displays the checkbox
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	get_post_meta, _e
		 * @return	void
		 */
		public function post_submitbox_misc_actions() {
		
			if ( isset( $_GET[ 'post' ] ) )
				$scheduled_delete = get_post_meta( $_GET[ 'post' ], 'spd_scheduled_delete', TRUE );
			else
				$scheduled_delete = FALSE;
			?>
			<div class="misc-pub-section curtime misc-pub-section-last">
				<input type="checkbox" id="spd_scheduled_delete" name="spd_scheduled_delete" <?php if ( 'on' == $scheduled_delete ) echo 'checked="checked"'; ?> />
				<label for="spd_scheduled_delete"><?php _e( 'Schedule a deletion for this post', parent::$textdomain ); ?></label>								<?php $this->touch_time( $scheduled_delete ); ?>
			</div>
			<?php
		}				/**
		 * Displays the datepick stuff		 *		 * @since	0.1		 * @access	public		 * @param	mixed $scheduled_delete check if the checkbox is checked		 * @uses	get_post_meta, $wp_locale, $post		 * @return	void
		 */		public function touch_time( $scheduled_delete = FALSE ) {			global $wp_locale, $post;						// Set Tab index			$tab_index = 0;			$tab_index_attribute = '';			if ( (int) $tab_index > 0 )				$tab_index_attribute = " tabindex=\"$tab_index\"";					// Set Checkup			$time_adj = current_time( 'timestamp' );			$post_date = get_post_meta( $post->ID, 'spd_scheduled_delete_date', TRUE );			if ( '' == $post_date )				$post_date = date( 'Y-m-d h:i:s' );							$jj = mysql2date( 'd', $post_date, FALSE );			$mm = mysql2date( 'm', $post_date, FALSE );			$aa = mysql2date( 'Y', $post_date, FALSE );			$hh = mysql2date( 'H', $post_date, FALSE );			$mn = mysql2date( 'i', $post_date, FALSE );			$ss = mysql2date( 's', $post_date, FALSE );					$cur_jj = gmdate( 'd', $time_adj );			$cur_mm = gmdate( 'm', $time_adj );			$cur_aa = gmdate( 'Y', $time_adj );			$cur_hh = gmdate( 'H', $time_adj );			$cur_mn = gmdate( 'i', $time_adj );					$month = "<select id=\"mm\" name=\"spd_mm\"$tab_index_attribute>\n";			for ( $i = 1; $i < 13; $i = $i +1 ) {				$monthnum = zeroise($i, 2);				$month .= "\t\t\t" . '<option value="' . $monthnum . '"';				if ( $i == $mm )					$month .= ' selected="selected"';				/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */				$month .= '>' . sprintf( __( '%1$s-%2$s' ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . "</option>\n";			}			$month .= '</select>';					$day = '<input type="text" id="jj" name="spd_jj" value="' . $jj . '" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';			$year = '<input type="text" id="aa" name="spd_aa" value="' . $aa . '" size="4" maxlength="4"' . $tab_index_attribute . ' autocomplete="off" />';			$hour = '<input type="text" id="hh" name="spd_hh" value="' . $hh . '" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';			$minute = '<input type="text" id="mn" name="spd_mn" value="' . $mn . '" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';					echo '<div id="spd_timestamp" style="display: ' . ( FALSE == $scheduled_delete ? 'none' : 'block' ) . ';">';			/* translators: 1: month input, 2: day input, 3: year input, 4: hour input, 5: minute input */			printf( __( '%1$s%2$s, %3$s @ %4$s : %5$s' ), $month, $day, $year, $hour, $minute );					echo '<input type="hidden" id="spd_ss" name="spd_ss" value="' . $ss . '" /></div>';		}	}}

// Kickoff
if ( function_exists( 'add_filter' ) )
	Scheduled_Post_Delete_Box::get_instance();?>