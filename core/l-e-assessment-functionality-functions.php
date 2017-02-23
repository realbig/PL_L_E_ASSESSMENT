<?php
/**
 * Provides helper functions.
 *
 * @since	  1.0.0
 *
 * @package	L_E_Assessment_Functionality
 * @subpackage L_E_Assessment_Functionality/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		1.0.0
 *
 * @return		L_E_Assessment_Functionality
 */
function LEASSESSMENTFUNCTIONALITY() {
	return L_E_Assessment_Functionality::instance();
}