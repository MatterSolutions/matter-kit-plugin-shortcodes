<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Name: Matter Kit - Shortcodes
 * Plugin URI: http://www.mattersolutions.com.au
 * Description: Shortcodes that can be used within the Matter Kit framework.
 * Version: 0.9.0
 * Author: MatterSolutions
 * Author URI: http://www.mattersolutions.com.au
 * License: GPL2
 */



/* ---------------------------------------------------------
*	Output the head office phone number
 ---------------------------------------------------------*/
add_shortcode( 'mttr_phone_number', 'mttr_contact_phone_number_shortcode' );

if ( !function_exists( 'mttr_contact_phone_number_shortcode' ) ) {

	function mttr_contact_phone_number_shortcode( $atts ) {

		if ( function_exists( 'mttr_get_global_contact_phone_number' ) ) {

			$phone_number = mttr_get_global_contact_phone_number();

			$a = shortcode_atts( array(
				'tel' => '',
				'number' => $phone_number,
			), $atts );

			if ( $phone_number && $a['tel'] != '' ) {

				return mttr_filter_phone_number_tel_link( esc_attr( $a[ 'number' ] ) );

			}

			return esc_attr( $a[ 'number' ] );

		}

		return false;

	}

}




/* ---------------------------------------------------------
*	Output email address
 ---------------------------------------------------------*/
add_shortcode( 'mttr_email_address', 'mttr_contact_email_address_shortcode' );

if ( !function_exists( 'mttr_contact_email_address_shortcode' ) ) {

	function mttr_contact_email_address_shortcode( $atts ) {

		if ( function_exists( 'mttr_get_contact_email_address' ) ) {

			$email_address = mttr_get_contact_email_address();

			$a = shortcode_atts( array(
				'email' => $email_address,
			), $atts );

			if ( $email_address ) {

				return sanitize_email( $a[ 'email' ] );

			}

		}

		return false;

	}

}




/* ---------------------------------------------------------
*	Output fax number
 ---------------------------------------------------------*/
add_shortcode( 'mttr_fax_number', 'mttr_contact_fax_number_shortcode' );

if ( !function_exists( 'mttr_contact_fax_number_shortcode' ) ) {

	function mttr_contact_fax_number_shortcode( $atts ) {

		if ( function_exists( 'mttr_get_contact_fax_number' ) ) {

			$fax_number = mttr_get_contact_fax_number();

			$a = shortcode_atts( array(
				'number' => $fax_number,
				'tel' => '',
			), $atts );

			if ( $fax_number && $a['tel'] != '' ) {

				return mttr_filter_phone_number_tel_link( esc_attr( $a[ 'number' ] ) );

			}

			return esc_attr( $a[ 'number' ] );

		}

		return false;

	}

}




/* ---------------------------------------------------------
*	Output physical address
 ---------------------------------------------------------*/
add_shortcode( 'mttr_address', 'mttr_contact_physical_address_shortcode' );

if ( !function_exists( 'mttr_contact_physical_address_shortcode' ) ) {

	function mttr_contact_physical_address_shortcode( $atts ) {

		if ( function_exists( 'mttr_get_contact_physical_address' ) ) {

			$address = mttr_get_contact_physical_address();

			$a = shortcode_atts( array(
				'address' => $address,
				'format' => '',
			), $atts );

			if ( $a[ 'address' ] ) {

				if ( $a['format'] == 'short' ) {

					return mttr_remove_line_break_formatting( esc_attr( $a[ 'address' ] ) );

				}

				return apply_filters( 'the_content', esc_attr( $a[ 'address' ] ) );

			}

		}

		return false;

	}

}





/* ---------------------------------------------------------
*	Output postal address
 ---------------------------------------------------------*/
add_shortcode( 'mttr_postal_address', 'mttr_contact_postal_address_shortcode' );

if ( !function_exists( 'mttr_contact_postal_address_shortcode' ) ) {

	function mttr_contact_postal_address_shortcode( $atts ) {

		if ( function_exists( 'mttr_get_contact_postal_address' ) ) {

			$address = mttr_get_contact_postal_address();

			$a = shortcode_atts( array(
				'address' => $address,
				'format' => '',
			), $atts );

			if ( $a[ 'address' ] ) {

				if ( $a['format'] == 'short' ) {

					return mttr_remove_line_break_formatting( esc_attr( $a[ 'address' ] ) );

				}

				return apply_filters( 'the_content', esc_attr( $a[ 'address' ] ) );

			}

		}

		return false;

	}

}






