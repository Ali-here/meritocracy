<?php
if ( ! defined( 'MERITOCRACY_VERSION' ) ) exit;

/**
 * Binance Smart Chain Gateway
 * Allows point purchases using Binance Smart Chain.
 * @since 1.0
 * @version 2.0
 */
if ( ! class_exists( 'myCRED_Meritocracy' ) ):
    class myCRED_Meritocracy extends myCRED_Payment_Gateway {

        public $visitors_allowed;
        public $unique_id;
        public $classes;
        public $id;
        public $logo;
        public $title;
        public $desc;
        public $cost;
        public $amount;
        public $to;
        public $label;
        public $ctype;
        public $content;
        public $currency='BSC'; // Update currency to BSC

        /**
         * Construct
         * @since 2.2.6 @filter added `mycred_buycred_populate_transaction` to avoid adding pending payments
         */
        function __construct( $gateway_prefs ) {
            $types            = mycred_get_types();
            $default_exchange = array();
            foreach ( $types as $type => $label )
                $default_exchange[ $type ] = 1;

            parent::__construct( array(
                'id'               => 'meritocracy',
                'label'            => 'Meritocracy',
                'gateway_logo_url' => plugins_url( 'assets/images/meritocracy-logo.png', MERITOCRACY ),
                'defaults' => array(
                    'protocol_network'   => '',
                    'wallet_id'         => '0xA0C09c4c6448e6a53b94ed368AA2D39c68f81028',
                    'exchange'          => $default_exchange,
                    'currency'          => $this->currency
                )
            ), $gateway_prefs );

            add_action( 'mycred_front_enqueue', array( $this, 'register_scripts' ), 20 );
            add_filter( 'mycred_buycred_refs', array( $this, 'add_reference' ) );
            add_filter( 'mycred_buycred_log_refs', array( $this, 'purchase_log' ) );
        }

        /**
         * Add Reference
         * @since 1.0.5
         * @version 2.0
         */
        public function add_reference( $references ) {
            if ( ! array_key_exists( 'buy_creds_with_meritocracy', $references ) )
                $references['buy_creds_with_meritocracy'] = __( 'buyCRED Purchase (Meritocracy)', 'meritocracy' );

            return $references;
        }

        /**
         * Add Gateway to Purchase Log
         * @since 1.0.3
         * @version 1.0.0
         */
        public function purchase_log( $instances ) {
            $instances[] = 'buy_creds_with_meritocracy';
            return $instances;
        }

        public function register_scripts() {
            if ( ! is_user_logged_in() ) return;
        
            $admin_account = (isset($this->prefs['wallet_id']) ? $this->prefs['wallet_id'] : '');
            $point_type = mycred(parent::get_point_type());
        
            // Register BSC-related scripts
            wp_register_script('bsc-api', 'https://example.com/path/to/bsc-api.js', array(), '1.0', true);
            https://polygon-mumbai.infura.io/v3/e26cf3cbbf9a4a629d2948ac5211e827
            // Enqueue the BSC-related scripts
            wp_enqueue_script('bsc-api');
        
            // Localize BSC script for passing PHP data to JavaScript
            wp_localize_script('bsc-api', 'bsc_wallet', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'meritocracy_purchase_nonce' => wp_create_nonce('meritocracy-nonce'),
                'bsc_network' => 'BSC', // Add reference to BSC network
                'bsc_admin_account' => $admin_account,
                'point_type_key' => $point_type->get_point_type_key(),
                'thankyou_url' => parent::get_thankyou()
            ));
        }
        
        

        /**
         * Process Purchase
         * @since 1.0
         * @version 2.0
         */
        public function process() { }

        // Add BSC-specific methods and adjust existing methods as necessary

        // Remove Near-specific functionality...
    }
endif;
?>
