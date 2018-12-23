<?php

class Table
{

	var $th;
	var $td;
	var $return;
	var $style = 1;
	var $width;

	function __construct() {

		$ids = uniqid();
		$this->return .= "<table style=\"width: 100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"table\">";
	}

	function render($data) {
		$num = count( $data ) - 1;
		$i   = 0;
		if ( isset( $data[0] ) && !empty( $data[0] ) ) {

			foreach ( $data[0] as $key => $val ) {
				$this->th .= $this->th( $key );
			}

			$this->return .= "<thead>";
			$this->return .= $this->tr( $this->th, "th" );
			$this->return .= "</thead>";
			$this->return .= "<tbody>";
			while ( $i <= $num ) {
				$this->td = '';
				if ( $this->style == 2 ) {
					$this->style = 1;
				} else {
					$this->style = 2;
				}

				foreach ( $data[$i] as $key => $val ) {
					$this->td .= $this->td( $val );
				}
				$this->return .= $this->tr( $this->td, ( ( $this->style == 1 ) ? "tr2" : "tr" ) );
				$i++;
			}
			$this->return .= "</tbody>";
			$this->return .= $this->finish();
			return $this->return;
		}
	}

	function th( $key ) {

		return "\n\t\t<th " . ( isset( $this->width[$key] ) ? "style=\"width: " . $this->width[$key] . "\"" : "" ) . " class=\"th\" nowrap=\"nowrap\">" . ( !empty( $key ) ? $key : '&nbsp;' ) . "</th>";
	}

	function td( $val ) {

		return "\n\t\t<td" . ( $this->style == 2 ? "  class=\"td\"" : " class=\"td2\"" ) . ">" . ( !empty( $val ) ? $val : '&nbsp;' ) . "</td>";
	}

	function tr( $info, $type = FALSE ) {

		return "\n\t<tr" . ( ( $type != FALSE ) ? " style=\"margin:0;vertical-align:top;\" class=\"$type\"" : " class=\"tr" . $this->style . "\"" ) . ">" . ( !empty( $info ) ? $info : '&nbsp;' ) . "\n\t</tr>";
	}

	function finish() {

		return "\n</table>\n";
	}
}
