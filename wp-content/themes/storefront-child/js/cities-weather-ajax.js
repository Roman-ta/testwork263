jQuery(document).ready(function($){
    function loadCitiesWeather(search = '') {
        $.ajax({
            url: citiesWeatherAjax.ajax_url,
            method: 'POST',
            data: {
                action: 'get_cities_weather',
                nonce: citiesWeatherAjax.nonce,
                search: search
            },
            success: function(response) {
                if(response.success) {
                    $('#cities-weather-table tbody').html(response.data);
                } else {
                    $('#cities-weather-table tbody').html('<tr><td colspan="3">Нет данных</td></tr>');
                }
            },
            error: function() {
                $('#cities-weather-table tbody').html('<tr><td colspan="3">Ошибка загрузки данных</td></tr>');
            }
        });
    }

    loadCitiesWeather();

    let typingTimer;
    $('#city-search').on('keyup', function() {
        clearTimeout(typingTimer);
        let query = $(this).val();
        typingTimer = setTimeout(function() {
            loadCitiesWeather(query);
        }, 500);
    });
});
