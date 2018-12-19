<?php

function config($file, $key = null)
{
    $config = include ROOT . '/core/configs/config.' . $file . '.php';

    return ! empty($key) ? $config[$key] : $config;
}


/**
 * Gražina kalbą
 *
 * @global array $conf
 * @return string
 */
if(! function_exists('lang')) {
	function lang() {

		if ( empty( $_SESSION[SLAPTAS]['lang'] ) ) {
			global $conf;
			$_SESSION[SLAPTAS]['lang'] = basename( $conf['kalba'], '.php' );
		}

		return $_SESSION[SLAPTAS]['lang'];
	}
}

/**
 * Meta tagai ir kita
 */
if(! function_exists('header_info')) {
	function header_info() {

		global $conf, $page_pavadinimas, $lang, $pageMetaData;
		if (isset($conf['googleanalytics'])) { ?>
			<!--START Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $conf['googleanalytics'];?>"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '<?php echo $conf['googleanalytics'];?>');
			</script>
			<!-- END Global site tag (gtag.js) - Google Analytics -->
		<?php };
		if ( isset($pageMetaData['title']) && !empty($pageMetaData['title']) ){ 
			$pageTitle = $pageMetaData['title'] . ' - ' . input( strip_tags( $conf['Pavadinimas'] ) ); 
		} else { 
			$pageTitle = input( strip_tags( $conf['Pavadinimas'] ) . ' - ' . $page_pavadinimas );
		}
		if ( isset($pageMetaData['description']) && !empty($pageMetaData['description']) ){ 
			$pageDescription = $pageMetaData['description']; 
		} else { 
			$pageDescription = trimlink( trim( str_replace( "\n\r", "", strip_tags( $conf['Apie'] ) ) ), 120 );
		}
		if ( isset($pageMetaData['keywords'])  && !empty($pageMetaData['keywords']) ){ 
			$pageKeywords = $pageMetaData['keywords']; 
		} else { 
			$pageKeywords = input( strip_tags( $conf['Keywords'] ) );}
		echo '
		<base href="' . adresas() . '"></base>
		<meta name="generator" content="MightMedia TVS" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="' . lang() . '" />
		<meta name="description" content="' . $pageDescription . '" />
		<meta name="keywords" content="' . $pageKeywords . '" />
		<meta name="author" content="' . input( strip_tags( $conf['Copyright'] ) ) . '" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="' . $pageTitle . '" />
		<meta property="og:description" content="' . $pageDescription . '" />
		<meta property="og:url" content="' . adresas() . '" />
		<meta property="og:image" content="' . adresas() . 'content/themes/' . input( strip_tags( $conf['Stilius'] ) ) . '/paveiksleliai/mm_logo.png" />

		' . ( isset( $conf['pages']['rss.php'] ) ? '<link rel="alternate" type="application/rss+xml" title="' . input( strip_tags( $conf['Pavadinimas'] ) ) . '" href="rss.php" />' : '' ) . '
		
		<title>' . input( strip_tags( $conf['Pavadinimas'] ) . ' - ' . $page_pavadinimas ) . '</title>
		<script type="text/javascript" src="core/assets/javascript/main.js"></script>
		
		<script type="text/javascript">
			//Active mygtukas
			//todo: this stuff need to be in backend
			var mmPath = location.pathname.substring(1);
			if (mmPath) {
				var mmPathItems = document.querySelectorAll(\'ul li a[href$="\' + mmPath + \'"\');
				if(mmPathItems.length) {
					for (var mmI = 0; mmPathItems.length > mmI; mmI++) {
						mmPathItems[mmI].classList.add(\'active\');
					}
				}
			}
		</script>';

		if(file_exists(ROOT . 'content/themes/' . input( strip_tags( $conf['Stilius'] ) ) . '/default.css')) {
			echo '<link rel="stylesheet" type="text/css" href="content/themes/' . input( strip_tags( $conf['Stilius'] ) ) . '/default.css" />';
		}
	}
}

/**
 * Uždrausti IP ant serverio
 *
 * @param string $ipas
 * @param string $kodel
 */
if(! function_exists('ban')) {
function ban( $ipas = '', $kodel = '' ) {

	global $lang, $_SERVER, $ip, $forwarded, $remoteaddress;
	if ( empty( $kodel ) ) {
		$kodel = $lang['system']['forhacking'] . ' - ' . input( str_replace( "\n", "", $_SERVER['QUERY_STRING'] ) );
	}
	if ( empty( $ipas ) ) {
		$ipas = getip();
	}
	$atidaryti = fopen( ROOTAS . ".htaccess", "a" );
	fwrite( $atidaryti, '# ' . $kodel . " \nSetEnvIf Remote_Addr \"^{$ipas}$\" draudziam\n" );
	fclose( $atidaryti );
	//@chmod(".htaccess", 0777);

	$forwarded     = ( isset( $forwarded ) ? $forwarded : 'N/A' );
	$remoteaddress = ( isset( $remoteaddress ) ? $remoteaddress : 'N/A' );
	$ip            = ( isset( $ip ) ? $ip : getip() );
	$referer       = ( isset( $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : 'N/A' );

	$message = <<< HTML
  FROM:{$referer}
  REQ:{$_SERVER['REQUEST_METHOD']}
  FILE:{$_SERVER['SCRIPT_FILENAME']}
  QUERY:{$_SERVER['QUERY_STRING']}
  IP:{$ip} - Forwarded = {$forwarded} - Remoteaddress = {$remoteaddress}
HTML;

	if ( $kodel == $lang['system']['forhacking'] ) {
		die( "<center><h1>{$lang['system']['nohacking']}!</h1><font color='red'><b>" . $kodel . " - {$lang['system']['forbidden']}<blink>!</blink></b></font><hr/></center>" );
	}
}
}


/**
 * Grąžina lankytojo IP
 *
 * @return string
 */
if(! function_exists('getip')) {
	function getip() {

		$ip = (( !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) )
			? $_SERVER["HTTP_X_FORWARDED_FOR"]
			: $_SERVER["REMOTE_ADDR"]
		);
		if (strstr( $ip, "," )) {
			$ip = explode( ',', $ip );
			$ip = reset($ip);
			$ip = trim($ip );
		}
		return $ip;
	}
}


/**
 * Suapavalinimas
 *
 * @param     $sk
 * @param int $kiek
 *
 * @return float
 */
if(! function_exists('apvalinti')) {
	function apvalinti( $sk, $kiek = 2 ) {

		if ( $kiek < 0 ) {
			$kiek = 0;
		}
		$mult = pow( 10, $kiek );

		return ceil( $sk * $mult ) / $mult;
	}
}


/**
 * Verčiam baitus į žmonių kalbą
 *
 * @param     $size
 * @param int $digits
 *
 * @return string
 */
if(! function_exists('baitai')) {
	function baitai( $size, $digits = 2 ) {

		$kb = 1024;
		$mb = 1024 * $kb;
		$gb = 1024 * $mb;
		$tb = 1024 * $gb;
		if ( $size == 0 ) {
			return " Nulis";
		} elseif ( $size < $kb ) {
			return $size . " Baitai";
		} elseif ( $size < $mb ) {
			return round( $size / $kb, $digits ) . " Kb";
		} elseif ( $size < $gb ) {
			return round( $size / $mb, $digits ) . " Mb";
		} elseif ( $size < $tb ) {
			return round( $size / $gb, $digits ) . " Gb";
		} else {
			return round( $size / $tb, $digits ) . " Tb";
		}
	}
}


//Paskaiciuojam procentus
if(! function_exists('procentai')) {
	function procentai( $reikia, $yra, $zenklas = FALSE ) {

		$return = (int)round( ( 100 * $yra ) / $reikia );
		if ( $return > 100 && $zenklas ) {
			$return = "<img src='" . ROOT . "core/assets/images/icons/accept.png' class='middle' alt='100%' title='100%' borders='0' />";
		} elseif ( $return > 0 && $zenklas ) {
			$return = "<img src='" . ROOT . "core/assets/images/icons/cross.png' class='middle' alt='" . $return . "%' title='" . $reikia . "/" . $yra . " - " . $return . "%' borders='0' />";
		}

		return $return;
	}
}

/**
 * Gražina patvirtinimo kodą
 *
 * @return HTML
 */
if(! function_exists('kodas')) {
function kodas() {

	global $lang;
	$return = <<<HTML
	<script type="text/javascript">
	var \$human = document.createElement('img');
	\$human.setAttribute('src','core/inc/inc.human.php');
	\$human.setAttribute('style','cursor: pointer');
	\$human.setAttribute('title','{$lang['system']['refresh']}');
	\$human.setAttribute('onclick','this.src="core/inc/inc.human.php?"+Math.random();');
	\$human.setAttribute('alt','code');
	\$human.setAttribute('id','captcha');

	$('#captcha_content').html(\$human);
	</script>
HTML;

	return "<p id='captcha_content'></p>$return";
}
}

/** Gražina versijos numerį */
if(! function_exists('versija')) {
	// function versija() {
	// 	$scid  	= file( ROOTAS . '/version.txt' );
	// 	$values = array_values($scid);
	// 	$scid 	= trim(array_shift($values));

	// 	return apvalinti( ( intval( $scid ) / 5000 ) + '1.28', 2 );
	// }
	function versija() {
		$content  	= file_get_contents(ROOTAS . '/version.txt');

		return $content;
	}
}

/**
 * Editorius skirtas vaizdžiai redaguoti html
 *
 * @example echo editorius('tiny_mce','mini');
 * @example echo editorius('spaw','standartinis',array('Glaustai'=>'Glaustai','Placiau'=>'Plačiau'),array('Glaustai'=>'Naujiena glaustai','Placiau'=>'Naujiena plačiau'));
 *
 * @param string $tipas
 * @param string $dydis
 * @param string $id
 * @param string $value
 *
 * @return string
 */
if(! function_exists('editorius')) {
    function editorius( $tipas = 'rte', $dydis = 'standartinis', $id = FALSE, $value = '' ) {
    
        global $conf;
        if ( !$id ) {
            $id = md5( uniqid() );
        }
    
        $arr = array();
        if ( is_array( $id ) ) {
            foreach ( $id as $key => $val ) {
                $arr[$val] = "'$key'";
            }
            $areos = implode( $arr, "," );
        } else {
            $areos = "'$id'";
        }
        $root   = ROOT;
        $return = <<<HTML
<script type="text/javascript" src="{$root}core/assets/javascript/htmlarea/nicedit/nicEdit.js"></script>
HTML;

    if ( is_array( $id ) ) {
        foreach ( $id as $key => $val ) {

            $return .= <<< HTML
            
<script type="text/javascript">
bkLib.onDomLoaded(function() {
    new nicEditor({fullPanel : true, iconsPath : '{$root}core/assets/javascript/htmlarea/nicedit/nicEditorIcons.gif', width: '100%'}).panelInstance('{$key}');
});
</script>
<textarea class="editorius" id="{$key}" name="{$key}">{$value[$key]}</textarea>
HTML;
        }
    } else {
        $return .= <<< HTML
<script type="text/javascript">
bkLib.onDomLoaded(function() {
    new nicEditor({fullPanel : true, iconsPath : '{$root}core/assets/javascript/htmlarea/nicedit/nicEditorIcons.gif', width: '100%'}).panelInstance('{$id}');
});
</script>

<textarea class="editorius" id="{$id}" name="{$id}">{$value}</textarea>
HTML;
    }


    return $return;
}
}
    
    
    
if(! function_exists('checkVersion')) {
    /**
     * Version check function
     *
     * @return void
     */
    function checkVersion()
    {
        if($existData = cacheGetData('versionCheck')) {
            return $existData;
        }

        $url = 'https://mightmedia.lt/api.php';
        $data = [
            'token' 	=> SLAPTAS,
            'version' 	=> versija(),
            'type'		=> 'versionCheck'
        ];

        if($result = postRemote($url, $data)) {
            $response = json_decode($result, true);

            $time = (100 * 60 * 60);
            cachePutData('versionCheck', $response, $time);

            return $response;
        }

        return false;
    }
}
