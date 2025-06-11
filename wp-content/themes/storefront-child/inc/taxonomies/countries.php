<?php
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
