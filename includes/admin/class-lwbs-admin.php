<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Handle all admin related functions.
 *
 */
class LWBS_Admin {


    /**
     * Constructor.
     *
     */
    public function __construct() {

        // Add to WC Screen IDs to load scripts.
        add_filter('woocommerce_screen_ids', array($this, 'add_screen_ids'));

        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        // Help tab
        add_action('current_screen', array($this, 'add_help_tab'), 90);

    }


    /**
     * Add 'lwbs' to the screen IDs so the WooCommerce scripts are loaded.
     *
     * @param array $screen_ids List of existing screen IDs.
     * @return array             List of modified screen IDs.
     *
     */
    public function add_screen_ids($screen_ids) {

        $screen_ids[] = 'lwbs';

        return $screen_ids;

    }


    /**
     * Enqueue scripts and styles
     *
     */
    public function admin_enqueue_scripts() {

        // Use minified libraries if LWBS_SCRIPT_DEBUG is turned off
        $suffix = (defined('LWBS_SCRIPT_DEBUG') && LWBS_SCRIPT_DEBUG) ? '' : '.min';

        // Style script
        wp_register_style('livemesh-weight-based-shipping', LWBS_PLUGIN_URL . 'assets/css/livemesh-weight-based-shipping' . $suffix . '.css', array(), LWBS_VERSION);

        // Javascript
        wp_register_script('livemesh-weight-based-shipping', LWBS_PLUGIN_URL . 'assets/js/livemesh-weight-based-shipping' . $suffix . '.js', array('jquery', 'jquery-ui-sortable', 'jquery-blockui', 'jquery-tiptip'), LWBS_VERSION, true);

        // Only load scripts on relevant pages
        if (isset($_REQUEST['tab']) && 'shipping' === $_REQUEST['tab']) :

            wp_enqueue_style('livemesh-weight-based-shipping');
            wp_enqueue_script('livemesh-weight-based-shipping');

            wp_enqueue_script('lwc-conditions');

            wp_localize_script('lwc-conditions', 'lwc2', array(
                'action_prefix' => 'lwbs_',
            ));
            wp_localize_script('livemesh-weight-based-shipping', 'lwbs', array(
                'nonce' => wp_create_nonce('livemesh-shipping'),
                'rate_id' => get_the_ID(),
            ));

            wp_dequeue_script('autosave');

        endif;

    }

    /**
     * Add help tab on Livemesh Weight Based Shipping related pages.
     *
     */
    public function add_help_tab() {
        $screen = get_current_screen();

        if (!$screen || !is_lwbs_page()) {
            return;
        }

        $screen->add_help_tab(array(
            'id' => 'livemesh_weight_based_shipping_help',
            'title' => __('Livemesh Weight Based Shipping', 'livemesh-wb-shipping'),
            'content' => '<h2>' . __('Livemesh Weight Based Shipping', 'livemesh-wb-shipping') . '</h2>' .
                '<p>
						<strong>' . __('Where do I configure my Livemesh Weight Based Shipping rates?', 'livemesh-wb-shipping') . '</strong><br/>' .
                __('Livemesh Weight Based Shipping rates can be setup within the shipping zones', 'livemesh-wb-shipping') .
                '</p>
					<p>
						<a href="https://livemeshwp.com/weight-based-shipping/doc/faq/" target="_blank" class="button">' . __('Frequently Asked Questions', 'livemesh-wb-shipping') . '</a>
						<a href="https://livemeshwp.com/weight-based-shipping/doc/" class="button button-primary" target="_blank">' . __('Online documentation', 'livemesh-wb-shipping') . '</a>
						<a href="https://livemeshwp.com/weight-based-shipping/support/" target="_blank" class="button">' . __('Contact support', 'livemesh-wb-shipping') . '</a>
					</p>',
        ));

        $screen->set_help_sidebar(
            '<p><strong>' . __('More links', 'livemesh-wb-shipping') . '</strong></p>' .
            '<p><a href="https://livemeshwp.com/weight-based-shipping/pricing" target="_blank">' . __('Purchase/renew license', 'livemesh-wb-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/weight-based-shipping/doc/" target="_blank">' . __('Documentation', 'livemesh-wb-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/weight-based-shipping/support/" target="_blank">' . __('Support', 'livemesh-wb-shipping') . '</a></p>' .
            '<p><a href="https://livemeshwp.com/" target="_blank">' . __('More plugins by the Author', 'livemesh-wb-shipping') . '</a></p>'
        );

        // Make sure to not show Woo help to not confuse users
        $screen->remove_help_tab('woocommerce_support_tab');
        $screen->remove_help_tab('woocommerce_bugs_tab');
        $screen->remove_help_tab('woocommerce_education_tab');
        $screen->remove_help_tab('woocommerce_onboard_tab');
    }
}
