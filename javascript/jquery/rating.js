/**
 * Balsavimo žvaigždutės
 */

function init_rating(id) {

    $('#score_' + id + ' a').click(function () {
        $(this).parent().parent().parent().addClass('scored');
        $.get("rating.php" + $(this).attr("href") + "&reload=true", {}, function (data) {
            $('.scored').fadeOut("normal", function () {
                $(this).html(data);
                $(this).fadeIn();
                $(this).removeClass('scored');
            });
        });
        return false;
    });

}