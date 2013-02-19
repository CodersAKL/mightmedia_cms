/* BBCODE */
function mail(user, domain) {
    window.location = 'mailto:' + user + '@' + domain;
}
function flip(rid) {
    document.getElementById(rid).style.display = document.getElementById(rid).style.display == 'none' ? 'block' : 'none'
}

function addText(elname, wrap1, wrap2) {
    if (document.selection) { // for IE
        var str = document.selection.createRange().text;
        document.forms[elname].elements[elname].focus();
        var sel = document.selection.createRange();
        sel.text = wrap1 + str + wrap2;
        return;
    } else if ((typeof document.forms[elname].elements[elname].selectionStart) != 'undefined') { // for Mozilla
        var txtarea = document.forms[elname].elements[elname];
        var selLength = txtarea.textLength;
        var selStart = txtarea.selectionStart;
        var selEnd = txtarea.selectionEnd;
        var oldScrollTop = txtarea.scrollTop;
        //if (selEnd == 1 || selEnd == 2)
        //selEnd = selLength;
        var s1 = (txtarea.value).substring(0, selStart);
        var s2 = (txtarea.value).substring(selStart, selEnd)
        var s3 = (txtarea.value).substring(selEnd, selLength);
        txtarea.value = s1 + wrap1 + s2 + wrap2 + s3;
        txtarea.selectionStart = s1.length;
        txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
        txtarea.scrollTop = oldScrollTop;
        txtarea.focus();
        return;
    } else {
        insertText(elname, wrap1 + wrap2);
    }
}

function MM_openBrWindow(theURL, winName, features) { //v2.0
    window.open(theURL, winName, features);
}

function rodyk(id) {
    if (document.getElementById) {
        obj = document.getElementById(id);
        if (obj.style.display == "none") {
            obj.style.display = "";
            createCookie(id, 0, 1);
        } else {
            obj.style.display = "none";
            createCookie(id, 1, 1);
        }
    }
}

function checkSearch() {
    if (document.getElementById && document.getElementById('search').value == "") {
        alert("Įrašyk paieškos frazę.");
        document.getElementById('search').focus();
        return false;
    } else {
        return true;
    }
}

function favorit() {
    if (document.all && navigator.userAgent.indexOf("Opera") == -1) {
        document.write("<a href=\"javascript:window.external.addfavorite('http://'+document.domain,'HTMLSource: HTML Tutorials');\" class=\"nav\">Įtraukti į adresyną!</a><br />");
    } else {
        document.write("Spausk Ctrl+D!");
    }
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function startCount() {
    var countfrom = parseInt(document.getElementById('sekundes').innerHTML); // Nuo kokio skaičiaus pradėti skaičiuoti
    var countnow = document.getElementById('sekundes').innerHTML = countfrom + 1;
    countAction(countnow)
}

function countAction(countnow) {
    if (countnow != 0) {
        countnow -= 1
        document.getElementById('sekundes').innerHTML = countnow;
    } else {
        window.location.href = unescape(window.location);
        return
    }
    setTimeout("countAction(" + countnow + ")", 1000)
}
checked = false;
function checkedAll(frm1) {
    var aa = document.getElementById(frm1);
    if (checked == false) {
        checked = true
    }
    else {
        checked = false
    }
    for (var i = 0; i < aa.elements.length; i++) {
        aa.elements[i].checked = checked;
    }
}

$(document).ready(function () {
    $('form').submit(function () {
        $(':submit, :button', this).click(function () {
            this.disabled = true;
            return false;
        })
    });

});

