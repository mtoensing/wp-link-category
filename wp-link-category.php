<?php

/*
Plugin Name: WP Link Category
Description: Displays a notice that verified accounts exists
GitHub Plugin URI: mtoensing/wp-verified-notice
Version:     1.9
Author:      MarcDK
Text Domain: wp-link-category
Domain Path: /language
Author URI:  https://marc.tv
License URI: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

function appendCategory( $content ) {

	$categories = get_the_category();
	$html       = '';

	$category   = get_the_category();
	$useCatLink = true;
// If post has a category assigned.
	if ( $category ) {
		$category_display = '';
		$category_link    = '';
		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			// Show the post's 'Primary' category, if this Yoast feature is available, & one is set
			$wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term               = get_term( $wpseo_primary_term );
			if ( is_wp_error( $term ) ) {
				// Default to first category (not Yoast) if an error is returned
				$category_display = $category[0]->name;
				$category_link    = get_category_link( $category[0]->term_id );
			} else {
				// Yoast Primary category
				$category_display = $term->name;
				$category_link    = get_category_link( $term->term_id );
			}
		} else {
			// Default, display the first category in WP's list of assigned categories
			$category_display = $category[0]->name;
			$category_link    = get_category_link( $category[0]->term_id );
		}
		// Display category
		$link = sprintf( '<a href="%1$s">' . __( 'More about the topic %2$s.', 'wp-link-category' ) . '</a>', $category_link, htmlspecialchars( $category_display ) );

		if ( ! empty( $category_display ) ) {
			if ( $useCatLink == true && ! empty( $category_link ) ) {
				$html .= '<span class="post-category">';
				$html .= '<p>' . $link . '</p>';
				$html .= '</span>';
			} else {
				$html .= '<span class="post-category"><p>' . $link . '</p></span>';
			}
		}
	}

	return $content . $html;
}

add_filter( 'the_content', 'appendCategory', 10 );
add_action( 'plugins_loaded', 'wan_load_textdomain' );


function wan_load_textdomain() {
	load_plugin_textdomain( 'wp-link-category', false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
}

?>

