<?php
/**
 * Plugin Name: Footer Reveal
 * Description: Easily add the famous footer reveal effect to your wordpress. Based on footer-reveal.js https://iainandrew.github.io/footer-reveal/
 * Author: Webévasion
 * Version: 1.1.0
 * Author URI: https://webevasion.net/
 *
 * Text Domain: we-footer-reveal
 */

/*
===================================
== ╦ ╦┌─┐┌┐ ┌─┐┬  ┬┌─┐┌─┐┬┌─┐┌┐┌ ==
== ║║║├┤ ├┴┐├┤ └┐┌┘├─┤└─┐││ ││││ ==
== ╚╩╝└─┘└─┘└─┘ └┘ ┴ ┴└─┘┴└─┘┘└┘ ==
===================================
====== 2019 - webevasion.net ======
===================================
=== Freelance Expert WordPress ====
===================================
===== codeur.com/-webevasion ======
===================================

===================================
=== Footer Reveal for WordPress ===
===================================
*/



class WeFooterReveal {
	public function __construct() {
		add_action('wp_enqueue_scripts', [$this, 'wefr_enqueue_scripts']);
		add_action('customize_register', array($this, 'wefr_customize_register'), 20);
	}

	public function wefr_enqueue_scripts() {
		wp_enqueue_script('footer-reveal', plugins_url('assets/js/footer-reveal.js', __FILE__), array(), '1.0.0', true);
		wp_enqueue_script('we-footer-reveal', plugins_url('assets/js/we-footer-reveal.js', __FILE__), array(), '1.0.0', true);

		//Inject to footer-reveal.js
		$try_to_fix_opacity_color = get_theme_mod('try_to_fix_opacity_color');
		$try_to_fix_opacity_color = (!empty($try_to_fix_opacity_color) && !is_bool($try_to_fix_opacity_color) ? $try_to_fix_opacity_color : '#fffff');
		wp_localize_script('footer-reveal', 'try_to_fix_opacity_color', $try_to_fix_opacity_color);
		$try_to_fix_opacity = get_theme_mod('try_to_fix_opacity');
		wp_localize_script('footer-reveal', 'try_to_fix_opacity', ($try_to_fix_opacity == true ? 'true' : 'false'));

		//Inject to plugin js
		$we_footer_reveal_selector = get_theme_mod('we_footer_reveal_selector');
		$we_footer_reveal_selector = (empty($we_footer_reveal_selector) ? 'footer, .footer, #footer' : $we_footer_reveal_selector);
		wp_localize_script('we-footer-reveal', 'we_footer_reveal_selector', $we_footer_reveal_selector);
		$elementor_indicator = __('Footer Reveal active on this element', 'we-footer-reveal');
		wp_localize_script('we-footer-reveal', 'we_footer_reveal_elementor_indicator', $elementor_indicator);
	}

	public function wefr_customize_register($wp_customize) {
		$wp_customize->add_section('we_footer_reveal', array(
			'title' => __('Footer Reveal Options', 'we-footer-reveal'),
			'priority' => 10,
			'capability' => 'edit_theme_options',
		));

		//Selector
		$wp_customize->add_setting(
			'we_footer_reveal_selector' , array(
				'default'	=> '',
				'transport'	=> 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize, 'we_footer_reveal_selector', array(
					'label'		=> __('Footer Reveal selector', 'we-footer-reveal'),
					'section'	=> 'we_footer_reveal',
					'settings'	=> 'we_footer_reveal_selector',
				)
			)
		);

		/* Auto add featured image */
		$wp_customize->add_setting('try_to_fix_opacity', array(
			'default'           => false,
			'sanitize_callback' => array($this, 'sanitize_checkbox')
		));
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize, 'try_to_fix_opacity', array(
					'label'       => esc_html__('Try to fix opacity issue', 'we-footer-reveal'),
					'description' => esc_html__('If you see the footer behind the content, it will try to add a background color to fix that.', 'we-footer-reveal'),
					'section'     => 'we_footer_reveal',
					'settings'    => 'try_to_fix_opacity',
					'type'        => 'checkbox',
					'priority'    => 10
				)
			)
		);


		//Color of fix opacity
		$wp_customize->add_setting('try_to_fix_opacity_color', array(
			'default'           => '#ffffff'
		));
		$wp_customize->add_control( 
			new WP_Customize_Color_Control( 
			$wp_customize, 
			'try_to_fix_opacity_color', 
			array(
				'label'      => __( 'Color', 'mytheme' ),
				'section'    => 'we_footer_reveal',
				'settings'   => 'try_to_fix_opacity_color',
			) ) 
		);
	}
	
	public function sanitize_checkbox($checked) {
		return ((isset($checked) && true == $checked) ? true : false);
	}
}

$WeFooterReveal = new WeFooterReveal();