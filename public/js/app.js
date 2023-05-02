$(document).ready(function() {
    $('.search-form').submit(function(event) {
        event.preventDefault();
        var query = $('#search-query').val();
        $.get('/search', {'query': query}, function(data) {
            console.log(data);
            // Traitez les résultats de recherche ici
        });
    });
});