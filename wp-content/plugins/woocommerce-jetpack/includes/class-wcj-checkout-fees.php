<?php
/**
 * Booster for WooCommerce - Module - Checkout Fees
 *
 * @version 5.2.0
 * @since   3.7.0
 * @author  Pluggabl LLC.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Checkout_Fees' ) ) :

class WCJ_Checkout_Fees extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 5.2.0
	 * @since   3.7.0
	 * @todo    (maybe) rename module to "Cart & Checkout Fees"
	 */
	function __construct() {

		$this->id         = 'checkout_fees';
		$this->short_desc = __( 'Checkout Fees', 'woocommerce-jetpack' );
		$this->desc       = __( 'Add fees to WooCommerce cart & checkout (1 fee allowed in free version).', 'woocommerce-jetpack' );
		$this->desc_pro   = __( 'Add fees to WooCommerce cart & checkout.', 'woocommerce-jetpack' );
		$this->link_slug  = 'woocommerce-checkout-fees';
		parent::__construct();

		if ( $this->is_enabled() ) {
			// Core function
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_fees' ), PHP_INT_MAX );
			// Checkout fields
			$this->checkout_fields = $this->get_option( 'wcj_checkout_fees_data_checkout_fields', array() );
			$this->checkout_fields = array_filter( $this->checkout_fields );
			if ( ! empty( $this->checkout_fields ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			}
		}
	}

	/**
	 * enqueue_scripts.
	 *
	 * @version 3.8.0
	 * @since   3.8.0
	 */
	function enqueue_scripts() {
		if ( is_checkout() ) {
			wp_enqueue_script(  'wcj-checkout-fees', wcj_plugin_url() . '/includes/js/wcj-checkout-fees.js', array( 'jquery' ), WCJ()->version, true );
			wp_localize_script( 'wcj-checkout-fees', 'wcj_checkout_fees', array(
				'checkout_fields' => 'input[name="' . implode( '"], input[name="', $this->checkout_fields ) . '"]',
			) );
		}
	}

	/**
	 * Validate fee without considering overlapping.
	 *
	 * @version 4.6.1
	 * @since   4.5.0
	 *
	 * @param $fee_id
	 * @param WC_Cart $cart
	 *
	 * @return bool
	 */
	function is_fee_valid( $fee_id, \WC_Cart $cart ) {
		$fees    = $this->get_fees();
		$enabled = $this->get_option( 'wcj_checkout_fees_data_enabled', array() );
		$values  = $this->get_option( 'wcj_checkout_fees_data_values', array() );

		// Check if is active and empty value
		if (
			( isset( $enabled[ $fee_id ] ) && 'no' === $enabled[ $fee_id ] ) ||
			( 0 == ( $value = ( isset( $values[ $fee_id ] ) ? $values[ $fee_id ] : 0 ) ) )
		) {
			return false;
		}

		// Check cart quantity
		if (
			$cart->get_cart_contents_count() < $fees[ $fee_id ]['cart_min'] ||
			( $fees[ $fee_id ]['cart_max'] > 0 && $cart->get_cart_contents_count() > $fees[ $fee_id ]['cart_max'] )
		) {
			return false;
		}

		// Check cart total
		if (
			$cart->get_cart_contents_total() < $fees[ $fee_id ]['cart_min_total'] ||
			( ! empty( $fees[ $fee_id ]['cart_max_total'] ) && $fees[ $fee_id ]['cart_max_total'] > 0 && $cart->get_cart_contents_total() > $fees[ $fee_id ]['cart_max_total'] )
		) {
			return false;
		}

		// Check checkout fields
		if ( ! empty( $this->checkout_fields[ $fee_id ] ) ) {
			if ( isset( $post_data ) || isset( $_REQUEST['post_data'] ) ) {
				if ( ! isset( $post_data ) ) {
					$post_data = array();
					parse_str( $_REQUEST['post_data'], $post_data );
				}
				if ( empty( $post_data[ $this->checkout_fields[ $fee_id ] ] ) ) {
					return false;
				}
			} elseif ( empty( $_REQUEST[ $this->checkout_fields[ $fee_id ] ] ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @version 4.5.0
	 * @since   4.5.0
	 *
	 * @param $valid_fees
	 *
	 * @return array
	 */
	function get_overlapped_fees( $valid_fees ) {
		$fees       = $this->get_fees();
		$overlapped = array();
		foreach ( $valid_fees as $fee_id ) {
			if ( ! in_array( $fee_id, $overlapped ) ) {
				$overlapped = array_unique( array_merge( $overlapped, $fees[ $fee_id ]['overlap'] ) );
			}
		}
		return $overlapped;
	}

	/**
	 * Get Fees.
	 *
	 * @version 4.6.1
	 * @since   4.5.0
	 *
	 * @param bool $only_enabled
	 * @param bool $adjust_priority
	 *
	 * @return array
	 */
	function get_fees( $only_enabled = true, $adjust_priority = true ) {
		$total_number    = apply_filters( 'booster_option', 1, $this->get_option( 'wcj_checkout_fees_total_number', 1 ) );
		$titles          = $this->get_option( 'wcj_checkout_fees_data_titles', array() );
		$types           = $this->get_option( 'wcj_checkout_fees_data_types', array() );
		$values          = $this->get_option( 'wcj_checkout_fees_data_values', array() );
		$cart_min        = $this->get_option( 'wcj_checkout_fees_cart_min_amount', array() );
		$cart_min_total  = $this->get_option( 'wcj_checkout_fees_cart_min_total_amount', array() );
		$cart_max        = $this->get_option( 'wcj_checkout_fees_cart_max_amount', array() );
		$cart_max_total  = $this->get_option( 'wcj_checkout_fees_cart_max_total_amount', array() );
		$taxable         = $this->get_option( 'wcj_checkout_fees_data_taxable', array() );
		$checkout_fields = $this->get_option( 'wcj_checkout_fees_data_values', array() );
		$enabled         = $this->get_option( 'wcj_checkout_fees_data_enabled', array() );
		$overlap_opt     = $this->get_option( 'wcj_checkout_fees_overlap', array() );
		$priorities      = $this->get_option( 'wcj_checkout_fees_priority', array() );

		$fees = array();
		for ( $i = 1; $i <= $total_number; $i ++ ) {
			if ( ! isset( $priorities[ $i ] ) || empty( $priorities[ $i ] ) ) {
				$priorities[ $i ] = 0;
			}
			$enabled = isset( $enabled[ $i ] ) ? $enabled[ $i ] : 'yes';
			if ( $only_enabled && "no" === $enabled ) {
				continue;
			}
			$fees[ $i ] = array(
				'enabled'        => $enabled,
				'cart_min'       => isset( $cart_min[ $i ] ) ? $cart_min[ $i ] : 1,
				'cart_min_total' => isset( $cart_min_total[ $i ] ) ? $cart_min_total[ $i ] : 0,
				'cart_max'       => isset( $cart_max[ $i ] ) ? $cart_max[ $i ] : 0,
				'cart_max_total' => isset( $cart_max_total[ $i ] ) ? $cart_max_total[ $i ] : '',
				'title'          => isset( $titles[ $i ] ) ? $titles[ $i ] : '',
				'type'           => isset( $types[ $i ] ) ? $types[ $i ] : 'fixed',
				'value'          => isset( $values[ $i ] ) ? $values[ $i ] : 0,
				'priority'       => isset( $priorities[ $i ] ) ? ( $priorities[ $i ] ) : 0,
				'taxable'        => isset( $taxable[ $i ] ) ? $taxable[ $i ] : 'yes',
				'checkout_field' => isset( $checkout_fields[ $i ] ) ? $checkout_fields[ $i ] : '',
				'overlap'        => isset( $overlap_opt[ $i ] ) ? $overlap_opt[ $i ] : array(),
			);
		}
		if ( $adjust_priority ) {
			uksort( $fees, function ( $a, $b ) use ( $fees, $priorities ) {
				return $priorities[ $a ] < $priorities[ $b ];
			} );
		}
		return $fees;
	}

	/**
	 * Get valid fees.
	 *
	 * @version 4.5.0
	 * @since   4.5.0
	 *
	 * @param $cart
	 * @param bool $ignore_overlapped
	 *
	 * @return array
	 */
	function get_valid_fees( $cart, $ignore_overlapped = true ) {
		$titles  = $this->get_option( 'wcj_checkout_fees_data_titles', array() );
		$types   = $this->get_option( 'wcj_checkout_fees_data_types', array() );
		$values  = $this->get_option( 'wcj_checkout_fees_data_values', array() );
		$taxable = $this->get_option( 'wcj_checkout_fees_data_taxable', array() );

		//$total_number = apply_filters( 'booster_option', 1, $this->get_option( 'wcj_checkout_fees_total_number', 1 ) );
		$fees = $this->get_fees();

		$fees_to_add  = array();
		$valid_fees = array();

		// Get Valid fees
		foreach ( $fees as $fee_id => $fee_title ) {
			if ( ! $this->is_fee_valid( $fee_id, $cart ) ) {
				continue;
			}
			$valid_fees[] = $fee_id;
		}

		// Ignore overlapped
		if ( $ignore_overlapped ) {
			$overlapped_fees = $this->get_overlapped_fees( $valid_fees );
			$valid_fees      = array_diff( $valid_fees, $overlapped_fees );
		}

		foreach ( $valid_fees as $fee_id ) {
			// Adding the fee
			$title = ( isset( $titles[ $fee_id ] ) ? $titles[ $fee_id ] : __( 'Fee', 'woocommerce-jetpack' ) . ' #' . $fee_id );
			$value = isset( $values[ $fee_id ] ) ? $values[ $fee_id ] : 0;
			if ( isset( $types[ $fee_id ] ) && 'percent' === $types[ $fee_id ] ) {
				$value = $cart->get_cart_contents_total() * $value / 100;
			}
			$fees_to_add[ $fee_id ] = array(
				'name'      => $title,
				'amount'    => $value,
				'taxable'   => ( isset( $taxable[ $fee_id ] ) ? ( 'yes' === $taxable[ $fee_id ] ) : true ),
				'tax_class' => 'standard',
			);
		}

		return $fees_to_add;
	}

	/**
	 * add_fees.
	 *
	 * @version 4.5.0
	 * @since   3.7.0
	 * @todo    fees with same title
	 * @todo    options: `tax_class`
	 * @todo    options: `cart total` (for percent) - include/exclude shipping etc. - https://docs.woocommerce.com/wc-apidocs/class-WC_Cart.html
	 * @todo    options: `rounding` (for percent)
	 * @todo    options: `min/max cart amount`
	 * @todo    options: `products, cats, tags to include/exclude`
	 * @todo    options: `countries to include/exclude`
	 * @todo    options: `user roles to include/exclude`
	 * @todo    see https://wcbooster.zendesk.com/agent/tickets/446
	 */
	function add_fees( $cart ) {
		if ( ! wcj_is_frontend() ) {
			return;
		}

		$fees_to_add = $this->get_valid_fees( $cart );

		if ( ! empty( $fees_to_add ) ) {
			foreach ( $fees_to_add as $fee_to_add ) {
				$cart->add_fee( $fee_to_add['name'], $fee_to_add['amount'], $fee_to_add['taxable'], $fee_to_add['tax_class'] );
			}
		}
	}

}

endif;

return new WCJ_Checkout_Fees();