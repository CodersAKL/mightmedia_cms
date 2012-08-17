<?php 
$exclude_files =  array(
"_derived",
"_private",
"_vti_cnf",
"_vti_pvt",
"vti_script",
"_vti_txt"
); // add any other folders or files you wish to exclude from the player.


//READING ID3 TAGS


// id3 tags converting to utf-8
function conv($str) {
  
    for ( $i = 0, $length = strlen($str); $i < $length; $i++ ) {
    	
    	
        if((ord($str[$i])=='0'||ord($str[$i])=='4')){
        	$str1 = $str1;
        	
        }else{  $str1 = $str1.$str[$i];}
        
      
    }
    
    
if( ( strpos($str1,chr(209).chr(143).chr(209).chr(142)) === 0) ) 
 { $str2 = substr($str1, 4); $str1 = $str2;}else{$str1 = $str1;}
        
    return $str1;
}


function detectUTF8($string)
{
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
}

function cp1251_utf8( $sInput )
{
    $sOutput = "";

    for ( $i = 0; $i < strlen( $sInput ); $i++ )
    {
        $iAscii = ord( $sInput[$i] );

        if ( $iAscii >= 192 && $iAscii <= 255 )
            $sOutput .=  "&#".( 1040 + ( $iAscii - 192 ) ).";";
        else if ( $iAscii == 168 )
            $sOutput .= "&#".( 1025 ).";";
        else if ( $iAscii == 184 )
            $sOutput .= "&#".( 1105 ).";";
        else
            $sOutput .= $sInput[$i];
    }
    
    return $sOutput;
}

function encoding($string){
    if (function_exists('iconv')) {    
        if (@!iconv('utf-8', 'cp1251', $string)) {
            $string = iconv('cp1251', 'utf-8', $string);
        }
        return $string;
    } else {
        if (detectUTF8($string)) {
            return $string;        
        } else {
            return cp1251_utf8($string);
        }
    }
}

error_reporting(0);

//errors

$errors[0] = '';//means no error. (Change it and things can become very strange)
$errors[1] = 'File Name not set';
$errors[2] = 'Unable to open MP3 file';
$errors[3] = 'ID3v2 Tag not found on this file';
$errors[4] = 'TAG not Supported';
$errors[5] = 'Tag not found(maybe you need to call getInfo() first?)';


//pear

define('PEAR_ERROR_RETURN',     1);
define('PEAR_ERROR_PRINT',      2);
define('PEAR_ERROR_TRIGGER',    4);
define('PEAR_ERROR_DIE',        8);
define('PEAR_ERROR_CALLBACK',  16);

define('PEAR_ERROR_EXCEPTION', 32);

define('PEAR_ZE2', (function_exists('version_compare') &&
                    version_compare(zend_version(), "2-dev", "ge")));

if (substr(PHP_OS, 0, 3) == 'WIN') {
    define('OS_WINDOWS', true);
    define('OS_UNIX',    false);
    define('PEAR_OS',    'Windows');
} else {
    define('OS_WINDOWS', false);
    define('OS_UNIX',    true);
    define('PEAR_OS',    'Unix');
}


if (!defined('PATH_SEPARATOR')) {
    if (OS_WINDOWS) {
        define('PATH_SEPARATOR', ';');
    } else {
        define('PATH_SEPARATOR', ':');
    }
}

$GLOBALS['_PEAR_default_error_mode']     = PEAR_ERROR_RETURN;
$GLOBALS['_PEAR_default_error_options']  = E_USER_NOTICE;
$GLOBALS['_PEAR_destructor_object_list'] = array();
$GLOBALS['_PEAR_shutdown_funcs']         = array();
$GLOBALS['_PEAR_error_handler_stack']    = array();

@ini_set('track_errors', true);

class PEAR
{
    var $_debug = false;
    var $_default_error_mode = null;
    var $_default_error_options = null;
    var $_default_error_handler = '';
    var $_error_class = 'PEAR_Error';
    var $_expected_errors = array();

