<?php
/**
 * Виджет погоды для городов
 */
class Cities_Weather_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'cities_weather_widget',
            'Погода в городе',
            ['description' => 'Показывает погоду в выбранном городе']
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $title = !empty($instance['title']) ? $instance['title'] : 'Погода';
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];

        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

        if ($city_id) {
            $this->display_weather($city_id);
        } else {
            echo '<p>Город не выбран</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : 'Погода';
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

        $cities = get_posts([
            'post_type' => 'cities',
            'numberposts' => -1,
            'post_status' => 'publish',
        ]);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input class="widefat"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>">Выберите город:</label>
            <select class="widefat"
                    id="<?php echo $this->get_field_id('city_id'); ?>"
                    name="<?php echo $this->get_field_name('city_id'); ?>">
                <option value="">-- Выберите город --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city->ID; ?>"
                        <?php selected($city_id, $city->ID); ?>>
                        <?php echo $city->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? strip_tags($new_instance['city_id']) : '';
        return $instance;
    }

    private function display_weather($city_id)
    {
        $city_name = get_the_title($city_id);

        echo '<div class="weather-widget">';
        echo '<h4>' . esc_html($city_name) . '</h4>';


        $weather = get_weather($city_name);
        if (is_array($weather)) {
            echo "<p>Температура: {$weather['temp']}</p>";
            echo "<p>Погода: {$weather['description']}</p>";
            echo "<p><small>Координаты:" . esc_html($weather['lat']) . ", " . esc_html($weather['lon']) . " </small></p>";
        } else {
            echo '<p>Неверно указан город</p>';
        }

        echo '</div>';
    }
}

// Регистрация виджета
function register_cities_weather_widget()
{
    register_widget('Cities_Weather_Widget');
}

add_action('widgets_init', 'register_cities_weather_widget');

// Добавим CSS для виджета
function cities_weather_widget_styles()
{
    ?>
    <style>
        .weather-widget {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .weather-widget h4 {
            margin-top: 0;
            color: #333;
        }

        .weather-widget p {
            margin: 5px 0;
        }

        .weather-widget small {
            color: #666;
        }
    </style>
    <?php
}

add_action('wp_head', 'cities_weather_widget_styles');