/* ---------------------------------------------------------
*	Add icons using the icon partial
 ---------------------------------------------------------*/
add_shortcode( 'mttr_icon', 'mttr_icon_shortcode' );

if ( !function_exists( 'mttr_icon_shortcode' ) ) {

	function mttr_icon_shortcode( $atts ) {

		$a = shortcode_atts( array(

			'icon' => '',
			'size' => '',
			'before' => '',
			'valign' => '',
			'after' => '',

		), $atts );

		// Build up modifier array
		if ( $a[ 'icon' ] != '' ) {

			$modifiers = array();

			// Size modifiers
			if ( $a[ 'size' ] != '' ) {

				$modifiers[] = '  o-icon--' . sanitize_html_class( $a[ 'size' ] );

			}

			// Before modifier
			if ( $a[ 'before' ] != '' ) {

				$modifiers[] = '  o-icon--before';

			}

			// Before modifier
			if ( $a[ 'valign' ] != '' ) {

				$modifiers[] = '  o-icon--' . sanitize_html_class( $a[ 'valign' ] );

			}

			// After modifier
			if ( $a[ 'after' ] != '' ) {

				$modifiers[] = '  o-icon--before';

			}

			// Implode modifiers
			$modifiers = implode( '  ', $modifiers );

			// Setup data
			$data = array(

				'icon' => esc_attr( $a[ 'icon' ] ) . '.svg',
				'modifiers' => $modifiers,

			);

			ob_start();

				if ( class_exists( 'Mttr_Component_Icon' ) ) {

					// Set up the icon
					$icon = new Mttr_Component_Icon();
					$icon->render_component( $data );

				}

				// Return the thing
				$return = ob_get_contents();

			ob_get_clean();

			return $return;

		} else {

			return false;

		}

	}

}





/* ---------------------------------------------------------
*	Add a shortcode to display a list of posts
 ---------------------------------------------------------*/
add_shortcode( 'mttr_posts', 'mttr_add_posts_shortcode' );

if ( !function_exists( 'mttr_add_posts_shortcode' ) ) {

	function mttr_add_posts_shortcode( $atts ) {

		$a = shortcode_atts( array(
			'id' => false,
			'style' => 'snippet',
			'type' => false,
			'category' => false,
			'count' => false,
		), $atts );

		$args = array();

		$args['post_status'] = 'publish';

		if ( $a['count'] ) {

			$args['posts_per_page'] = intval( $a['count'] );

		}

		if ( $a['type'] ) {

			$args['post_type'] = $a['type'];

		}

		if ( $a['type'] == 'post' ) {

			if ( $a['category'] ) {

				$args['cat'] = intval( $a['category'] );

			}

		} else {

			if ( $a['category'] ) {

				$args['tax_query'] = array(

					array(
						'taxonomy' => $args['post_type'] . '_category',
						'field'    => 'ID',
						'terms'    => $categories
					)

				);

			}

		}

		if ( $a['id'] ) {

			$args['post__in'] = explode( ',', $a['id'] );
			$args['orderby'] = 'post__in';
			$args['order'] = 'ASC';

		} else {

			// This applies ordering filters from the CPT plugins
			apply_filters( 'mttr_latest_posts_' . $a['type'], $args );

		}

		$q = new WP_Query( $args );
		$list = array();

		if ( $q->have_posts() ) {

			while ( $q->have_posts() ) {

				$q->the_post();
				$list[] = get_the_ID();

			}

		}

		wp_reset_postdata();

		// Output grid items
		$grid = new Mttr_Component_Grid();
		$data = $grid->get_data( $a['style'], $list );

		// Hide the wrapper
		$data['listing']['wrap'] = 'u-hard';

		ob_start();

		mttr_get_template( $grid::$component_template_location, $data );
		$return = ob_get_contents();

		ob_end_clean();

		return $return;

	}

}