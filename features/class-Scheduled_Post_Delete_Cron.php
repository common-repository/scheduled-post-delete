<?php
 * Feature Name:	The wp-cron job
 * Version:			0.1
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

if ( ! class_exists( 'Scheduled_Post_Delete_Cron' ) ) {

	class Scheduled_Post_Delete_Cron extends Scheduled_Post_Delete {

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
		 * @var		NULL | Scheduled_Post_Delete_Cron
		 */
		private static $instance = NULL;
		
		/**
		 * Method for ensuring that only one instance of this object is used
		 *
		 * @since	0.1
		 * @access	public
		 * @static
		 * @return	Scheduled_Post_Delete_Cron
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
			
				wp_schedule_event( current_time( 'timestamp' ), 'daily', 'check_and_remove_scheduled_delete_posts');
		}
		 * This method checks all the old posts with
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	
		 * @return	void
		 */
					array(
						'key'		=> 'spd_scheduled_delete',
						'value'		=> 'on',
						'compare'	=> 'IS'
					),
						'key'		=> 'spd_scheduled_delete_date',
						'value'		=> date( 'Y-m-d h:i:s' ),
						'compare'	=> '<='
					)
				),
				delete_post_meta( $scheduled_post->ID, 'spd_scheduled_unstick_date' );

// Kickoff
if ( function_exists( 'add_filter' ) )
	Scheduled_Post_Delete_Cron::get_instance();