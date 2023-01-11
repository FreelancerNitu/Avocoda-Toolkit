<?php 
/*
Plugin Name: Avocado Toolkit
Version: 1.0
Description: This plugin used for Avocode WordPress Theme.
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


final class Avocado_Elementor_Dependency {
	const VERSION = '1.0.0';
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const MINIMUM_PHP_VERSION = '5.6';
	private static $_instance = null;
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'init' ] );
	}
	public function init() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			esc_html__( '%1$s requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Theme', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			esc_html__( '%1$s requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Theme', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			esc_html__( '%1$s requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Avocado Theme', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function init_widgets() {

		require_once( __DIR__ . '/addons.php' );

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocaro_Slider_Widget() );
        
        if ( class_exists( 'WooCommerce' ) ) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocado_Categories_Widget() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocado_ProductCarousel_Widget() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocado_ProductHoverCard_Widget() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocado_AJAXProducts_Widget() );
						\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Avocado_StepCheckout_Widget() );
          
        }

	}
}

Avocado_Elementor_Dependency::instance();

function avocado_toolkit_scripts() {
	wp_enqueue_style( 'avacado-toolkit', plugin_dir_url( __FILE__ ) . '/assets/css/avocado-toolkit.css', array(), '20151215' );
	
	wp_enqueue_style( 'avacado-toolkit-responsive', plugin_dir_url( __FILE__ ) . '/assets/css/responsive.css', array(), '20151215' );
	
	wp_enqueue_style( 'slick', plugin_dir_url( __FILE__ ) . '/assets/css/slick.css', array(), '1.0.0' );

	wp_enqueue_script('slick', plugins_url('./assets/js/slick.min.js', __FILE__), array('jquery'), '1.0.0', true);
	
	wp_enqueue_script('bootstrap-tab', plugins_url('./assets/js/bootstrap.js', __FILE__), array('jquery'), '1.0.0', true);
}
add_action( 'wp_enqueue_scripts', 'avocado_toolkit_scripts' );


add_action('wp_ajax_my_ajax_action', 'my_ajax_function');
add_action('wp_ajax_nopriv_my_ajax_action', 'my_ajax_function');


function my_ajax_function() {

    
    
    if(wp_verify_nonce($_POST['nonce_get'], 'my_ajax_action')) {
        $q = new WP_Query( array(
            'posts_per_page' => $_POST['count'], 
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $_POST['cat_id'],
                )
            ),
        ));


        $html = '<div class="row">';

        $thumb_id = get_woocommerce_term_meta( $_POST['cat_id'], 'thumbnail_id', true );
        $term_img = wp_get_attachment_image_url(  $thumb_id, 'large' );

        if(!empty($thumb_id)) {
            $html .= '<div class="col-lg-6">
                <div class="f-cat-thumb" style="background-image:url('.$term_img.')"></div>
            </div>';
        }

        while($q->have_posts()) : $q->the_post();
            global $product;
            $html .= '<div class="col-lg-2">
                <div class="single-f-product">
                    <div class="single-f-product-bg" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')"></div>
                    <h4>'.get_the_title().'</h4>
                    <div class="c-product-price">'.$product->get_price_html().'</div>
                </div>
            </div>';
        endwhile; wp_reset_query();

        $html .= '</div>';

       
    } else {
        
        $html = '<div class="alert alert-danger">Error!</div>';
    }


    echo $html;

    die();
}