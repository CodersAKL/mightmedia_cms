function simple_tooltip(target_items, name) {
    $(target_items).each(function (i) {
        var title_text = $(this).attr('title');
        if (title_text && title_text.length > 0)
            $("body").append("<div class='" + name + "' id='" + name + i + "'>" + title_text + "</div>");
        var my_tooltip = $("#" + name + i);
        var tooltip = $(this);

        tooltip.attr("title", '').mouseover(function () {
            my_tooltip.css({
                opacity:0.8,
                display:"none"
            }).fadeIn(400);
        }).mousemove(function (kmouse) {
                var border_top = $(window).scrollTop();
                var border_right = $(window).width();
                var left_pos;
                var top_pos;
                var offset = 5;
                if (border_right - (offset * 2) >= my_tooltip.width() + kmouse.pageX) {
                    left_pos = kmouse.pageX + offset;
                } else {
                    left_pos = border_right - my_tooltip.width() - offset;
                }

                if (border_top + (offset * 2) >= kmouse.pageY - my_tooltip.height()) {
                    top_pos = border_top + offset;
                } else {
                    top_pos = kmouse.pageY - my_tooltip.height() - offset;
                }

                my_tooltip.css({
                    left:left_pos,
                    top:top_pos
                });
            }).mouseout(function () {
                my_tooltip.css({
                    left:"-9999px"
                });
            });

    });
}

$(document).ready(function () {
    simple_tooltip("a[title],img[title],div[title],span[title],button[title]", "tooltip");
});