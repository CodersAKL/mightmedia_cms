<?php
//LENTELĖS KLASĖ
class Table
{

	var $th;
	var $td;
	var $return;
	var $style = 1;
    var $width;
    
    private $data;

	public function __construct($data) {
        $this->data = $data;

		$ids = uniqid();

		$this->return .= '<table class="table table-bordered table-striped table-hover">';
	}

	public function render() {
		$num = count($this->data) - 1;
		$i   = 0;
		if ( isset($this->data[0]) && !empty($this->data[0]) ) {

			foreach ($this->data[0] as $key => $val) {
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

				foreach ($this->data[$i] as $key => $val) {
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

	public function th( $key ) {

		return "\n\t\t<th " . ( isset( $this->width[$key] ) ? "style=\"width: " . $this->width[$key] . "\"" : "" ) . " class=\"th\" nowrap=\"nowrap\">" . ( !empty( $key ) ? $key : '&nbsp;' ) . "</th>";
	}

	public function td( $val ) {

		return "\n\t\t<td" . ( $this->style == 2 ? "  class=\"td\"" : " class=\"td2\"" ) . ">" . ( !empty( $val ) ? $val : '&nbsp;' ) . "</td>";
	}

	public function tr( $info, $type = FALSE ) {

		return "\n\t<tr" . ( ( $type != FALSE ) ? " style=\"margin:0;vertical-align:top;\" class=\"$type\"" : " class=\"tr" . $this->style . "\"" ) . ">" . ( !empty( $info ) ? $info : '&nbsp;' ) . "\n\t</tr>";
	}

	public function finish() {

		return "\n</table>\n";
	}
}