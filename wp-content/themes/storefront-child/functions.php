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
    $search = sanitize_text_field($_POST['search'] ?? '');

    $search_sql = '';
    if ($search) {
        $search_sql = $wpdb->prepare(" AND posts.post_title LIKE %s ", '%' . $wpdb->esc_like($search) . '%');
    }

    // Исправленный SQL запрос с правильными JOIN-ами
    $query = "SELECT posts.ID, wt.name as country, posts.post_title as city
                FROM {$wpdb->posts} as posts
                LEFT JOIN {$wpdb->term_relationships} wtr ON posts.ID = wtr.object_id
                LEFT JOIN {$wpdb->term_taxonomy} wtt ON wtr.term_taxonomy_id = wtt.term_taxonomy_id
                LEFT JOIN {$wpdb->terms} wt ON wtt.term_id = wt.term_id
                WHERE posts.post_status = 'publish' 
                AND posts.post_type = 'cities'" . $search_sql;

    $results = $wpdb->get_results($query);

    if (!$results) {
        wp_send_json_success('<tr><td colspan="3">Нет данных</td></tr>');
        return;
    }

    $html = '';
    foreach ($results as $row) {
        $temp = '-';
        $weather = get_weather($row->city);

        if (is_array($weather) && isset($weather['temp'])) {
            $temp = esc_html($weather['temp']) . ' °C';
        }

        $html .= '<tr>';
        $html .= '<td>' . esc_html($row->country ?? 'Не указано') . '</td>';
        $html .= '<td>' . esc_html($row->city) . '</td>';
        $html .= '<td>' . $temp . '</td>';
        $html .= '</tr>';
    }

    wp_send_json_success($html);
}

add_action('wp_ajax_get_cities_weather', 'ajax_get_cities_weather');
add_action('wp_ajax_nopriv_get_cities_weather', 'ajax_get_cities_weather');