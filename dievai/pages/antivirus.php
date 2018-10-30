<?php
//                  _                   _   _     _
//             _ __| |_  _ __  __ _ _ _| |_(_)_ _(_)_ _ _  _ ___
//            | '_ \ ' \| '_ \/ _` | ' \  _| \ V / | '_| || (_-<
//            | .__/_||_| .__/\__,_|_||_\__|_|\_/|_|_|  \_,_/__/
//            |_|       |_|
//                               Version 1.0.3
//
//    Official Site:                                     Authors:
//    http://phpantivirus.sourceforge.net                KeyboardArtist
//                                                       Deekay
//    Sourceforge Page:                                  Nico
//    http://sourceforge.net/projects/phpantivirus/      Murphy
//
//    This software is provided as-is, without warranty or guarantee of
//    any kind. Use at your own risk. This software is licenced under the
//    GNU GPL license. More information is available in 'COPYING' included
//    with this distribution.
//
//
// ROOT PATH TO SCAN
//-----------------
// This can be a relative or full path WITHOUT a trailing
// slash. All files and folders will be recursively scanned
// within this path. NB: Due to your web host's configuration
// it is likely this script will be terminated after 30-60
// seconds of continuous operation. Please keep an eye on
// the number of files inside this directory - if it is too
// large it may fail.
// Default: Document root defined in Apache
$CONFIG = Array();
$CONFIG['extensions'] = Array();
$CONFIG['scanpath']   = realpath( dirname( __file__ ) . '/../' );
// SCANABLE FILES
// --------------
// The next few lines tell PHP AntiVirus what files to scan
// within the directory set above. It does it by file
// extension (the text after the period or dot in the file
// name) - for example "htm", "html" or "php" files.
// Default: None
// Static files? This should be a comprehensive list, add
// more if required.
$CONFIG['extensions'][] = 'htm';
$CONFIG['extensions'][] = 'html';
$CONFIG['extensions'][] = 'shtm';
$CONFIG['extensions'][] = 'shtml';
$CONFIG['extensions'][] = 'css';
$CONFIG['extensions'][] = 'js';
$CONFIG['extensions'][] = 'vbs';
$CONFIG['extensions'][] = 'php';
$CONFIG['extensions'][] = 'php3';
$CONFIG['extensions'][] = 'php4';
$CONFIG['extensions'][] = 'php5';
$CONFIG['extensions'][] = 'txt';
$CONFIG['debug']        = 1;

// declare variables
$report = '';
// set counters
$dircount  = 0;
$filecount = 0;
$infected  = 0;
$debug     = $CONFIG['debug'];
// load virus defs from flat file
$defs = load_defs( ROOT . 'virus.def', $debug );
// scan specified root for specified defs
$report .= '<div style="width:100%; height:400px; overflow:auto;">';
file_scan( $CONFIG['scanpath'], $defs, $CONFIG['debug'] );
$report .= '</div>';
// output summary
$report .= '<h2 class="ico_mug">' . $lang['admin']['antivirus_scan_completed'] . '</h2>';
$report .= '<div id=summary>';
$report .= '<p><strong>' . $lang['admin']['antivirus_scaned_folders'] . ':</strong> ' . $dircount . '</p>';
$report .= '<p><strong>' . $lang['admin']['antivirus_scaned_files'] . ':</strong> ' . $filecount . '</p>';
$report .= '<p style="color:red;"><strong>' . $lang['admin']['antivirus_infected_files'] . ':</strong> ' . $infected . '</p>';
$report .= '</div>';

// output full report
lentele( $lang['admin']['antivirus'], $report );
function file_scan( $folder, $defs, $debug ) {

	// hunts files/folders recursively for scannable items
	global $dircount, $report, $lang;
	$dircount++;
	if ( $debug ) {
		$report .= '<p class="info">' . $lang['admin']['antivirus_scanning'] . ' ' . $folder . ' ...</p>';
	}
	if ( $d = dir( $folder ) ) {
		while ( FALSE !== ( $entry = $d->read() ) ) {
			$isdir = is_dir( $folder . '/' . $entry );
			if ( !$isdir and $entry != '.' and $entry != '..' and $entry != '.svn' ) {
				virus_check( $folder . '/' . $entry, $defs, $debug );
			} elseif ( $isdir && !in_array( $entry, array( '.', '..', '.svn', '.idea' ) ) ) {
				file_scan( $folder . '/' . $entry, $defs, $debug );
			}
		}
		$d->close();
	}
}


function virus_check( $file, $defs, $debug ) {

	global $filecount, $infected, $report, $CONFIG, $lang;
	// find scannable files
	$scannable = 0;
	foreach ( $CONFIG['extensions'] as $ext ) {
		if ( substr( $file, -3 ) == $ext ) {
			$scannable = 1;
		}
	}
	// compare against defs
	if ( $scannable ) {
		// affectable formats
		$filecount++;
		$data  = file( $file );
		$data  = implode( "\r\n", $data );
		$clean = 1;
		foreach ( $defs as $virus ) {
			if ( @stripos( $data, trim( $virus[1] ) ) ) { //ne=inau kas 2ia blogai, pridejau eta
				// file matches virus defs
				$report .= '<p style="color:red;">' . $lang['admin']['antivirus_infected'] . ': ' . $file . ' (' . $virus[0] . ')</p>';
				$infected++;
				$clean = 0;
			}
		}
		if ( ( $debug ) && ( $clean ) ) {
			$report .= '<p style="color:green;">' . $lang['admin']['antivirus_clean'] . ': ' . $file . '</p>';
		}
	}
}

function load_defs( $file, $debug ) {

	// reads tab-delimited defs file
	$defs     = file( $file );
	$counter  = 0;
	$counttop = sizeof( $defs );
	while ( $counter < $counttop ) {
		$defs[$counter] = explode( "\t", $defs[$counter] );
		$counter++;
	}
	if ( $debug ) {
		echo '<p>Loaded ' . sizeof( $defs ) . ' virus definitions</p>';
	}

	return $defs;
}

?>