<?php

namespace VIWEC\INCLUDES;

use _WP_Editors;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
 * Initialize Plugin
 */

class Init {
	protected static $instance = null;
	public static $img_map;
	protected $cache_products = [];
	protected $cache_posts = [];

	private function __construct() {
//		$this->define_params();
		$this->class_init();

		add_action( 'init', array( $this, 'plugin_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'wc_enhanced_select' ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_run_file' ), 9999 );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		//Premium
		add_action( 'villatheme_auto_update', array( $this, 'plugin_update' ) );
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'check_update' ] );
		add_action( 'admin_init', [ $this, 'save_update_key' ], 20 );
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function define_params() {
		self::$img_map = apply_filters( 'viwec_image_map', [
			'infor_icons' => [
				'home'     => [
					'home-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'home-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'home-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
					'home-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
					'home-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
					'home-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
				],
				'email'    => [
					'email-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'email-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'email-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
					'email-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
					'email-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
					'email-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
				],
				'phone'    => [
					'phone-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'phone-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'phone-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
					'phone-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
					'phone-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
					'phone-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
				],
				'location' => [
					'location-white'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'location-black'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'location-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
					'location-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
					'location-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
					'location-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
				],
			],

			'social_icons' => [
				'facebook' => [
					''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'fb-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'fb-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'fb-blue'         => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'fb-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'fb-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'fb-blue-border'  => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'fb-blue-white'   => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'fb-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'fb-white-blue'   => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'twitter' => [
					''                     => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'twi-black'            => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'twi-white'            => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'twi-cyan'             => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'twi-white-border'     => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'twi-black-border'     => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'twi-cyan-border'      => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'twi-cyan-white'       => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'twi-white-black'      => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'twi-white-cyan'       => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
					'twi-black-new'        => esc_html__( 'Black New', 'viwec-email-template-customize' ),
					'twi-white-new'        => esc_html__( 'White New', 'viwec-email-template-customize' ),
					'twi-cyan-new'         => esc_html__( 'Color New', 'viwec-email-template-customize' ),
					'twi-white-border-new' => esc_html__( 'White border New', 'viwec-email-template-customize' ),
					'twi-black-border-new' => esc_html__( 'Black border New', 'viwec-email-template-customize' ),
					'twi-cyan-border-new'  => esc_html__( 'Color border New', 'viwec-email-template-customize' ),
					'twi-cyan-white-new'   => esc_html__( 'Color - White New', 'viwec-email-template-customize' ),
					'twi-white-black-new'  => esc_html__( 'Black - White New', 'viwec-email-template-customize' ),
					'twi-white-cyan-new'   => esc_html__( 'White - Color New', 'viwec-email-template-customize' ),
				],

				'instagram' => [
					''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'ins-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'ins-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'ins-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'ins-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'ins-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'ins-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'ins-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'ins-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'ins-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'youtube' => [
					''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'yt-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'yt-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'yt-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'yt-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'yt-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'yt-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'yt-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'yt-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'yt-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'linkedin' => [
					''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'li-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'li-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'li-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'li-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'li-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'li-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'li-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'li-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'li-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'whatsapp' => [
					''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'wa-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'wa-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'wa-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'wa-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'wa-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'wa-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'wa-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'wa-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'wa-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'telegram' => [
					''                  => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'tele-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'tele-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'tele-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'tele-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'tele-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'tele-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'tele-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'tele-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'tele-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'tiktok' => [
					''                    => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'tiktok-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'tiktok-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'tiktok-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'tiktok-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'tiktok-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'tiktok-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'tiktok-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'tiktok-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'tiktok-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'pinterest' => [
					''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'pin-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'pin-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'pin-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'pin-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'pin-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'pin-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'pin-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'pin-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'pin-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],

				'discord' => [
					''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
					'discord-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
					'discord-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
					'discord-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
					'discord-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
					'discord-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
					'discord-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
					'discord-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
					'discord-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
					'discord-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				],
			]
		] );
	}
    public static function get_img_map($type=''){
        if (!self::$img_map){
	        self::$img_map = apply_filters( 'viwec_image_map', [
		        'infor_icons' => [
			        'home'     => [
				        'home-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'home-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'home-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
				        'home-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
				        'home-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
				        'home-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
			        ],
			        'email'    => [
				        'email-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'email-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'email-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
				        'email-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
				        'email-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
				        'email-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
			        ],
			        'phone'    => [
				        'phone-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'phone-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'phone-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
				        'phone-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
				        'phone-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
				        'phone-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
			        ],
			        'location' => [
				        'location-white'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'location-black'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'location-white-border' => esc_html__( 'White/Border', 'viwec-email-template-customizer' ),
				        'location-black-border' => esc_html__( 'Black/Border', 'viwec-email-template-customizer' ),
				        'location-black-white'  => esc_html__( 'Black/White', 'viwec-email-template-customizer' ),
				        'location-white-black'  => esc_html__( 'White/Black', 'viwec-email-template-customizer' ),
			        ],
		        ],

		        'social_icons' => [
			        'facebook' => [
				        ''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'fb-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'fb-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'fb-blue'         => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'fb-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'fb-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'fb-blue-border'  => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'fb-blue-white'   => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'fb-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'fb-white-blue'   => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'twitter' => [
				        ''                     => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'twi-black'            => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'twi-white'            => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'twi-cyan'             => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'twi-white-border'     => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'twi-black-border'     => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'twi-cyan-border'      => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'twi-cyan-white'       => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'twi-white-black'      => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'twi-white-cyan'       => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
				        'twi-black-new'        => esc_html__( 'Black New', 'viwec-email-template-customize' ),
				        'twi-white-new'        => esc_html__( 'White New', 'viwec-email-template-customize' ),
				        'twi-cyan-new'         => esc_html__( 'Color New', 'viwec-email-template-customize' ),
				        'twi-white-border-new' => esc_html__( 'White border New', 'viwec-email-template-customize' ),
				        'twi-black-border-new' => esc_html__( 'Black border New', 'viwec-email-template-customize' ),
				        'twi-cyan-border-new'  => esc_html__( 'Color border New', 'viwec-email-template-customize' ),
				        'twi-cyan-white-new'   => esc_html__( 'Color - White New', 'viwec-email-template-customize' ),
				        'twi-white-black-new'  => esc_html__( 'Black - White New', 'viwec-email-template-customize' ),
				        'twi-white-cyan-new'   => esc_html__( 'White - Color New', 'viwec-email-template-customize' ),
			        ],

			        'instagram' => [
				        ''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'ins-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'ins-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'ins-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'ins-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'ins-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'ins-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'ins-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'ins-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'ins-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'youtube' => [
				        ''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'yt-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'yt-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'yt-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'yt-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'yt-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'yt-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'yt-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'yt-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'yt-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'linkedin' => [
				        ''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'li-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'li-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'li-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'li-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'li-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'li-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'li-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'li-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'li-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'whatsapp' => [
				        ''                => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'wa-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'wa-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'wa-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'wa-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'wa-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'wa-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'wa-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'wa-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'wa-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'telegram' => [
				        ''                  => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'tele-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'tele-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'tele-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'tele-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'tele-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'tele-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'tele-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'tele-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'tele-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'tiktok' => [
				        ''                    => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'tiktok-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'tiktok-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'tiktok-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'tiktok-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'tiktok-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'tiktok-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'tiktok-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'tiktok-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'tiktok-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'pinterest' => [
				        ''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'pin-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'pin-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'pin-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'pin-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'pin-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'pin-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'pin-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'pin-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'pin-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],

			        'discord' => [
				        ''                 => esc_html__( 'Disable', 'viwec-email-template-customizer' ),
				        'discord-black'        => esc_html__( 'Black', 'viwec-email-template-customizer' ),
				        'discord-white'        => esc_html__( 'White', 'viwec-email-template-customizer' ),
				        'discord-color'        => esc_html__( 'Color', 'viwec-email-template-customizer' ),
				        'discord-white-border' => esc_html__( 'White border', 'viwec-email-template-customizer' ),
				        'discord-black-border' => esc_html__( 'Black border', 'viwec-email-template-customizer' ),
				        'discord-color-border' => esc_html__( 'Color border', 'viwec-email-template-customizer' ),
				        'discord-color-white'  => esc_html__( 'Color - White', 'viwec-email-template-customizer' ),
				        'discord-white-black'  => esc_html__( 'Black - White', 'viwec-email-template-customizer' ),
				        'discord-white-color'  => esc_html__( 'White - Color', 'viwec-email-template-customizer' ),
			        ],
		        ]
	        ] );
        }
        if (!$type){
            return self::$img_map;
        }
        return self::$img_map[$type]??null;
    }

	public function class_init() {
		Email_Builder::init();
		Email_Trigger::init();
		Compatible::init();
		View_Product::init();
		include_once VIWEC_SUPPORT . 'update.php';
		include_once VIWEC_SUPPORT . 'check_update.php';
		include_once VIWEC_INCLUDES . 'functions.php';
		villtheme_include_folder( VIWEC_DIR . 'class' . DIRECTORY_SEPARATOR, 'just_require' );
		villtheme_include_folder( VIWEC_DIR . 'plugins' . DIRECTORY_SEPARATOR, 'VIWEC_Plugins_' );
	}

	public function plugin_textdomain() {
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		// Global + Frontend Locale
		load_textdomain( 'viwec-email-template-customizer', VIWEC_LANGUAGES . "viwec-email-template-customizer-$locale.mo" );
		load_plugin_textdomain( 'viwec-email-template-customizer', false, VIWEC_LANGUAGES );
	}

	public function register_libs_scripts() {
		$scripts = [ 'chart', 'tab', 'accordion',  'checkbox', 'select2', 'dimmer', 'transition', 'modal' ];
		foreach ( $scripts as $script ) {
			wp_register_script( VIWEC_SLUG . '-' . $script, VIWEC_JS . 'libs/' . $script . '.min.js', [ 'jquery' ], VIWEC_VER, false );
		}

		$styles = [ 'tab', 'menu', 'accordion',  'select2', 'dimmer', 'transition', 'modal', 'button','checkbox', 'form', 'segment', 'icon', 'input' ];
		foreach ( $styles as $style ) {
			wp_register_style( VIWEC_SLUG . '-' . $style, VIWEC_CSS . 'libs/' . $style . '.min.css', '', VIWEC_VER );
		}
	}

	public function register_exe_scripts() {
		$scripts = [ 'inputs', 'email-builder', 'properties', 'components', 'report', 'get-key' ];
		foreach ( $scripts as $script ) {
			wp_register_script( VIWEC_SLUG . '-' . $script, VIWEC_JS . $script . '.js', [ 'jquery', 'wp-i18n' ], VIWEC_VER, false );
		}

		$styles = [ 'email-builder', 'admin' ];
		foreach ( $styles as $style ) {
			wp_register_style( VIWEC_SLUG . '-' . $style, VIWEC_CSS . $style . '.css', '', VIWEC_VER );
		}
	}

	public function wc_enhanced_select() {
		wp_enqueue_script( 'wc-enhanced-select' );
	}

	public function admin_enqueue() {
		$this->register_libs_scripts();
		$this->register_exe_scripts();

		global $post;
		$screen = get_current_screen()->id;

		switch ( $screen ) {
			case 'viwec_template':
			case 'viwec_template_block':
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				/*add script when user_can_richedit enable*/
				if ( ! class_exists( '_WP_Editors', false ) ) {
					require ABSPATH . WPINC . '/class-wp-editor.php';
				}
				global $wp_scripts;
				if ( isset( $wp_scripts->registered['jquery-ui-accordion'] ) ) {
					unset( $wp_scripts->registered['jquery-ui-accordion'] );
					wp_dequeue_script( 'jquery-ui-accordion' );
				}
				if ( isset( $wp_scripts->registered['accordion'] ) ) {
					unset( $wp_scripts->registered['accordion'] );
					wp_dequeue_script( 'accordion' );
				}

				_WP_Editors::print_tinymce_scripts();
				wp_enqueue_editor();
				wp_enqueue_media();
				wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), [ 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch', 'wc-enhanced-select' ], VIWEC_VER, false );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-draggable' );

				foreach ( [ 'tab', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'inputs', 'email-builder', 'properties', 'components' ] as $script ) {
					wp_enqueue_script( VIWEC_SLUG . '-' . $script );
				}
				foreach ( [ 'tab', 'menu', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'button', 'email-builder' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}

				if ( function_exists( 'wp_set_script_translations' ) ) {
					wp_set_script_translations( VIWEC_SLUG . '-components', 'viwec-email-template-customizer', VIWEC_LANGUAGES );
				}

				$header = Utils::parse_block( Email_Samples::sample_header() );
				$footer = Utils::parse_block( Email_Samples::sample_footer() );

				$samples         = Email_Samples::sample_templates( $header, $footer );
				$hide_rule       = Utils::get_hide_rules_data();
				$accept_elements = Utils::get_accept_elements_data();

				$params = [
					'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
					'nonce'               => wp_create_nonce( 'viwec_nonce' ),
					'product'             => VIWEC_IMAGES . 'product.png',
					'post'                => VIWEC_IMAGES . 'post.png',
					'placeholder'         => VIWEC_IMAGES . 'placeholder.jpg',
					'emailTypes'          => Utils::get_email_ids_grouped(),
					'samples'             => $samples,
					'subjects'            => Email_Samples::default_subject(),
					'adminBarStt'         => Utils::get_admin_bar_stt(),
					'suggestProductPrice' => wc_price( 20 ),
					'homeUrl'             => home_url(),
					'siteUrl'             => site_url(),
					'shopUrl'             => wc_get_endpoint_url( 'shop' ),
					'adminEmail'          => get_bloginfo( 'admin_email' ),
					'adminPhone'          => get_user_meta( get_current_user_id(), 'billing_phone', true ) ?? '202-000-0000',
					'hide_rule'           => $hide_rule,
					'accept_elements'     => $accept_elements,
					'uploadUrl'           => admin_url( 'upload.php' )
				];
                $social_icons = self::get_img_map('social_icons');
                if (is_array($social_icons) && !empty($social_icons)){
	                foreach ( $social_icons as $type => $data ) {
		                foreach ( $data as $key => $text ) {
			                $url                               = $key ? VIWEC_IMAGES . $key . '.png' : '';
			                $params['social_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
		                }
	                }
                }
                $infor_icons = self::get_img_map('infor_icons');
                if (is_array($infor_icons) && !empty($infor_icons)) {
	                foreach ( $infor_icons as $type => $data ) {
		                foreach ( $data as $key => $text ) {
			                $params['infor_icons'][ $type ][] = [ 'id' => VIWEC_IMAGES . $key . '.png', 'text' => $text, 'slug' => $key ];
		                }
	                }
                }

				$params['post_categories']    = $this->get_categories( 'category' );
				$params['product_categories'] = $this->get_categories( 'product_cat' );
				$params['commonShortcodes']   = Utils::common_shortcodes();
				$params['typedShortcodes']    = Utils::typed_shortcodes();

				$email_structure = get_post_meta( $post->ID, 'viwec_email_structure', true );
				if ( $email_structure ) {
                    $email_structure = $this->html_entity_decode($email_structure);
					$json_decode_email_structure = json_decode( $email_structure, true );
					if ( is_array( $json_decode_email_structure ) ) {
						array_walk_recursive( $json_decode_email_structure, function ( $value, $key ) {
							if ( in_array( $key, [
                                    'data-suggest_product-exclude-product',
                                'data-suggest_product-include-product',
                                    'data-coupon-include-product',
                                'data-coupon-exclude-product'
                            ], true ) ) {
								$value                = explode( ',', $value );
								$this->cache_products = array_merge( $this->cache_products, $value );
							}

							if ( in_array( $key, [ 'data-include-post-id', 'data-exclude-post-id' ], true ) ) {
								$value             = explode( ',', $value );
								$this->cache_posts = array_merge( $this->cache_posts, $value );
							}
						} );
					}

					$products_temp = [ [ 'id' => '', 'text' => '' ] ];
					$posts_temp    = [];

					if ( ! empty( $this->cache_products ) ) {
						$this->cache_products = array_values( array_unique( $this->cache_products ) );

						$products = wc_get_products( [ 'limit' => - 1, 'include' => $this->cache_products ] );
						if ( ! empty( $products ) ) {
							foreach ( $products as $p ) {
								$products_temp[] = [ 'id' => (string) $p->get_id(), 'text' => $p->get_name() ];
							}
						}
					}

					if ( ! empty( $this->cache_posts ) ) {
						$this->cache_posts = array_values( array_unique( $this->cache_posts ) );

						$posts = get_posts( [ 'numberposts' => 5, 'include' => $this->cache_posts ] );
						if ( ! empty( $posts ) ) {
							foreach ( $posts as $p ) {
								$posts_temp[] = [ 'id' => $p->ID, 'text' => $p->post_title, 'content' => do_shortcode( $p->post_content ) ];
							}
						}
					}

					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecCachePosts', $posts_temp );
					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecCacheProducts', $products_temp );
					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecLoadTemplate', [ $email_structure ] );
				}

				$params['i18n'] = I18n::init();

				if ( ! empty( $_GET['sample'] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'edit' ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$style            = ! empty( $_GET['style'] ) ? sanitize_text_field( $_GET['style'] ) : 'basic';// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$params['addNew'] = [ 'type' => sanitize_text_field( $_GET['sample'] ), 'style' => $style ];// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					}
				}

				if ( $screen === 'viwec_template' ) {
					$block_data = [];
					$blocks     = get_posts( [ 'post_type' => 'viwec_template_block', 'post_status' => 'publish', 'numberposts' => - 1 ] );
					if ( ! empty( $blocks ) ) {
						foreach ( $blocks as $block ) {
							$id              = $block->ID;
							$email_structure = get_post_meta( $id, 'viwec_email_structure', true );
							$email_structure =self::html_entity_decode( $email_structure );
							$email_structure = json_decode( $email_structure, true );

							$block_data[] = [ 'id' => $id, 'text' => $block->post_title, 'data' => $email_structure ];
						}
					}
					$params['templateBlocks'] = $block_data;
				}

				$params['postType'] = $screen;

				global $viwec_params;
				$viwec_params = $params;

				wp_localize_script( VIWEC_SLUG . '-inputs', 'viWecParams', $params );

				break;

			case 'edit-viwec_template':
				foreach ( [ 'form', 'segment', 'button', 'icon', 'admin' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}
				break;

			//Premium
			case 'viwec_template_page_viwec-auto-update':
				wp_enqueue_script( VIWEC_SLUG . '-checkbox' );
				wp_enqueue_script( VIWEC_SLUG . '-get-key' );
				foreach ( [ 'form', 'segment', 'button','checkbox', 'icon', 'input' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}
				break;

			case 'viwec_template_page_viwec_report':
				wp_enqueue_script( VIWEC_SLUG . '-chart' );
				wp_enqueue_script( VIWEC_SLUG . '-report' );
				wp_enqueue_style( VIWEC_SLUG . '-segment' );
				wp_enqueue_style( VIWEC_SLUG . '-admin' );
				break;
		}
	}
    public static function pre_html_entity_decode($email_structure){
	    if (strpos($email_structure,'=&quot;')){
		    $email_structure = preg_replace_callback('/=&quot;(.*?)&quot;/', function ($matches) {
                $tmp = $matches[1];
			    return '=\&quot;' . $tmp. '\&quot;';
		    }, $email_structure);
	    }
	    return $email_structure;
    }
    public static function html_entity_decode($email_structure){
	    if (strpos($email_structure,'=&quot;')){
		    $email_structure = self::pre_html_entity_decode($email_structure);
	    }
        return html_entity_decode( $email_structure );
    }

	public function get_categories( $type ) {
		$cats       = [];
		$categories = get_terms( [ 'taxonomy' => $type, 'orderby' => 'name', 'hide_empty' => 0 ] );
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				$cats[] = [ 'id' => $cat->term_id, 'text' => $cat->name ];
			}
		}

		return $cats;
	}

	public function enqueue_run_file() {
		if ( in_array( get_current_screen()->id, [ 'viwec_template', 'viwec_template_block' ] ) ) {
			Utils::enqueue_admin_scripts( [ 'run' ], [ 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'wp-color-picker' ] );
		}
	}

	public function admin_body_class( $class ) {
		$admin_bar = Utils::get_admin_bar_stt();
		$class     = $admin_bar ? $class : $class . ' viwec-admin-bar-hidden';

		return $class;
	}

	public function admin_footer() {
		if ( get_current_screen()->id === 'edit-viwec_template' ) {
			?>
            <div id="viwec-in-all-email-page">
				<?php do_action( 'villatheme_support_' . VIWEC_SLUG ); ?>
            </div>
		<?php }
	}

	public function save_update_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_viwec_settings_nonce'] ) || ! wp_verify_nonce( wc_clean($_POST['_viwec_settings_nonce']), 'viwec_settings_action_nonce' ) ) {
			return;
		}
//        $old_key = get_option('viwec_auto_update_key','');
		$key = isset( $_POST['viwec_auto_update_key'] ) ? sanitize_text_field( $_POST['viwec_auto_update_key'] ) : '';
		update_option( 'viwec_auto_update_key', $key,'no' );
		delete_site_transient( 'update_plugins' );
		delete_option( '_site_transient_update_plugins' );
		delete_transient( 'villatheme_item_52550' );
		delete_option( VIWEC_SLUG . '_messages' );
		do_action( 'villatheme_save_and_check_key_'.VIWEC_SLUG, $key );
        $viwec_settings = isset($_POST['viwec_settings'])? wc_clean($_POST['viwec_settings']):[];
		update_option( 'viwec_settings', $viwec_settings,'no' );
	}
	public function plugin_update() {
		$key = get_option( 'viwec_auto_update_key' );
        $params = get_option('viwec_settings',['mail_log'=> 1]);
		?>
        <div id="viwec-settings-page" class="wrap">
        <h2><?php echo esc_html__( 'Settings', 'viwec-email-template-customizer' ) ?></h2>
        <form method="post" class="vi-ui form small villatheme-support-auto-update-key">
	        <?php
	        wp_nonce_field( 'viwec_settings_action_nonce', '_viwec_settings_nonce' ,false);
	        ?>
            <div class="vi-ui segment">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Auto update key', 'viwec-email-template-customizer' ) ?></th>
                        <td>
                            <div class="vi-ui action input fluid">
                                <input type="text" name="viwec_auto_update_key"
                                       id="auto-update-key"
                                       class="villatheme-autoupdate-key-field"
                                       value="<?php echo esc_attr( $key ) ?>">

                                <button type="button" class="vi-ui button green small villatheme-get-key-button"
                                        data-href="https://api.envato.com/authorization?response_type=code&client_id=villatheme-download-keys-6wzzaeue&redirect_uri=https://villatheme.com/update-key"
                                        data-id="28656007">
			                        <?php echo esc_html__( 'Get Key', 'viwec-email-template-customizer' ) ?>
                                </button>
                            </div>

	                        <?php
	                        do_action( 'woocommerce-email-template-customizer_key' );
	                        ?>
                            <p class="description">
		                        <?php esc_html_e( 'Please fill your key what you get from', 'viwec-email-template-customizer' ); ?>
                                <a target="_blank" href="https://villatheme.com/my-download">https://villatheme.com/my-download</a>.
		                        <?php esc_html_e( 'You can automatically update this plugin. See', 'viwec-email-template-customizer' ); ?>
                                <a target="_blank"
                                   href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/"><?php esc_html_e( 'guide', 'viwec-email-template-customizer' ); ?></a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Email log', 'viwec-email-template-customizer' ) ?></th>
                        <td>
                            <div class="vi-ui toggle checkbox checked">
                                <input type="checkbox"
                                       name="viwec_settings[mail_log]" <?php checked($params['mail_log']??'', 1 ); ?>
                                       value="1">
                                <label></label>
                            </div>
                            <p class="description">
                                <?php esc_html_e('Log the basic info of the custom email into the WooCommerce log, with the entry name starting with "viwec"', 'viwec-email-template-customizer' ); ?>
                            </p>
                            <p class="description">
                                <?php
                                printf( esc_html__( 'Log files older than %s days will be automatically deleted by WooCommerce', 'viwec-email-template-customizer' ),wp_kses_post(apply_filters( 'woocommerce_logger_days_to_retain_logs', get_option('woocommerce_logs_retention_period_days', 30) )));
                                ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <button type="submit" class="vi-ui primary small button"><?php echo esc_html__( 'Save Key', 'viwec-email-template-customizer' ) ?></button>

        </form>
		<?php do_action( 'villatheme_support_' . VIWEC_SLUG ); ?>
        </div><?php
	}

	public function add_menu() {
		add_submenu_page(
			'edit.php?post_type=viwec_template',
			__( 'Settings', 'viwec-email-template-customizer' ),
			__( 'Settings', 'viwec-email-template-customizer' ),
			'manage_options',
			'viwec-auto-update', [ $this, 'auto_update' ] );
	}

	public function auto_update() {
		do_action( 'villatheme_auto_update' );
	}

        public function check_update() {
                /*
                 * Disable outbound requests to the VillaTheme update endpoint.
                 * The original implementation instantiated VillaTheme_Plugin_Check_Update
                 * and VillaTheme_Plugin_Updater, which triggered remote calls to check
                 * for new versions. Leaving the method empty prevents those network
                 * requests while keeping the action hook intact for backwards compatibility.
                 */
                return;
        }

}

Init::init();

