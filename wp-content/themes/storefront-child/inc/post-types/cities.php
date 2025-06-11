<?php
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