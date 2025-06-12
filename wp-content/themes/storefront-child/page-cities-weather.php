<?php

/*
Template Name: Weather Cities Page
*/

get_header(); ?>

    <div class="weather-cities-page">
        <div class="container">
            <h1>Погода в городах</h1>

            <?php do_action('before_cities_weather_table'); ?>

            <div class="search-container" style="margin-bottom: 20px;">
                <input type="text" id="city-search" placeholder="Поиск по городам..." style="padding: 10px; width: 300px;">
                <button id="search-btn" type="button" style="padding: 10px 20px; margin-left: 10px;">Поиск</button>
                <button id="reset-btn" type="button" style="padding: 10px 20px; margin-left: 5px;">Сбросить</button>
            </div>


            <table id="cities-weather-table" style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="padding: 12px; border: 1px solid #ddd;">Страна</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Город</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Температура</th>
                </tr>
                </thead>
                <tbody id="cities-weather-tbody">
                <tr>
                    <td colspan="3" style="padding: 20px; text-align: center;">Загрузка данных...</td>
                </tr>
                </tbody>
            </table>

            <?php do_action('after_cities_weather_table'); ?>

        </div>
    </div>


<?php get_footer(); ?>