<?php

abstract class Hreflang_Meta_Box_Helper {

	/**
	 * Set up and add the meta box.
	 */
	public static function add_meta() {
		$screens = [ 'page' ];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'hreflang_box_id',
				'Page `hreflang` Attribute',
				[ self::class, 'render_html' ],
				$screen
			);
		}
	}

	/**
	 * Save the meta box selections.
	 *
	 * @param int $post_id  The post ID.
	 */
	public static function save_meta( $post_id ) {
		if ( array_key_exists( 'hreflang_field', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_hreflang',
				$_POST['hreflang_field']
			);
		}
	}


	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public static function render_html( $post ) {
		$value = get_post_meta( $post->ID, '_hreflang', true );
		?>

		<label for="hreflang_field">Choose the language for this page:</label>

		<select name="hreflang_field" id="hreflang_field" class="postbox" required>
			<option value="">Please select one...</option>
			<option value="en-US" <?php selected( $value, 'en-US' ); ?>>English (en-US)</option>
			<option value="en-GB" <?php selected( $value, 'en-GB' ); ?>>English (en-GB)</option>
			<option value="en-IN" <?php selected( $value, 'en-IN' ); ?>>English (en-IN)</option>
		</select>

		<?php
	}

}

add_action( 'add_meta_boxes', [ 'Hreflang_Meta_Box_Helper', 'add_meta' ] );
add_action( 'save_post_page', [ 'Hreflang_Meta_Box_Helper', 'save_meta' ] );
