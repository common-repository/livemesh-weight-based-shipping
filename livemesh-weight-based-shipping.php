<?php

/**
 * Plugin Name: Weight Based Shipping For WooCommerce
 * Plugin URI: https://wordpress.org/plugins/livemesh-weight-based-shipping/
 * Description: Discover the most intuitive yet flexible way to set conditional weight based shipping rates for WooCommerce.
 * Author: Livemesh
 * Author URI: https://livemeshwp.com/woocommerce-weight-based-shipping/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 1.4
 * WC requires at least: 5.2
 * WC tested up to: 6.9
 * Text Domain: livemesh-wb-shipping
 * Domain Path: languages
 *
 * Weight Based Shipping For WooCommerce by Livemesh is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Weight Based Shipping For WooCommerce by Livemesh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Weight Based Shipping For WooCommerce by Livemesh. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'lwbs_fs' ) ) {
    lwbs_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    // Ensure the free version is deactivated if premium is running
    
    if ( !function_exists( 'lwbs_fs' ) ) {
        // Plugin version
        define( 'LWBS_VERSION', '1.4' );
        // Plugin Root File
        define( 'LWBS_PLUGIN_FILE', __FILE__ );
        // Plugin Folder Path
        define( 'LWBS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'LWBS_PLUGIN_SLUG', dirname( plugin_basename( __FILE__ ) ) );
        // Plugin Folder URL
        define( 'LWBS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
        // Plugin Help Page URL
        define( 'LWBS_PLUGIN_HELP_URL', admin_url() . 'admin.php?page=livemesh_wc_shipping_documentation' );
        // Create a helper function for easy SDK access.
        function lwbs_fs()
        {
            global  $lwbs_fs ;
            
            if ( !isset( $lwbs_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $lwbs_fs = fs_dynamic_init( array(
                    'id'             => '10000',
                    'slug'           => 'livemesh-weight-based-shipping',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_3a6913349e81c94ccf98c107e68f3',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'first-path' => 'plugins.php',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $lwbs_fs;
        }
        
        // Init Freemius.
        lwbs_fs();
        // Signal that SDK was initiated.
        do_action( 'lwbs_fs_loaded' );
    }
    
    require_once dirname( __FILE__ ) . '/plugin.php';
}