    function PEAR($error_class = null)
    {
        $classname = strtolower(get_class($this));
        if ($this->_debug) {
            print "PEAR constructor called, class=$classname\n";
        }
        if ($error_class !== null) {
            $this->_error_class = $error_class;
        }
        while ($classname && strcasecmp($classname, "pear")) {
            $destructor = "_$classname";
            if (method_exists($this, $destructor)) {
                global $_PEAR_destructor_object_list;
                $_PEAR_destructor_object_list[] = &$this;
                if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
                    register_shutdown_function("_PEAR_call_destructors");
                    $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
                }
                break;
            } else {
                $classname = get_parent_class($classname);
            }
        }
    }

    function _PEAR() {
        if ($this->_debug) {
            printf("PEAR destructor called, class=%s\n", strtolower(get_class($this)));
        }
    }

    function &getStaticProperty($class, $var)
    {
        static $properties;
        return $properties[$class][$var];
    }

    function registerShutdownFunc($func, $args = array())
    {
        if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
            register_shutdown_function("_PEAR_call_destructors");
            $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
        }
        $GLOBALS['_PEAR_shutdown_funcs'][] = array($func, $args);
    }


    function isError($data, $code = null)
    {
        if (is_a($data, 'PEAR_Error')) {
            if (is_null($code)) {
                return true;
            } elseif (is_string($code)) {
                return $data->getMessage() == $code;
            } else {
                return $data->getCode() == $code;
            }
        }
        return false;
    }

    function setErrorHandling($mode = null, $options = null)
    {
        if (isset($this) && is_a($this, 'PEAR')) {
            $setmode     = &$this->_default_error_mode;
            $setoptions  = &$this->_default_error_options;
        } else {
            $setmode     = &$GLOBALS['_PEAR_default_error_mode'];
            $setoptions  = &$GLOBALS['_PEAR_default_error_options'];
        }

        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $setmode = $mode;
                $setoptions = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $setmode = $mode;
                if (is_callable($options)) {
                    $setoptions = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
    }

    function expectError($code = '*')
    {
        if (is_array($code)) {
            array_push($this->_expected_errors, $code);
        } else {
            array_push($this->_expected_errors, array($code));
        }
        return sizeof($this->_expected_errors);
    }

    function popExpect()
    {
        return array_pop($this->_expected_errors);
    }

    function _checkDelExpect($error_code)
    {
        $deleted = false;

        foreach ($this->_expected_errors AS $key => $error_array) {
            if (in_array($error_code, $error_array)) {
                unset($this->_expected_errors[$key][array_search($error_code, $error_array)]);
                $deleted = true;
            }

            if (0 == count($this->_expected_errors[$key])) {
                unset($this->_expected_errors[$key]);
            }
        }
        return $deleted;
    }

    function delExpect($error_code)
    {
        $deleted = false;

        if ((is_array($error_code) && (0 != count($error_code)))) {

            foreach($error_code as $key => $error) {
                if ($this->_checkDelExpect($error)) {
                    $deleted =  true;
                } else {
                    $deleted = false;
                }
            }
            return $deleted ? true : PEAR::raiseError("The expected error you submitted does not exist"); // IMPROVE ME
        } elseif (!empty($error_code)) {
            if ($this->_checkDelExpect($error_code)) {
                return true;
            } else {
                return PEAR::raiseError("The expected error you submitted does not exist"); // IMPROVE ME
            }
        } else {
            return PEAR::raiseError("The expected error you submitted is empty"); // IMPROVE ME
        }
    }

    function &raiseError($message = null,
                         $code = null,
                         $mode = null,
                         $options = null,
                         $userinfo = null,
                         $error_class = null,
                         $skipmsg = false)
    {
        if (is_object($message)) {
            $code        = $message->getCode();
            $userinfo    = $message->getUserInfo();
            $error_class = $message->getType();
            $message->error_message_prefix = '';
            $message     = $message->getMessage();
        }

        if (isset($this) && isset($this->_expected_errors) && sizeof($this->_expected_errors) > 0 && sizeof($exp = end($this->_expected_errors))) {
            if ($exp[0] == "*" ||
                (is_int(reset($exp)) && in_array($code, $exp)) ||
                (is_string(reset($exp)) && in_array($message, $exp))) {
                $mode = PEAR_ERROR_RETURN;
            }
        }
        if ($mode === null) {

            if (isset($this) && isset($this->_default_error_mode)) {
                $mode    = $this->_default_error_mode;
                $options = $this->_default_error_options;

            } elseif (isset($GLOBALS['_PEAR_default_error_mode'])) {
                $mode    = $GLOBALS['_PEAR_default_error_mode'];
                $options = $GLOBALS['_PEAR_default_error_options'];
            }
        }

        if ($error_class !== null) {
            $ec = $error_class;
        } elseif (isset($this) && isset($this->_error_class)) {
            $ec = $this->_error_class;
        } else {
            $ec = 'PEAR_Error';
        }
        if ($skipmsg) {
            $a = &new $ec($code, $mode, $options, $userinfo);
            return $a;
        } else {
            $a = &new $ec($message, $code, $mode, $options, $userinfo);
            return $a;
        }
    }


    function &throwError($message = null,
                         $code = null,
                         $userinfo = null)
    {
        if (isset($this) && is_a($this, 'PEAR')) {
            $a = &$this->raiseError($message, $code, null, null, $userinfo);
            return $a;
        } else {
            $a = &PEAR::raiseError($message, $code, null, null, $userinfo);
            return $a;
        }
    }


    function staticPushErrorHandling($mode, $options = null)
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        $def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
        $def_options = &$GLOBALS['_PEAR_default_error_options'];
        $stack[] = array($def_mode, $def_options);
        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $def_mode = $mode;
                $def_options = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $def_mode = $mode;
                if (is_callable($options)) {
                    $def_options = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
        $stack[] = array($mode, $options);
        return true;
    }

    function staticPopErrorHandling()
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        $setmode     = &$GLOBALS['_PEAR_default_error_mode'];
        $setoptions  = &$GLOBALS['_PEAR_default_error_options'];
        array_pop($stack);
        list($mode, $options) = $stack[sizeof($stack) - 1];
        array_pop($stack);
        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $setmode = $mode;
                $setoptions = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $setmode = $mode;
                if (is_callable($options)) {
                    $setoptions = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
        return true;
    }

    function pushErrorHandling($mode, $options = null)
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        if (isset($this) && is_a($this, 'PEAR')) {
            $def_mode    = &$this->_default_error_mode;
            $def_options = &$this->_default_error_options;
        } else {
            $def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
            $def_options = &$GLOBALS['_PEAR_default_error_options'];
        }
        $stack[] = array($def_mode, $def_options);

        if (isset($this) && is_a($this, 'PEAR')) {
            $this->setErrorHandling($mode, $options);
        } else {
            PEAR::setErrorHandling($mode, $options);
        }
        $stack[] = array($mode, $options);
        return true;
    }

    function popErrorHandling()
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        array_pop($stack);
        list($mode, $options) = $stack[sizeof($stack) - 1];
        array_pop($stack);
        if (isset($this) && is_a($this, 'PEAR')) {
            $this->setErrorHandling($mode, $options);
        } else {
            PEAR::setErrorHandling($mode, $options);
        }
        return true;
    }

    function loadExtension($ext)
    {
        if (!extension_loaded($ext)) {
            if ((ini_get('enable_dl') != 1) || (ini_get('safe_mode') == 1)) {
                return false;
            }
            if (OS_WINDOWS) {
                $suffix = '.dll';
            } elseif (PHP_OS == 'HP-UX') {
                $suffix = '.sl';
            } elseif (PHP_OS == 'AIX') {
                $suffix = '.a';
            } elseif (PHP_OS == 'OSX') {
                $suffix = '.bundle';
            } else {
                $suffix = '.so';
            }
            return @dl('php_'.$ext.$suffix) || @dl($ext.$suffix);
        }
        return true;
    }

}


