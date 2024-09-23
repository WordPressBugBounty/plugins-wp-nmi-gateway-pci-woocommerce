<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_NMI class.
 *
 * @extends WC_Payment_Gateway_CC
 */
class WC_Gateway_NMI extends WC_Payment_Gateway_CC {

	public $testmode;
	public $api_keys;
	public $capture;
	public $private_key;
	public $public_key;
	public $username;
	public $password;
	public $logging;
	public $debugging;
	public $line_items;
	public $allowed_card_types;
	public $customer_receipt;

    const NMI_REQUEST_URL_LOGIN = 'https://secure.networkmerchants.com/api/transact.php';
    const NMI_REQUEST_URL_API_KEYS = 'https://secure.nmi.com/api/transact.php';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                   = 'nmi';
		$this->method_title         = __( 'NMI', 'wc-nmi' );
		$this->method_description 	= __( 'NMI works by adding credit card fields on the checkout and then sending the details to the gateway for processing the transactions.', 'wc-nmi' ) . '<h3>' . __( 'Upgrade to Enterprise', 'wc-nmi' ) . '</h3>' . sprintf( __( 'Enterprise version is a full blown plugin that provides full support for processing subscriptions, pre-orders, payments via saved cards or eCheck accounts and refunds directly from your website. The credit card or eCheck account information is saved in your gateway merchant account and is reused to charge future orders, recurring payments or pre-orders at a later time. It also has an option to enable 3D Secure 2 card verification and make your site Strong Customer Authentication (SCA) compliant. <br/><br/><a href="%s" target="_blank">Click here</a> to upgrade to Enterprise version or to know more about it.', 'wc-nmi' ), 'https://pledgedplugins.com/products/nmi-payment-gateway-woocommerce/' );
		$this->has_fields           = true;
		$this->supports             = array( 'products', 'refunds' );

		// Load the form fields
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values.
		$this->title       		  	= $this->get_option( 'title' );
		$this->description 		  	= $this->get_option( 'description' );
		$this->enabled     		  	= $this->get_option( 'enabled' );
		$this->testmode    		  	= $this->get_option( 'testmode' ) === 'yes';
		$this->api_keys    		  	= $this->get_option( 'api_keys' ) === 'yes';
		$this->capture     		  	= $this->get_option( 'capture', 'yes' ) === 'yes';
		$this->private_key	   		= $this->get_option( 'private_key' );
		$this->public_key	   		= $this->get_option( 'public_key' );
		$this->username	   		  	= $this->get_option( 'username' );
		$this->password	   		  	= $this->get_option( 'password' );
		$this->logging     		  	= $this->get_option( 'logging' ) === 'yes';
		$this->debugging   		  	= $this->get_option( 'debugging' ) === 'yes';
		$this->line_items  		  	= $this->get_option( 'line_items' ) === 'yes';
		$this->allowed_card_types 	= $this->get_option( 'allowed_card_types', array() );
		$this->customer_receipt   	= $this->get_option( 'customer_receipt' ) === 'yes';

		if ( $this->testmode ) {
			$this->description .= ' ' . sprintf( __( '<br /><br /><strong>TEST MODE ENABLED</strong><br /> In test mode, you can use the card number 4111111111111111 with any CVC and a valid expiration date or check the documentation "<a href="%s">NMI Direct Post API</a>" for more card numbers.', 'wc-nmi' ), 'https://secure.nmi.com/merchants/resources/integration/download.php?document=directpost' );
			$this->description  = trim( $this->description );
		}

