<?php

define('SAFE_HTML_VERSION', 'safe_html.php/0.6');

/* safe_html.php
Copyright 2003 by Chris Snyder (csnyder@chxo.com)
Free to use and redistribute, but see License and Disclaimer below

- Huge thanks to James Wetterau for initial testing and feedback!
- Originally posted at http://lists.nyphp.org/pipermail/talk/2003-May/003832.html

Version History:
2007-01-29 - 0.6 -- added additional check after tag stripping, thanks to GĆ¶rg Pflug for exploit!
-- finally linked to standard tests page in demo
2005-09-05 - 0.5 -- upgrade to handle cases at http://ha.ckers.org/xss.html
2005-04-24 - 0.4 -- added check for encoded ascii entities
2003-05-31 - 0.3 -- initial public release

License and Disclaimer:
Copyright 2003 Chris Snyder. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, 
are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this 
list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this 
list of conditions and the following disclaimer in the documentation and/or other 
materials provided with the distribution.

THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
AUTHOR OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;  LOSS OF USE, DATA, OR PROFITS;
OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  

*/

if (!empty($_GET['source']) && $_GET['source'] == 'safe_html') {
	header('Content-Type: text/plain');
	exit(file_get_contents(__file__));
}

// first, an HTML attribute stripping function used by safe_html()
//   after stripping attributes, this function does a second pass
//   to ensure that the stripping operation didn't create an attack
//   vector.
function strip_attributes($html, $attrs) {
	if (!is_array($attrs)) {
		$array = array("$attrs");
		unset($attrs);
		$attrs = $array;
	}

	foreach ($attrs as $attribute) {
		// once for ", once for ', s makes the dot match linebreaks, too.
		$search[] = "/" . $attribute . '\s*=\s*".+"/Uis';
		$search[] = "/" . $attribute . "\s*=\s*'.+'/Uis";
		// and once more for unquoted attributes
		$search[] = "/" . $attribute . "\s*=\s*\S+/i";
	}
	$html = preg_replace($search, "", $html);

	// do another pass and strip_tags() if matches are still found
	foreach ($search as $pattern) {
		if (preg_match($pattern, $html)) {
			$html = strip_tags($html);
			break;
		}
	}

	return $html;
}

function js_and_entity_check($html) {
	// anything with ="javascript: is right out -- strip all tags if found
	$pattern = "/=[\S\s]*s\s*c\s*r\s*i\s*p\s*t\s*:\s*\S+/Ui";
	if (preg_match($pattern, $html)) {
		return true;
	}

	// anything with encoded entites inside of tags is out, too
	$pattern = "/<[\S\s]*&#[x0-9]*[\S\s]*>/Ui";
	if (preg_match($pattern, $html)) {
		return true;
	}

	return false;
}

// the safe_html() function
//   note, there is a special format for $allowedtags, see ~line 90
function safe_html($html, $allowedtags = "") {

	// check for obvious oh-noes
	if (js_and_entity_check($html)) {
		$html = strip_tags($html);
		return $html;
	}

	// setup -- $allowedtags is an array of $tag=>$closeit pairs,
	//   where $tag is an HTML tag to allow and $closeit is 1 if the tag
	//   requires a matching, closing tag
	if ($allowedtags == "") {
		$allowedtags = array("p" => 1, "br" => 0, "a" => 1, "img" => 0, "li" => 1, "ol" => 1, "ul" => 1, "b" => 1, "i" => 1, "em" => 1, "strong" => 1, "del" => 1, "ins" => 1, "u" => 1, "code" => 1, "pre" => 1, "blockquote" => 1, "hr" => 0, "span" => 1, "sup" => 1, "sub" => 1, "font" => 1, "h1" => 1, "h2" => 1, "h3" => 1, "h4" => 1, "h5" => 1, "h6" => 1);
	} elseif (!is_array($allowedtags)) {
		$array = array("$allowedtags");
	}

	// there's some debate about this.. is strip_tags() better than rolling your own regex?
	// note: a bug in PHP 4.3.1 caused improper handling of ! in tag attributes when using strip_tags()
	$stripallowed = "";
	foreach ($allowedtags as $tag => $closeit) {
		$stripallowed .= "<$tag>";
	}

	//print "Stripallowed: $stripallowed -- ".print_r($allowedtags,1);
	$html = strip_tags($html, $stripallowed);

	// also, lets get rid of some pesky attributes that may be set on the remaining tags...
	// this should be changed to keep_attributes($htmlm $goodattrs), or perhaps even better keep_attributes
	//  should be run first. then strip_attributes, if it finds any of those, should cause safe_html to strip all tags.
	$badattrs = array("on\w+", "span", "font", "fs\w+", "seek\w+");
	$html = strip_attributes($html, $badattrs);

	// close html tags if necessary -- note that this WON'T be graceful formatting-wise, it just has to fix any maliciousness
	foreach ($allowedtags as $tag => $closeit) {
		if (!$closeit)
			continue;
		$patternopen = "/<$tag\b[^>]*>/Ui";
		$patternclose = "/<\/$tag\b[^>]*>/Ui";
		$totalopen = preg_match_all($patternopen, $html, $matches);
		$totalclose = preg_match_all($patternclose, $html, $matches2);
		if ($totalopen > $totalclose) {
			$html .= str_repeat("</$tag>", ($totalopen - $totalclose));
		}
	}

	// check (again!) for obvious oh-noes that might have been caused by tag stipping
	if (js_and_entity_check($html)) {
		$html = strip_tags($html) . "<!--xss stripped after processing-->";
		return $html;
	}

	// close any open <!--'s and identify version just in case
	//$html.= '<!-- '.SAFE_HTML_VERSION.' -->';

	return $html;
}

// End of file
