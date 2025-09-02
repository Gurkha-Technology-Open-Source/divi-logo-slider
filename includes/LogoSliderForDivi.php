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

    /**
     * Get admin managed logos for select options
     */
    public function get_admin_logos_options() {
        $logos = get_posts( array(
            'post_type'      => 'lsfd_logo',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ) );

        $options = array();
        foreach ( $logos as $logo ) {
            $options[ $logo->ID ] = $logo->post_title;
        }

        return $options;
    }

    public function get_fields() {
        return array(
            'logo_source' => array(
                'label'           => esc_html__( 'Logo Source', 'logo-slider-for-divi' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'admin'   => esc_html__( 'Use Admin Managed Logos', 'logo-slider-for-divi' ),
                    'custom'  => esc_html__( 'Add Custom Logos', 'logo-slider-for-divi' ),
                ),
                'default'         => 'admin',
                'affects'         => array( 'logos', 'selected_logos' ),
            ),
            'selected_logos' => array(
                'label'           => esc_html__( 'Select Logos', 'logo-slider-for-divi' ),
                'type'            => 'multiple_checkboxes',
                'option_category' => 'basic_option',
                'options'         => $this->get_admin_logos_options(),
                'depends_show_if' => 'admin',
            ),
            'logos' => array(
                'label'           => esc_html__( 'Custom Logos', 'logo-slider-for-divi' ),
                'type'            => 'composite',
                'option_category' => 'basic_option',
                'composite_type'  => 'add_new',
                'depends_show_if' => 'custom',
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
        $logo_source = $this->props['logo_source'];
        $selected_logos = $this->props['selected_logos'];
        $custom_logos = $this->props['logos'];
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

        // Get logos based on source
        if ( 'admin' === $logo_source && ! empty( $selected_logos ) ) {
            // Use admin-managed logos
            $logo_ids = explode( '|', $selected_logos );
            foreach ( $logo_ids as $logo_id ) {
                if ( empty( $logo_id ) ) continue;
                
                $logo_image = get_post_meta( $logo_id, 'logo_image', true );
                $logo_url   = get_post_meta( $logo_id, 'logo_url', true );
                $logo_alt   = get_post_meta( $logo_id, 'logo_alt', true );

                if ( $logo_image ) {
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
        } elseif ( 'custom' === $logo_source && $custom_logos ) {
            // Use custom logos from builder
            foreach ( $custom_logos as $logo ) {
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