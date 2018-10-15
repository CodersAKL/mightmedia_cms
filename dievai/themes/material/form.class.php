<?php

class Form
{
    private $inputs;
    private $defaultClass;

    public function __construct($inputs)
    {
        $this->inputs = $inputs;
        $this->defaultClass = [
            'form'      => 'form-horizontal',
            'input'     => 'form-control', // .input
            'select'    => 'form-control show-tick', // .select
            'radio'     => 'with-gap',
            'button'    => 'btn btn-primary waves-effect' //.submit
        ];
    }

	public function form() {
        $return = '';

		if (is_array($this->inputs)) {
                        
			if (isset( $this->inputs['Form'])) {
                $return .= "\n<form" . ( isset($this->inputs['Form']['id'] ) ? " id=\"" . $this->inputs['Form']['id'] . "\"" : "" ) . "
                " . ( isset( $this->inputs['Form']['name'] ) ? " name=\"" . $this->inputs['Form']['name'] . "\"" : "" ) . "
                " . ( isset( $this->inputs['Form']['method'] ) ? " method=\"" . $this->inputs['Form']['method'] . "\"" : "" ) . "
                " . ( isset( $this->inputs['Form']['action'] ) ? " action=\"" . $this->inputs['Form']['action'] . "\"" : "" ) . "
                " . ( isset( $this->inputs['Form']['enctype'] ) ? " enctype=\"" . $this->inputs['Form']['enctype'] . "\"" : "" ) . "
                class=\"" . $this->defaultClass['form'] . ( isset( $this->inputs['Form']['class'] ) ? " " . $this->inputs['Form']['class'] : "" ) . "\"
                " . ( isset( $this->inputs['Form']['onSubmit'] ) ? " onSubmit=\"" . $this->inputs['Form']['onSubmit'] . "\"" : "" ) . ">\n";
            }
            
			foreach ($this->inputs as $pav => $input) {
				if (! empty($input) && $pav != 'Form') {
                    $return .= '<div class="row clearfix">';
                    $return .= '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">';
                    $return .= '<label for="' . (isset($input['name']) ? $input['name'] : '' ) . '">' . (!empty($pav) ? $pav : '&nbsp;') . '</label>';
                    $return .= '</div>';
                    $return .= '<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">';
                    $return .= '<div class="form-group">';
                    $return .= '<div class="form-line">';
                    $return .= $this->input($input);
                    $return .= '</div>';
                    $return .= '</div>';
					$return .= '</div>';
                    $return .= '</div>';
                }
            }
            
        }
        
		if ( isset( $this->inputs['Form'] ) ) {
			$return .= "</form>\n";
        }
        
		return $return;
	}

	public function input($array) {

		if ( is_array( $array ) ) {
			switch ( $array['type'] ) {
				case "select":
					{
                        $return = '';
                        if ( is_array( $array['value'] ) ) {
                            $return = "<select" . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                            class=\"" . $this->defaultClass['select'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                            " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                            " . ( isset( $array['jump'] ) ? " onchange=\"top.location.href='" . $array['jump'] . "' + this.value;\"" : "" ) . "
                            " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">\n";
                            
                            foreach ($array['value'] as $val => $pav ) {
                                $return .= "\t\t\t\t<option value=\"" . $val . "\"
                                " . ( ( ( isset( $array['selected'] ) && !is_array( $array['selected'] ) 
                                && stripslashes( $array['selected'] ) == stripslashes( $val ) ) 
                                || ( isset( $array['selected'] ) 
                                && is_array( $array['selected'] ) 
                                && in_array( $val, $array['selected'] ) ) ) ? " selected=\"selected\"" : "" ) . "
                                " . ( isset( $array['disabled'] ) && ( $array['disabled'] == $val ) ? " disabled=\"disabled\"" : "" ) . ">" . $pav . "</option>\n";
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
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "reset":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "password":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "file":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "image":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['src'] ) ? " src=\"" . $array['src'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "hidden":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "" ) . "
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "button":
					{
                    return "<button type=\"" . $array['type'] . "\" 
                    " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "class=\"input\"" ) . "
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">
                    " . ( isset( $array['value'] ) ? $array['value'] : "" ) . "
                    </button>";
					}
				case "submit":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['button'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "radio":
					{
                    return "<label for='" . $array['id'] . "'>Radio - With Gap</label>
                    <input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['radio'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "checkbox":
					{
                    return "<label for='" . $array['id'] . "'>Radio - With Gap</label>
                    <input type=\"" . $array['type'] . "\" 
                    " . ( isset( $array['class'] ) ? "class=\"" . $array['class'] . "\"" : "" ) . "
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "textarea":
					{
                    return "<" . $array['type'] . "
                    " . ( isset( $array['rows'] ) ? " rows=\"" . $array['rows'] . "\"" : "" ) . "
                    " . ( isset( $array['class'] ) ? " class=\"" . $array['class'] . "\"" : "" ) . "
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">" . ( isset( $array['value'] ) ? $array['value'] : "" ) . "</" . $array['type'] . ">";
					}
			}
		}
	}
}