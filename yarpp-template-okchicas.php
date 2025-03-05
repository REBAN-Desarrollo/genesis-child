<?php
/*
YARPP Template: OkChicas
Description: Requires a theme which supports post thumbnails
Author: Saad Sarfraz
*/

/**
 * Function to resize images to a specific size. If the resized image doesn't already exist,
 * it attempts to resize and save the new image on the server.
 *
 * @param int|null $attach_id Attachment ID of the image.
 * @param string|null $img_url URL of the image.
 * @param int $width Desired width of the resized image.
 * @param int $height Desired height of the resized image.
 * @param bool $crop Whether to crop the image to the specified dimensions.
 * @return array|null Resized image details (url, width, height) or null on failure.
 */
function my_resize($attach_id = null, $img_url = null, $width, $height, $crop = false) {
    $width = intval($width);
    $height = intval($height);

    // If we have an attachment ID, get the full image src and file path
    if ($attach_id) {
        $image_src = wp_get_attachment_image_src($attach_id, 'full');
        $file_path = get_attached_file($attach_id);
    } 
    // Otherwise, if we have an image URL, determine the file path from the URL
    elseif ($img_url) {
        $uploads_dir = wp_upload_dir();
        $file_path = str_replace($uploads_dir['baseurl'], $uploads_dir['basedir'], $img_url);
        $image_src = [$img_url, getimagesize($file_path)[0], getimagesize($file_path)[1]];
    } 
    // If neither is provided, return null
    else {
        return null;
    }

    // Construct the path without extension and the extension itself
    $no_ext_path = pathinfo($file_path, PATHINFO_DIRNAME) . '/' . pathinfo($file_path, PATHINFO_FILENAME);
    $extension = '.' . pathinfo($file_path, PATHINFO_EXTENSION);

    // Check if the cropped image already exists
    $cropped_img_path = "{$no_ext_path}-{$width}x{$height}{$extension}";
    if (file_exists($cropped_img_path)) {
        $cropped_img_url = str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
        return ['url' => $cropped_img_url, 'width' => $width, 'height' => $height];
    }

    // Resize the image if it doesn't already exist
    $image = wp_get_image_editor($file_path);
    if (!is_wp_error($image)) {
        $image->resize($width, $height, $crop);
        $save_data = $image->save();
        return [
            'url' => str_replace(basename($image_src[0]), basename($save_data['path']), $image_src[0]),
            'width' => $save_data['width'],
            'height' => $save_data['height']
        ];
    }

    // If the resize fails, return the original image dimensions
    return ['url' => $image_src[0], 'width' => $image_src[1], 'height' => $image_src[2]];
}

/**
 * Trims a string to a specified length and adds ellipsis if the string is longer than the limit.
 *
 * @param string $string The string to trim.
 * @param int $trimLength The maximum length of the string.
 * @return string The trimmed string.
 */
function string_trim($string, $trimLength = 40) {
    return mb_strlen($string) > $trimLength ? mb_substr($string, 0, $trimLength - 3) . '...' : $string;
}
?>

<div style="text-align:center"><h3 class="trending-title">Recomendados</h3></div>
<div class="yarpp-grids">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (has_post_thumbnail()) : ?>
				<a href="<?php the_permalink(); ?>" class="yarpp-thumbnail" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php $image = my_resize(get_post_thumbnail_id(), '', 360, 188, true); if ($image): ?><img src="<?php echo esc_url($image['url']); ?>" width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" /><?php endif; ?><div class="desc"><span><?php echo esc_html(string_trim(get_the_title(), 100)); ?></span></div></a>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No hay artículos relacionados.</p>
    <?php endif; ?>
</div>