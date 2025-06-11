<?php
require_once get_stylesheet_directory() . '/inc/setup/theme-init.php';
require_once get_stylesheet_directory() . '/weather.php';
function storefront_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');


function storefront_child_enqueue_cities_weather_scripts()
{
    // Подключаем скрипт для AJAX поиска
    wp_enqueue_script('cities-weather-ajax', get_stylesheet_directory_uri() . '/js/cities-weather-ajax.js', ['jquery'], null, true);

    // Локализуем скрипт, чтобы передать URL AJAX и nonce (безопасность)
    wp_localize_script('cities-weather-ajax', 'citiesWeatherAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('cities_weather_nonce'),
    ]);
}

add_action('wp_enqueue_scripts', 'storefront_child_enqueue_cities_weather_scripts');

function ajax_get_cities_weather()
{
    // Проверяем nonce для безопасности
    check_ajax_referer('cities_weather_nonce', 'nonce');

    global $wpdb;

    // Получаем поисковый запрос, если есть
    $search = sanitize_text_field($_POST['search']) ?? '';

    // Таблицы WP для запросов
    $cities_table = $wpdb->prefix . 'posts';
    $postmeta_table = $wpdb->prefix . 'postmeta';
    $terms_table = $wpdb->prefix . 'terms';
    $term_taxonomy_table = $wpdb->prefix . 'term_taxonomy';
    $term_relationships_table = $wpdb->prefix . 'term_relationships';

    $search_sql = '';
    if ($search) {
        $search_sql = $wpdb->prepare(" AND p.post_title LIKE %s ", '%' . $wpdb->esc_like($search) . '%');
    }

    $query = "
        SELECT
            t.name AS country,
            p.ID AS city_id,
            p.post_title AS city,
            lat.meta_value AS latitude,
            lon.meta_value AS longitude
        FROM {$cities_table} p
        INNER JOIN {$term_relationships_table} tr ON (p.ID = tr.object_id)
        INNER JOIN {$term_taxonomy_table} tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'countries')
        INNER JOIN {$terms_table} t ON (tt.term_id = t.term_id)
        LEFT JOIN {$postmeta_table} lat ON (p.ID = lat.post_id AND lat.meta_key = '_cities_latitude')
        LEFT JOIN {$postmeta_table} lon ON (p.ID = lon.post_id AND lon.meta_key = '_cities_longitude')
        WHERE p.post_type = 'cities'
          AND p.post_status = 'publish'
          {$search_sql}
        ORDER BY t.name ASC, p.post_title ASC
        LIMIT 100
    ";



    $results = $wpdb->get_results($query);

    if (!$results) {
        wp_send_json_success('<tr><td colspan="3">Нет данных</td></tr>');
    }

    // Теперь нам нужно получить температуру для каждого города по координатам
    // Предположим, у тебя есть функция get_weather_by_coords($lat, $lon) которая возвращает температуру в °C
    // Если такой функции нет — можно использовать ранее созданную get_weather($city_name), но лучше по координатам

    $html = '';
    foreach ($results as $row) {
        $temp = '-';

        if (!empty($row->latitude) && !empty($row->longitude)) {
            // Вызов твоей функции получения температуры по координатам
            $weather = get_weather_by_coords($row->latitude, $row->longitude);
            if (is_array($weather) && isset($weather['temp'])) {
                $temp = esc_html($weather['temp']) . ' °C';
            }
        }

        $html .= '<tr>';
        $html .= '<td>' . esc_html($row->country) . '</td>';
        $html .= '<td>' . esc_html($row->city) . '</td>';
        $html .= '<td>' . $temp . '</td>';
        $html .= '</tr>';
    }

    wp_send_json_success($html);
}

add_action('wp_ajax_get_cities_weather', 'ajax_get_cities_weather');
add_action('wp_ajax_nopriv_get_cities_weather', 'ajax_get_cities_weather');
