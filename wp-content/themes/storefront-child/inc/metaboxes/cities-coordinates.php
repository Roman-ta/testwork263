<?php
/**
 * Добавление метабокса с координатами города
 */
function add_cities_meta_boxes()
{
    add_meta_box(
        'cities_coordinates',
        'Координаты города',
        'cities_coordinates_callback',
        'cities',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_cities_meta_boxes');

/**
 * @param $post
 * @return void
 */
function cities_coordinates_callback($post)
{
    wp_nonce_field('cities_coordinates_nonce', 'cities_coordinates_nonce');

    $latitude = get_post_meta($post->ID, '_cities_latitude', true);
    $longitude = get_post_meta($post->ID, '_cities_longitude', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="cities_latitude">Широта (Latitude):</label></th>
            <td>
                <input type="number"
                       id="cities_latitude"
                       name="cities_latitude"
                       value="<?php echo esc_attr($latitude); ?>"
                       step="any"
                       style="width: 100%;"/>
                <p class="description">Например: 46.30090</p>
            </td>
        </tr>
        <tr>
            <th><label for="cities_longitude">Долгота (Longitude):</label></th>
            <td>
                <input type="number"
                       id="cities_longitude"
                       name="cities_longitude"
                       value="<?php echo esc_attr($longitude); ?>"
                       step="any"
                       style="width: 100%;"/>
                <p class="description">Например: 29.23010</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * @param $post_id
 * @return void
 */
function save_cities_coordinates($post_id)
{
    if (!isset($_POST['cities_coordinates_nonce']) ||
        !wp_verify_nonce($_POST['cities_coordinates_nonce'], 'cities_coordinates_nonce')) {
        return;
    }

    // root user
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // !autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // type
    if (get_post_type($post_id) != 'cities') {
        return;
    }

    // save lat
    if (isset($_POST['cities_latitude'])) {
        $latitude = sanitize_text_field($_POST['cities_latitude']);
        update_post_meta($post_id, '_cities_latitude', $latitude);
    }

    // save lon
    if (isset($_POST['cities_longitude'])) {
        $longitude = sanitize_text_field($_POST['cities_longitude']);
        update_post_meta($post_id, '_cities_longitude', $longitude);
    }
}

add_action('save_post', 'save_cities_coordinates');
