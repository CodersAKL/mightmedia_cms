<?php
// todo: remove this from core

//Siunciam nurodyta faila i narsykle. Pratestavau ant visu operaciniu ir narsykliu.
if(! function_exists('download')) {
	function download( $file, $filter = ".htaccess|.|..|index.php|configuration.php|config.php" ) {

		global $sql, $lang;
		$filter = explode( "|", $filter );
		if ( !in_array( $file, $filter ) && is_file( $file ) ) {
			if ( strstr( $_SERVER['HTTP_USER_AGENT'], "MSIE" ) ) {
				$file = preg_replace( '/\./', '%2e', $file, substr_count( $file, '.' ) - 1 );
			}
			if ( is_file( $file ) ) {
				if ( connection_status() == 0 ) {
					if ( get_user_os() == "MAC" ) {
						header( "Content-Type: application/x-unknown\n" );
						header( "Content-Disposition: attachment; filename=\"" . basename( $file ) . "\"\n" );
					} elseif ( browser( htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ) ) == "IE" ) {
						$disposition = 'attachment'; //(!eregi("\.zip$", basename($file))) ? 'attachment' : 'inline';
						header( 'Content-Description: File Transfer' );
						header( 'Content-Type: application/force-download' );
						header( 'Content-Length: ' . (string)( filesize( $file ) ) );
						header( "Content-Disposition: $disposition; filename=\"" . basename( $file ) . "\"\n" );
						header( "Cache-Control: cache, must-revalidate" );
						header( 'Pragma: public' );
					} elseif ( browser( htmlspecialchars( $_SERVER['HTTP_USER_AGENT'] ) ) == "OPERA" ) {
						header( "Content-Disposition: attachment; filename=\"" . basename( $file ) . "\"\n" );
						header( "Content-Type: application/octetstream\n" );
					} else {
						header( "Content-Disposition: attachment; filename=\"" . basename( $file ) . "\"\n" );
						header( "Content-Type: application/octet-stream\n" );
					}
					header( "Content-Length: " . (string)( filesize( $file ) ) . "\n\n" );
					readfile( '' . $file . '' );
					exit;
				} else {
					header( "location: " . $_SERVER['PHP_SELF'] );
					exit;
				}
			} else {
				klaida( getLangText('system', 'error'), getLangText('download', 'notfound') );
				header( "HTTP/1.0 404 Not Found" );
			}
		} else {
			header( "location: " . $sql['file'] );
		}
	}
}

if(! function_exists('svente')) {
	function svente( $array, $siandien = '', $return = '' ) {

		// Gauname šiandienos (mėnesis-diena)
		if ( !$siandien ) {
			$siandien = date( 'n-j' );
		}

		// Tikriname ar švenčių masyve nurodyta diena egzistuoja
		if ( array_key_exists( $siandien, $array ) ) {
			foreach ( $array[$siandien] as $key => $val ) {
				if ( empty( $return ) ) {
					$return .= $val;
				} //Jei išvedam šventę pirmą kartą
				else {
					$return .= ", <br />" . $val;
				} //Išvedame daugiau nei vieną šventę, atskiriame kableliais
			}
		}

		return $return;
	}
}

