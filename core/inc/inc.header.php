<?php

/*
* Siame faile vykdomos komandos pries uzsikraunant puslapiui
* pvz "vartotojai online" sistema
*/
//todo: do we need this file???

if ( !defined( "OK" ) ) {
	header( "Location: ?" );
	exit;
}

if ( $_SERVER['QUERY_STRING'] === 'versija' ) {
	exit( versija() );
}
$timeoutseconds = 300; // Timeout sekundemis

if ( isset( $url['id'] ) ) {
	$failas = $url['id'];
} //kuriame faile yra
else {
	$failas = 'home';
}

if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
	$ref = strip_tags( $_SERVER['HTTP_REFERER'] );
} //is kur atejo
else {
	$ref = 'Direct Link';
}

$ip = getip(); //funkcija IP gauti

$timestamp = time(); //timestampas
$timeout   = $timestamp - $timeoutseconds; //taimout aritmetika :)
$db_expire = $timestamp - 259200; //3 dienos

if (! empty(getSession('username'))) {
	$username = getSession('username');
	$uzerid   = getSession('id');
} else {
	$username = $lang['system']['guest'];
	$uzerid   = '0';
}

//uzsetinam unikalu sausaini
if ( !isset( $_COOKIE["uid"] ) ) {
	$uid = random_name();
	setcookie( "uid", $uid, $timestamp + 259200 ); //3 dienos
} else {
	$uid = $_COOKIE["uid"];
}

if ( !check_name( $uid ) ) {
	//echo "unsetinau";
	setcookie( "uid", "", time() - 3600 );
	unset( $uid );
}

//jei sausainis yra (bus lengviau nuo botu)
if ( isset( $uid ) ) {

//Jei tai ne botas atnaujinam
	if ( !botas( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '' ) ) {

		//paskutinio prisijungimo atnaujinimas
		$q = "INSERT INTO `" . LENTELES_PRIESAGA . "kas_prisijunges` (`id`,`uid`,`timestamp`,`ip`,`file`,`user`,`agent`,`ref`,`clicks`) VALUES (
				" . escape( $uzerid ) . ",
				" . escape( $uid ) . ",
				" . escape( $timestamp ) . ",
				" . escape( $ip ) . ",
				" . escape( htmlspecialchars( str_replace( 'id=', '', $_SERVER['QUERY_STRING'] ) ) ) . ",
				" . escape( $username ) . ",
				" . escape( htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ) ) . ",
				" . escape( $ref ) . ",
				'1')
            ON DUPLICATE KEY UPDATE
			`id`=" . $uzerid . ",
			`ip`=" . escape( $ip ) . ",
			`clicks` = clicks+1,
			`ref`=" . escape( $ref ) . ",
			`file`=" . escape( htmlspecialchars( str_replace( 'id=', '', $_SERVER['QUERY_STRING'] ) ) ) . ",
			`agent`=" . escape( "User-Agent: " . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? escape( htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ) ) : 'Undefined' ) ) . ",
			`user`=" . escape( $username ) . ",
			`timestamp`=" . escape( $timestamp ) . ";";

		mysql_query1( $q );
	}
}

$q = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp`<" . escape( $db_expire ) . "" );
$q = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp`>" . escape( $timeout ) . "" );

$online = '';
$i      = '';
$u      = '';

foreach ( $q as $row ) {
	$nekvepuoja = $timestamp - $row['timestamp'];
	if ( time() - $nekvepuoja >= 1 * 60 * 60 && ! empty(getSession('id')) && $row['id'] == getSession('id') ) {
		mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `login_data` = '" . time() . "', `ip` = '" . escape( getip() ) . "' WHERE `id` ='" . $uzerid . "' LIMIT 1" );
	}
	if ( !empty( $row['id'] ) ) {
		$i++;
		$user_online[$row['id']] = TRUE;
	} else {
		$u++;
	}
}
if ( isset( $u ) && $u == 1 ) {
	$online .= $u . getLangText('system','guest'). "\n";
} elseif ( isset( $u ) && $u > 1 ) {
	$online .=  $u . getLangText('online','guests'). "\n";
}
if ( isset( $i ) && $i > 0 && isset( $u ) && $u > 0 ) {
	$online .= " ir ";
}
if ( isset( $i ) && $i == 1 ) {
	$online .= $i . getLangText('system','user'). "\n";
} elseif ( isset( $i ) && $i > 1 ) {
	$online .=  $i . getLangText('online','users'). "\n";
}

//todo: check this functions
//////////////////// ONLINE END /////////////////////
function botas( $userAgent ) {

	$crawlers  = 'HostTracker|EasyDL|e-collector|EmailCollector|Telesoft|Twiceler|InternetSeer.com|MJ12bot|YahooFeedSeeker|Yahoo-MMCrawler|Yandex|findlinks|Bloglines subscriber|Dumbot|Sosoimagespider|QihooBot|FAST-WebCrawler|Superdownloads Spiderman|LinkWalker|msnbot|ASPSeek|WebAlta Crawler|Lycos|FeedFetcher-Google|Yahoo|YoudaoBot|AdsBot-Google|Googlebot|Scooter|Gigabot|Charlotte|eStyle|AcioRobot|GeonaBot|msnbot-media|Baidu|CocoCrawler|Google|Charlotte t|Yahoo! Slurp China|Sogou web spider|YodaoBot|MSRBOT|AbachoBOT|Sogou head spider|AltaVista|IDBot|Sosospider|Yahoo! Slurp|Java VM|DotBot|LiteFinder|Yeti|Rambler|Scrubby|Baiduspider|accoona';
	$isCrawler = ( preg_match( "/$crawlers/i", $userAgent ) > 0 );
	return $isCrawler;
}

function check_name( $name ) {

//return preg_match("/^w$/",$name);
	return TRUE;
}

function curl_get_file_contents( $URL ) {

	if ( function_exists( 'curl_init' ) ) {
		$c = curl_init();
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $c, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $c, CURLOPT_URL, $URL );
		$contents = curl_exec( $c );
		curl_close( $c );

		return $contents;
	} else {
		return FALSE;
	}
}

function browser( $browser ) {

	$path       = "/rpc/rpctxtsimple.php";
	$host       = "http://user-agent-string.info";
	$access_key = "free";
	$ua         = base64_encode( $browser );
	$file       = $host . $path . "?key=" . $access_key . "&ua=" . $ua;
	$data       = curl_get_file_contents( $file );
	if ( $data ) {
		return $data;
	} else {
		return 'curl error';
	}
}

function getUserCountry( $ip ) {

	if ( function_exists( 'curl_init' ) ) {
		$url     = 'http://api.wipmania.com/' . $ip . '?' . url();
		$ch      = curl_init();
		$headers = "Typ: phpcurl\r\n";
		$headers .= "Ver: 1.0\r\n";
		$headers .= "Connection: Close\r\n\r\n";
		$timeout = 5;
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( $headers ) );
		$content = curl_exec( $ch );
		curl_close( $ch );
		return $content;
	} else {
		return 'lt';
	}
}
