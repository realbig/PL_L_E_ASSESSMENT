<?php
/**
 * L&E Assessment Admin Functionality
 *
 * @since 1.0.0
 *
 * @package L_E_Assessment_Functionality
 * @subpackage L_E_Assessment_Functionality/core/admin
 */

defined( 'ABSPATH' ) || die();

class L_E_Assessment_Functionality_Admin {

	/**
	 * L_E_Assessment_Functionality_Admin constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		
		$current_roles = wp_get_current_user()->roles;
		
		// Allow multiple different User Roles to be prevented from viewing their own Quiz results on their Profile Screen
		$restricted_roles = apply_filters( 'l-e-assessment-restricted', array(
			'subscriber',
		) );
		
		foreach( $restricted_roles as $role ) {
			
			if ( in_array( $role, $current_roles ) ) {

				// LearnDash isn't exactly kind with how it handles things
				$this->remove_class_action( 'show_user_profile', 'SFWD_LMS', 'show_course_info' );
				$this->remove_class_action( 'edit_user_profile', 'SFWD_LMS', 'show_course_info' );

				$this->remove_class_action( 'show_user_profile', 'Learndash_Admin_User_Profile_Edit', 'show_user_profile' );
				$this->remove_class_action( 'edit_user_profile', 'Learndash_Admin_User_Profile_Edit', 'show_user_profile' );

			}
			
		}

	}

	/**
	 * Remove Class Filter Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_filter() on a filter added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove filters with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 *
	 * @param string $tag         Filter to remove
	 * @param string $class_name  Class name for the filter's callback
	 * @param string $method_name Method name for the filter's callback
	 * @param int    $priority    Priority of the filter (default 10)
	 *
	 * @return bool Whether the function is removed.
	 */
	public function remove_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;

		// Check that filter actually exists first
		if ( ! isset( $wp_filter[ $tag ] ) ) return FALSE;

		/**
		 * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
		 * a simple array, rather it is an object that implements the ArrayAccess interface.
		 *
		 * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
		 *
		 * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		 */
		if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {
			$callbacks = &$wp_filter[ $tag ]->callbacks;
		} else {
			$callbacks = &$wp_filter[ $tag ];
		}

		// Exit if there aren't any callbacks for specified priority
		if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) return FALSE;

		// Loop through each filter for the specified priority, looking for our class & method
		foreach( (array) $callbacks[ $priority ] as $filter_id => $filter ) {

			// Filter should always be an array - array( $this, 'method' ), if not goto next
			if ( ! isset( $filter[ 'function' ] ) || ! is_array( $filter[ 'function' ] ) ) continue;

			// If first value in array is not an object, it can't be a class
			if ( ! is_object( $filter[ 'function' ][ 0 ] ) ) continue;

			// Method doesn't match the one we're looking for, goto next
			if ( $filter[ 'function' ][ 1 ] !== $method_name ) continue;

			// Method matched, now let's check the Class
			if ( get_class( $filter[ 'function' ][ 0 ] ) === $class_name ) {

				// Now let's remove it from the array
				unset( $callbacks[ $priority ][ $filter_id ] );

				// and if it was the only filter in that priority, unset that priority
				if ( empty( $callbacks[ $priority ] ) ) unset( $callbacks[ $priority ] );

				// and if the only filter for that tag, set the tag to an empty array
				if ( empty( $callbacks ) ) $callbacks = array();

				// If using WordPress older than 4.7
				if ( ! is_object( $wp_filter[ $tag ] ) ) {
					// Remove this filter from merged_filters, which specifies if filters have been sorted
					unset( $GLOBALS[ 'merged_filters' ][ $tag ] );
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_action() on an action added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove actions with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 *
	 * @param string $tag         Action to remove
	 * @param string $class_name  Class name for the action's callback
	 * @param string $method_name Method name for the action's callback
	 * @param int    $priority    Priority of the action (default 10)
	 *
	 * @return bool               Whether the function is removed.
	 */
	public function remove_class_action( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		$this->remove_class_filter( $tag, $class_name, $method_name, $priority );
	}

}