function _PEAR_call_destructors()
{
    global $_PEAR_destructor_object_list;
    if (is_array($_PEAR_destructor_object_list) &&
        sizeof($_PEAR_destructor_object_list))
    {
        reset($_PEAR_destructor_object_list);
        if (@PEAR::getStaticProperty('PEAR', 'destructlifo')) {
            $_PEAR_destructor_object_list = array_reverse($_PEAR_destructor_object_list);
        }
        while (list($k, $objref) = each($_PEAR_destructor_object_list)) {
            $classname = get_class($objref);
            while ($classname) {
                $destructor = "_$classname";
                if (method_exists($objref, $destructor)) {
                    $objref->$destructor();
                    break;
                } else {
                    $classname = get_parent_class($classname);
                }
            }
        }
        $_PEAR_destructor_object_list = array();
    }

    if (is_array($GLOBALS['_PEAR_shutdown_funcs']) AND !empty($GLOBALS['_PEAR_shutdown_funcs'])) {
        foreach ($GLOBALS['_PEAR_shutdown_funcs'] as $value) {
            call_user_func_array($value[0], $value[1]);
        }
    }
}

class PEAR_Error
{

    var $error_message_prefix = '';
    var $mode                 = PEAR_ERROR_RETURN;
    var $level                = E_USER_NOTICE;
    var $code                 = -1;
    var $message              = '';
    var $userinfo             = '';
    var $backtrace            = null;

    function PEAR_Error($message = 'unknown error', $code = null,
                        $mode = null, $options = null, $userinfo = null)
    {
        if ($mode === null) {
            $mode = PEAR_ERROR_RETURN;
        }
        $this->message   = $message;
        $this->code      = $code;
        $this->mode      = $mode;
        $this->userinfo  = $userinfo;
        if (function_exists("debug_backtrace")) {
            if (@!PEAR::getStaticProperty('PEAR_Error', 'skiptrace')) {
                $this->backtrace = debug_backtrace();
            }
        }
        if ($mode & PEAR_ERROR_CALLBACK) {
            $this->level = E_USER_NOTICE;
            $this->callback = $options;
        } else {
            if ($options === null) {
                $options = E_USER_NOTICE;
            }
            $this->level = $options;
            $this->callback = null;
        }
        if ($this->mode & PEAR_ERROR_PRINT) {
            if (is_null($options) || is_int($options)) {
                $format = "%s";
            } else {
                $format = $options;
            }
            printf($format, $this->getMessage());
        }
        if ($this->mode & PEAR_ERROR_TRIGGER) {
            trigger_error($this->getMessage(), $this->level);
        }
        if ($this->mode & PEAR_ERROR_DIE) {
            $msg = $this->getMessage();
            if (is_null($options) || is_int($options)) {
                $format = "%s";
                if (substr($msg, -1) != "\n") {
                    $msg .= "\n";
                }
            } else {
                $format = $options;
            }
            die(sprintf($format, $msg));
        }
        if ($this->mode & PEAR_ERROR_CALLBACK) {
            if (is_callable($this->callback)) {
                call_user_func($this->callback, $this);
            }
        }
        if ($this->mode & PEAR_ERROR_EXCEPTION) {
            trigger_error("PEAR_ERROR_EXCEPTION is obsolete, use class PEAR_Exception for exceptions", E_USER_WARNING);
            eval('$e = new Exception($this->message, $this->code);throw($e);');
        }
    }

    function getMode() {
        return $this->mode;
    }

    function getCallback() {
        return $this->callback;
    }

    function getMessage()
    {
        return ($this->error_message_prefix . $this->message);
    }

     function getCode()
     {
        return $this->code;
     }

    function getType()
    {
        return get_class($this);
    }

    function getUserInfo()
    {
        return $this->userinfo;
    }

    function getDebugInfo()
    {
        return $this->getUserInfo();
    }

    function getBacktrace($frame = null)
    {
        if (defined('PEAR_IGNORE_BACKTRACE')) {
            return null;
        }
        if ($frame === null) {
            return $this->backtrace;
        }
        return $this->backtrace[$frame];
    }

    function addUserInfo($info)
    {
        if (empty($this->userinfo)) {
            $this->userinfo = $info;
        } else {
            $this->userinfo .= " ** $info";
        }
    }

    function toString() {
        $modes = array();
        $levels = array(E_USER_NOTICE  => 'notice',
                        E_USER_WARNING => 'warning',
                        E_USER_ERROR   => 'error');
        if ($this->mode & PEAR_ERROR_CALLBACK) {
            if (is_array($this->callback)) {
                $callback = (is_object($this->callback[0]) ?
                    strtolower(get_class($this->callback[0])) :
                    $this->callback[0]) . '::' .
                    $this->callback[1];
            } else {
                $callback = $this->callback;
            }
            return sprintf('[%s: message="%s" code=%d mode=callback '.
                           'callback=%s prefix="%s" info="%s"]',
                           strtolower(get_class($this)), $this->message, $this->code,
                           $callback, $this->error_message_prefix,
                           $this->userinfo);
        }
        if ($this->mode & PEAR_ERROR_PRINT) {
            $modes[] = 'print';
        }
        if ($this->mode & PEAR_ERROR_TRIGGER) {
            $modes[] = 'trigger';
        }
        if ($this->mode & PEAR_ERROR_DIE) {
            $modes[] = 'die';
        }
        if ($this->mode & PEAR_ERROR_RETURN) {
            $modes[] = 'return';
        }
        return sprintf('[%s: message="%s" code=%d mode=%s level=%s '.
                       'prefix="%s" info="%s"]',
                       strtolower(get_class($this)), $this->message, $this->code,
                       implode("|", $modes), $levels[$this->level],
                       $this->error_message_prefix,
                       $this->userinfo);
    }
}


//id3 v1

