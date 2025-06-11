<?php

function get_weather($city): array|string
{
    // Создаем уникальный ключ для каждого города
    $cache_key = 'weather_' . sanitize_title($city);

    // Пытаемся получить данные из кеша
    $cached_weather = get_transient($cache_key);

    // Если данные есть в кеше - возвращаем их
    if ($cached_weather !== false) {
        return $cached_weather;
    }

    // Если данных нет в кеше - делаем запрос к API
    $api_key = '57907c8787abebd533e9ec49ac746094';
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$api_key}&units=metric&lang=ru";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return "Ошибка OpenWeather";
    }

    $body = wp_remote_retrieve_body($response);
    $weather = json_decode($body, true);

    if (empty($weather) || !isset($weather["main"])) {
        return 'Погода недоступна';
    }

    // Подготавливаем данные для возврата
    $weather_data = [
        "temp" => round($weather["main"]["temp"]),
        'description' => $weather['weather'][0]["description"],
        'lon' => $weather['coord']['lon'],
        'lat' => $weather['coord']['lat'],
    ];

    // Сохраняем данные в кеш на 15 минут (15 * 60 = 900 секунд)
    set_transient($cache_key, $weather_data, 900);

    return $weather_data;
}

// Функция для очистки кеша погоды (полезно для админки)
function clear_weather_cache($city = null) {
    if ($city) {
        // Очищаем кеш для конкретного города
        $cache_key = 'weather_' . sanitize_title($city);
        delete_transient($cache_key);
    } else {
        // Очищаем весь кеш погоды
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_weather_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_weather_%'");
    }
}

// Добавляем возможность очистки кеша через админку
add_action('wp_ajax_clear_weather_cache', function() {
    if (!current_user_can('manage_options')) {
        wp_die('Нет прав доступа');
    }

    clear_weather_cache();
    wp_send_json_success('Кеш погоды очищен');
});

// Добавляем кнопку очистки кеша в админку
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $wp_admin_bar->add_node([
        'id' => 'clear-weather-cache',
        'title' => 'Очистить кеш погоды',
        'href' => '#',
        'meta' => [
            'onclick' => 'if(confirm("Очистить кеш погоды?")) { 
                jQuery.post(ajaxurl, {action: "clear_weather_cache"}, function(response) {
                    alert("Кеш очищен!");
                    location.reload();
                }); 
                return false; 
            }'
        ]
    ]);
}, 100);