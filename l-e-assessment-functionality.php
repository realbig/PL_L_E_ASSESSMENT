<?php
/*
Plugin Name: L&E Assessment Functionality
Description: Adds some additional functionality for the L&E Assessment
Version: 0.1.0
Text Domain: l-e-assessment-functionality
Author: Eric Defore
Author URL: https://realbigmarketing.com/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'L_E_Assessment_Functionality' ) ) {

	/**
	 * Main L_E_Assessment_Functionality class
	 *
	 * @since	  1.0.0
	 */
	class L_E_Assessment_Functionality {
		
		/**
		 * @var			L_E_Assessment_Functionality $plugin_data Holds Plugin Header Info
		 * @since		1.0.0
		 */
		public $plugin_data;
		
		/**
		 * @var			L_E_Assessment_Functionality $admin Admin Functionality
		 * @since		1.0.0
		 */
		public $admin;
		
		/**
		 * @var			L_E_Assessment_Functionality $front Frontend Functionality
		 * @since		1.0.0
		 */
		public $front;
		
		/**
		 * @var			L_E_Assessment_Functionality $admin_errors Stores all our Admin Errors to fire at once
		 * @since		1.0.0
		 */
		private $admin_errors;

		/**
		 * Get active instance
		 *
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  object self::$instance The one true L_E_Assessment_Functionality
		 */
		public static function instance() {
			
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new static();
			}
			
			return $instance;

		}
		
		protected function __construct() {
			
			$this->setup_constants();
			$this->load_textdomain();
			
			$this->require_necessities();
			
			// Register our CSS/JS for the whole plugin
			add_action( 'init', array( $this, 'register_scripts' ) );
			
		}

		/**
		 * Setup plugin constants
		 *
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function setup_constants() {
			
			// WP Loads things so weird. I really want this function.
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			
			// Only call this once, accessible always
			$this->plugin_data = get_plugin_data( __FILE__ );
			
			if ( ! defined( 'L_E_Assessment_Functionality_ID' ) ) {
				// Plugin Text Domain
				define( 'L_E_Assessment_Functionality_ID', $this->plugin_data['TextDomain'] );
			}

			if ( ! defined( 'L_E_Assessment_Functionality_VER' ) ) {
				// Plugin version
				define( 'L_E_Assessment_Functionality_VER', $this->plugin_data['Version'] );
			}

			if ( ! defined( 'L_E_Assessment_Functionality_DIR' ) ) {
				// Plugin path
				define( 'L_E_Assessment_Functionality_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'L_E_Assessment_Functionality_URL' ) ) {
				// Plugin URL
				define( 'L_E_Assessment_Functionality_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'L_E_Assessment_Functionality_FILE' ) ) {
				// Plugin File
				define( 'L_E_Assessment_Functionality_FILE', __FILE__ );
			}

		}

		/**
		 * Internationalization
		 *
		 * @access	  private 
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function load_textdomain() {

			// Set filter for language directory
			$lang_dir = L_E_Assessment_Functionality_DIR . '/languages/';
			$lang_dir = apply_filters( 'l_e_assessment_functionality_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), L_E_Assessment_Functionality_ID );
			$mofile = sprintf( '%1$s-%2$s.mo', L_E_Assessment_Functionality_ID, $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/' . L_E_Assessment_Functionality_ID . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/l-e-assessment-functionality/ folder
				// This way translations can be overridden via the Theme/Child Theme
				load_textdomain( L_E_Assessment_Functionality_ID, $mofile_global );
			}
			else if ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/l-e-assessment-functionality/languages/ folder
				load_textdomain( L_E_Assessment_Functionality_ID, $mofile_local );
			}
			else {
				// Load the default language files
				load_plugin_textdomain( L_E_Assessment_Functionality_ID, false, $lang_dir );
			}

		}
		
		/**
		 * Include different aspects of the Plugin
		 * 
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function require_necessities() {
			
			if ( is_admin() ) {
				
				require_once L_E_Assessment_Functionality_DIR . 'core/admin/l-e-assessment-functionality-admin.php';
				$this->admin = new L_E_Assessment_Functionality_Admin();
				
			}
			else {
				
				require_once L_E_Assessment_Functionality_DIR . 'core/front/l-e-assessment-functionality-front.php';
				$this->front = new L_E_Assessment_Functionality_Front();
				
			}
			
		}
		
		/**
		 * Show admin errors.
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  HTML
		 */
		public function admin_errors() {
			?>
			<div class="error">
				<?php foreach ( $this->admin_errors as $notice ) : ?>
					<p>
						<?php echo $notice; ?>
					</p>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		/**
		 * Register our CSS/JS to use later
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  void
		 */
		public function register_scripts() {
			
			wp_register_style(
				L_E_Assessment_Functionality_ID . '-admin',
				L_E_Assessment_Functionality_URL . 'assets/css/admin.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : L_E_Assessment_Functionality_VER
			);
			
			wp_register_script(
				L_E_Assessment_Functionality_ID . '-admin',
				L_E_Assessment_Functionality_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : L_E_Assessment_Functionality_VER,
				true
			);
			
			wp_localize_script( 
				L_E_Assessment_Functionality_ID . '-admin',
				'lEAssessmentFunctionality',
				apply_filters( 'l_e_assessment_functionality_localize_admin_script', array() )
			);
			
		}
		
	}
	
} // End Class Exists Check

require_once __DIR__ . '/core/l-e-assessment-functionality-functions.php';

/**
 * The main function responsible for returning the one true L_E_Assessment_Functionality
 * instance to functions everywhere
 *
 * @since	  1.0.0
 * @return	  \L_E_Assessment_Functionality The one true L_E_Assessment_Functionality
 */
add_action( 'init', 'l_e_assessment_functionality_load' );
function l_e_assessment_functionality_load() {
	
	LEASSESSMENTFUNCTIONALITY();

}
