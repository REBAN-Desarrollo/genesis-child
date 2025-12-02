<?php
/*
YARPP Template: OkChicas
Description: Requires a theme which supports post thumbnails
Author: Saad Sarfraz
*/

/**
 * Build image data for YARPP items using native attachment helpers.
 *
 * @param int $attachment_id Image attachment ID.
 * @return array|null
 */
function reban_yarpp_get_image_data( $attachment_id ) {
	$attachment_id = absint( $attachment_id );

	if ( ! $attachment_id ) {
		return null;
	}

	$size      = array( 360, 188 );
	$image_src = wp_get_attachment_image_src( $attachment_id, $size );

	if ( ! $image_src ) {
		$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
	}

	if ( ! $image_src ) {
		return null;
	}

	$srcset = wp_get_attachment_image_srcset( $attachment_id, $size );
	$sizes  = wp_get_attachment_image_sizes( $attachment_id, $size );

	return array(
		'url'    => $image_src[0],
		'width'  => isset( $image_src[1] ) ? (int) $image_src[1] : null,
		'height' => isset( $image_src[2] ) ? (int) $image_src[2] : null,
		'srcset' => $srcset ?: '',
		'sizes'  => $sizes ?: '(max-width: 600px) 100vw, 360px',
	);
}

/**
 * Trims a string to a specified length and adds ellipsis if the string is longer than the limit.
 *
 * @param string $string The string to trim.
 * @param int $trimLength The maximum length of the string.
 * @return string The trimmed string.
 */
function reban_yarpp_trim( $string, $trimLength = 40 ) {
	return mb_strlen( $string ) > $trimLength ? mb_substr( $string, 0, $trimLength - 3 ) . '...' : $string;
}
?>

<div style="text-align:center"><h3 class="trending-title">Recomendados</h3></div>
<div class="yarpp-grids">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) : ?>
                <?php
                $image       = reban_yarpp_get_image_data( get_post_thumbnail_id() );
                $title_attr  = the_title_attribute( array( 'echo' => false ) );
                $title_short = reban_yarpp_trim( get_the_title(), 100 );
                ?>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="yarpp-thumbnail" rel="bookmark" title="<?php echo esc_attr( $title_attr ); ?>">
                    <?php if ( $image ) : ?>
                        <img
                            src="<?php echo esc_url( $image['url'] ); ?>"
                            <?php if ( $image['srcset'] ) : ?>
                                srcset="<?php echo esc_attr( $image['srcset'] ); ?>"
                                sizes="<?php echo esc_attr( $image['sizes'] ); ?>"
                            <?php endif; ?>
                            <?php echo $image['width'] ? 'width="' . esc_attr( $image['width'] ) . '"' : ''; ?>
                            <?php echo $image['height'] ? 'height="' . esc_attr( $image['height'] ) . '"' : ''; ?>
                            alt="<?php echo esc_attr( $title_attr ); ?>"
                            loading="lazy"
                        />
                    <?php endif; ?>
                    <div class="desc"><span><?php echo esc_html( $title_short ); ?></span></div>
                </a>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No hay artículos relacionados.</p>
    <?php endif; ?>
</div>
