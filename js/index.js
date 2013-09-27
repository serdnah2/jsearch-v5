window.onload = function() {
    $(".pull-right").click(function() {
        $('body').animate({scrollTop: 0}, 'slow');
    });

    $('#js-search').submit(function(event) {
        var validate = $('#js-input').val();
        if (validate === "") {
            event.preventDefault();
            return false;
        }
    });
};

