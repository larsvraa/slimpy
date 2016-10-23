<?php
/**
 * Slimpy Theme Customizer
 *
 * @package slimpy
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function slimpy_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'slimpy_customize_register' );

/**
 * Options for Slimpy Theme Customizer.
 */
function slimpy_customizer( $wp_customize ) {

    /* Main option Settings Panel */
    $wp_customize->add_panel('slimpy_main_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Slimpy Options', 'slimpy'),
        'description' => __('Panel to update slimpy theme options', 'slimpy'), // Include html tags such as <p>.
        'priority' => 10 // Mixed with top-level-section hierarchy.
    ));

        // add "Content Options" section
        $wp_customize->add_section( 'slimpy_content_section' , array(
                'title'      => esc_html__( 'Content Options', 'slimpy' ),
                'priority'   => 50,
                'panel' => 'slimpy_main_options'
        ) );
            // add setting for excerpts/full posts toggle
            $wp_customize->add_setting( 'slimpy_excerpts', array(
                    'default'           => 1,
                    'sanitize_callback' => 'slimpy_sanitize_checkbox',
            ) );
            // add checkbox control for excerpts/full posts toggle
            $wp_customize->add_control( 'slimpy_excerpts', array(
                    'label'     => esc_html__( 'Show post excerpts?', 'slimpy' ),
                    'section'   => 'slimpy_content_section',
                    'priority'  => 10,
                    'type'      => 'checkbox'
            ) );

            $wp_customize->add_setting( 'slimpy_page_comments', array(
                    'default' => 1,
                    'sanitize_callback' => 'slimpy_sanitize_checkbox',
            ) );
            $wp_customize->add_control( 'slimpy_page_comments', array(
                    'label'		=> esc_html__( 'Display Comments on Static Pages?', 'slimpy' ),
                    'section'	=> 'slimpy_content_section',
                    'priority'	=> 20,
                    'type'      => 'checkbox',
            ) );

        /* Slimpy Main Options */
        $wp_customize->add_section('slimpy_slider_options', array(
            'title' => __('Slider options', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            $wp_customize->add_setting( 'slimpy[slimpy_slider_checkbox]', array(
                    'default' => 0,
                    'type' => 'option',
                    'sanitize_callback' => 'slimpy_sanitize_checkbox',
            ) );
            $wp_customize->add_control( 'slimpy[slimpy_slider_checkbox]', array(
                    'label'	=> esc_html__( 'Check if you want to enable slider', 'slimpy' ),
                    'section'	=> 'slimpy_slider_options',
                    'priority'	=> 5,
                    'type'      => 'checkbox',
            ) );

            // Pull all the categories into an array
            global $options_categories;
            $wp_customize->add_setting('slimpy[slimpy_slide_categories]', array(
                'default' => '',
                'type' => 'option',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'slimpy_sanitize_slidecat'
            ));
            $wp_customize->add_control('slimpy[slimpy_slide_categories]', array(
                'label' => __('Slider Category', 'slimpy'),
                'section' => 'slimpy_slider_options',
                'type'    => 'select',
                'description' => __('Select a category for the featured post slider', 'slimpy'),
                'choices'    => $options_categories
            ));

            $wp_customize->add_setting('slimpy[slimpy_slide_number]', array(
                'default' => 3,
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_number'
            ));
            $wp_customize->add_control('slimpy[slimpy_slide_number]', array(
                'label' => __('Number of slide items', 'slimpy'),
                'section' => 'slimpy_slider_options',
                'description' => __('Enter the number of slide items', 'slimpy'),
                'type' => 'text'
            ));

        $wp_customize->add_section('slimpy_layout_options', array(
            'title' => __('Layout options', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            // Layout options
            global $site_layout;
            $wp_customize->add_setting('slimpy[site_layout]', array(
                 'default' => 'side-pull-left',
                 'type' => 'option',
                 'sanitize_callback' => 'slimpy_sanitize_layout'
            ));
            $wp_customize->add_control('slimpy[site_layout]', array(
                 'label' => __('Website Layout Options', 'slimpy'),
                 'section' => 'slimpy_layout_options',
                 'type'    => 'select',
                 'description' => __('Choose between different layout options to be used as default', 'slimpy'),
                 'choices'    => $site_layout
            ));
            
            if ( class_exists( 'WooCommerce' ) ) {
                $wp_customize->add_setting('slimpy[woo_site_layout]', array(
                     'default' => 'full-width',
                     'type' => 'option',
                     'sanitize_callback' => 'slimpy_sanitize_layout'
                ));
                $wp_customize->add_control('slimpy[woo_site_layout]', array(
                     'label' => __('WooCommerce Page Layout Options', 'slimpy'),
                     'section' => 'slimpy_layout_options',
                     'type'    => 'select',
                     'description' => __('Choose between different layout options to be used as default for all woocommerce pages', 'slimpy'),
                     'choices'    => $site_layout
                ));
            }

            $wp_customize->add_setting('slimpy[element_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[element_color]', array(
                'label' => __('Element Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_layout_options',
                'settings' => 'slimpy[element_color]',
            )));

            $wp_customize->add_setting('slimpy[element_color_hover]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[element_color_hover]', array(
                'label' => __('Element color on hover', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_layout_options',
                'settings' => 'slimpy[element_color_hover]',
            )));

         /* Slimpy Action Options */
        $wp_customize->add_section('slimpy_action_options', array(
            'title' => __('Action Button', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            $wp_customize->add_setting('slimpy[w2f_cfa_text]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('slimpy[w2f_cfa_text]', array(
                'label' => __('Call For Action Text', 'slimpy'),
                'description' => sprintf(__('Enter the text for call for action section', 'slimpy')),
                'section' => 'slimpy_action_options',
                'type' => 'textarea'
            ));

            $wp_customize->add_setting('slimpy[w2f_cfa_button]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_nohtml'
            ));
            $wp_customize->add_control('slimpy[w2f_cfa_button]', array(
                'label' => __('Call For Action Button Title', 'slimpy'),
                'section' => 'slimpy_action_options',
                'description' => __('Enter the title for Call For Action button', 'slimpy'),
                'type' => 'text'
            ));

            $wp_customize->add_setting('slimpy[w2f_cfa_link]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control('slimpy[w2f_cfa_link]', array(
                'label' => __('CFA button link', 'slimpy'),
                'section' => 'slimpy_action_options',
                'description' => __('Enter the link for Call For Action button', 'slimpy'),
                'type' => 'text'
            ));

            $wp_customize->add_setting('slimpy[cfa_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[cfa_color]', array(
                'label' => __('Call For Action Text Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_action_options',
            )));
            $wp_customize->add_setting('slimpy[cfa_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[cfa_bg_color]', array(
                'label' => __('Call For Action Background Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_action_options',
            )));
            $wp_customize->add_setting('slimpy[cfa_btn_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[cfa_btn_color]', array(
                'label' => __('Call For Action Button Border Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_action_options',
            )));
            $wp_customize->add_setting('slimpy[cfa_btn_txt_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[cfa_btn_txt_color]', array(
                'label' => __('Call For Action Button Text Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_action_options',
            )));            
            $wp_customize->add_setting('slimpy[cfa_btn_back_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[cfa_btn_back_color]', array(
                'label' => __('Call For Action Button Background Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_action_options',
            )));

        /* Slimpy Typography Options */
        $wp_customize->add_section('slimpy_typography_options', array(
            'title' => __('Typography', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            // Typography Defaults
            $typography_defaults = array(
                    'size'  => '14px',
                    'face'  => 'Open Sans',
                    'style' => 'normal',
                    'color' => '#6B6B6B'
            );

            // Typography Options
            global $typography_options;
            $wp_customize->add_setting('slimpy[main_body_typography][size]', array(
                'default' => $typography_defaults['size'],
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_typo_size'
            ));
            $wp_customize->add_control('slimpy[main_body_typography][size]', array(
                'label' => __('Main Body Text', 'slimpy'),
                'description' => __('Used in p tags', 'slimpy'),
                'section' => 'slimpy_typography_options',
                'type'    => 'select',
                'choices'    => $typography_options['sizes']
            ));
            $wp_customize->add_setting('slimpy[main_body_typography][face]', array(
                'default' => $typography_defaults['face'],
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_typo_face'
            ));
            $wp_customize->add_control('slimpy[main_body_typography][face]', array(
                'section' => 'slimpy_typography_options',
                'type'    => 'select',
                'choices'    => $typography_options['faces']
            ));
            $wp_customize->add_setting('slimpy[main_body_typography][style]', array(
                'default' => $typography_defaults['style'],
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_typo_style'
            ));
            $wp_customize->add_control('slimpy[main_body_typography][style]', array(
                'section' => 'slimpy_typography_options',
                'type'    => 'select',
                'choices'    => $typography_options['styles']
            ));
            $wp_customize->add_setting('slimpy[main_body_typography][color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[main_body_typography][color]', array(
                'section' => 'slimpy_typography_options',
            )));

            $wp_customize->add_setting('slimpy[heading_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[heading_color]', array(
                'label' => __('Heading Color', 'slimpy'),
                'description'   => __('Color for all headings (h1-h6)','slimpy'),
                'section' => 'slimpy_typography_options',
            )));
            $wp_customize->add_setting('slimpy[link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[link_color]', array(
                'label' => __('Link Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_typography_options',
            )));
            $wp_customize->add_setting('slimpy[link_hover_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[link_hover_color]', array(
                'label' => __('Link:hover Color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_typography_options',
            )));

        /* Slimpy Header Options */
        $wp_customize->add_section('slimpy_header_options', array(
            'title' => __('Header', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
        
            $wp_customize->add_setting('slimpy[sticky_header]', array(
                'default' => 0,
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_checkbox'
            ));
            $wp_customize->add_control('slimpy[sticky_header]', array(
                'label' => __('Sticky Header', 'slimpy'),
                'description' => sprintf(__('Check to show fixed header', 'slimpy')),
                'section' => 'slimpy_header_options',
                'type' => 'checkbox',
            ));
            
            $wp_customize->add_setting('slimpy[nav_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_bg_color]', array(
                'label' => __('Top nav background color', 'slimpy'),
                'description'   => __('Default used if no color is selected','slimpy'),
                'section' => 'slimpy_header_options',
            )));
            $wp_customize->add_setting('slimpy[nav_link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_link_color]', array(
                'label' => __('Top nav item color', 'slimpy'),
                'description'   => __('Link color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

            $wp_customize->add_setting('slimpy[nav_item_hover_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_item_hover_color]', array(
                'label' => __('Top nav item hover color', 'slimpy'),
                'description'   => __('Link:hover color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

            $wp_customize->add_setting('slimpy[nav_dropdown_bg]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_dropdown_bg]', array(
                'label' => __('Top nav dropdown background color', 'slimpy'),
                'description'   => __('Background of dropdown item hover color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

            $wp_customize->add_setting('slimpy[nav_dropdown_item]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_dropdown_item]', array(
                'label' => __('Top nav dropdown item color', 'slimpy'),
                'description'   => __('Dropdown item color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

            $wp_customize->add_setting('slimpy[nav_dropdown_item_hover]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_dropdown_item_hover]', array(
                'label' => __('Top nav dropdown item hover color', 'slimpy'),
                'description'   => __('Dropdown item hover color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

            $wp_customize->add_setting('slimpy[nav_dropdown_bg_hover]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[nav_dropdown_bg_hover]', array(
                'label' => __('Top nav dropdown item background hover color', 'slimpy'),
                'description'   => __('Background of dropdown item hover color','slimpy'),
                'section' => 'slimpy_header_options',
            )));

        /* Slimpy Footer Options */
        $wp_customize->add_section('slimpy_footer_options', array(
            'title' => __('Footer', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            $wp_customize->add_setting('slimpy[footer_widget_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[footer_widget_bg_color]', array(
                'label' => __('Footer widget area background color', 'slimpy'),
                'section' => 'slimpy_footer_options',
            )));

            $wp_customize->add_setting('slimpy[footer_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[footer_bg_color]', array(
                'label' => __('Footer background color', 'slimpy'),
                'section' => 'slimpy_footer_options',
            )));

            $wp_customize->add_setting('slimpy[footer_text_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[footer_text_color]', array(
                'label' => __('Footer text color', 'slimpy'),
                'section' => 'slimpy_footer_options',
            )));

            $wp_customize->add_setting('slimpy[footer_link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[footer_link_color]', array(
                'label' => __('Footer link color', 'slimpy'),
                'section' => 'slimpy_footer_options',
            )));

            $wp_customize->add_setting('slimpy[custom_footer_text]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('slimpy[custom_footer_text]', array(
                'label' => __('Footer information', 'slimpy'),
                'description' => sprintf(__('Copyright text in footer', 'slimpy')),
                'section' => 'slimpy_footer_options',
                'type' => 'textarea'
            ));

        /* Slimpy Social Options */
        $wp_customize->add_section('slimpy_social_options', array(
            'title' => __('Social', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            $wp_customize->add_setting('slimpy[social_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[social_color]', array(
                'label' => __('Social icon color', 'slimpy'),
                'description' => sprintf(__('Default used if no color is selected', 'slimpy')),
                'section' => 'slimpy_social_options',
            )));

            $wp_customize->add_setting('slimpy[social_footer_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'slimpy_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'slimpy[social_footer_color]', array(
                'label' => __('Footer social icon color', 'slimpy'),
                'description' => sprintf(__('Default used if no color is selected', 'slimpy')),
                'section' => 'slimpy_social_options',
            )));

            $wp_customize->add_setting('slimpy[footer_social]', array(
                'default' => 0,
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_checkbox'
            ));
            $wp_customize->add_control('slimpy[footer_social]', array(
                'label' => __('Footer Social Icons', 'slimpy'),
                'description' => sprintf(__('Check to show social icons in footer', 'slimpy')),
                'section' => 'slimpy_social_options',
                'type' => 'checkbox',
            ));

        /* Slimpy Other Options */
        $wp_customize->add_section('slimpy_other_options', array(
            'title' => __('Other', 'slimpy'),
            'priority' => 31,
            'panel' => 'slimpy_main_options'
        ));
            $wp_customize->add_setting('slimpy[custom_css]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'slimpy_sanitize_textarea'
            ));
            $wp_customize->add_control('slimpy[custom_css]', array(
                'label' => __('Custom CSS', 'slimpy'),
                'description' => sprintf(__('Additional CSS', 'slimpy')),
                'section' => 'slimpy_other_options',
                'type' => 'textarea'
            ));

        $wp_customize->add_section('slimpy_important_links', array(
            'priority' => 5,
            'title' => __('Support and Documentation', 'slimpy')
        ));
            $wp_customize->add_setting('slimpy[imp_links]', array(
              'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control(
            new Slimpy_Important_Links(
            $wp_customize,
                'slimpy[imp_links]', array(
                'section' => 'slimpy_important_links',
                'type' => 'slimpy-important-links'
            )));

}
add_action( 'customize_register', 'slimpy_customizer' );



/**
 * Sanitzie checkbox for WordPress customizer
 */
function slimpy_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Adds sanitization callback function: colors
 * @package Slimpy
 */
function slimpy_sanitize_hexcolor($color) {
    if ($unhashed = sanitize_hex_color_no_hash($color))
        return '#' . $unhashed;
    return $color;
}

/**
 * Adds sanitization callback function: Nohtml
 * @package Slimpy
 */
function slimpy_sanitize_nohtml($input) {
    return wp_filter_nohtml_kses($input);
}

/**
 * Adds sanitization callback function: Number
 * @package Slimpy
 */
function slimpy_sanitize_number($input) {
    if ( isset( $input ) && is_numeric( $input ) ) {
        return $input;
    }
}

/**
 * Adds sanitization callback function: Strip Slashes
 * @package Slimpy
 */
function slimpy_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Adds sanitization callback function: Sanitize Text area
 * @package Slimpy
 */
function slimpy_sanitize_textarea($input) {
    return sanitize_text_field($input);
}

/**
 * Adds sanitization callback function: Slider Category
 * @package Slimpy
 */
function slimpy_sanitize_slidecat( $input ) {
    global $options_categories;
    if ( array_key_exists( $input, $options_categories ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Sidebar Layout
 * @package Slimpy
 */
function slimpy_sanitize_layout( $input ) {
    global $site_layout;
    if ( array_key_exists( $input, $site_layout ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Typography Size
 * @package Slimpy
 */
function slimpy_sanitize_typo_size( $input ) {
    global $typography_options, $typography_defaults;
    if ( array_key_exists( $input, $typography_options['sizes'] ) ) {
        return $input;
    } else {
        return $typography_defaults['size'];
    }
}
/**
 * Adds sanitization callback function: Typography Face
 * @package Slimpy
 */
function slimpy_sanitize_typo_face( $input ) {
    global $typography_options, $typography_defaults;
    if ( array_key_exists( $input, $typography_options['faces'] ) ) {
        return $input;
    } else {
        return $typography_defaults['face'];
    }
}
/**
 * Adds sanitization callback function: Typography Style
 * @package Slimpy
 */
function slimpy_sanitize_typo_style( $input ) {
    global $typography_options, $typography_defaults;
    if ( array_key_exists( $input, $typography_options['styles'] ) ) {
        return $input;
    } else {
        return $typography_defaults['style'];
    }
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function slimpy_customize_preview_js() {
	wp_enqueue_script( 'slimpy_customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), '20140317', true );
}
add_action( 'customize_preview_init', 'slimpy_customize_preview_js' );

/**
 * Add CSS for custom controls
 */
function slimpy_customizer_custom_control_css() {
	?>
    <style>
        #customize-control-slimpy-main_body_typography-size select, #customize-control-slimpy-main_body_typography-face select,#customize-control-slimpy-main_body_typography-style select { width: 60%; }
    </style><?php
}
add_action( 'customize_controls_print_styles', 'slimpy_customizer_custom_control_css' );

if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Class to create a Slimpy important links
 */
class Slimpy_Important_Links extends WP_Customize_Control {

   public $type = "slimpy-important-links";

   public function render_content() {?>
         <!-- Twitter -->
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

        <!-- Facebook -->
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=328285627269392";
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>

        <div class="inside">
            <div id="social-share">
              <div class="fb-like" data-href="<?php echo esc_url( 'https://www.facebook.com/tripwiremagazine' ); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
              <div class="tw-follow" ><a href="https://twitter.com/tripwiremag" class="twitter-follow-button" data-show-count="false">Follow @tripwiremag</a></div>
            </div>
            <p><b><a href="<?php echo esc_url( 'http://www.tripwiremagazine.com/wp/support/slimpy' ); ?>"><?php esc_html_e('Slimpy Documentation','slimpy'); ?></a></b></p>
            <p><?php _e('The best way to contact us with <b>support questions</b> and <b>bug reports</b> is via','slimpy') ?> <a href="<?php echo esc_url( 'http://www.tripwiremagazine.com/wp/forums' ); ?>"><?php esc_html_e('the support forum','slimpy') ?></a>.</p>
            <p><?php esc_html_e('If you like this theme, I\'d appreciate any of the following:','slimpy') ?></p>
            <ul>
              <li><a class="button" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/slimpy?filter=5' ); ?>" title="<?php esc_attr_e('Rate this Theme', 'slimpy'); ?>" target="_blank"><?php printf(esc_html__('Rate this Theme','slimpy')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://www.facebook.com/tripwiremagazine' ); ?>" title="Like Colorlib on Facebook" target="_blank"><?php printf(esc_html__('Like on Facebook','slimpy')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://twitter.com/tripwiremag/' ); ?>" title="Follow tripwiremag on Twitter" target="_blank"><?php printf(esc_html__('Follow on Twitter','slimpy')); ?></a></li>
            </ul>
        </div><?php
   }

}

/*
 * Custom Scripts
 */
add_action( 'customize_controls_print_footer_scripts', 'customizer_custom_scripts' );

function customizer_custom_scripts() { ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        /* This one shows/hides the an option when a checkbox is clicked. */
        jQuery('#customize-control-slimpy-slimpy_slide_categories, #customize-control-slimpy-slimpy_slide_number').hide();
        jQuery('#customize-control-slimpy-slimpy_slider_checkbox input').click(function() {
            jQuery('#customize-control-slimpy-slimpy_slide_categories, #customize-control-slimpy-slimpy_slide_number').fadeToggle(400);
        });

        if (jQuery('#customize-control-slimpy-slimpy_slider_checkbox input:checked').val() !== undefined) {
            jQuery('#customize-control-slimpy-slimpy_slide_categories, #customize-control-slimpy-slimpy_slide_number').show();
        }
    });
</script>
<style>
    li#accordion-section-slimpy_important_links h3.accordion-section-title, li#accordion-section-slimpy_important_links h3.accordion-section-title:focus { background-color: #00cc00 !important; color: #fff !important; }
    li#accordion-section-slimpy_important_links h3.accordion-section-title:hover { background-color: #00b200 !important; color: #fff !important; }
    li#accordion-section-slimpy_important_links h3.accordion-section-title:after { color: #fff !important; }
</style>
<?php
}