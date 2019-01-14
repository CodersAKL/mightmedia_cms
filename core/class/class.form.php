<?php

class Form
{
	public function table( $pav, $text ) {

		return "\n<table style=\"width: 100%\" border=\"0\" class=\"form_table\">\n\t<tr>\n\t\t<td><fieldset style=\"border:silver solid 1px\"><legend style=\"color: silver\">$pav</legend>$text</fieldset></td>\n\t</tr>\n</table>\n";
	}

	public function render($inputs = []) {

		if ( is_array( $inputs ) ) {
			$return = '';
			if ( isset( $inputs['Form'] ) ) {
				$return .= "\n<form" . ( isset( $inputs['Form']['id'] ) ? " id=\"" . $inputs['Form']['id'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['name'] ) ? " name=\"" . $inputs['Form']['name'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['method'] ) ? " method=\"" . $inputs['Form']['method'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['action'] ) ? " action=\"" . $inputs['Form']['action'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['enctype'] ) ? " enctype=\"" . $inputs['Form']['enctype'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['class'] ) ? " class=\"" . $inputs['Form']['class'] . "\"" : "" ) . "" . ( isset( $inputs['Form']['onSubmit'] ) ? " onSubmit=\"" . $inputs['Form']['onSubmit'] . "\"" : "" ) . ">\n";
			}
			$return .= "<table border=\"0\" style=\"width: 100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"form_table\">";
			foreach ( $inputs as $pav => $type ) {
				if ( !empty( $type ) && $pav != 'Form' ) {
					$return .= "\n\t<tr>\n\t\t<td align=\"right\" >" . ( !empty( $pav ) ? $pav : '&nbsp;' ) . "</td>";
					$return .= "\n\t\t<td align=\"left\" >\n\t\t\t" . $this->input( $type ) . "\n\t\t</td>\n\t</tr>";
				}
			}
			$return .= "\n</table>\n";
		}
		if ( isset( $inputs['Form'] ) ) {
			$return .= "</form>\n";
		}
		return $return;
	}

	public function input( $array ) {

		if ( is_array( $array ) ) {
			switch ( $array['type'] ) {
				case "select":
					{
					$return = '';
					if ( is_array( $array['value'] ) ) {
						$return = "<select" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['class'] ) ? " class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['jump'] ) ? " onchange=\"top.location.href='" . $array['jump'] . "' + this.value;\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">\n";
						foreach ( $array['value'] as $val => $pav ) {
							$return .= "\t\t\t\t<option value=\"" . $val . "\"" . ( ( ( isset( $array['selected'] ) && !is_array( $array['selected'] ) && stripslashes( $array['selected'] ) == stripslashes( $val ) ) || ( isset( $array['selected'] ) && is_array( $array['selected'] ) && in_array( $val, $array['selected'] ) ) ) ? " selected=\"selected\"" : "" ) . "" . ( isset( $array['disabled'] ) && ( $array['disabled'] == $val ) ? " disabled=\"disabled\"" : "" ) . ">" . $pav . "</option>\n";
						}
						$return .= "\t\t\t</select>";
					}
					return $return;
					}
				case "string":
					{
					return $array['value'] . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" );
					}
				case "text":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "reset":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "password":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "file":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "image":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['src'] ) ? " src=\"" . $array['src'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "hidden":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "button":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "submit":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"submit\"" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "radio":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "checkbox":
					{
					return "<input type=\"" . $array['type'] . "\" " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "textarea":
					{
					return "<" . $array['type'] . "" . ( isset( $array['rows'] ) ? " rows=\"" . $array['rows'] . "\"" : "" ) . "" . ( isset( $array['class'] ) ? " class=\"" . $array['class'] . "\"" : "" ) . "" . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "" . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">" . ( isset( $array['value'] ) ? $array['value'] : "" ) . "</" . $array['type'] . ">";
					}
			}
		}
	}
}