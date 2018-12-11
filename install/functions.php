<?php
session_start();

@ini_set( 'error_reporting', E_ALL );
@ini_set( 'display_errors', 'On' );

if (isset($_SESSION['language'])) {
	include_once(ROOT . "lang/" . $_SESSION['language']);
} else {
	include_once(ROOT . "lang/lt.php");
}
/**
 * Svetainės adresui gauti
 *
 * @return string
 */
if(! function_exists('adresas')) {
	function adresas() {
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$adresas = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
			$adresas .= '://' . $_SERVER['HTTP_HOST'];
			$adresas .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );
		} else {
			$adresas = 'http://localhost/';
		}

		return $adresas;
	}
}

function stepClass($currentStep, $key)
{
    if ($currentStep < $key) {
        $return = 'disabled';
    } elseif($currentStep == $key) {
        $return = 'active';
    } else {
		$return = 'list-group-item-success';
	}

    return $return;
}

// Sugeneruojam atsitiktinį duomenų bazės prieždėlį
function random( $return = '' ) {

	$simboliai = "abcdefghijkmnopqrstuvwxyz0123456789";
	for ( $i = 1; $i < 3; ++$i ) {
		$num = rand() % 33;
		$return .= substr( $simboliai, $num, 1 );
	}
	return $return . '_';
}

function notifyMsg($data) {
	?>
	<script>
		$(function () {
			showNotification(
				'<?php echo $data['type']; ?>', 
				'<?php echo $data['message']; ?>'
			);
		});
	</script>
	<?php
}

function koduoju( $pass ) {
	return md5( sha1( md5( $pass ) ) );
}