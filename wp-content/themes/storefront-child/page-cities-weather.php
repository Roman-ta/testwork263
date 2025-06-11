<?php

/*
Template Name: Weather Cities Page
*/

get_header(); ?>

    <div class="weather-cities-page">
        <div class="container">
            <h1>Погода в городах</h1>

            <?php
            // Custom action hook ДО таблицы
            do_action('before_cities_weather_table');
            ?>

            <!-- Поле поиска -->
            <div class="search-container" style="margin-bottom: 20px;">
                <input type="text" id="city-search" placeholder="Поиск по городам..." style="padding: 10px; width: 300px;">
                <button id="search-btn" type="button" style="padding: 10px 20px; margin-left: 10px;">Поиск</button>
                <button id="reset-btn" type="button" style="padding: 10px 20px; margin-left: 5px;">Сбросить</button>
            </div>

            <!-- Таблица с данными -->
            <table id="cities-weather-table" style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Страна</th>
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Город</th>
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Температура</th>
                </tr>
                </thead>
                <tbody id="cities-weather-tbody">
                <!-- Данные загружаются через AJAX -->
                <tr>
                    <td colspan="3" style="padding: 20px; text-align: center;">Загрузка данных...</td>
                </tr>
                </tbody>
            </table>

            <?php
            // Custom action hook ПОСЛЕ таблицы
            do_action('after_cities_weather_table');
            ?>

        </div>
    </div>

    <style>
        .weather-cities-page {
            padding: 40px 0;
        }

        .search-container {
            margin-bottom: 20px;
        }

        #cities-weather-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #cities-weather-table th,
        #cities-weather-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        #cities-weather-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        #cities-weather-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #cities-weather-table tbody tr:hover {
            background-color: #f0f8ff;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-container button {
            padding: 10px 20px;
            border: none;
            background-color: #0073aa;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-container button:hover {
            background-color: #005a87;
        }

        .search-container button#reset-btn {
            background-color: #666;
        }

        .search-container button#reset-btn:hover {
            background-color: #444;
        }
    </style>

<?php get_footer(); ?>