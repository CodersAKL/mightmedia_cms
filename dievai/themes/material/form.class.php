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
            'checkbox'  => 'filled-in',
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
                    if($input['type'] === 'hidden') {
                        $return .= $this->input($input);
                    } else {
                        $return .= '<div class="row clearfix ' . (isset($input['row_class']) ? $input['row_class'] : '' ) . '">';
                        $return .= '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">';
                        if(!empty($pav)) {
                            $return .= '<label for="' . (isset($input['name']) ? $input['name'] : '' ) . '">' . $pav . '</label>';
                        }
                        $return .= '</div>';
                        $return .= '<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">';
                        $return .= '<div class="form-group">';
                        $return .= '<div class="' . (! empty($input['form_line']) ? $input['form_line'] : 'form-line') . '">';
                        $return .= $this->input($input);
                        $return .= '</div>';
                        $return .= '</div>';
                        $return .= '</div>';
                        $return .= '</div>';
                    }
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
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "reset":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "password":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
					}
				case "file":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
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
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['radio'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>
                    <label for='" . $array['id'] . "'></label>";
					}
				case "checkbox":
					{
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['checkbox'] . ( isset( $array['class'] ) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>
                    <label for='" . $array['id'] . "'></label>";
                    }
                case "switch":
                    {
                        return '<div class="switch">
                        <label>
                        ' . (isset($array['label_on']) ? $array['label_on'] : '') . '
                        <input 
                        type="checkbox" 
                        ' . (isset($array['checked']) && $array['checked'] ? ' checked' : '') . '
                        ' . (isset($array['name']) ? ' name="' . $array['name'] . '"' : '') . '
                        ' . (isset($array['value']) ? ' value="' . $array['value'] . '"' : '') . '
                        ' . (isset($array['id']) ? ' id="' . $array['id'] . '"' : '') . '
                        >
                        <span class="lever switch-col-orange"></span>
                        ' . (isset($array['label_off']) ? $array['label_off'] : '') . '
                        </label>
                        </div>';
                    }
				case "textarea":
					{
                    return "<textarea
                    rows=\"1\" class=\"form-control no-resize auto-growth" . ( isset( $array['class'] ) ? ' ' . $array['class'] : '' ) . "\"
                    " . ( isset( $array['rows'] ) ? " rows=\"" . $array['rows'] . "\"" : "" ) . "
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . ">" . ( isset( $array['value'] ) ? $array['value'] : "" ) . "</" . $array['type'] . ">";
                    }
                    
                default:
                {
                    return "<input type=\"" . $array['type'] . "\" 
                    class=\"" . $this->defaultClass['input'] . (isset($array['class']) ? " " . $array['class'] : "" ) . "\"
                    " . ( isset( $array['id'] ) ? " id=\"" . $array['id'] . "\"" : "" ) . "
                    " . ( isset( $array['name'] ) ? " name=\"" . $array['name'] . "\"" : "" ) . "
                    " . ( isset( $array['value'] ) ? " value=\"" . $array['value'] . "\"" : "" ) . "
                    " . ( isset( $array['placeholder'] ) ? " placeholder=\"" . $array['placeholder'] . "\"" : "" ) . "
                    " . ( isset( $array['style'] ) ? " style=\"" . $array['style'] . "\"" : "" ) . ( isset( $array['extra'] ) ? ' ' . $array['extra'] : "" ) . "/>";
                }
                //todo: checkbox or radio list
			}
		}
	}
}