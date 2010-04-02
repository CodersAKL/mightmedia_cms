<?php
/*
Uploadify v2.1.0
Release Date: August 24, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

require_once('../../../../../../../../priedai/conf.php');
require_once('../../../../../../../../priedai/funkcijos.php');

$fileArray = array();
foreach ($_POST as $key => $value) {
	/*if ($key == 'folder') {
		$folder = explode('//',$value);
		file_put_contents(ROOTAS.'temp.txt', $key .'=>'.$folder[1]);
	}*/
	if ($key != 'folder') {
		//file_put_contents(ROOTAS.'temp.txt',str_replace('javascript/htmlarea/markitup/sets/default/utils/manager/','',$_POST['folder']) . '/' . $value);
		if (file_exists($_SERVER['DOCUMENT_ROOT'].str_replace('javascript/htmlarea/markitup/sets/default/utils/manager/','',$_POST['folder']) . '/' . $value)) {
			$fileArray[$key] = $value;
		}
	}

}
echo json_encode($fileArray);
?>