<?php 


class Avocaro_Slider_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'slider';
    }
    
	public function get_title() {
		return __( 'Slider', 'plugin-domain' );
	}

	public function get_icon() {
		return 'fa fa-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {


        
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
        );
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title', [
				'label' => __( 'Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Slide Title' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'content', [
				'label' => __( 'Content', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Slide Content' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'slide_btn_text', [
				'label' => __( 'Button text', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Read More' , 'plugin-domain' ),
			]
		);

		$repeater->add_control(
			'slide_link', [
				'label' => __( 'Button link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'slide_bg', [
				'label' => __( 'Slide Background', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);
        $this->add_control(
            'nav',
            [
                'label' => __( 'Enable navigation?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Enable autoplay?', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'Slide Title', 'plugin-domain' ),
						'slide_btn_text' => __( 'Read More', 'plugin-domain' ),
					]
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
        
        if($settings['nav'] == 'yes'){
            $arrows = 'true'; 
          }else{
            $arrows = 'false'; 
          }
                    
          if($settings['autoplay'] == 'yes'){
            $autoplay = 'true'; 
          }else{
            $autoplay = 'false'; 
          }

        if(!empty($settings['slides'])) {
            $html = '';
            $random = rand(8977,897987);
            if(count($settings['slides']) > 1) {
                $html .= '<script>
                    jQuery(document).ready(function($) {
                        $("#slide-'.$random.'").slick({
                            arrows: '.$arrows.',
                            autoplay: '.$autoplay.',
                            prevArrow: "<i class=\'fa fa-angle-left\'></i>",
                            nextArrow: "<i class=\'fa fa-angle-right\'></i>",
                        });
                    });
                </script>';
            }
            $html .= '<div class="slider-wrapper"><div id="slide-'.$random.'" class="slides">';
                foreach($settings['slides'] as $slide) {
                    $html .= '<div style="background-image:url('.wp_get_attachment_image_url($slide['slide_bg']['id'], 'large',).')" class="single-slide-item">
                        <div class="container">
                            <div class="row justify-content-center text-center">
                                <div class="col my-auto">
                                    <div class="slide-text">
										<h2>'.$slide['title'].'</h2>
										'.wpautop(do_shortcode($slide['content'])).'
                                        <a href="'.$slide['slide_link'].'" class="boxed-btn">'.$slide['slide_btn_text'].'</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            $html .= '</div><img class="slider-shape" src="'.get_template_directory_uri().'/assets/img/slider-bottom.png" alt=""/></div>';
        } else {
            $html = '<div class="alert alert-warning">Please add slides.</div>';
        }
        


        echo $html;

	}

}

if ( class_exists( 'WooCommerce' ) ) {

    function avocado_product_list( ) {

        $args = wp_parse_args( array(
            'post_type'   => 'product',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ) );
    
        $query_query = get_posts( $args );
    
        $dropdown_array = array();
        if ( $query_query ) {
            foreach ( $query_query as $query ) {
                $dropdown_array[ $query->ID ] = $query->post_title;
            }
        }
    
        return $dropdown_array;
    }


    function avocado_product_cat_list( ) {
        $elements = get_terms( 'product_cat', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }
    class Avocado_Categories_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'avocado-categories';
        }
        
        public function get_title() {
            return __( 'Avocado Cagegories', 'plugin-name' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'general' ];
        }

        protected function _register_controls() {


            
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );


            $this->add_control(
                'cat_ids',
                [
                    'label' => __( 'Select Categories', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_cat_list()
                ]
            );

            $this->add_control(
                'columns',
                [
                    'label' => __( 'Columns', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '4'  => __( '4 Columns', 'plugin-domain' ),
                        '3'  => __( '3 Columns', 'plugin-domain' ),
                        '2'  => __( '2 Columns', 'plugin-domain' ),
                        '1'  => __( '1 Columns', 'plugin-domain' ),
                    ],
                ]
            );

            $this->add_control(
                'bg',
                [
                    'label' => __( 'Image as background?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            if($settings['columns'] == '4') {
                $columns_markup = 'col-lg-3';
            } else if($settings['columns'] == '3') {
                $columns_markup = 'col-lg-4';
            } else if($settings['columns'] == '2') {
                $columns_markup = 'col-lg-6';
            } else {
                $columns_markup = 'col';
            }

            if(!empty($settings['cat_ids'])) {
                $html = '<div class="row">';
                foreach($settings['cat_ids'] as $cat) {
                    $thumb_id = get_woocommerce_term_meta( $cat, 'thumbnail_id', true );
                    $term_img = wp_get_attachment_image_url(  $thumb_id, 'medium' );
                    $info = get_term($cat, 'product_cat');
                    $html .= '<div class="'.$columns_markup.' single-category-item">';

                        if(!empty($thumb_id)) {
                            if($settings['bg'] == 'yes') {
                                $html .= '<div class="cat-img cat-img-bg" style="background-image:url('.$term_img.')"></div>';
                            } else {
                                $html .='
                                <div class="row cat-img">
                                    <div class="col text-center">
                                        <img src="'.$term_img.'" alt=""/>
                                    </div>
                                </div>';
                            }
                            
                        } else {
                            $html .= '<div class="cat-no-thumb"><p>No thumbnail</p></div>';
                        }
                        

                        $html .='

                        <h3>'.$info->name.'</h3>
                        '.$info->description.'
                    </div>';
                }
                $html .= '</div>';
            } else {
                $html = '<div class="alert alert-warning"><p>Please select categories.</p></div>';
            }

            echo $html;

        }

    }

    class Avocado_ProductCarousel_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'avocado-product-carousel';
        }
        
        public function get_title() {
            return __( 'Avocado ProducrCarousel', 'plugin-domain' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'general' ];
        }

        protected function _register_controls() {

            
            
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'from',
                [
                    'label' => __( 'Products from', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'select'  => __( 'Select Products', 'plugin-domain' ),
                        'category'  => __( 'Select Categories', 'plugin-domain' )
                    ],
                    'default' => 'select'
                ]
            );


            $this->add_control(
                'p_ids',
                [
                    'label' => __( 'And/Or Select products', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_list(),
                    'condition' => [
                        'from' => 'select',
                    ],
                ]
            );


            $this->add_control(
                'cat_ids',
                [
                    'label' => __( 'And/Or Categories', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_cat_list(),
                    'condition' => [
                        'from' => 'category',
                    ],
                ]
            );

            $this->add_control(
                'nav',
                [
                    'label' => __( 'Enable navigation?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes'
                ]
            );

            $this->add_control(
                'dots',
                [
                    'label' => __( 'Enable dots?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'autoplay',
                [
                    'label' => __( 'Enable autoplay?', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            if($settings['from'] == 'category') {
                $q = new WP_Query( array(
                    'posts_per_page' => 10, 
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $settings['cat_ids'],
                        )
                    ),
                ));
            } else {
                $q = new WP_Query( array(
                    'posts_per_page' => 10, 
                    'post_type' => 'product',
                    'post__in' => $settings['p_ids'],
                ));
            }


            $rand = rand(897987,9879877);
            
            if($settings['nav'] == 'yes'){
                $arrows = 'true'; 
              }else{
                $arrows = 'false'; 
              }
              
              if($settings['dots'] == 'yes'){
                $dots = 'true'; 
              }else{
                $dots = 'false'; 
              }
              
              if($settings['autoplay'] == 'yes'){
                $autoplay = 'true'; 
              }else{
                $autoplay = 'false'; 
              }
        
            $html = '
            <script>
            jQuery(document).ready(function($){
             $("#product-carousel-'.$rand.'").slick({
               arrows: '.$arrows.',
               dots: '.$dots.',
               autoplay: '.$autoplay.',
               prevArrow: "<i class=\'fa fa-angle-left\'></i>",
               nextArrow: "<i class=\'fa fa-angle-right\'></i>",
              });
             });
           </script>
            
            <div class="product-carousel" id="product-carousel-'.$rand.'">';
                while($q->have_posts()) : $q->the_post();
                global $product;
                    $html .= '<div class="single-c-product">
                        <div class="row">
                            <div class="col my-auto">
                             <div class="product-thumnb-c-inner">
                                <div class="product-thumnb-c" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')">';
                                
                                if($product->is_on_sale()){
                                   $html .= '<span class="c-product-sale">Sale</span>'; 
                                }
                                $html .='
                                </div>
                             </div>
                            </div>

                            <div class="col my-auto text-center">
                                <div class="c-product-info">
                                    <h3>'.get_the_title().'</h3>
                                    <div class="c-product-price">'.$product->get_price_html().'</div>';
                                    
                                    if($average = $product->get_average_rating()) {
                                        $html .='<div class="c-product_starRating"><div class="star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div></div>';
                                    }

                                    
                                    $html .='
                                    
                                    <div class="product-add-to-cart-c">'.do_shortcode('[add_to_cart style="" show_price="FALSE" id="'.get_the_ID().'"]').'</div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>';
                endwhile; wp_reset_query();


                $html .= '</div>';

            if($settings['from'] == 'category' && empty($settings['cat_ids'])) {
                $html = '<div class="alert alert-warning"><p>Please select product category</p></div>';  
            } 
            

            echo $html;

        }

    }

    class Avocado_ProductHoverCard_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'avocado-product-hovercard';
        }
        
        public function get_title() {
            return __( 'Avocado ProducrCard', 'plugin-domain' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'general' ];
        }

        protected function _register_controls() {

            
            
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'from',
                [
                    'label' => __( 'Products from', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'select'  => __( 'Select Products', 'plugin-domain' ),
                        'category'  => __( 'Select Categories', 'plugin-domain' )
                    ],
                    'default' => 'select'
                ]
            );


            $this->add_control(
                'p_ids',
                [
                    'label' => __( 'And/Or Select products', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_list(),
                    'condition' => [
                        'from' => 'select',
                    ],
                ]
            );


            $this->add_control(
                'cat_ids',
                [
                    'label' => __( 'And/Or Categories', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_cat_list(),
                    'condition' => [
                        'from' => 'category',
                    ],
                ]
            );

            $this->add_control(
                'count',
                [
                    'label' => __( 'Count', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '6'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            if($settings['from'] == 'category') {
                $q = new WP_Query( array(
                    'posts_per_page' => $settings['count'], 
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $settings['cat_ids'],
                        )
                    ),
                ));
            } else {
                $q = new WP_Query( array(
                    'posts_per_page' => $settings['count'], 
                    'post_type' => 'product',
                    'post__in' => $settings['p_ids'],
                ));
            }


            $html = 
            '<div class="product-hovercard">';
                while($q->have_posts()) : $q->the_post();
                global $product;
                
                $html .= '<div class="single-hc-product">
                  <div class="hc-product-base">
                    '.get_the_post_thumbnail(get_the_ID(), 'thumbnail').'
                    <span>
                    <i class="fa fa-angle-down"></i>
                    </span>
                  </div>
                  
                  <div class="product-hovercard-info">
                    <div class="product-thumb-hc" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')"></div>
                    <h4>'.get_the_title().'</h4>
                    <div class="c-product-price">'.$product->get_price_html().'</div>
                    <div class="product-add-to-cart-c">'.do_shortcode('[add_to_cart style="" show_price="FALSE" id="'.get_the_ID().'"]').'</div>
                  </div>
                  
                </div>';
                endwhile; wp_reset_query();


                $html .= '</div>';

            if($settings['from'] == 'category' && empty($settings['cat_ids'])) {
                $html = '<div class="alert alert-warning"><p>Please select product category</p></div>';  
            } 

            echo $html;

        }

    }
    
    class Avocado_AJAXProducts_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'avocado-ajax-products';
        }
        
        public function get_title() {
            return __( 'Avocado AJAX Tab', 'ppm-quickstart' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'general' ];
        }

        protected function _register_controls() {

            
            
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );



            $this->add_control(
                'title',
                [
                    'label' => __( 'Title', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Featured',
                ]
            );

            $this->add_control(
                'cat_ids',
                [
                    'label' => __( 'Categories', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_cat_list(),
                    
                ]
            );

            $this->add_control(
                'count',
                [
                    'label' => __( 'Products Count', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '9'
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {

            $settings = $this->get_settings_for_display();

            

            $html = '
            <script>
                jQuery(document).ready(function($) {
                    $(".featured-category-wrapper ul li button").on("click", function() {
                        $(".featured-category-wrapper ul li button").removeClass("active");
                        $(this).addClass("active");
                        var cat_id = $(this).attr("data-id");
                        var count = $(this).attr("data-count");
                        var nonce = $(this).attr("data-nonce");
                        $.ajax({
                            url: "'.admin_url('admin-ajax.php').'",
                            type: "POST",
                            data: {
                                action: "my_ajax_action",
                                cat_id: cat_id,
                                count: count,
                                nonce_get: nonce
                            },
                            beforeSend: function() {
                                $(".featured-cat-products").empty();
                                $(".featured-cat-products").append(\'<div class="loading-bar"><i class="fa fa-spin fa-cog"></i> Loading</div>\');
                            },
                            success: function(html) {
                                $(".featured-cat-products").empty();
                                $(".featured-cat-products").append(html);
                            }
                        });
                    });
                });
            </script>
            <div class="featured-category-wrapper"><h3>'.$settings['title'].'</h3>';
            if(!empty($settings['cat_ids'])) {
                $html .= '<ul>';
                $i = 0;
                foreach($settings['cat_ids'] as $cat) {
                    $i++;
                    if($i == 1) {
                        $ac_class = 'active';
                    } else {
                        $ac_class = '';
                    }
                    $cat_info = get_term($cat, 'product_cat');
                    $html .= '<li><button data-count="'.$settings['count'].'" class="'.$ac_class.'" data-nonce="'.wp_create_nonce('my_ajax_action').'"  data-id="'.$cat_info->term_id.'">'.$cat_info->name.'</button></li>';
                }
                $html .= '</ul>';


                $html .= '<div class="featured-cat-products">';
                    $q = new WP_Query( array(
                        'posts_per_page' => $settings['count'], 
                        'post_type' => 'product',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $settings['cat_ids'][0],
                            )
                        ),
                    ));

                    $html .= '<div class="row">';

                    $thumb_id = get_woocommerce_term_meta( $settings['cat_ids'][0], 'thumbnail_id', true );
                    $term_img = wp_get_attachment_image_url(  $thumb_id, 'large' );
                   

                    if(!empty($thumb_id)) {
                        $html .= '<div class="col-lg-6">
                            <div class="f-cat-thumb" style="background-image:url('.$term_img.')"></div>
                        </div>';
                    }

                    while($q->have_posts()) : $q->the_post();
                    global $product;
                    
                    $products = new WP_Query($args);
                    foreach ($products as $prod){
                        $link = get_the_permalink($prod->ID);   
                    }

                        $html .= '<div class="col-lg-2">
                            <div class="single-f-product">
                            <a href="'.$link.'" class=""><div class="single-f-product-bg" style="background-image:url('.get_the_post_thumbnail_url(get_the_ID(), 'medium').')"></div></a>
                          <a href="'.$link.'" class="f-product-title"><h4>'.get_the_title().'</h4></a>
                            <div class="c-product-price">'.$product->get_price_html().'</div>
                            </div>
                        </div>';
                    endwhile; wp_reset_query();
                    $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';

            if(empty($settings['cat_ids'])) {
                $html = '<div class="alert alert-warning"><p>Please select product category</p></div>';  
            } 
            

            echo $html;

        }

    }
    
    class Avocado_StepCheckout_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'stepcheckout';
        }
        
        public function get_title() {
            return __( 'Avocado StepCheckOut', 'plugin-domain' );
        }

        public function get_icon() {
            return 'fa fa-code';
        }

        public function get_categories() {
            return [ 'general' ];
        }

        protected function _register_controls() {


            
            $this->start_controls_section(
                'step-one-configuration',
                [
                    'label' => __( 'Step One Configuration', 'plugin-name' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'top_text',
                [
                    'label' => __( 'Top Text', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'default' => 'Select Your Starter Kit',
                ]
            );
             
            $this->add_control(
                'base_products',
                [
                    'label' => __( 'Select Base Products', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => avocado_product_list(),
                ]
            );

        $this->end_controls_section();
        
        // Step 2
        $this->start_controls_section(
            'step_two_configuratino',
            [
                'label' => __( 'Step Two Configuration', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'step_two_title',
            [
                'label' => __( 'Step Two Title', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Choose Candle Holders, Vases, & Pillows',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'step_two_content',
            [
                'label' => __( 'Step Two Content', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Summer Elevated Kit $199.00',
                'label_block' => true,
            ]
        );
        $this->add_control(
            'step_two_img',
            [
                'label' => __( 'Step Two Image', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );
        
       $repeater = new \Elementor\Repeater();
       
        $repeater->add_control(
            'title', [
               'label' => __( 'Title', 'plugin-domain' ),
               'type' => \Elementor\Controls_Manager::TEXT,
               'default' => __( 'Box Title' , 'plugin-domain' ),
               'label_block' => true,
            ]
        );

        $repeater->add_control(
            'box_product_ids',
            [
               'label' => __( 'Select Box Prodcts', 'plugin-domain' ),
               'type' => \Elementor\Controls_Manager::SELECT2,
               'multiple' => true,
               'options' => avocado_product_list(),
               'label_block' => true,
            ]
        );

        $this->add_control(
            'boxes',
            [
              'label' => __( 'Product Boxes', 'plugin-domain' ),
              'type' => \Elementor\Controls_Manager::REPEATER,
              'fields' => $repeater->get_controls(),
              'default' => [
                 [
                    'title' => __( 'Box Title', 'plugin-domain' ),
                 ]
               ],
              'title_field' => '{{{ title }}}',
            ]
        );
   
        
        $this->end_controls_section();
        
        // Step 3
        $this->start_controls_section(
            'step_three_configuratino',
            [
                'label' => __( 'Step Three Configuration', 'plugin-name' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
         $this->add_control(
            'step-three-title',
            [
                'label' => __( 'Step Three Title', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Choose Add-Ons',
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'step_three_products',
            [
                'label' => __( 'Select Step Three Products', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => avocado_product_list(),
            ]
        );
        
        $this->end_controls_section();


        }

        protected function render() {

            $settings = $this->get_settings_for_display();

         

    echo $html;

        }

    }


    
    
}