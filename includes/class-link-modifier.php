<?php

abstract class Links_Modifier {

	/**
	 * Modifies the links to add `hreflang` attribute based on what the value of `_hreflang` is
	 *
	 * @param int $post_id  The post ID.
	 */
	public static function modify_links( $post_id, $post, $update ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! empty( $current_page_hreflang = get_post_meta( $post_id, '_hreflang', true ) ) ) {
			$page                = get_post( $post_id );
			$page_content        = $page->post_content;
			$regexp              = "<a\s[^>]*href=\"[^\"]*\"[^>]*>.*<\/a>";
			$invalid_link_regexp = "/<a (?![^>]*hreflang=\"{$current_page_hreflang}\").*?>/i";

			if ( preg_match_all( "/$regexp/siU", $page_content, $link_matches, PREG_SET_ORDER ) && ! empty( $link_matches ) ) {
				foreach ( $link_matches as $link_match ) {
					$link = reset( $link_match );

					/**
					 * Check for existing hreflang attributes and remove them.
					 * The existing hreflang attributes need to be removed before setting the new hreflang attribute to avoid duplicates like in the below example
					 * example: <a href="#" hreflang="en-US" hreflang="en-IN">link</a>
					 */

					preg_match_all('/[a-z]+=".+"/iU', $link, $link_attributes);

					if ( $link_attributes ) {
						$link_attributes = reset( $link_attributes );

						foreach ( $link_attributes as $attr ) {
							$attr_name = stristr($attr, '=', true);

							if ( $attr_name == 'hreflang' ) {
								$updated_link = str_replace( $attr, '', $link );
								$page_content = str_replace($link, $updated_link, $page_content);
								$link         = $updated_link;
							}
						}
					}

					/**
					 * Set the hreflang attribute based on what is selected in the Page `hreflang` Attribute meta box currently
					 */

					$is_invalid_link = preg_match( $invalid_link_regexp, $link, $invalid_parts );

					if ( $is_invalid_link ) {
						$invalid_part = reset( $invalid_parts );
						$updated_link = substr(preg_replace($invalid_part, rtrim($invalid_part, '>') . " hreflang=\"{$current_page_hreflang}\"", $link), 1);
						$page_content = str_replace($link, $updated_link, $page_content);
					}
				}

				remove_action( 'save_post_page', [ 'Links_Modifier', 'modify_links' ], 10, 3 );

				wp_update_post( array(
					'ID'           => $post_id,
					'post_content' => $page_content,
				) );

				add_action( 'save_post_page', [ 'Links_Modifier', 'modify_links' ], 10, 3 );
			}
		}
	}

}

add_action( 'save_post_page', [ 'Links_Modifier', 'modify_links' ], 10, 3 );