define('PEAR_MP3_ID_FNO', 1);
define('PEAR_MP3_ID_RE', 2);
define('PEAR_MP3_ID_TNF', 3);
define('PEAR_MP3_ID_NOMP3', 4);
class MP3_Id {
    var $file = false;
    var $id3v1 = false;
    var $id3v11 = false;
    var $id3v2 = false;
    var $name = '';
    var $artists = '';
    var $album = '';
    var $year = '';
    var $comment = '';
    var $track = 0;
    var $genre = '';
    var $genreno = 255;
    var $studied = false;
    var $mpeg_ver = 0;
    var $layer = 0;
    var $bitrate = 0;
    var $crc = false;
    var $frequency = 0;
    var $encoding_type = 0;
    var $samples_per_frame = 0;
    var $samples = 0;
    var $musicsize = -1;
    var $frames = 0;
    var $quality = 0;
    var $padding = false;
    var $private = false;
    var $mode = '';
    var $copyright = false;
    var $original = false;
    var $emphasis = '';
    var $filesize = -1;
    var $frameoffset = -1;
    var $lengthh = false;
    var $length = false;
    var $lengths = false;
    var $error = false;
    var $debug = false;
    var $debugbeg = '<DIV STYLE="margin: 0.5 em; padding: 0.5 em; border-width: thin; border-color: black; border-style: solid">';
    var $debugend = '</DIV>';
//////////////////////////////////////////////////
    function MP3_Id($study = false)
	{
        if(defined('ID3_SHOW_DEBUG')) $this->debug = true;
        $this->study=($study || defined('ID3_AUTO_STUDY'));
    }
/////////////////////////////////////////////////
    function read( $file="")
	{
        if ($this->debug) print($this->debugbeg . "id3('$file')<HR>\n");

        if(!empty($file))$this->file = $file;
        if ($this->debug) print($this->debugend);

        return $this->_read_v1();
    }
/////////////////////////////////////////////////
    function setTag($name, $value)
	{
        if( is_array($name))
		{
            foreach( $name as $n => $v)
			{
                $this -> $n = $v ;
            }
        }
		else
		{
            $this -> $name = $value ;
        }
    }
/////////////////////////////////////////////////
    function getTag($name, $default = 0)
	{
        if(empty($this -> $name))
		{
            return $default ;
        }
		else
		{
            return $this -> $name ;
        }
    }
//////////////////////////////////////////////////
    function write($v1 = true)
	{
    if ($this->debug) print($this->debugbeg . "write()<HR>\n");
    if ($v1)
	{
        $this->_write_v1();
    }
    if ($this->debug) print($this->debugend);
    }

