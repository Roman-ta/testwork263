jQuery(document).ready(function($) {

    // Функция для загрузки данных
    function loadCitiesWeather(searchQuery = '') {
        $.ajax({
            url: citiesWeatherAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cities_weather',
                search: searchQuery,
                nonce: citiesWeatherAjax.nonce
            },
            beforeSend: function() {
                $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center;">Загрузка...</td></tr>');
            },
            success: function(response) {
                if (response.success) {
                    $('#cities-weather-tbody').html(response.data);
                } else {
                    $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center; color: red;">Ошибка загрузки данных</td></tr>');
                }
            },
            error: function() {
                $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center; color: red;">Ошибка соединения</td></tr>');
            }
        });
    }

    // Загружаем данные при загрузке страницы
    loadCitiesWeather();

    // Обработчик кнопки поиска
    $('#search-btn').on('click', function() {
        var searchQuery = $('#city-search').val().trim();
        loadCitiesWeather(searchQuery);
    });

    // Обработчик кнопки сброса
    $('#reset-btn').on('click', function() {
        $('#city-search').val('');
        loadCitiesWeather();
    });

    // Поиск при нажатии Enter в поле поиска
    $('#city-search').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            var searchQuery = $(this).val().trim();
            loadCitiesWeather(searchQuery);
        }
    });

    // Живой поиск (опционально, раскомментируйте если нужно)
    /*
    $('#city-search').on('input', function() {
        var searchQuery = $(this).val().trim();
        // Добавляем небольшую задержку для оптимизации
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function() {
            loadCitiesWeather(searchQuery);
        }, 500);
    });
    */

});