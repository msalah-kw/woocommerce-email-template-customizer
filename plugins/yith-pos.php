<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class VIWEC_Plugins_Yith_Pos {
	public function __construct() {
		if (!function_exists('yith_pos_init')){
			return;
		}
		add_filter('viwec_woocommerce_order_item_get_product',[$this,'viwec_woocommerce_order_item_get_product'],10,2);
	}
	/**
	 * @param $product WC_Product
	 * @param $order_item WC_Order_Item_Product
	 *
	 */
	public function viwec_woocommerce_order_item_get_product($product, $order_item){
		if (is_a($product,'WC_Product') || !$order_item->get_meta( 'yith_pos_custom_product' )){
			return $product;
		}
		$product = (object)[
			'viwec_product_permalink' => '#',
			'viwec_product_sku' => '',
			'viwec_purchase_note' => '',
		];
		return $product;
	}
}