    function study()
	{
	    $this->studied = true;
	    $this->_readframe();
    }

/////////////////////////////////////////////////////
    function copy($from)
	{
	    if ($this->debug) print($this->debugbeg . "copy(\$from)<HR>\n");
	    $this->name = $from->name;
	    $this->artists  = $from->artists;
	    $this->album    = $from->album;
	    $this->year = $from->year;
	    $this->comment  = $from->comment;
	    $this->track    = $from->track;
	    $this->genre    = $from->genre;
	    $this->genreno  = $from->genreno;
	    if ($this->debug) print($this->debugend);
    }
///////////////////////////////////////////////////
    function remove($id3v1 = true, $id3v2 = true)
	{
	    if ($this->debug) print($this->debugbeg . "remove()<HR>\n");

	    if ($id3v1)
		{
	        $this->_remove_v1();
	    }

	    if ($id3v2)
		{
	        
	    }

	    if ($this->debug) print($this->debugend);
    }

//////////////////////////////////////////////////
    function _read_v1()
	{
	    if ($this->debug) print($this->debugbeg . "_read_v1()<HR>\n");

	    $mqr = get_magic_quotes_runtime();
	    set_magic_quotes_runtime(0);

	    if (! ($f = @fopen($this->file, 'rb')) ) {
	        return PEAR::raiseError( "Unable to open " . $this->file, PEAR_MP3_ID_FNO);
	    }

	    if (fseek($f, -128, SEEK_END) == -1) {
	        return PEAR::raiseError( 'Unable to see to end - 128 of ' . $this->file, PEAR_MP3_ID_RE);
	    }

	    $r = fread($f, 128);
	    fclose($f);
	    set_magic_quotes_runtime($mqr);

	    if ($this->debug) {
	        $unp = unpack('H*raw', $r);
	        print_r($unp);
	    }

	    $id3tag = $this->_decode_v1($r);

	    if(!PEAR::isError( $id3tag)) {
	        $this->id3v1 = true;

	        $tmp = explode(Chr(0), $id3tag['NAME']);
	        $this->name = $tmp[0];

	        $tmp = explode(Chr(0), $id3tag['ARTISTS']);
	        $this->artists = $tmp[0];

	        $tmp = explode(Chr(0), $id3tag['ALBUM']);
	        $this->album = $tmp[0];

	        $tmp = explode(Chr(0), $id3tag['YEAR']);
	        $this->year = $tmp[0];

	        $tmp = explode(Chr(0), $id3tag['COMMENT']);
	        $this->comment = $tmp[0];

	        if (isset($id3tag['TRACK'])) {
	        $this->id3v11 = true;
	        $this->track = $id3tag['TRACK'];
	        }

	        $this->genreno = $id3tag['GENRENO'];
	        $this->genre = $id3tag['GENRE'];
	    } else {
	        return $id3tag ;
	        }

	    if ($this->debug) print($this->debugend);
    }
///////////////////////////////////////////////
    function _decode_v1($rawtag) {
    if ($this->debug) print($this->debugbeg . "_decode_v1(\$rawtag)<HR>\n");

    if ($rawtag[125] == Chr(0) and $rawtag[126] != Chr(0)) {

        $format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a28COMMENT/x1/C1TRACK/C1GENRENO';
    } else {

        $format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a30COMMENT/C1GENRENO';
    }

    $id3tag = unpack($format, $rawtag);
    if ($this->debug) print_r($id3tag);

    if ($id3tag['TAG'] == 'TAG') {
        $id3tag['GENRE'] = $this->getgenre($id3tag['GENRENO']);
    } else {
        $id3tag = PEAR::raiseError( 'TAG not found', PEAR_MP3_ID_TNF);
    }
    if ($this->debug) print($this->debugend);
    return $id3tag;
    }
//////////////////////////////////////
    function _write_v1() {
    if ($this->debug) print($this->debugbeg . "_write_v1()<HR>\n");

    $file = $this->file;

    if (! ($f = @fopen($file, 'r+b')) ) {
        return PEAR::raiseError( "Unable to open " . $file, PEAR_MP3_ID_FNO);
    }

    if (fseek($f, -128, SEEK_END) == -1) {
        return PEAR::raiseError( "Unable to see to end - 128 of " . $file, PEAR_MP3_ID_RE);
    }

    $this->genreno = $this->getgenreno($this->genre, $this->genreno);

    $newtag = $this->_encode_v1();
    
    $mqr = get_magic_quotes_runtime();
    set_magic_quotes_runtime(0);

    $r = fread($f, 128);

    if ( !PEAR::isError( $this->_decode_v1($r))) {
        if (fseek($f, -128, SEEK_END) == -1) {
        return PEAR::raiseError( "Unable to see to end - 128 of " . $file, PEAR_MP3_ID_RE);
        }
        fwrite($f, $newtag);
    } else {
        if (fseek($f, 0, SEEK_END) == -1) {
        return PEAR::raiseError( "Unable to see to end of " . $file, PEAR_MP3_ID_RE);
        }
        fwrite($f, $newtag);
    }
    fclose($f);
    set_magic_quotes_runtime($mqr);

    if ($this->debug) print($this->debugend);
    }
//////////////////////////////////////////////////
    function _encode_v1() {
    if ($this->debug) print($this->debugbeg . "_encode_v1()<HR>\n");

    if ($this->track) {
        $id3pack = 'a3a30a30a30a4a28x1C1C1';
        $newtag = pack($id3pack,
            'TAG',
            $this->name,
            $this->artists,
            $this->album,
            $this->year,
            $this->comment,
            $this->track,
            $this->genreno
              );
    } else {
        $id3pack = 'a3a30a30a30a4a30C1';
        $newtag = pack($id3pack,
            'TAG',
            $this->name,
            $this->artists,
            $this->album,
            $this->year,
            $this->comment,
            $this->genreno
              );
    }

    if ($this->debug) {
        print('id3pack: ' . $id3pack . "\n");
        $unp = unpack('H*new', $newtag);
        print_r($unp);
    }

    if ($this->debug) print($this->debugend);
    return $newtag;
    }
///////////////////////////////////////////////
    function _remove_v1() {
    if ($this->debug) print($this->debugbeg . "_remove_v1()<HR>\n");

    $file = $this->file;

    if (! ($f = fopen($file, 'r+b')) ) {
        return PEAR::raiseError( "Unable to open " . $file, PEAR_MP3_ID_FNO);
    }

    if (fseek($f, -128, SEEK_END) == -1) {
        return PEAR::raiseError( 'Unable to see to end - 128 of ' . $file, PEAR_MP3_ID_RE);
    }

    $mqr = get_magic_quotes_runtime();
    set_magic_quotes_runtime(0);

    $r = fread($f, 128);

    $success = false;
    if ( !PEAR::isError( $this->_decode_v1($r))) {
        $size = filesize($this->file) - 128;
        if ($this->debug) print('size: old: ' . filesize($this->file));
        $success = ftruncate($f, $size);
        clearstatcache();
        if ($this->debug) print(' new: ' . filesize($this->file));
    }
    fclose($f);
    set_magic_quotes_runtime($mqr);

    if ($this->debug) print($this->debugend);
    return $success;
    }
//////////////////////////////////////////////////
    function _readframe() {
    if ($this->debug) print($this->debugbeg . "_readframe()<HR>\n");

    $file = $this->file;

    $mqr = get_magic_quotes_runtime();
    set_magic_quotes_runtime(0);

    if (! ($f = fopen($file, 'rb')) ) {
        if ($this->debug) print($this->debugend);
        return PEAR::raiseError( "Unable to open " . $file, PEAR_MP3_ID_FNO) ;
    }

    $this->filesize = filesize($file);

    do {
        while (fread($f,1) != Chr(255)) {
        if ($this->debug) echo "Find...\n";
        if (feof($f)) {
            if ($this->debug) print($this->debugend);
            return PEAR::raiseError( "No mpeg frame found", PEAR_MP3_ID_NOMP3) ;
        }
        }
        fseek($f, ftell($f) - 1);

        $frameoffset = ftell($f);

        $r = fread($f, 4);
        $bits = sprintf("%'08b%'08b%'08b%'08b", ord($r{0}), ord($r{1}), ord($r{2}), ord($r{3}));
    } while (!$bits[8] and !$bits[9] and !$bits[10]);
    if ($this->debug) print('Bits: ' . $bits . "\n");

    $this->frameoffset = $frameoffset;

    if ($bits[11] == 0) {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 9;
        } else {
            $vbroffset = 17;
        }
    } else if ($bits[12] == 0) {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 9;
        } else {
            $vbroffset = 17;
        }
    } else {
        if (($bits[24] == 1) && ($bits[25] == 1)) {
            $vbroffset = 17;
        } else {
            $vbroffset = 32;
        }
    }

    fseek($f, ftell($f) + $vbroffset);
    $r = fread($f, 4);

    switch ($r) {
        case 'Xing':
            $this->encoding_type = 'VBR';
        case 'Info':
  
            if ($this->debug) print('Encoding Header: ' . $r . "\n");

            $r = fread($f, 4);
            $vbrbits = sprintf("%'08b", ord($r{3}));

            if ($this->debug) print('XING Header Bits: ' . $vbrbits . "\n");

            if ($vbrbits[7] == 1) {
                $r = fread($f, 4);
                $this->frames = unpack('N', $r);
                $this->frames = $this->frames[1];
            }

            if ($vbrbits[6] == 1) {
                $r = fread($f, 4);
                $this->musicsize = unpack('N', $r);
                $this->musicsize = $this->musicsize[1];
            }

            if ($vbrbits[5] == 1) {
                fseek($f, ftell($f) + 100);
            }

            if ($vbrbits[4] == 1) {
                $r = fread($f, 4);
                $this->quality = unpack('N', $r);
                $this->quality = $this->quality[1];
            }

            break;

        case 'VBRI':
        default:
            if ($vbroffset != 32) {
                fseek($f, ftell($f) + 32 - $vbroffset);
                $r = fread($f, 4);

                if ($r != 'VBRI') {
                    $this->encoding_type = 'CBR';
                    break;
                }
            } else {
                $this->encoding_type = 'CBR';
                break;
            }

            if ($this->debug) print('Encoding Header: ' . $r . "\n");

            $this->encoding_type = 'VBR';

            fseek($f, ftell($f) + 2);

            fseek($f, ftell($f) + 2);

            $r = fread($f, 2);
            $this->quality = unpack('n', $r);
            $this->quality = $this->quality[1];

            $r = fread($f, 4);
            $this->musicsize = unpack('N', $r);
            $this->musicsize = $this->musicsize[1];

			
            $r = fread($f, 4);
            $this->frames = unpack('N', $r);
            $this->frames = $this->frames[1];
    }

    fclose($f);
    set_magic_quotes_runtime($mqr);

    if ($bits[11] == 0) {
        $this->mpeg_ver = "2.5";
        $bitrates = array(
            '1' => array(0, 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256, 0),
            '2' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
            '3' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
                 );
    } else if ($bits[12] == 0) {
        $this->mpeg_ver = "2";
        $bitrates = array(
            '1' => array(0, 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256, 0),
            '2' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
            '3' => array(0,  8, 16, 24, 32, 40, 48,  56,  64,  80,  96, 112, 128, 144, 160, 0),
                 );
    } else {
        $this->mpeg_ver = "1";
        $bitrates = array(
            '1' => array(0, 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448, 0),
            '2' => array(0, 32, 48, 56,  64,  80,  96, 112, 128, 160, 192, 224, 256, 320, 384, 0),
            '3' => array(0, 32, 40, 48,  56,  64,  80,  96, 112, 128, 160, 192, 224, 256, 320, 0),
                 );
    }
    if ($this->debug) print('MPEG' . $this->mpeg_ver . "\n");

    $layer = array(
        array(0,3),
        array(2,1),
              );
    $this->layer = $layer[$bits[13]][$bits[14]];
    if ($this->debug) print('layer: ' . $this->layer . "\n");

    if ($bits[15] == 0) {
        if ($this->debug) print("protected (crc)\n");
        $this->crc = true;
    }

    $bitrate = 0;
    if ($bits[16] == 1) $bitrate += 8;
    if ($bits[17] == 1) $bitrate += 4;
    if ($bits[18] == 1) $bitrate += 2;
    if ($bits[19] == 1) $bitrate += 1;
    $this->bitrate = $bitrates[$this->layer][$bitrate];

    $frequency = array(
        '1' => array(
            '0' => array(44100, 48000),
            '1' => array(32000, 0),
                ),
        '2' => array(
            '0' => array(22050, 24000),
            '1' => array(16000, 0),
                ),
        '2.5' => array(
            '0' => array(11025, 12000),
            '1' => array(8000, 0),
                  ),
          );
    $this->frequency = $frequency[$this->mpeg_ver][$bits[20]][$bits[21]];

    $this->padding = $bits[22];
    $this->private = $bits[23];

    $mode = array(
        array('Stereo', 'Joint Stereo'),
        array('Dual Channel', 'Mono'),
             );
    $this->mode = $mode[$bits[24]][$bits[25]];

    $this->copyright = $bits[28];
    $this->original = $bits[29];

    $emphasis = array(
        array('none', '50/15ms'),
        array('', 'CCITT j.17'),
             );
    $this->emphasis = $emphasis[$bits[30]][$bits[31]];

    $samplesperframe = array(
        '1' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 1152
        ),
        '2' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 576
        ),
        '2.5' => array(
            '1' => 384,
            '2' => 1152,
            '3' => 576
        ),
    );
    $this->samples_per_frame = $samplesperframe[$this->mpeg_ver][$this->layer];

    if ($this->encoding_type != 'VBR') {
        if ($this->bitrate == 0) {
            $s = -1;
        } else {
            $s = ((8*filesize($this->file))/1000) / $this->bitrate;
        }
        $this->length = sprintf('%02d:%02d',floor($s/60),floor($s-(floor($s/60)*60)));
        $this->lengthh = sprintf('%02d:%02d:%02d',floor($s/3600),floor($s/60),floor($s-(floor($s/60)*60)));
        $this->lengths = (int)$s;

        $this->samples = ceil($this->lengths * $this->frequency);
        if(0 != $this->samples_per_frame) {
            $this->frames = ceil($this->samples / $this->samples_per_frame);
        } else {
            $this->frames = 0;
        }
        $this->musicsize = ceil($this->lengths * $this->bitrate * 1000 / 8);
    } else {
        $this->samples = $this->samples_per_frame * $this->frames;
        $s = $this->samples / $this->frequency;

        $this->length = sprintf('%02d:%02d',floor($s/60),floor($s-(floor($s/60)*60)));
        $this->lengthh = sprintf('%02d:%02d:%02d',floor($s/3600),floor($s/60),floor($s-(floor($s/60)*60)));
        $this->lengths = (int)$s;

        $this->bitrate = (int)(($this->musicsize / $s) * 8 / 1000);
    }

    if ($this->debug) print($this->debugend);
    }
