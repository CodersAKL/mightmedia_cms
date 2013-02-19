<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );
if ( !isset( $_SESSION ) ) {
	session_start();
}

if ( is_file( '../priedai/conf.php' ) && filesize( '../priedai/conf.php' ) > 1 ) {
	include_once ( "../priedai/conf.php" );
} elseif ( is_file( '../install/index.php' ) ) {
	header( 'location: ../install/index.php' );
	exit();
} else {
	die( klaida( 'Sistemos klaida / System error', 'Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.' ) );
}
include_once ( "../priedai/prisijungimas.php" );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo input( strip_tags( $conf['Pavadinimas'] ) ); ?> - Admin</title>
	<meta name="description" content="<?php echo input( strip_tags( $conf['Pavadinimas'] ) . ' - ' . trimlink( strip_tags( $conf['Apie'] ), 120 ) ); ?>" />
	<meta name="keywords" content="<?php echo input( strip_tags( $conf['Keywords'] ) );?>" />
	<meta name="author" content="<?php echo input( strip_tags( $conf['Copyright'] ) );?>" />
	<link rel="stylesheet" type="text/css" media="all" href="css/prisijungimo.css" />
</head>
<body>
<div id='plotis'>
	<div id='kaire'>
		<div class='skalpas'><a href="<?php echo adresas(); ?>" title="<?php echo adresas(); ?>">
			<div class='logo'></div>
		</a>
		</div>
	</div>
	<div id='kunas'>
		<div id='meniu_juosta'><?php echo input( strip_tags( $conf['Pavadinimas'] ) );?></div>
		<div class='sonas2'></div>
		<div id='centras'>
			<?php if ( !empty( $strError ) ): ?>
			<div class='klaida'>
				<div class='info_ikona'></div>
				<div class='info_pavadinimas'><?php echo $lang['system']['warning']; ?></div>
				<div class='info_tekstas'><?php echo $strError; ?></div>
			</div>
			<br />
			<?php endif ?>
			<div class='pavadinimas'>Admin</div>
			<div class='vidus'>
				<div class='text'>
					<?php if ( !isset( $_SESSION[SLAPTAS]['username'] ) ) { ?>
					<form id="user_reg" name="user_reg" method="post" action="">
						<div id="login" class="section">
							<form name="loginform" id="loginform" action="panel.html" method="post">
								<label><strong><?php echo $lang['user']['user'];?></strong></label><br /><input type="text" name="vartotojas" id="user_login" size="28" class="input" />
								<br />
								<label><strong><?php echo $lang['user']['password']; ?></strong></label><br /><input type="password" name="slaptazodis" id="user_pass" size="28" class="input" />
								<br />
								<!--<strong>Remember Me</strong><input type="checkbox" id="remember" class="input noborder" />-->
								<input type="hidden" name="action" value="prisijungimas" />
								<input style="margin-top: 5px;" id="save" class="loginbutton" type="submit" class="submit" value="<?php echo $lang['user']['login']; ?>" />
							</form>
						</div>
					</form>
					<?php
				} elseif ( isset( $_SESSION[SLAPTAS]['level'] ) && $_SESSION[SLAPTAS]['level'] == 1 ) {
					redirect( 'main.php' );
				}
					?>
				</div>
			</div>


			<div id='kojos'>
				<div class='tekstas'><?php echo $conf['Copyright'];?></div>
				<a href='http://mightmedia.lt' target='_blank' title='Mightmedia'>
					<div class='logo'></div>
				</a>
			</div>

		</div>

		<div class='sonas'></div>

	</div>
</div>
<div id='another' class='clear'>
	<div class='lygiuojam'>
		<div class='taisom'></div>
	</div>
</div>
</body>
</html>
