<?php

//Germanized for WooCommerce by vendidero
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class VIWEC_Plugins_Woo_Germanized {

	public function __construct() {
		if (!class_exists('WC_GZD_Customer_Helper')){
			return;
		}
		add_action('woocommerce_gzd_customer_opt_in_finished',[$this,'woocommerce_gzd_customer_opt_in_finished'], 10 ,1);
		add_action('woocommerce_gzd_order_confirmation',[$this,'woocommerce_gzd_order_confirmation'], 10 ,1);
		add_action('viwec_before_render_email_content',[$this,'viwec_before_render_email_content']);
	}
	public function viwec_before_render_email_content(){
		$this->remove_order_email_filters();
		// Add order item name actions
		add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_differential_taxation_mark', wc_gzd_get_hook_priority( 'email_product_differential_taxation' ), 2 );

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_product_units' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_units', wc_gzd_get_hook_priority( 'email_product_units' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_delivery_time' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_delivery_time', wc_gzd_get_hook_priority( 'email_product_delivery_time' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_product_item_desc' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_item_desc', wc_gzd_get_hook_priority( 'email_product_item_desc' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_product_defect_description' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_defect_description', wc_gzd_get_hook_priority( 'email_product_defect_description' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_product_attributes' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_attributes', wc_gzd_get_hook_priority( 'email_product_attributes' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_unit_price' ) ) {
			add_filter( 'woocommerce_order_formatted_line_subtotal', 'wc_gzd_cart_product_unit_price', wc_gzd_get_hook_priority( 'email_product_unit_price' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_deposit_packaging_type' ) ) {
			add_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_deposit_packaging_type', wc_gzd_get_hook_priority( 'email_deposit_packaging_type' ), 2 );
		}

		if ( 'yes' === get_option( 'woocommerce_gzd_display_emails_deposit' ) ) {
			add_filter( 'woocommerce_order_formatted_line_subtotal', 'wc_gzd_cart_product_deposit_amount', wc_gzd_get_hook_priority( 'email_product_deposit_amount' ), 2 );
		}
	}
	public function remove_order_email_filters() {
		/**
		 * Remove order shopmarks
		 */
		foreach ( wc_gzd_get_order_shopmarks() as $shopmark ) {
			$shopmark->remove();
		}

		remove_action( 'woocommerce_thankyou', 'woocommerce_gzd_template_order_item_hooks', 0 );
		remove_action( 'before_woocommerce_pay', 'woocommerce_gzd_template_order_item_hooks', 10 );

		// Remove order email filters
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_differential_taxation_mark', wc_gzd_get_hook_priority( 'email_product_differential_taxation' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_units', wc_gzd_get_hook_priority( 'email_product_units' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_delivery_time', wc_gzd_get_hook_priority( 'email_product_delivery_time' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_item_desc', wc_gzd_get_hook_priority( 'email_product_item_desc' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_defect_description', wc_gzd_get_hook_priority( 'email_product_defect_description' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_attributes', wc_gzd_get_hook_priority( 'email_product_attributes' ) );
		remove_filter( 'woocommerce_order_item_name', 'wc_gzd_cart_product_deposit_packaging_type', wc_gzd_get_hook_priority( 'email_deposit_packaging_type' ) );

		remove_filter( 'woocommerce_order_formatted_line_subtotal', 'wc_gzd_cart_product_deposit_amount', wc_gzd_get_hook_priority( 'email_product_deposit_amount' ) );
		remove_filter( 'woocommerce_order_formatted_line_subtotal', 'wc_gzd_cart_product_unit_price', wc_gzd_get_hook_priority( 'email_product_unit_price' ) );
	}
	public function woocommerce_gzd_order_confirmation($order){
		if (function_exists('wc_gzd_remove_all_hooks')){
			wc_gzd_remove_all_hooks( 'woocommerce_get_checkout_order_received_url', 1005 );
		}
	}
	public function woocommerce_gzd_customer_opt_in_finished($user){
		if (!is_a($user, 'WP_User')){
			return;
		}
		delete_user_meta( $user->ID, '_woocommerce_activation' );
	}
}