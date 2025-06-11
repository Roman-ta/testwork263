<?php

/**
 * Template Name: Города и погода
 */

get_header();

do_action('before_cities_weather_table');

?>

    <h1>Список городов и их температура</h1>

    <div id="cities-weather-container">
        <input type="text" id="city-search" placeholder="Поиск города..."
               style="width: 300px; padding: 5px; margin-bottom: 15px;">

        <table id="cities-weather-table" border="1" cellpadding="10" cellspacing="0"
               style="border-collapse: collapse; width: 100%;">
            <thead>
            <tr>
                <th>Страна</th>
                <th>Город</th>
                <th>Температура (°C)</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

<?php
do_action('after_cities_weather_table');

get_footer();
