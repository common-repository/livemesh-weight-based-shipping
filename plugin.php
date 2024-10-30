<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('Livemesh_Weight_Based_Shipping')) :

    /**
     * Main Livemesh_Weight_Based_Shipping Class
     *
     */
    final class Livemesh_Weight_Based_Shipping {

        /** Singleton *************************************************************/

        private static $instance;

        /**
         * @var object|Livemesh_Weight_Based_Shipping_Method
         */
        public $shipping_method;

        /**
         * @var object|LWBS_Ajax_Helper
         */
        public $ajax_helper;

        /**
         * @var object|LWBS_Admin
         */
        public $admin;

        /**
         * @var object|LWBS_Table_Rates_Helper
         */
        public $table_rates_helper;

        /**
         * Livemesh_Weight_Based_Shipping Singleton Instance
         *
         * Allow only one instance of the class to be created.
         */
        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof Livemesh_Weight_Based_Shipping)) {

                self::$instance = new Livemesh_Weight_Based_Shipping;

                if (!self::$instance->is_woocommerce_active())
                    return;

                self::$instance->setup_debug_constants();

                add_action('plugins_loaded', array(self::$instance, 'load_plugin_textdomain'));

                self::$instance->includes();

                self::$instance->hooks();

                self::$instance->init();

            }
            return self::$instance;
        }

        /**
         * Throw error if someone tries to clone the object since this is a singleton class
         *
         */
        public function __clone() {
            // Cloning instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'livemesh-wb-shipping'), '1.4');
        }

        /**
         * Disable deserialization
         */
        public function __wakeup() {

            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'livemesh-wb-shipping'), '1.4');
        }

        private function is_woocommerce_active(): bool {

            if (!function_exists('is_plugin_active'))
                require_once(ABSPATH . '/wp-admin/includes/plugin.php');

            if (!is_plugin_active('woocommerce/woocommerce.php') && !function_exists('WC')) {

                add_action('admin_notices', array($this, 'woocommerce_required_notice'));

                return false;
            }

            return true;
        }

        public function woocommerce_required_notice() {

            $class = 'notice notice-error';

            $message = esc_html__('WooCommerce is required for Livemesh Weight Based Shipping plugin to work. Please install or activate WooCommerce plugin', 'livemesh-wb-shipping');

            printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);

        }

        /**
         * Setup debug constants required for the plugin
         */
        private function setup_debug_constants() {

            $enable_debug = true;

            $settings = get_option('lwbs_settings');

            if ($settings && isset($settings['lwbs_enable_debug']) && $settings['lwbs_enable_debug'] == "true")
                $enable_debug = true;

            // Enable script debugging
            if (!defined('LWBS_SCRIPT_DEBUG')) {
                define('LWBS_SCRIPT_DEBUG', $enable_debug);
            }

            // Minified JS file name suffix
            if (!defined('LWBS_JS_SUFFIX')) {
                if ($enable_debug)
                    define('LWBS_JS_SUFFIX', '');
                else
                    define('LWBS_JS_SUFFIX', '.min');
            }
        }

        /**
         * Include required files
         *
         */
        private function includes() {

            require_once LWBS_PLUGIN_DIR . '/includes/core-functions.php';

            require_once LWBS_PLUGIN_DIR . '/includes/method-conditions/functions.php';

            require_once LWBS_PLUGIN_DIR . '/includes/class-lwbs-table-rates-helper.php';

        }

        /**
         * Load Plugin Text Domain
         *
         * Looks for the plugin translation files in certain directories and loads
         * them to allow the plugin to be localised
         */
        public function load_plugin_textdomain() {

            $lang_dir = apply_filters('lwbs_lang_dir', trailingslashit(LWBS_PLUGIN_DIR . 'languages'));

            // Traditional WordPress plugin locale filter
            $locale = apply_filters('plugin_locale', get_locale(), 'livemesh-wb-shipping');
            $mofile = sprintf('%1$s-%2$s.mo', 'livemesh-wb-shipping', $locale);

            // Setup paths to current locale file
            $mofile_local = $lang_dir . $mofile;

            if (file_exists($mofile_local)) {
                // Look in the /wp-content/plugins/livemesh-weight-based-shipping/languages/ folder
                load_textdomain('livemesh-wb-shipping', $mofile_local);
            }
            else {
                // Load the default language files
                load_plugin_textdomain('livemesh-wb-shipping', false, $lang_dir);
            }

            return false;
        }

        /**
         * Setup the default hooks and actions
         */
        private function hooks() {

            // Initialize shipping method class
            add_action('woocommerce_shipping_init', array($this, 'init_shipping_method'));

            // Add shipping method
            add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));

        }

        private function init() {

            $this->table_rates_helper = new LWBS_Table_Rates_Helper();

            if (defined('DOING_AJAX') && DOING_AJAX) {
                require_once LWBS_PLUGIN_DIR . '/includes/class-lwbs-ajax-helper.php';
                $this->ajax_helper = new LWBS_Ajax_Helper();
            }

            if (is_admin()) {
                require_once LWBS_PLUGIN_DIR . '/includes/admin/class-lwbs-condition.php';
                require_once LWBS_PLUGIN_DIR . '/includes/admin/class-lwbs-admin.php';
                $this->admin = new LWBS_Admin();
            }
        }


        /**
         * Initialize shipping method.
         *
         */
        public function init_shipping_method() {

            require_once LWBS_PLUGIN_DIR . '/includes/class-lwbs-shipping-method.php';

            $this->shipping_method = new Livemesh_Weight_Based_Shipping_Method();

        }

        /**
         * Add shipping method to the available shipping methods in WooCommerce
         *
         */
        public function add_shipping_method($methods) {

            if (class_exists('Livemesh_Weight_Based_Shipping_Method')) {
                $methods['livemesh_weight_based_shipping'] = 'Livemesh_Weight_Based_Shipping_Method';
            }
            return $methods;
        }
    }

endif; // End if class_exists check


/**
 * The main function responsible for returning the one true Livemesh_Weight_Based_Shipping
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 */
function LWBS() {
    return Livemesh_Weight_Based_Shipping::instance();
}

// Get LWBS Running
LWBS();