		// Hooks
        add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * get_icon function.
	 *
	 * @access public
	 * @return string
	 */
	public function get_icon() {
		$icon = '';
		if( in_array( 'visa', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/visa.svg' ) . '" alt="Visa" width="32" />';
		}
		if( in_array( 'mastercard', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard.svg' ) . '" alt="Mastercard" width="32" />';
		}
		if( in_array( 'amex', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/amex.svg' ) . '" alt="Amex" width="32" />';
		}
		if( in_array( 'discover', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/discover.svg' ) . '" alt="Discover" width="32" />';
		}
		if( in_array( 'diners-club', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/diners.svg' ) . '" alt="Diners Club" width="32" />';
		}
		if( in_array( 'jcb', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb.svg' ) . '" alt="JCB" width="32" />';
		}
		if( in_array( 'maestro', $this->allowed_card_types ) ) {
			$icon .= '<img style="margin-left: 0.3em" src="' . WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/maestro.svg' ) . '" alt="Maestro" width="32" />';
		}
		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	/**
	 * Check if SSL is enabled and notify the user
	 */
	public function admin_notices() {
		if ( $this->enabled == 'no' ) {
			return;
		}

		// Check required fields
		if ( ! $this->api_keys && ! $this->username ) {
			echo '<div class="error"><p>' . sprintf( __( 'NMI error: Please enter your Username <a href="%s">here</a>', 'wc-nmi' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=nmi' ) ) . '</p></div>';
			return;

		} elseif ( ! $this->api_keys && ! $this->password ) {
			echo '<div class="error"><p>' . sprintf( __( 'NMI error: Please enter your Password <a href="%s">here</a>', 'wc-nmi' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=nmi' ) ) . '</p></div>';
			return;
		}

        if ( $this->api_keys && ! $this->private_key ) {
			echo '<div class="error"><p>' . sprintf( __( 'NMI error: Please enter your Private Key <a href="%s">here</a>', 'wc-nmi' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=nmi' ) ) . '</p></div>';
			return;

		}

		// Simple check for duplicate keys
		if ( ! $this->api_keys && $this->username == $this->password ) {
			echo '<div class="error"><p>' . sprintf( __( 'NMI error: Your Username and Password match. Please check and re-enter.', 'wc-nmi' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=nmi' ) ) . '</p></div>';
			return;
		}

		// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
		if ( ! wc_checkout_is_https() ) {
			echo '<div class="notice notice-warning"><p>' . sprintf( __( 'NMI is enabled, but a SSL certificate is not detected. Your checkout may not be secure! Please ensure your server has a valid <a href="%1$s" target="_blank">SSL certificate</a>', 'wc-nmi' ), 'https://en.wikipedia.org/wiki/Transport_Layer_Security' ) . '</p></div>';
 		}

	}

    public function admin_options() { ?>
		<script>
            //alert(123);
            jQuery( function( $ ) {
                'use strict';

                /**
                 * Object to handle NMI admin functions.
                 */
                let wc_nmi_admin = {
                    isAPIKey: function() {
                        return $( '#woocommerce_nmi_api_keys' ).is( ':checked' );
                    },

                    /**
                     * Initialize.
                     */
                    init: function() {
                        $( document.body ).on( 'change', '#woocommerce_nmi_api_keys', function() {
                           let field_username = $( '#woocommerce_nmi_username' ).parents( 'tr' ).eq( 0 ),
					           field_password = $( '#woocommerce_nmi_password' ).parents( 'tr' ).eq( 0 ),
					           field_private_key = $( '#woocommerce_nmi_private_key' ).parents( 'tr' ).eq( 0 ),
					           field_public_key = $( '#woocommerce_nmi_public_key' ).parents( 'tr' ).eq( 0 );

                            if ( $( this ).is( ':checked' ) ) {
                                field_private_key.show();
                                field_public_key.show();
                                field_username.hide();
                                field_password.hide();
                            } else {
                                field_private_key.hide();
                                field_public_key.hide();
                                field_username.show();
                                field_password.show();
                            }
                        } );

                        $( '#woocommerce_nmi_api_keys' ).change();
                    }
                };

                wc_nmi_admin.init();
            } );
        </script>
		<?php parent::admin_options();
	}

	/**
	 * Check if this gateway is enabled
	 */
	public function is_available() {
		if ( $this->enabled == "yes" ) {
			if ( is_add_payment_method_page() ) {
				return false;
			}
			// Required fields check
			if ( ! $this->api_keys && ( ! $this->username || ! $this->password ) ) {
				return false;
			}

            if ( $this->api_keys && ! $this->private_key ) {
				return false;
			}
			return true;
		}
		return parent::is_available();
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters( 'wc_nmi_settings', array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable', 'wc-nmi' ),
				'label'       => __( 'Enable NMI', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => __( 'Title', 'wc-nmi' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc-nmi' ),
				'default'     => __( 'Credit card (NMI)', 'wc-nmi' )
			),
			'description' => array(
				'title'       => __( 'Description', 'wc-nmi' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc-nmi' ),
				'default'     => __( 'Pay with your credit card via NMI.', 'wc-nmi' )
			),
			'testmode' => array(
				'title'       => __( 'Test mode', 'wc-nmi' ),
				'label'       => __( 'Enable Test Mode', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( 'Place the payment gateway in test mode. This will display test information on the checkout page.', 'wc-nmi' ),
				'default'     => 'yes'
			),
			'api_keys' => array(
				'title'       => __( 'API Keys', 'wc-nmi' ),
				'label'       => __( 'Enable Authentication via API keys instead of login credentials.', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( 'RECOMMENDED! This ensures you are using the most updated API method. If you disable this, the plugin will process via a legacy method and will need you to enter your login username and password.', 'wc-nmi' ),
				'default'     => 'yes'
			),
            'private_key' => array(
				'title'       => __( 'Private Key', 'wc-nmi' ),
				'type'        => 'password',
				'description' => __( 'Used for authenticating transactions. Make sure the private key you enter here has "API" permission enabled.', 'wc-nmi' ),
				'default'     => ''
			),
            'public_key' => array(
				'title'       => __( 'Public Tokenization Key', 'wc-nmi' ),
				'type'        => 'text',
				'description' => __( 'Used for Collect.js tokenization for PCI compliance. Leave it empty ONLY if you are facing Javascript issues at checkout and the plugin will default to Direct Post method.', 'wc-nmi' ),
				'default'     => ''
			),
			'username' => array(
				'title'       => __( 'Gateway Username', 'wc-nmi' ),
				'type'        => 'text',
				'description' => __( 'Legacy API method. Enter your NMI account username.', 'wc-nmi' ),
				'default'     => ''
			),
			'password' => array(
				'title'       => __( 'Gateway Password', 'wc-nmi' ),
				'type'        => 'password',
				'description' => __( 'Legacy API method. Enter your NMI account password.', 'wc-nmi' ),
				'default'     => ''
			),
			'capture' => array(
				'title'       => __( 'Capture', 'wc-nmi' ),
				'label'       => __( 'Capture charge immediately', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( 'Whether or not to immediately capture the charge. When unchecked, the charge issues an authorization and will need to be captured later.', 'wc-nmi' ),
				'default'     => 'yes'
			),
			'logging' => array(
				'title'       => __( 'Logging', 'wc-nmi' ),
				'label'       => __( 'Log debug messages', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'Save debug messages to the WooCommerce System Status log file <code>%s</code>.', 'wc-nmi' ), WC_Log_Handler_File::get_log_file_path( 'wc-nmi' ) ),
				'default'     => 'no'
			),
			'debugging' => array(
				'title'       => __( 'Gateway Debug', 'wc-nmi' ),
				'label'       => __( 'Log gateway requests and response to the WooCommerce System Status log.', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( '<strong>CAUTION! Enabling this option will write gateway requests including card numbers and CVV to the logs.</strong> Do not turn this on unless you have a problem processing credit cards. You must only ever enable it temporarily for troubleshooting or to send requested information to the plugin author. It must be disabled straight away after the issues are resolved and the plugin logs should be deleted.', 'wc-nmi' ) . ' ' . sprintf( __( '<a href="%s">Click here</a> to check and delete the full log file.', 'wc-nmi' ), admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . WC_Log_Handler_File::get_log_file_name( 'woocommerce-gateway-nmi' ) ) ),
				'default'     => 'no'
			),
			'line_items' => array(
				'title'       => __( 'Line Items', 'wc-nmi' ),
				'label'       => __( 'Enable Line Items', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( 'Add line item data to description sent to the gateway (eg. Item x qty).', 'wc-nmi' ),
				'default'     => 'no'
			),
			'allowed_card_types' => array(
				'title'       => __( 'Allowed Card types', 'wc-nmi' ),
				'class'       => 'wc-enhanced-select',
				'type'        => 'multiselect',
				'description' => __( 'Select the card types you want to allow payments from.', 'wc-nmi' ),
				'default'     => array( 'visa','mastercard','discover','amex' ),
				'options'	  => array(
					'visa' 			=> __( 'Visa', 'wc-nmi' ),
					'mastercard' 	=> __( 'MasterCard', 'wc-nmi' ),
					'discover' 		=> __( 'Discover', 'wc-nmi' ),
					'amex' 			=> __( 'American Express', 'wc-nmi' ),
					'diners-club' 	=> __( 'Diners Club', 'wc-nmi' ),
					'jcb' 			=> __( 'JCB', 'wc-nmi' ),
					'maestro' 		=> __( 'Maestro', 'wc-nmi' ),
				),
			),
			'customer_receipt' => array(
				'title'       => __( 'Receipt', 'wc-nmi' ),
				'label'       => __( 'Send Gateway Receipt', 'wc-nmi' ),
				'type'        => 'checkbox',
				'description' => __( 'If enabled, the customer will be sent an email receipt from NMI.', 'wc-nmi' ),
				'default'     => 'no'
			),
		) );
	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		echo '<div class="nmi_new_card" id="nmi-payment-data">';

		if ( $this->description ) {
			echo apply_filters( 'wc_nmi_description', wpautop( wp_kses_post( $this->description ) ) );
		}

		if( $this->api_keys && $this->public_key ) {
            $this->collect_js_form();
        } else {
            $this->form();
        }

        echo '</div>';
	}

    public function collect_js_form() {
		?>
		<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

			<!-- Used to display form errors -->
            <div class="nmi-source-errors" role="alert"></div>

            <div class="form-row form-row-wide">
                <label for="nmi-card-number-element"><?php esc_html_e( 'Card Number', 'wc-nmi' ); ?> <span class="required">*</span></label>
                <div class="nmi-card-group">
                    <div id="nmi-card-number-element" class="wc-nmi-elements-field">
                    <!-- a NMI Element will be inserted here. -->
                    </div>

                    <i class="nmi-credit-card-brand nmi-card-brand" alt="Credit Card"></i>
                </div>
            </div>

            <div class="form-row form-row-first">
                <label for="nmi-card-expiry-element"><?php esc_html_e( 'Expiry Date', 'wc-nmi' ); ?> <span class="required">*</span></label>

                <div id="nmi-card-expiry-element" class="wc-nmi-elements-field">
                <!-- a NMI Element will be inserted here. -->
                </div>
            </div>

            <div class="form-row form-row-last">
                <label for="nmi-card-cvc-element"><?php esc_html_e( 'Card Code (CVC)', 'wc-nmi' ); ?> <span class="required">*</span></label>
                <div id="nmi-card-cvc-element" class="wc-nmi-elements-field">
                <!-- a NMI Element will be inserted here. -->
                </div>
            </div>
            <div class="clear"></div>

			<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
			<div class="clear"></div>
		</fieldset>
		<?php
	}

    public function payment_scripts() {
		if ( ! $this->api_keys || ! $this->public_key || ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && ! is_add_payment_method_page() ) ) {
			return;
		}

        add_filter( 'script_loader_tag', array( $this, 'add_public_key_to_js' ), 10, 2 );

		wp_enqueue_script( 'nmi-collect-js', 'https://secure.nmi.com/token/Collect.js', '', null, true );
		wp_enqueue_script( 'woocommerce_nmi', plugins_url( 'assets/js/nmi.js', WC_NMI_PCI_MAIN_FILE ), array( 'jquery-payment', 'nmi-collect-js' ), WC_NMI_PCI_VERSION, true );

		wp_localize_script( 'woocommerce_nmi', 'wc_nmi_params', apply_filters( 'wc_nmi_params', $this->javascript_params() ) );
	}

	public function javascript_params() {
		$nmi_params = array(
			'public_key'           	=> $this->public_key,
			'allowed_card_types'   	=> $this->allowed_card_types,
			'i18n_terms'           	=> __( 'Please accept the terms and conditions first', 'wc-nmi' ),
			'i18n_required_fields'	=> __( 'Please fill in required checkout fields first', 'wc-nmi' ),
			'no_card_number_error'  => __( 'Enter a card number.', 'wc-nmi' ),
			'no_card_expiry_error'  => __( 'Enter an expiry date.', 'wc-nmi' ),
			'no_cvv_error'          => __( 'CVC code is required.', 'wc-nmi' ),
			'card_number_error' 	=> __( 'Invalid card number.', 'wc-nmi' ),
			'card_expiry_error' 	=> __( 'Invalid card expiry date.', 'wc-nmi' ),
			'card_cvc_error' 		=> __( 'Invalid card CVC.', 'wc-nmi' ),
			'placeholder_cvc'	 	=> __( 'CVC', 'woocommerce' ),
			'placeholder_expiry' 	=> __( 'MM / YY', 'woocommerce' ),
			'card_disallowed_error' => __( 'Card Type Not Accepted.', 'wc-nmi' ),
			'error_ref' 			=> __( '(Ref: [ref])', 'wc-nmi' ),
			'timeout_error' 		=> __( 'The tokenization did not respond in the expected timeframe. Please make sure the fields are correctly filled in and submit the form again.', 'wc-nmi' ),
			'collect_js_error' 		=> __( 'Collect.js could not be loaded. Please try a different payment method or contact the administrator.', 'wc-nmi' ),
		);
		$nmi_params['is_checkout'] = ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no'; // wpcs: csrf ok.

		return $nmi_params;
	}

    public function add_public_key_to_js( $tag, $handle ) {
       if ( 'nmi-collect-js' !== $handle ) return $tag;
       return str_replace( ' src', ' data-tokenization-key="' . $this->public_key . '" src', $tag );
    }

    public function get_nmi_js_response() {
        if( !isset( $_POST['nmi_js_response'] ) ) {
            return false;
        }
		$response = json_decode( stripslashes( $_POST['nmi_js_response'] ), 1 );
		return $response;
	}

	/**
	 * Process the payment
	 */
	public function process_payment( $order_id, $retry = true ) {

        $order		= wc_get_order( $order_id );

		$this->log( "Info: Beginning processing payment for order $order_id for the amount of {$order->get_total()}" );

		$response = false;

		// Use NMI CURL API for payment
		try {
			$post_data = array();

			if( !$this->get_nmi_js_response() ) {

				// Check for CC details filled or not
				if( empty( $_POST['nmi-card-number'] ) || empty( $_POST['nmi-card-expiry'] ) || empty( $_POST['nmi-card-cvc'] ) ) {
					throw new Exception( __( 'Credit card details cannot be left incomplete.', 'wc-nmi' ) );
				}

				// Check for card type supported or not
				if( ! in_array( $this->get_card_type( wc_clean( $_POST['nmi-card-number'] ), 'pattern', 'name' ), $this->allowed_card_types ) ) {
					$this->log( sprintf( __( 'Card type being used is not one of supported types in plugin settings: %s', 'wc-nmi' ), $this->get_card_type( wc_clean( $_POST['nmi-card-number'] ) ) ) );
					throw new Exception( __( 'Card Type Not Accepted', 'wc-nmi' ) );
				}
			}

			if( $js_response = $this->get_nmi_js_response() ) {
				$post_data['payment_token'] = $js_response['token'];
			} else {
				$expiry = explode( ' / ', wc_clean( $_POST['nmi-card-expiry'] ) );
				$expiry[1] = substr( $expiry[1], -2 );
				$post_data['ccnumber']	= wc_clean( $_POST['nmi-card-number'] );
				$post_data['ccexp']		= $expiry[0] . $expiry[1];
				$post_data['cvv']		= wc_clean( $_POST['nmi-card-cvc'] );
			}

			$description = sprintf( __( '%s - Order %s', 'wc-nmi' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number() );

			if( $this->line_items ) {
				$description .= ' (' . $this->get_line_items( $order ) . ')';
			}

			$payment_args = array(
				'orderid'	 		=> $order->get_order_number(),
				'order_description'	=> $description,
				'amount'			=> $order->get_total(),
				'transactionid'		=> $order->get_transaction_id(),
				'type'				=> $this->capture ? 'sale' : 'auth',
				'first_name'		=> $order->get_billing_first_name(),
				'last_name'			=> $order->get_billing_last_name(),
				'address1'			=> $order->get_billing_address_1(),
				'address2'			=> $order->get_billing_address_2(),
				'city'				=> $order->get_billing_city(),
				'state'				=> $order->get_billing_state(),
				'country'			=> $order->get_billing_country(),
				'zip'				=> $order->get_billing_postcode(),
				'email' 			=> $order->get_billing_email(),
				'phone'				=> $order->get_billing_phone(),
				'company'			=> $order->get_billing_company(),
				'currency'			=> $this->get_payment_currency( $order_id ),
			);

			$payment_args = array_merge( $payment_args, $post_data );

			$payment_args = apply_filters( 'wc_nmi_request_args', $payment_args, $order );

			$response = $this->nmi_request( $payment_args );

			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			}

			// Store charge ID
			$order->update_meta_data( '_nmi_charge_id', $response['transactionid'] );
			$order->update_meta_data( '_nmi_authorization_code', $response['authcode'] );

			if ( $response['response'] == 1 ) {
				$order->set_transaction_id( $response['transactionid'] );

				if( $payment_args['type'] == 'sale' ) {

					// Store captured value
					$order->update_meta_data( '_nmi_charge_captured', 'yes' );
					$order->update_meta_data( 'NMI Payment ID', $response['transactionid'] );

					// Payment complete
					$order->payment_complete( $response['transactionid'] );

					// Add order note
					$complete_message = sprintf( __( 'NMI charge complete (Charge ID: %s)', 'wc-nmi' ), $response['transactionid'] );
					$order->add_order_note( $complete_message );
					$this->log( "Success: $complete_message" );

				} else {

					// Store captured value
					$order->update_meta_data( '_nmi_charge_captured', 'no' );

					if ( $order->has_status( array( 'pending', 'failed' ) ) ) {
						wc_reduce_stock_levels( $order_id );
					}

					// Mark as on-hold
					$authorized_message = sprintf( __( 'NMI charge authorized (Charge ID: %s). Process order to take payment, or cancel to remove the pre-authorization.', 'wc-nmi' ), $response['transactionid'] );
					$order->update_status( 'on-hold', $authorized_message . "\n" );
					$this->log( "Success: $authorized_message" );

				}

				$order->save();

			}

			// Remove cart
			WC()->cart->empty_cart();

			do_action( 'wc_gateway_nmi_process_payment', $response, $order );

			// Return thank you page redirect
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);

		} catch ( Exception $e ) {
			wc_add_notice( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ), 'error' );
			$this->log( sprintf( __( 'Gateway Error: %s', 'wc-nmi' ), $e->getMessage() ) );

			if( is_wp_error( $response ) && $response = $response->get_error_data() ) {
				$order->add_order_note( sprintf( __( 'NMI failure reason: %s', 'wc-nmi' ), $response['response_code'] . ' - ' . $response['responsetext'] ) );
            }

			do_action( 'wc_gateway_nmi_process_payment_error', $e, $order );

			$order->update_status( 'failed' );

			return array(
				'result'   => 'fail',
				'redirect' => ''
			);

		}
	}

	function nmi_request( $args ) {

		$gateway_debug = ( $this->logging && $this->debugging );

        $request_url = $this->api_keys ? self::NMI_REQUEST_URL_API_KEYS : self::NMI_REQUEST_URL_LOGIN;
		$request_url = apply_filters( 'wc_nmi_request_url', $request_url );

        $auth_params = $this->api_keys ? array( 'security_key' => $this->private_key ) : array(
            'username' => $this->username,
            'password' => $this->password,
        );

		$args['customer_receipt'] = isset( $args['customer_receipt'] ) ? $args['customer_receipt'] : $this->customer_receipt;
		$args['ipaddress'] = isset( $args['ipaddress'] ) ? $args['ipaddress'] : WC_Geolocation::get_ip_address();

        if( isset( $args['transactionid'] ) && empty( $args['transactionid'] ) ) {
            unset( $args['transactionid'] );
        }

        if( isset( $args['currency'] ) && empty( $args['currency'] ) ) {
            $args['currency'] = get_woocommerce_currency();
        }

        if( isset( $args['state'] ) && empty( $args['state'] ) && ! in_array( $args['type'], array( 'capture', 'void', 'refund' ) ) ) {
            $args['state'] = 'NA';
        }

        $args = array_merge( $args, $auth_params );

        // Setting custom timeout for the HTTP request
		add_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ), 9999 );

        $headers = array();
        $response = wp_remote_post( $request_url, array( 'body' => $args , 'headers' => $headers ) );

		$result = is_wp_error( $response ) ? $response : wp_remote_retrieve_body( $response );

        // Saving to Log here
		if( $gateway_debug ) {
			$message = sprintf( "\nPosting to: \n%s\nRequest: \n%sResponse: \n%s", $request_url, print_r( $args, 1 ), print_r( $result, 1 ) );
			WC_NMI_Logger::log( $message );
		}

		remove_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ), 9999 );

		if ( is_wp_error( $result ) ) {
			return $result;
		} elseif( empty( $result ) ) {
			$error_message = __( 'There was an error with the gateway response.', 'wc-nmi' );
			return new WP_Error( 'invalid_response', apply_filters( 'woocommerce_nmi_error_message', $error_message, $result ) );
		}

        parse_str( $result, $result );

        if( count( $result ) < 8 ) {
			$error_message = sprintf( __( 'Unrecognized response from the gateway: %s', 'wc-nmi' ), $response );
			return new WP_Error( 'invalid_response', apply_filters( 'woocommerce_nmi_error_message', $error_message, $result ) );
        }

        if( !isset( $result['response'] ) || !in_array( $result['response'], array( 1, 2, 3 ) ) ) {
			$error_message = __( 'There was an error with the gateway response.', 'wc-nmi' );
			return new WP_Error( 'invalid_response', apply_filters( 'woocommerce_nmi_error_message', $error_message, $result ) );
        }

        if( $result['response'] == 2 ) {
			$error_message = '<!-- Error: ' . $result['response_code'] . ' --> ' . __( 'Your card has been declined.', 'wc-nmi' );
			return new WP_Error( 'decline_response', apply_filters( 'woocommerce_nmi_error_message', $error_message, $result ), $result );
		}

        if( $result['response'] == 3 ) {
			$error_message = '<!-- Error: ' . $result['response_code'] . ' --> ' . $result['responsetext'];
			return new WP_Error( 'error_response', apply_filters( 'woocommerce_nmi_error_message', $error_message, $result ), $result );
		}

        return $result;

	}

	function get_line_items( $order ) {
		$line_items = array();
		// order line items
		foreach ( $order->get_items() as $item ) {
			$line_items[] = $item->get_name() . ' x ' .$item->get_quantity();
		}
		return implode( ', ', $line_items );
	}

	/**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() || $amount <= 0 ) {
			return false;
		}

		$this->log( "Info: Beginning refund for order $order_id for the amount of {$amount}" );

		$args = array(
			'amount'  			=> $amount,
			'transactionid'		=> $order->get_transaction_id(),
			'email' 			=> $order->get_billing_email(),
			'type'		 		=> 'refund',
			'order_description' => $reason,
			'currency'			=> $this->get_payment_currency( $order_id ),
		);

		$args = apply_filters( 'wc_nmi_request_args', $args, $order );

		$response = $this->nmi_request( $args );

		if ( is_wp_error( $response ) ) {
			$this->log( "Gateway Error: " . $response->get_error_message() );
			return $response;
		} elseif ( ! empty( $response['transactionid'] ) ) {
			$refund_message = sprintf( __( 'Refunded %s - Refund ID: %s - Reason: %s', 'wc-nmi' ), $amount, $response['transactionid'], $reason );
			$order->add_order_note( $refund_message );
			$order->save();
			$this->log( "Success: " . html_entity_decode( strip_tags( $refund_message ) ) );
			return true;
		}
	}

    public function http_request_timeout( $timeout_value ) {
		return 45; // 45 seconds. Too much for production, only for testing.
	}

	function get_card_type( $value, $field = 'pattern', $return = 'label' ) {
		$card_types = array(
			array(
				'label' => 'American Express',
				'name' => 'amex',
				'pattern' => '/^3[47]/',
				'valid_length' => '[15]'
			),
			array(
				'label' => 'JCB',
				'name' => 'jcb',
				'pattern' => '/^35(2[89]|[3-8][0-9])/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Discover',
				'name' => 'discover',
				'pattern' => '/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'MasterCard',
				'name' => 'mastercard',
				'pattern' => '/^5[1-5]/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Visa',
				'name' => 'visa',
				'pattern' => '/^4/',
				'valid_length' => '[16]'
			),
			array(
				'label' => 'Maestro',
				'name' => 'maestro',
				'pattern' => '/^(5018|5020|5038|6304|6759|676[1-3])/',
				'valid_length' => '[12, 13, 14, 15, 16, 17, 18, 19]'
			),
			array(
				'label' => 'Diners Club',
				'name' => 'diners-club',
				'pattern' => '/^3[0689]/',
				'valid_length' => '[14]'
			),
		);

		foreach( $card_types as $type ) {
			$compare = $type[$field];
			if ( ( $field == 'pattern' && preg_match( $compare, $value, $match ) ) || $compare == $value ) {
				return $type[$return];
			}
		}

		return false;

	}

	/**
	 * Get payment currency, either from current order or WC settings
	 *
	 * @since 4.1.0
	 * @return string three-letter currency code
	 */
	function get_payment_currency( $order_id = false ) {
 		$currency = get_woocommerce_currency();
		$order_id = ! $order_id ? $this->get_checkout_pay_page_order_id() : $order_id;

 		// Gets currency for the current order, that is about to be paid for
 		if ( $order_id ) {
 			$order    = wc_get_order( $order_id );
 			$currency = $order->get_currency();
 		}
 		return $currency;
 	}

	/**
	 * Returns the order_id if on the checkout pay page
	 *
	 * @since 3.0.0
	 * @return int order identifier
	 */
	public function get_checkout_pay_page_order_id() {
		global $wp;
		return isset( $wp->query_vars['order-pay'] ) ? absint( $wp->query_vars['order-pay'] ) : 0;
	}

	/**
	 * Send the request to NMI's API
	 *
	 * @since 2.6.10
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		if ( $this->logging ) {
			WC_NMI_Logger::log( $message );
		}
	}

	public function get_tokens() {
		return array();
	}

}
