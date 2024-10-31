<?php
/**
 * Feature Name:	Plugins Init
 * Version:			0.1
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

if ( ! class_exists( 'Scheduled_Post_Delete_Init' ) ) {

	class Scheduled_Post_Delete_Init extends Scheduled_Post_Delete {

		/**
		 * Instance holder
		 *
		 * @since	0.1
		 * @access	private
		 * @static
		 * @var		NULL | Scheduled_Post_Delete_Init
		 */
		private static $instance = NULL;
		
		/**
		 * Method for ensuring that only one instance of this object is used
		 *
		 * @since	0.1
		 * @access	public
		 * @static
		 * @return	Scheduled_Post_Delete_Init
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
			
			// Check if user is in admin panel and load the
			// admin filters
			if ( is_admin() ) {
				
				// Adding JS
				add_filter( 'admin_head', array( $this, 'admin_scripts' ) );
				
			}
		}
		
		/**
		 * Load the admin scripts
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	wp_register_script, wp_enqueue_script, plugin_dir_url
		 * @return	void
		 */
		public function admin_scripts() {
				
			// Styles
			wp_register_script( parent::$textdomain . '-admin-scripts', plugin_dir_url( __FILE__ ) . '../js/admin.js', array( 'jquery' ) );
			wp_enqueue_script( parent::$textdomain . '-admin-scripts' );
		}
	}
	
	// Kick-Off
	Scheduled_Post_Delete_Init::get_instance();
}