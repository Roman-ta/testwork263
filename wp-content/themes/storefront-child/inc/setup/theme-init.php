<?php
// CPT: Города
require_once get_stylesheet_directory() . '/inc/post-types/cities.php';

// Таксономия: Страны
require_once get_stylesheet_directory() . '/inc/taxonomies/countries.php';

// Метабоксы координат
require_once get_stylesheet_directory() . '/inc/metaboxes/cities-coordinates.php';

// Виджет погоды
require_once get_stylesheet_directory() . '/inc/widgets/cities-weather-widget.php';