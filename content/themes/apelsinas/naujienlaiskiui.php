<?php
//susigeneruojame stiliaus aplankalo adresą
$gabalas = explode("/".$conf['Admin_folder'], adresas());
$stilius = $gabalas[0]."/content/themes/".$conf['Stilius']."/";
$naujienlaiskio_css = '
<style type="text/css">
		/* Client-specific Styles */
	#outlook a {
		padding: 0;
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
		white-space: normal;
		word-break: break-all;
	}

	#background-table {
		background-color: #fff;
	}

		/* Unikalus Stilius geriausiai nuo čia */
	body {
		background: #ccc;
	}

	a {
		color: #f69d4e;
		text-decoration: underline;
	}

	a:hover {
		color: #666;
	}

	#kunas {
		margin: 20px;
		font-family: Arial;
		font-size: 14px;
		background: #ccc;
	}

	.vidus {
		padding: 5px;
		text-align: left;
		background-color: #f8f8f8;
	}

	.pavadinimas {
		background-color: #f69d4e;
		height: 21px;
		color: #fff;
		padding: 5px;
		text-align: left;
		font-weight: bold;
	}

	.galva {
		background-color: #f69d4e;
		height: 50px;
		border-bottom: 2px solid #ccc;
		font-size: 20px;
		padding-left: 5px;
		padding-top: 5px;
		color: #fff;
		font-weight: bold;
	}
</style>
';

//Atvaizduojame naujienlaiškio stilių

function naujienlaiskis($pavadinimas, $izanga, $nuoroda_i_naujiena, $nuoroda_atsisakyti){
	global $conf, $lang, $naujienlaiskio_css;
	$naujienlaiskis = "
{$naujienlaiskio_css}
<table id='kunas' align='center'>
	<tr>
		<td class='galva' align='center' width='100%'>".$conf['Pavadinimas']."</td>
	</tr>
	<tr>
		<td align='center' width='90%' class='pavadinimas'>{$pavadinimas}</td>
	</tr>
	<tr>
		<td align='center' valign='top' width='90%' class='vidus'>
			{$izanga}
			<a href='{$nuoroda_i_naujiena}' target='_blank' title='{$lang[' news']['read']}'>{$lang['news']['read']}</a>
			<a href='{$nuoroda_atsisakyti}' target='_blank' title='{$lang[' news']['unorder']}'>{$lang['news']['unorder']}</a>
		</td>
	</tr>
</table>
";
	return $naujienlaiskis;
}
?>
