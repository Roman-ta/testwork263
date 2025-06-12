jQuery(document).ready(function ($) {

    function loadCitiesWeather(searchQuery = '') {
        $.ajax({
            url: citiesWeatherAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cities_weather',
                search: searchQuery,
                nonce: citiesWeatherAjax.nonce
            },
            beforeSend: function () {
                $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center;">Загрузка...</td></tr>');
            },
            success: function (response) {
                if (response.success) {
                    $('#cities-weather-tbody').html(response.data);
                } else {
                    $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center; color: red;">Ошибка загрузки данных</td></tr>');
                }
            },
            error: function () {
                $('#cities-weather-tbody').html('<tr><td colspan="3" style="padding: 20px; text-align: center; color: red;">Ошибка соединения</td></tr>');
            }
        });
    }

    loadCitiesWeather();

    //search
    $('#search-btn').on('click', function () {
        var searchQuery = $('#city-search').val().trim();
        loadCitiesWeather(searchQuery);
    });

    // reset
    $('#reset-btn').on('click', function () {
        $('#city-search').val('');
        loadCitiesWeather();
    });

    // enter
    $('#city-search').on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            var searchQuery = $(this).val().trim();
            loadCitiesWeather(searchQuery);
        }
    });

});