////////////////////////////////////////////
    function getGenre($genreno) {
    if ($this->debug) print($this->debugbeg . "getgenre($genreno)<HR>\n");

    $genres = $this->genres();
    if (isset($genres[$genreno])) {
        $genre = $genres[$genreno];
        if ($this->debug) print($genre . "\n");
    } else {
        $genre = '';
    }

    if ($this->debug) print($this->debugend);
    return $genre;
    }
////////////////////////////////////////////
    function getGenreNo($genre, $default = 0xff) {
    if ($this->debug) print($this->debugbeg . "getgenreno('$genre',$default)<HR>\n");

    $genres = $this->genres();
    $genreno = false;
    if ($genre) {
        foreach ($genres as $no => $name) {
        if (strtolower($genre) == strtolower($name)) {
            if ($this->debug) print("$no:'$name' == '$genre'");
            $genreno = $no;
        }
        }
    }
    if ($genreno === false) $genreno = $default;
    if ($this->debug) print($this->debugend);
    return $genreno;
    }
////////////////////////////////////////////
    function genres() {
    return array(
        0   => 'Blues',
        1   => 'Classic Rock',
        2   => 'Country',
        3   => 'Dance',
        4   => 'Disco',
        5   => 'Funk',
        6   => 'Grunge',
        7   => 'Hip-Hop',
        8   => 'Jazz',
        9   => 'Metal',
        10  => 'New Age',
        11  => 'Oldies',
        12  => 'Other',
        13  => 'Pop',
        14  => 'R&B',
        15  => 'Rap',
        16  => 'Reggae',
        17  => 'Rock',
        18  => 'Techno',
        19  => 'Industrial',
        20  => 'Alternative',
        21  => 'Ska',
        22  => 'Death Metal',
        23  => 'Pranks',
        24  => 'Soundtrack',
        25  => 'Euro-Techno',
        26  => 'Ambient',
        27  => 'Trip-Hop',
        28  => 'Vocal',
        29  => 'Jazz+Funk',
        30  => 'Fusion',
        31  => 'Trance',
        32  => 'Classical',
        33  => 'Instrumental',
        34  => 'Acid',
        35  => 'House',
        36  => 'Game',
        37  => 'Sound Clip',
        38  => 'Gospel',
        39  => 'Noise',
        40  => 'Alternative Rock',
        41  => 'Bass',
        42  => 'Soul',
        43  => 'Punk',
        44  => 'Space',
        45  => 'Meditative',
        46  => 'Instrumental Pop',
        47  => 'Instrumental Rock',
        48  => 'Ethnic',
        49  => 'Gothic',
        50  => 'Darkwave',
        51  => 'Techno-Industrial',
        52  => 'Electronic',
        53  => 'Pop-Folk',
        54  => 'Eurodance',
        55  => 'Dream',
        56  => 'Southern Rock',
        57  => 'Comedy',
        58  => 'Cult',
        59  => 'Gangsta',
        60  => 'Top 40',
        61  => 'Christian Rap',
        62  => 'Pop/Funk',
        63  => 'Jungle',
        64  => 'Native US',
        65  => 'Cabaret',
        66  => 'New Wave',
        67  => 'Psychadelic',
        68  => 'Rave',
        69  => 'Showtunes',
        70  => 'Trailer',
        71  => 'Lo-Fi',
        72  => 'Tribal',
        73  => 'Acid Punk',
        74  => 'Acid Jazz',
        75  => 'Polka',
        76  => 'Retro',
        77  => 'Musical',
        78  => 'Rock & Roll',
        79  => 'Hard Rock',
        80  => 'Folk',
        81  => 'Folk-Rock',
        82  => 'National Folk',
        83  => 'Swing',
        84  => 'Fast Fusion',
        85  => 'Bebob',
        86  => 'Latin',
        87  => 'Revival',
        88  => 'Celtic',
        89  => 'Bluegrass',
        90  => 'Avantgarde',
        91  => 'Gothic Rock',
        92  => 'Progressive Rock',
        93  => 'Psychedelic Rock',
        94  => 'Symphonic Rock',
        95  => 'Slow Rock',
        96  => 'Big Band',
        97  => 'Chorus',
        98  => 'Easy Listening',
        99  => 'Acoustic',
        100 => 'Humour',
        101 => 'Speech',
        102 => 'Chanson',
        103 => 'Opera',
        104 => 'Chamber Music',
        105 => 'Sonata',
        106 => 'Symphony',
        107 => 'Booty Bass',
        108 => 'Primus',
        109 => 'Porn Groove',
        110 => 'Satire',
        111 => 'Slow Jam',
        112 => 'Club',
        113 => 'Tango',
        114 => 'Samba',
        115 => 'Folklore',
        116 => 'Ballad',
        117 => 'Power Ballad',
        118 => 'Rhytmic Soul',
        119 => 'Freestyle',
        120 => 'Duet',
        121 => 'Punk Rock',
        122 => 'Drum Solo',
        123 => 'Acapella',
        124 => 'Euro-House',
        125 => 'Dance Hall',
        126 => 'Goa',
        127 => 'Drum & Bass',
        128 => 'Club-House',
        129 => 'Hardcore',
        130 => 'Terror',
        131 => 'Indie',
        132 => 'BritPop',
        133 => 'Negerpunk',
        134 => 'Polsk Punk',
        135 => 'Beat',
        136 => 'Christian Gangsta Rap',
        137 => 'Heavy Metal',
        138 => 'Black Metal',
        139 => 'Crossover',
        140 => 'Contemporary Christian',
        141 => 'Christian Rock',
        142 => 'Merengue',
        143 => 'Salsa',
        144 => 'Trash Metal',
        145 => 'Anime',
        146 => 'Jpop',
        147 => 'Synthpop'
            );
    }
}


