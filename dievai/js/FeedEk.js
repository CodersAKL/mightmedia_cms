/*
 * FeedEk jQuery RSS/ATOM Feed Plugin
 * http://jquery-plugins.net/FeedEk/FeedEk.html
 * Author : Engin KIZIL
 * http://www.enginkizil.com
 */

(function ($) {
    $.fn.FeedEk = function (opt) {
        var def = {FeedUrl:'', MaxCount:5, ShowDesc:true, ShowPubDate:true};
        if (opt) {
            $.extend(def, opt)
        }
        var idd = $(this).attr('id');
        if (def.FeedUrl == null || def.FeedUrl == '') {
            $('#' + idd).empty();
            return
        }

        var pubdt;
        $('#' + idd).empty().append('<div style="text-align:left; padding:50px;"><img src="images/loading.gif" /></div>');
        $.ajax({url:'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=' + def.MaxCount + '&output=json&q=' + encodeURIComponent(def.FeedUrl) + '&callback=?', dataType:'json', success:function (data) {
            $('#' + idd).empty();
            $.each(data.responseData.feed.entries, function (i, entry) {
                $('#' + idd).append('<div class="ItemTitle"><a href="' + entry.link + '" target="_blank" >' + entry.title + '</a></div>');
                if (def.ShowPubDate) {
                    pubdt = new Date(entry.publishedDate);
                    var dateString = '';
// Get the month, day, and year.
                    dateString += pubdt.getFullYear() + "-";
                    dateString += (pubdt.getMonth() + 1) + "-";
                    dateString += pubdt.getDate();
                    $('#' + idd).append('<div class="ItemDate">' + pubdt.toLocaleDateString() + '</div>')
                    //$('#' + idd).append('<div class="ItemDate">' + dateString + '</div>')
                }
                if (def.ShowDesc)$('#' + idd).append('<div class="ItemContent">' + entry.content + '</div>')
            })
        }})
    }
})(jQuery);
