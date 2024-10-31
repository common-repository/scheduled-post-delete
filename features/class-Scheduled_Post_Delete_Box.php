<?php
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
			
			add_filter( 'post_submitbox_misc_actions', array( $this, 'post_submitbox_misc_actions' ) );
			add_filter( 'save_post', array( $this, 'save_meta_data' ) );
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
				
			update_post_meta( $_POST[ 'ID' ], 'spd_scheduled_delete', $_POST[ 'spd_scheduled_delete' ] );
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
				<label for="spd_scheduled_delete"><?php _e( 'Schedule a deletion for this post', parent::$textdomain ); ?></label>
			</div>
			<?php
		}
		 * Displays the datepick stuff
		 */

// Kickoff
if ( function_exists( 'add_filter' ) )
	Scheduled_Post_Delete_Box::get_instance();