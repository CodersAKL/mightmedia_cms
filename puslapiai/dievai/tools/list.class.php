<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

/*
* This class implements a PHP wrapper around the scriptaculous javascript libraries created by
* Thomas Fuchs (http://script.aculo.us/).
*
* SLLists was created by Greg Neustaetter in 2005 and may be used for free by anyone for any purpose.  Just keep my name in here please and
* give me credit if you like, but give Thomas all the real credit!
*/
class SLLists {

	var $lists = array();
	var $jsPath;
	var $debug = false;
	
	function SLLists($jsPath) {
		$this->jsPath = $jsPath;
	}
	
	function addList($list, $input, $tag = 'li', $additionalOptions = '') {
		if ($additionalOptions != '') $additionalOptions = ','.$additionalOptions;
		$this->lists[] = array("list" => $list, "input" => $input, "tag" => $tag, "additionalOptions" => $additionalOptions);
	}
	
	function printTopJS() {
		$return = '
		<script language="JavaScript" type="text/javascript"><!--
			function populateHiddenVars() {';
				foreach($this->lists as $list) {
					$return .= "document.getElementById('".$list['input']."').value = Sortable.serialize('".$list['list']."');";
				}
				$return .= '
				return true;
			}
			//-->
		</script>';
		return $return;
	}
	
	function printBottomJs() {
		$return = '
		 <script type="text/javascript">
			// <![CDATA[
			';
			foreach($this->lists as $list) {
				$return .= "
				Sortable.create('".$list['list']."',{tag:'".$list['tag']."'".$list['additionalOptions']."});";
			}
		$return .= '
			// ]]>
		 </script>';
		return $return;
	}
	
	function printHiddenInputs() {
		$inputType = ($this->debug) ? 'text' : 'hidden';
		$return = '';
		foreach($this->lists as $list) {
			if ($this->debug) $return .= '<br>'.$list['input'].': ';
			$return .= '<input type="'.$inputType.'" name="'.$list['input'].'" id="'.$list['input'].'" size="60">';
		}
		if ($this->debug) $return .= '<br>';
		return $return;
	}
	
	function printForm($action, $method = 'POST', $submitText = 'Submit', $submitClass = '',$formName = 'sortableListForm') {
		$return = '
		<form action="'.$action.'" method="'.$method.'" onSubmit="populateHiddenVars();" name="'.$formName.'" id="'.$formName.'">
			'.$this->printHiddenInputs().'
			<input type="hidden" name="sortableListsSubmitted" value="true">
			';
			if ($this->debug) {
				$return .= '<input type="button" value="View Serialized Lists" class="'.$submitClass.'" onClick="populateHiddenVars();"><br>';
			}
			$return .= '
			<input type="submit" value="'.$submitText.'" class="'.$submitClass.'">
		</form>
		';
		return $return;
	}
	
	function getOrderArray($input,$listname,$itemKeyName = 'element',$orderKeyName = 'order') {
		parse_str($input,$inputArray);
		$inputArray = $inputArray[$listname];
		$orderArray = array();
		for($i=0;$i<count($inputArray);$i++) {
			$orderArray[] = array($itemKeyName => $inputArray[$i], $orderKeyName => $i +1);
		}
		return $orderArray;
	}

}