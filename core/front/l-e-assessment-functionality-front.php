<?php
/**
 * L&E Assessment Front Functionality
 *
 * @since 1.0.0
 *
 * @package L_E_Assessment_Functionality
 * @subpackage L_E_Assessment_Functionality/core/front
 */

defined( 'ABSPATH' ) || die();

class L_E_Assessment_Functionality_Front {

	/**
	 * L_E_Assessment_Functionality_Front constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		
		add_action( 'show_admin_bar', array( $this, 'remove_admin_bar' ) );
		
		add_filter( 'login_redirect', array( $this, 'login_redirect' ), 10, 3 );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

	}
	
	/**
	 * Don't show an Admin Bar for non-priveleged users
	 * @return boolean Show/Hide
	 */
	public function remove_admin_bar() {
		
		$current_roles = wp_get_current_user()->roles;
		
		$restricted_roles = apply_filters( 'l-e-assessment-restricted', array(
			'subscriber',
		) );
		
		foreach( $restricted_roles as $role ) {
			
			if ( in_array( $role, $current_roles ) ) {

				return false;

			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request     URL the user is coming from.
	 * @param object $user        Logged user's data.
	 * @return string
	 */
	public function login_redirect( $redirect_to, $request, $user ) {
		
		if ( isset( $user->roles ) ) {
		
			$current_roles = $user->roles;

			$restricted_roles = apply_filters( 'l-e-assessment-restricted', array(
				'subscriber',
			) );

			foreach( $restricted_roles as $role ) {

				if ( in_array( $role, $current_roles ) ) {

					return home_url();

				}

			}
			
		}
		
		return $redirect_to;
		
	}
	
	public function wp_enqueue_scripts() {
		
		wp_enqueue_style( L_E_Assessment_Functionality_ID . '-front' );
		
		wp_enqueue_script( L_E_Assessment_Functionality_ID . '-front' );
		
	}

}