//id3 v2


class ID3{

   var $file_name=''; //full path to the file
   					  //the sugestion is that this path should be a
                      //relative path
   var $tags;   //array with ID3 tags extracted from the file
   var $last_error_num=0; //keep the number of the last error ocurred
   var $tags_count = 0; // the number of elements at the tags array
   /*********************/
   /**private functions**/
   /*********************/

   function hex2bin($data) {
   //thankz for the one who wrote this function
   //If iknew your name I would say it here
      $len = strlen($data);
      for($i=0;$i<$len;$i+=2) {
         $newdata .= pack("C",hexdec(substr($data,$i,2)));
      }
   return $newdata;
   }
   
   function get_frame_size($fourBytes){
      $tamanho[0] = str_pad(base_convert(substr($fourBytes,0,2),16,2),7,0,STR_PAD_LEFT);
      $tamanho[1] = str_pad(base_convert(substr($fourBytes,2,2),16,2),7,0,STR_PAD_LEFT);
      $tamanho[2] = str_pad(base_convert(substr($fourBytes,4,2),16,2),7,0,STR_PAD_LEFT);
      $tamanho[3] = str_pad(base_convert(substr($fourBytes,6,2),16,2),7,0,STR_PAD_LEFT);
      $total =    $tamanho[0].$tamanho[1].$tamanho[2].$tamanho[3];
      $tamanho[0] = substr($total,0,8);
      $tamanho[1] = substr($total,8,8);
      $tamanho[2] = substr($total,16,8);
      $tamanho[3] = substr($total,24,8);
      $total =    $tamanho[0].$tamanho[1].$tamanho[2].$tamanho[3];
		$total = base_convert($total,2,10);
   	return $total;
	}
	
