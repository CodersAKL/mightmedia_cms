<?php

function slug($str)
{
	return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $str)));

}

function config($file, $key = null)
{
    $config = include ROOT . 'core/configs/config.' . $file . '.php';

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

		if (! getSession('lang')) {
			$siteLang = getOption('site_lang');
			setSession('lang', $siteLang);

			return $siteLang;
		}

		return getSession('lang');
	}
}

// todo: check improve or delete

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
	$atidaryti = fopen( ROOT . ".htaccess", "a" );
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

/** Gražina versijos numerį */
if(! function_exists('versija')) {
	// function versija() {
	// 	$scid  	= file( ROOTAS . '/version.txt' );
	// 	$values = array_values($scid);
	// 	$scid 	= trim(array_shift($values));

	// 	return apvalinti( ( intval( $scid ) / 5000 ) + '1.28', 2 );
	// }
	function versija() {
		$content  	= file_get_contents(ROOT . '/version.txt');

		return $content;
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
            'token' 	=> SECRET,
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

if(! function_exists('getLangText')) {
	function getLangText($group, $key, $new = false, $value = ''){
		global $lang;

		// TODO: renew lang parse
		return $group . '-' . $key; // dev

		if (array_key_exists($group, $lang) && (array_key_exists($key, $lang[$group]))){
			$langText = $lang[$group][$key];
		} else {
			$language = lang();
			langTextError($group, $key, $language);
			$langText = null;
		}

		if (lang() == 'lt'){
			$needTranslation = '--- nenurodyta ---';
		} else if (lang() == 'en') {
			$needTranslation = '--- undefined ---';
		}

		if  (getSession('translation_status')){
			$sqlCheckTranslation = "SELECT * FROM `" . LENTELES_PRIESAGA . "translations` WHERE `group`= " . escape($group) . " and `key`= " . escape($key) . " and `status` = 0 ORDER BY `last_update` DESC LIMIT 1";
			if ($textFromDb = mysql_query1($sqlCheckTranslation)){
				$langTextFromDataBase =  $textFromDb['translation'];
			}
			$result = '<span id ="' . $group . '_' . $key . '"  class="mm-translation" onclick="editLanguageText(this,function(event){event.preventDefault()})"';
			$result .= ' data-group="' . $group . '" data-key="' . $key . '">';
			if (isset($langTextFromDataBase)){ 
				($textFromDb['status'] == 0) ? $result .= '<strong style="color:red;">' : '';
				
				$result .= $langTextFromDataBase; 
				($textFromDb['status'] == 0) ? $result .= '</strong>' : '';
			} else { 
				$result .= ! is_null($langText) ? $langText : $needTranslation;
			}

			$result .= '</span>'; 

			return $result;

		} else {
			return $langText;	
		}
			
	}
}

if (! function_exists('langTextError')){
	function langTextError($group, $key, $language) {
		$path = ROOT . 'content/extensions/translation/missingtranslations.json';
		if (file_exists($path)){
			$missingTranslationsFileContent = file_get_contents($path);
			$missingTranslations = json_decode($missingTranslationsFileContent,true);
			$missingTranslations[$language][$group][$key] = '';
			file_put_contents($path,json_encode($missingTranslations));
		} else {
			$missingTranslationsFileContent = fopen($path, "w+") or die("Unable to open file!");
			$missingTranslations[$language][$group][$key] = '';
			$missingTranslations = json_encode($missingTranslations);
			fwrite($missingTranslationsFileContent, $missingTranslations);
			fclose($missingTranslationsFileContent);
		}
	}
}

if (! function_exists('langTextToFile')){
	function langTextToFile() {
		$langIdentificator = getSession('lang');
		$sqlApprovedTranslations = "SELECT `group`,`key`,`translation` FROM `" . LENTELES_PRIESAGA . "translations` WHERE `status` = 1 and `lang`='$langIdentificator'";
		if ($approvedTranslations = mysql_query1($sqlApprovedTranslations)){
			$dir = ROOT . 'content/extensions/translation/' . lang();
			$path = $dir . '/translations.php';
			if(file_exists($path)){
				$translations = unserialize(file_get_contents($path));
			} else {
				!file_exists($dir) ? mkdir($dir) : null;
			}
			if (is_array($translations)){
				$result = array_merge($approvedTranslations, $translations);
			} else {
				$result = $approvedTranslations;
			}

			$fileName = fopen($path, "w+") or die("Unable to open file!");
			fwrite($fileName, serialize($result));
			fclose($fileName);
			$sqlDeleteApprovedTranslations = "DELETE FROM `" . LENTELES_PRIESAGA . "translations` WHERE `status` = 1";
			$deleteResult = mysql_query1($sqlDeleteApprovedTranslations);
			if (!$deleteResult){
				echo mysqli_error();
			}
		}
	}
}

function setSession(string $key, $value)
{
	// if session with current key exists make array with that key
	if(isset($_SESSION[SECRET][$key])) {

		if(is_array($value)) {
			foreach($value as $k => $v) {
				$_SESSION[SECRET][$key][$k] = $v;
			}
			
		} else {
			if(is_string($_SESSION[SECRET][$key])) {
				$_SESSION[SECRET][$key] = [];
			}

			$_SESSION[SECRET][$key][] = $value;
		}

	} else {
		$_SESSION[SECRET][$key] = $value;
	}

	return $_SESSION[SECRET][$key];
}

function setSessions(array $array)
{
	foreach ($array as $key => $value) {
		$_SESSION[SECRET][$key] = $value;
	}
}

function getSession($key)
{
	// todo: make sure we wil need null not a bolean
	return ! isset($_SESSION[SECRET][$key]) || empty($_SESSION[SECRET][$key]) ? null : $_SESSION[SECRET][$key];
}

function forgotSession($key)
{
	unset($_SESSION[SECRET][$key]);
}

function forgotSessions($keys = [])
{
	foreach ($keys as $key) {
		forgotSession($key);
	}
}

// flash messages to sessions
function setFlashMessages(string $name, string $type, $value)
{

	if(is_array($value)) {
		
		foreach ($value as $keyMessage => $valueMessage) {
			$message[$name][$type][$keyMessage] = $valueMessage;

			// if exsist - forget
			if(isset($_SESSION[SECRET][$name][$type][$keyMessage])) {
				unset($_SESSION[SECRET][$name][$type][$keyMessage]);
			}
		}
	} else {
		$message[$name][$type]['main'] = $value;

		// if exsist - forget
		if(isset($_SESSION[SECRET][$name][$type]['main'])) {
			unset($_SESSION[SECRET][$name][$type]['main']);
		}
	}

	setSession('flash_messages', $message);
}

function getFlashMessages(string $key)
{

	return getSession('flash_messages', $key);
}