//class maxCalendar{
if(! function_exists('showCalendar')) {
	function showCalendar( $year = 0, $month = 0 ) {

		global $lang;
		/*$sventes = array( //Valstybinės šventės
		"1-1" => array("Naujieji metai", "Lietuvos vėliavos diena"),
		"1-13" => array("Laisvės gynėjų diena"),
		"2-16" => array("Lietuvos valstybės atkūrimo diena"),
		"3-11" => array("Lietuvos nepriklausomybės atkūrimo diena"),
		"5-1" => array("Tarptautinė darbo diena"),
		"5-4" => array("Motinos diena"),
		"6-24" => array("Rasos diena", "Jonininės"),
		"7-6" => array("Valstybės diena", "Lietuvos karaliaus Mindaugo karūnavimo diena"),
		"8-15" => array("Žolinės"),
		"11-1" => array("Visų šventųjų diena"),
		"12-25" => array("Kalėdos"),
		"12-26" => array("Kalėdos (antra diena)"), //Lietuvos Respublikos atmintinos dienos
		"8-23" => array("Juodojo kaspino diena", "Baltijos kelio diena"),
		"8-31" => array("Laisvės diena"),
		"9-1" => array("Mokslo ir žinių diena"),
		"9-8" => array("Šilinė (Švč. Mergelės Marijos gimimo diena)", "Vytauto Didžiojo karūnavimo diena"), //Kitos šventės
		"3-18" => array("FDisk gimtadienis")
		);*/
		$sventes = array();

		//Ieskom kieno gimtadieniai
		//$sql = "SELECT `id`,`nick`,`gim_data`,DATE_FORMAT(`gim_data`,'%e') AS `diena` FROM `" . LENTELES_PRIESAGA . "users` WHERE DATE_FORMAT(`gim_data`,'%c')=DATE_FORMAT(NOW(),'%c')";
		$sql = "SELECT `id`, `nick`, `gim_data`, DATE_FORMAT(`gim_data`,'%e') as `diena` FROM `" . LENTELES_PRIESAGA . "users` WHERE DATE_FORMAT(`gim_data`,'%c')=MONTH(NOW())";

		$sql = mysql_query1( $sql, 86400 );
		foreach ( $sql as $row ) {
			if ( $row['diena'] >= date( "j" ) ) {
				$sventes[date( 'n' ) . "-" . $row['diena']][] = "<b>" . $row['nick'] . "</b> " . getLangText('calendar', 'birthday') . ". " . ( ($row['diena'] < date('j')) ? (amzius( $row['gim_data'] ) + 1) : amzius($row['gim_data'])) . "m.";
				//$sventes[date( 'n' ) . "-" . $row['diena']][] = "<b>" . $row['nick'] . "</b> " . getLangText('calendar', 'birthday') . ". " . ( amzius( $row['gim_data'] ) + 1 ) . "m.";
			}
		}


		// Get today, reference day, first day and last day info
		if ( ( $year == 0 ) || ( $month == 0 ) ) {
			$referenceDay = getdate();

		} else {
			$referenceDay = getdate( mktime( 0, 0, 0, $month, 1, $year ) );
		}
		$firstDay              = getdate( mktime( 0, 0, 0, $referenceDay['mon'], 1, $referenceDay['year'] ) );
		$lastDay               = getdate( mktime( 0, 0, 0, $referenceDay['mon'] + 1, 0, $referenceDay['year'] ) );
		$today                 = getdate();
		$ieskom                = array(
			"December",
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November"
		);
		$keiciam               = array(
			getLangText('calendar','December'),
			getLangText('calendar', 'January'),
			getLangText('calendar', 'February'),
			getLangText('calendar', 'March'),
			getLangText('calendar', 'April'),
			getLangText('calendar', 'May'),
			getLangText('calendar', 'June'),
			getLangText('calendar', 'July'),
			getLangText('calendar', 'August'),
			getLangText('calendar', 'September'),
			getLangText('calendar', 'October'),
			getLangText('calendar', 'November')
		);
		$referenceDay['month'] = str_replace( $ieskom, $keiciam, $referenceDay['month'] );
		$month                 = $referenceDay['mon'];
		$year                  = $referenceDay['year'];
		// Create a table with the necessary header informations
		$return = '<div class="kalendorius"><table class="table" width="100%" >
		<tr><th colspan="7">' . $referenceDay['month'] . ' - ' . $referenceDay['year'] . '</th></tr>
		<tr class="dienos"><td>' . getLangText('calendar', 'Mon') . '</td><td>' . getLangText('calendar', 'Tue') . '</td><td>' . getLangText('calendar', 'Wed') . '</td><td>' . getLangText('calendar', 'Thu') . '</td><td>' . getLangText('calendar', 'Fri') . '</td><td>' . getLangText('calendar', 'Sat') . '</td><td>' . getLangText('calendar', 'Sun') . '</td></tr>';


		// Display the first calendar row with correct positioning
		$return .= '<tr>';
		if ( $firstDay['wday'] == 0 ) {
			$firstDay['wday'] = 7;
		}
		for ( $i = 1; $i < $firstDay['wday']; $i++ ) {
			$return .= '<td>&nbsp;</td>';
		}
		$actday = 0;
		for ( $i = $firstDay['wday']; $i <= 7; $i++ ) {
			$actday++;
			$svente = svente( $sventes, "" . $today['mon'] . "-" . $actday . "" );
			if ( ( $actday == $today['mday'] ) /*&& ($today['mon'] == $month)*/ ) {
				$class = "  class='siandien'";
			} else {
				$class = '';
			}
			if ( !empty( $svente ) ) {
				$return .= "<td$class ><div style='color:red' title=\"<b>" . getLangText('calendar', 'this') . "</b><br/>" . $svente . "<br/>\">$actday</div></td>";
			} else {
				$return .= "<td$class>" . ( $actday <= $today['mday'] && puslapis('kas_naujo.php') ? "<a href='" . url( "?id," . puslapis( 'kas_naujo.php' ) . ';d,' . mktime( 23, 59, 59, $month, $actday, $year ) ) . "'>$actday</a>" : $actday ) . "</td>";
			}
		}


		$return .= '</tr>';

		//Get how many complete weeks are in the actual month
		$fullWeeks = floor( ( $lastDay['mday'] - $actday ) / 7 );

		for ( $i = 0; $i < $fullWeeks; $i++ ) {
			$return .= '<tr>';
			for ( $j = 0; $j < 7; $j++ ) {
				$actday++;
				$svente = svente( $sventes, "" . $today['mon'] . "-" . $actday . "" );
				if ( ( $actday == $today['mday'] ) && ( $today['mon'] == $month ) ) {
					$class = "  class='siandien'";
				} else {
					$class = '';
				}
				if ( !empty( $svente ) ) {
					$return .= "<td$class ><div style='color:red' title=\"<b>" . getLangText('calendar', 'this') . "</b><br/>" . $svente . "<br/>\">$actday</div></td>";
				} else {
					$return .= "<td$class>" . ( $actday <= $today['mday'] && puslapis( 'kas_naujo.php' ) ? "<a href='" . url( "?id," . puslapis( 'kas_naujo.php' ) . ';d,' . mktime( 23, 59, 59, $month, $actday, $year ) ) . "'>$actday</a>" : $actday ) . "</td>";
				}
			}

			$return .= '</tr>';
		}

		//Now display the rest of the month
		if ( $actday < $lastDay['mday'] ) {
			$return .= '<tr>';

			for ( $i = 0; $i < 7; $i++ ) {
				$actday++;
				$svente = svente( $sventes, "" . $today['mon'] . "-" . $actday . "" );

				if ( ( $actday == $today['mday'] ) && ( $today['mon'] == $month ) ) {
					$class = "  class='siandien'";
				} else {
					$class = '';
				}

				if ( $actday <= $lastDay['mday'] ) {
					if ( !empty( $svente ) ) {
						$return .= "<td$class ><div style='color:red' title=\"<b>" . getLangText('calendar', 'this') . "</b><br/>" . $svente . "<br/>\">$actday</div></td>";
					} else {
						$return .= "<td$class>" . ( $actday <= $today['mday'] && puslapis( 'kas_naujo.php' ) ? "<a href='" . url( "?id," . puslapis( 'kas_naujo.php' ) . ';d,' . mktime( 23, 59, 59, $month, $actday, $year ) ) . "'>$actday</a>" : $actday ) . "</td>";
					}
				} else {
					$return .= '<td>&nbsp;</td>';
				}
			}


			$return .= '</tr>';
		}

		$return .= '</table></div>';

		return $return;
	}
}