   function extractTags($text,&$tags){
      $size = -1;//inicializando diferente de zero para nгo sair do while
   	while ((strlen($text) != 0) and ($size != 0)){
      //while there are tags to read and they have a meaning
   	//while existem tags a serem tratadas e essas tags tem conteudo
			$ID    = substr($text,0,4);
      	$aux   = substr($text,4,4);
         $aux   = bin2hex($aux);
         $size  = $this->get_frame_size($aux);
         $flags = substr($text,8,2);
         $info  = substr($text,11,$size-1);
         if ($size != 0){
            $tags[$ID] = $info;
            $this->tags_count++;
         }
         $text = substr($text,10+$size,strlen($text));
   	}
   }
   
   /********************/
   /**public functions**/
   /********************/
   /**Constructor**/

   function ID3($file_name){
      $this->file_name = $file_name;
      $this->last_error_num = 0;
   }
   
   /**Read the file and put the TAGS
   content on $this->tags array**/
   function getInfo(){
		if ($this->file_name != ''){
			$mp3 = @fopen($this->file_name,"r");
       	$header = @fread($mp3,10);
         if (!$header) {
         	$this->last_error_num = 2;
            return false;
            die();
         }
       	if (substr($header,0,3) != "ID3"){
         	$this->last_error_num = 3;
            return false;
          	die();
       	}
       	$header = bin2hex($header);
   		$version = base_convert(substr($header,6,2),16,10).".".base_convert(substr($header,8,2),16,10);
   		$flags = base_convert(substr($header,10,2),16,2);
   		$flags = str_pad($flags,8,0,STR_PAD_LEFT);
   		if ($flags[7] == 1){
   			//echo('with Unsynchronisation<br>');
   		}
   		if ($flags[6] == 1){
   			//echo('with Extended header<br>');
   		}
   		if ($flags[5] == 1){//Esperimental tag
            $this->last_error_num = 4;
            return false;
          	die();
   		}
   		$total = $this->get_frame_size(substr($header,12,8));
         $text = @fread($mp3,$total);
   		fclose($mp3);
         $this->extractTags($text,$this->tags);
      }
      else{
         $this->last_error_num = 1;//file not set
         return false;
      	die();
      }
   	return true;
   }
   
   /*************
   *   PUBLIC
   * Functions to get information
   * from the ID3 tag
   **************/
   function getArtist(){
      if (array_key_exists('TPE1',$this->tags)){
      	return $this->tags['TPE1'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
   function getTrack(){
      if (array_key_exists('TRCK',$this->tags)){
      	return $this->tags['TRCK'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
   function getTitle(){
      if (array_key_exists('TIT2',$this->tags)){
      	return $this->tags['TIT2'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
   function getAlbum(){
      if (array_key_exists('TALB',$this->tags)){
      	return $this->tags['TALB'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
   function getYear(){
      if (array_key_exists('TYER',$this->tags)){
      	return $this->tags['TYER'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
   function getGender(){
      if (array_key_exists('TCON',$this->tags)){
      	return $this->tags['TCON'];
      }else{
      	$this->last_error_num = 5;
         return false;
      }
   }
   
}


//SCANNING FOLDER FOR MP3 FILES


$file_dir = $_GET['file_dir'];


function filearray($start) {
global $exclude_files;
  $dir=opendir($start);
  while (false !== ($found=readdir($dir))) { $getit[]=$found; 
}
  foreach($getit as $num => $item) {

   if (is_dir($start.$item) && $item!="." && $item!=".." && array_search($item, $exclude_files)===false) { $output["{$item}"]=filearray($start.$item."/"); }
   if (is_file($start.$item) && array_search($item, $exclude_files)===false) { $output["{$item}"]=$start.$item; }
  }
  closedir($dir);
  ksort($output);
  return $output;

}
$ff = filearray($file_dir."/");


function printXML($arr) {

	foreach($arr as $key => $val) {

		if(is_array($val)) {
		$folder_title=substr($key, $omit_folder_chars);
		
		ksort($val);
			 printXML($val);
		
		}else {
		$file = $val;
			if(substr($file, -3) == 'mp3' || (substr($file, -3) == 'MP3')) {
					$file_title=substr($file,0,strlen($file)-4);

// ID3v1 and ID3v2  tags parser

$nome_arq  = $file;

$myId3 = new ID3($nome_arq);

if ($myId3->getInfo())
 {
  $f_title=$myId3->getTitle();       
  $f_artist=$myId3->getArtist();
}

if(!($myId3->getArtist()))
 {
  $id3 = &new MP3_Id();
  $result = $id3->read($file);
  $f_title=$id3->getTag('name');
  $f_artist=$id3->getTag('artists');
 }

  
 if ( $f_artist == '0' && $f_title == '0'  )
 {
   $arr_names =  explode('/',strrev($nome_arq));
   $substr = substr($arr_names[0],4,strlen($arr_names[0]));
   $arr_names_last = explode('_',strrev($substr));
   
   //$f_artist = $arr_names_last[0];
   //$f_title = $arr_names_last [1];
   
   $f_artist = '';
   $f_title = strrev($substr);
   
 }
 
 
 if($f_artist == '0'){
 	
 	$f_artist='';
 }


$f_id=str_replace("/","",$file_title);
$f_id=str_replace(".","",$f_id);
$f_id=str_replace(">","",$f_id);
$f_id=str_replace("<","",$f_id);
$f_id=str_replace("=","",$f_id);
$f_id=str_replace("(","",$f_id);
$f_id=str_replace(")","",$f_id);
$f_id=str_replace("\"","",$f_id);


$f_title=encoding($f_title);
$f_artist=encoding($f_artist);

$f_title=conv($f_title);
$f_artist=conv($f_artist);


$file=encoding($file);


$f_title=str_replace(">","",$f_title);
$f_title=str_replace("<","",$f_title);

$f_artist=str_replace(">","",$f_artist);
$f_artist=str_replace("<","",$f_artist);

$f_id=encoding($f_id);
$f_id=conv($f_id);
$path= $_SERVER['HTTP_HOST'] .$_SERVER['PHP_SELF'];
$path=dirname($path);

$file='http://'.$path.'/'.$file;

// Writing in XML

   print '
   <song id="'.$f_id.'"  title="'.$f_title.'" artist="'.$f_artist.'"  src="'.$file.'" />';
				}
		}
	}
}
print '<?xml version="1.0" encoding="utf-8"?>';
print '
<playlist>';
printXML ($ff);
print '
</playlist>';
?>