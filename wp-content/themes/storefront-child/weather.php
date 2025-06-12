<?php
const FIFTEEN_MINUTES = 900;

function get_weather($city): array|string
{
    // Uniq key
    $cache_key = 'weather_' . sanitize_title($city);
    $cached_weather = get_transient($cache_key);

    if ($cached_weather !== false) {
        return $cached_weather;
    }
    // TODO refactor
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

    $weather_data = [
        "temp" => round($weather["main"]["temp"]),
        'description' => $weather['weather'][0]["description"],
        'lon' => $weather['coord']['lon'],
        'lat' => $weather['coord']['lat'],
    ];

    // Сохраняем данные в кеш на 15 минут (15 * 60 = 900 секунд)
    set_transient($cache_key, $weather_data, FIFTEEN_MINUTES);

    return $weather_data;
}

// TODO maybe function for clean cache
