<?php/**
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
						// Add the schedule event for the check			add_filter( 'check_and_remove_scheduled_delete_posts', array( $this, 'check_and_remove_posts' ) );			if ( ! wp_next_scheduled( 'check_and_remove_scheduled_delete_posts' ) )
				wp_schedule_event( current_time( 'timestamp' ), 'daily', 'check_and_remove_scheduled_delete_posts');
		}				/**
		 * This method checks all the old posts with		 * the post meta to delete itself
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	
		 * @return	void
		 */		public function check_and_remove_posts() {						// Get the scheduled posts			$scheduled_posts = new WP_Query( array(				'post_type'			=> 'post',				'post_status'		=> 'publish',				'showposts'			=> -1,				'posts_per_page'	=> -1,				'meta_query' => array(
					array(
						'key'		=> 'spd_scheduled_delete',
						'value'		=> 'on',
						'compare'	=> 'IS'
					),					array(
						'key'		=> 'spd_scheduled_delete_date',
						'value'		=> date( 'Y-m-d h:i:s' ),
						'compare'	=> '<='
					)
				),			) );						// Trash the posts			foreach ( $scheduled_posts->posts as $scheduled_post ) {				wp_trash_post( $scheduled_post->ID );				delete_post_meta( $scheduled_post->ID, 'spd_scheduled_unstick' );
				delete_post_meta( $scheduled_post->ID, 'spd_scheduled_unstick_date' );			}		}	}}

// Kickoff
if ( function_exists( 'add_filter' ) )
	Scheduled_Post_Delete_Cron::get_instance();?>