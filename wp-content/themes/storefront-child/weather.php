<?php

function get_weather($city): array|string
{
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

    return [
        "temp" => $weather["main"]["temp"] . "&deg; C",
        'description' => $weather['weather'][0]["description"],
        'lon' => $weather['coord']['lon'],
        'lat' => $weather['coord']['lat'],
    ];
}
