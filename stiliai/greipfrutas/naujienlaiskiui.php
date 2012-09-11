<?php
//susigeneruojame stiliaus aplankalo adresą
$gabalas = explode("/".$conf['Admin_folder'], adresas());
$stilius = $gabalas[0]."/stiliai/".$conf['Stilius']."/";
$naujienlaiskis = '
<head>
<style type="text/css">
/* Client-specific Styles */
#outlook a {
    padding: 0;
}

/* Force Outlook to provide a "view in browser" button. */
body {
    width: 100% !important;
}

.ReadMsgBody {
    width: 100%;
}

.ExternalClass {
    width: 100%;
    display: block !important;
}

/* Force Hotmail to display emails at full width */
/* Reset Styles */
img {
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    display: block;
}

br, strong br, b br, em br, i br {
    line-height: 100%;
}

h1, h2, h3, h4, h5, h6 {
    line-height: 100% !important;
    -webkit-font-smoothing: antialiased;
}

/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
table td, table tr {
    border-collapse: collapse;
}

.yshortcuts, .yshortcuts a, .yshortcuts a:link, .yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
    text-decoration: none !important;
    border-bottom: none !important;
    background: none !important;
}

/* Body text color for the New Yahoo.  This example sets the font of Yahoo\'s Shortcuts to black . */
/* This most probably won\'t work in all email clients. Don\'t include <code _tmplitem="784" > blocks in email. */
code {
    white -space: normal;
    word -break: break-all;
}

/* Unikalus Stilius geriausiai nuo čia */
#background-table {
    background-color: #E6E6E6;
}
body {
    margin: 0;
    padding: 0;
     background: #E6E6E6;
}

#kunas {
 background: #ccc;
 margin: 0 auto;
 margin: 20px;
 border-radius: 5px;
}

.vidus {
    text-shadow: #f8f8f8 1px 0px 0px;
    border: 1px solid #f8f8f8;
    background: #f8f8f8 url('.$stilius.'paveiksleliai/bloko_fonas.png) repeat-x;
}

.pavadinimas {
    text-shadow: #6b655c 1px 0px 0px;
    background: url('.$stilius.'paveiksleliai/meniu.png) repeat-x;
    width: auto;
    height: 21px;
    color: #fff;
    padding-left: 5px;
    padding-top: 5px;
    color: #fff;
    font-family: Arial, Verdana, Tahoma, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
}
</style >
</head>
';

//Atvaizduojame naujienlaiškio stilių
$naujienlaiskis .= "
<body>
<div id='kunas'>
<div class='pavadinimas'>{$pavadinimas}</div>
<div class='vidus'>
    <div class='text'>{$izanga}
     <a href='{$nuoroda_i_naujiena}' target='_blank' title='{$lang['news']['read']}'>{$lang['news']['read']}</a>
    <a href='{$nuoroda_atsisakyti}' target='_blank' title='{$lang['news']['unorder']}'>{$lang['news']['unorder']}</a>
    </div>
    </div>
     </div>
</body>
    ";
?>
