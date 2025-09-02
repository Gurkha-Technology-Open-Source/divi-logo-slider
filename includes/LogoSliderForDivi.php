<?php

class LogoSliderForDivi extends ET_Builder_Module {

    public $slug       = 'lsfd_logo_slider';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => 'https://github.com/aarjan/divi-logo-slider',
        'author'     => 'Arjan',
        'author_uri' => 'https://www.aariyan.com/',
    );

    public function init() {
        $this->name = esc_html__( 'Logo Slider', 'logo-slider-for-divi' );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'lsfd-styles', plugins_url( '../css/styles.css', __FILE__ ) );
        wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '1.0.0', true );
        wp_enqueue_script( 'lsfd-scripts', plugins_url( '../js/scripts.js', __FILE__ ), array( 'jquery', 'swiper-js' ), '1.0.0', true );
    }

    public function get_fields() {
        return array(
            'logos' => array(
                'label'           => esc_html__( 'Logos', 'logo-slider-for-divi' ),
                'type'            => 'composite',
                'option_category' => 'basic_option',
                'composite_type'  => 'add_new',
                'composite_structure' => array(
                    'logo_image' => array(
                        'label'              => esc_html__( 'Logo Image', 'logo-slider-for-divi' ),
                        'type'               => 'upload',
                        'option_category'    => 'basic_option',
                        'upload_button_text' => esc_attr__( 'Upload an image', 'logo-slider-for-divi' ),
                        'choose_text'        => esc_attr__( 'Choose an Image', 'logo-slider-for-divi' ),
                        'update_text'        => esc_attr__( 'Set As Image', 'logo-slider-for-divi' ),
                    ),
                    'logo_url' => array(
                        'label'           => esc_html__( 'Logo URL', 'logo-slider-for-divi' ),
                        'type'            => 'text',
                        'option_category' => 'basic_option',
                    ),
                    'logo_alt' => array(
                        'label'           => esc_html__( 'Logo Alt Text', 'logo-slider-for-divi' ),
                        'type'            => 'text',
                        'option_category' => 'basic_option',
                    ),
                ),
            ),
            'slides_per_view' => array(
                'label'           => esc_html__( 'Logos per View', 'logo-slider-for-divi' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'default'         => '5',
                'range_settings'  => array(
                    'min'  => '1',
                    'max'  => '10',
                    'step' => '1',
                ),
            ),
            'space_between' => array(
                'label'           => esc_html__( 'Space Between Logos', 'logo-slider-for-divi' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'default'         => '30',
                'range_settings'  => array(
                    'min'  => '0',
                    'max'  => '100',
                    'step' => '1',
                ),
            ),
            'slider_speed' => array(
                'label'           => esc_html__( 'Slider Speed', 'logo-slider-for-divi' ),
                'type'            => 'range',
                'option_category' => 'layout',
                'default'         => '500',
                'range_settings'  => array(
                    'min'  => '100',
                    'max'  => '2000',
                    'step' => '100',
                ),
            ),
            'autoplay' => array(
                'label'           => esc_html__( 'Autoplay', 'logo-slider-for-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'layout',
                'options'         => array(
                    'on'  => esc_html__( 'On', 'logo-slider-for-divi' ),
                    'off' => esc_html__( 'Off', 'logo-slider-for-divi' ),
                ),
                'default'         => 'on',
            ),
            'pause_on_hover' => array(
                'label'           => esc_html__( 'Pause on Hover', 'logo-slider-for-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'layout',
                'options'         => array(
                    'on'  => esc_html__( 'On', 'logo-slider-for-divi' ),
                    'off' => esc_html__( 'Off', 'logo-slider-for-divi' ),
                ),
                'default'         => 'on',
            ),
            'navigation_arrows' => array(
                'label'           => esc_html__( 'Navigation Arrows', 'logo-slider-for-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'layout',
                'options'         => array(
                    'on'  => esc_html__( 'On', 'logo-slider-for-divi' ),
                    'off' => esc_html__( 'Off', 'logo-slider-for-divi' ),
                ),
                'default'         => 'on',
            ),
            'pagination_dots' => array(
                'label'           => esc_html__( 'Pagination Dots', 'logo-slider-for-divi' ),
                'type'            => 'yes_no_button',
                'option_category' => 'layout',
                'options'         => array(
                    'on'  => esc_html__( 'On', 'logo-slider-for-divi' ),
                    'off' => esc_html__( 'Off', 'logo-slider-for-divi' ),
                ),
                'default'         => 'on',
            ),
        );
    }

    public function render( $attrs, $content = null, $render_slug ) {
        $logos = $this->props['logos'];
        $slides_per_view = $this->props['slides_per_view'];
        $space_between = $this->props['space_between'];
        $slider_speed = $this->props['slider_speed'];
        $autoplay = $this->props['autoplay'];
        $pause_on_hover = $this->props['pause_on_hover'];
        $navigation_arrows = $this->props['navigation_arrows'];
        $pagination_dots = $this->props['pagination_dots'];

        $data_attrs = sprintf(
            ' data-slides-per-view="%1$s" data-space-between="%2$s" data-slider-speed="%3$s" data-autoplay="%4$s" data-pause-on-hover="%5$s" data-navigation-arrows="%6$s" data-pagination-dots="%7$s"',
            esc_attr( $slides_per_view ),
            esc_attr( $space_between ),
            esc_attr( $slider_speed ),
            esc_attr( $autoplay ),
            esc_attr( $pause_on_hover ),
            esc_attr( $navigation_arrows ),
            esc_attr( $pagination_dots )
        );

        $output = '<div class="swiper-container lsfd-logo-slider"' . $data_attrs . '><div class="swiper-wrapper">';

        if ( $logos ) {
            foreach ( $logos as $logo ) {
                $logo_image = $logo['logo_image'];
                $logo_url   = $logo['logo_url'];
                $logo_alt   = $logo['logo_alt'];

                $output .= '<div class="swiper-slide">';
                if ( $logo_url ) {
                    $output .= sprintf( '<a href="%1$s" target="_blank">', esc_url( $logo_url ) );
                }

                $output .= sprintf( '<img src="%1$s" alt="%2$s" />', esc_url( $logo_image ), esc_attr( $logo_alt ) );

                if ( $logo_url ) {
                    $output .= '</a>';
                }
                $output .= '</div>';
            }
        }

        $output .= '</div>';

        if ( 'on' === $navigation_arrows ) {
            $output .= '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>';
        }

        if ( 'on' === $pagination_dots ) {
            $output .= '<div class="swiper-pagination"></div>';
        }

        $output .= '</div>';

        return $output;
    }
}

new LogoSliderForDivi;