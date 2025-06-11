<?php
error_log('Functions.php загружен!');
add_action('wp_footer', function () {
    echo '<!-- Functions.php работает -->';
});
require 'weather.php';
function storefront_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');

function register_cities_post_type()
{
    $labels = [
        'name' => 'Города',
        'singular_name' => 'Город',
        'menu_name' => 'Города',
        'add_new' => 'Добавить новый',
        'add_new_item' => 'Добавить новый город',
        'edit_item' => 'Редактировать город',
        'new_item' => 'Новый город',
        'view_item' => 'Посмотреть город',
        'search_items' => 'Найти города',
        'not_found' => 'Городов не найдено',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'supports' => ['title', 'editor', 'thumbnail'],
    ];

    register_post_type('cities', $args);
}

add_action('init', 'register_cities_post_type');

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

    // Проверяем права пользователя
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Проверяем, что это не автосохранение
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Проверяем тип записи
    if (get_post_type($post_id) != 'cities') {
        return;
    }

    // Сохраняем широту
    if (isset($_POST['cities_latitude'])) {
        $latitude = sanitize_text_field($_POST['cities_latitude']);
        update_post_meta($post_id, '_cities_latitude', $latitude);
    }

    // Сохраняем долготу
    if (isset($_POST['cities_longitude'])) {
        $longitude = sanitize_text_field($_POST['cities_longitude']);
        update_post_meta($post_id, '_cities_longitude', $longitude);
    }
}

add_action('save_post', 'save_cities_coordinates');

/**
 * @return void
 * Регистрация пользовательской таксономии "Countries"
 */
function register_countries_taxonomy()
{
    $labels = [
        'name' => 'Страны',
        'singular_name' => 'Страна',
        'search_items' => 'Найти страны',
        'all_items' => 'Все страны',
        'parent_item' => 'Родительская страна',
        'parent_item_colon' => 'Родительская страна:',
        'edit_item' => 'Редактировать страну',
        'update_item' => 'Обновить страну',
        'add_new_item' => 'Добавить новую страну',
        'new_item_name' => 'Название новой страны',
        'menu_name' => 'Страны',
    ];

    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'countries'],
    ];

    register_taxonomy('countries', ['cities'], $args);
}

add_action('init', 'register_countries_taxonomy');

/**
 * Виджет погоды для городов
 */
class Cities_Weather_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'cities_weather_widget',
            'Погода в городе',
            ['description' => 'Показывает погоду в выбранном городе']
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $title = !empty($instance['title']) ? $instance['title'] : 'Погода';
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];

        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

        if ($city_id) {
            $this->display_weather($city_id);
        } else {
            echo '<p>Город не выбран</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : 'Погода';
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

        $cities = get_posts([
            'post_type' => 'cities',
            'numberposts' => -1,
            'post_status' => 'publish',
        ]);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>">Выберите город:</label>
            <select class="widefat"
                    id="<?php echo $this->get_field_id('city_id'); ?>"
                    name="<?php echo $this->get_field_name('city_id'); ?>">
                <option value="">-- Выберите город --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city->ID; ?>"
                        <?php selected($city_id, $city->ID); ?>>
                        <?php echo $city->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? strip_tags($new_instance['city_id']) : '';
        return $instance;
    }

    private function display_weather($city_id)
    {
        $city_name = get_the_title($city_id);
        $latitude = get_post_meta($city_id, '_cities_latitude', true);
        $longitude = get_post_meta($city_id, '_cities_longitude', true);

        echo '<div class="weather-widget">';
        echo '<h4>' . esc_html($city_name) . '</h4>';

        if ($latitude && $longitude) {
            // Здесь должен быть реальный API-запрос к сервису погоды
            echo '<p>Температура: +15°C</p>';
            echo '<p>Погода: Ясно</p>';
            echo '<p><small>Координаты: ' . esc_html($latitude) . ', ' . esc_html($longitude) . '</small></p>';
        } else {
            echo '<p>Координаты не указаны</p>';
        }

        echo '</div>';
    }
}

// Регистрация виджета
function register_cities_weather_widget()
{
    register_widget('Cities_Weather_Widget');
}

add_action('widgets_init', 'register_cities_weather_widget');

// Добавим CSS для виджета
function cities_weather_widget_styles()
{
    ?>
    <style>
        .weather-widget {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .weather-widget h4 {
            margin-top: 0;
            color: #333;
        }

        .weather-widget p {
            margin: 5px 0;
        }

        .weather-widget small {
            color: #666;
        }
    </style>
    <?php
}

add_action('wp_head', 'cities_weather_widget_styles');

// Функция для отладки - проверяет, зарегистрирован ли виджет
function debug_widgets()
{
    global $wp_widget_factory;
    error_log('Registered widgets: ' . print_r(array_keys($wp_widget_factory->widgets), true));
}

add_action('init', 'debug_widgets', 999);