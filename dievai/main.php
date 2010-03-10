<?php
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
if (!isset($_SESSION))
	session_start();
define('LEVEL', $_SESSION['level']);
	/* detect root */
$out_page = true;
$inc="priedai/conf.php";
$root = '';
while(!file_exists($root.$inc) && strlen($root)<70 ) { $root="../".$root;}

#check if the file actually exists or if we crashed out.
if (!file_exists($root.$inc)) {
	die ("Kritine klaida.". $root.$inc);
}
if (is_file($root.'priedai/conf.php') && filesize($root.'priedai/conf.php') > 1) {

	if (!defined('ROOT')) {
	//_ROOT = $root;
		define('ROOT','../');
	} else {
		define('ROOT', $root);
	}

	include_once ($root.'priedai/conf.php');
	include_once ($root.'priedai/header.php');

	//Stiliaus funkcijos
	require_once("sfunkcijos.php");
	//Inkludinam tai ko mums reikia
	require_once($root.'priedai/funkcijos.php');

} elseif (is_file($root.'setup.php')) {
	header('location: '.$root.'setup.php');
	exit();
} else {
	die(klaida('Sistemos klaida / System error', 'Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.'));
}
if (empty($_SESSION['username']) || $_SESSION['level']!=1) {
	redirect('index.php');
}
if(isset($_GET['do'])){
	unset($_SESSION['username'],$_SESSION['level'],$_SESSION['password']);
}
$glob = glob('*.php');
$admin_tools = "";
foreach($glob as $id => $file) {
	$file = basename($file,'.php');
	$image = (is_file("img/{$file}.png")?"img/{$file}.png":'img/module.png');
	$admin_pages[$id] = $file;
	$admin_pagesid[$file] = $id;
	if ((isset($conf['puslapiai'][$file.'.php']['id']) || in_array($file, array('config','meniu','logai','paneles','vartotojai','komentarai','banai','balsavimas'))) && !in_array($file, array('index','pokalbiai', 'main'))) {

		$admin_tools .= "<li ".(($id == 1 ||$id == 10 || $id == 20)?'class="first_li"':'')."><a href=\"".url("?id,999;a,$id")."\"><img src=\"{$image}\" alt=\"\" /><span>".(isset($lang['admin'][$file])?$lang['admin'][$file]:$file)."</span></a></li>";

	}
}
function build_tree($data, $id=0, $active_class='active') {
	global $admin_pagesid, $lang;

	$re="";
	foreach ($data[$id] as $row) {
		if (isset($data[$row['id']])) {
			$re.= "<li>".$row['pavadinimas']."<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ). "\" style=\"align:right\" onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"".ROOT."images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" align=\"right\" /></a><ul>";
			$re.=build_tree($data, $row['id'],$active_class);
			$re.= "</ul></li>";
		} else $re.= "<li>".$row['pavadinimas']."
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';d,' . $row['id'] ). "\" style=\"align:right\" onClick=\"return confirm(\'" . $lang['admin']['delete'] . "?\')\"><img src=\"".ROOT."images/icons/cross.png\" title=\"" . $lang['admin']['delete'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';r,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/wrench.png\" title=\"" . $lang['admin']['edit'] . "\" align=\"right\" /></a>
<a href=\"".url('?id,999;a,' . $admin_pagesid['meniu'] . ';e,' . $row['id'] ). "\" style=\"align:right\"><img src=\"".ROOT."images/icons/pencil.png\" title=\"" . $lang['admin']['page_text'] . "\" align=\"right\" /></a>
</li>";
	}
	return $re;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo input(strip_tags($conf['Pavadinimas']) . ' - Admin')?></title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="robots" content="index,follow" />

		<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
		<link rel="Stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.1.custom.css"  />
		<!--[if IE 7]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen, projection" /><![endif]-->
		<!--[if IE 6]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection" /><![endif]-->

		<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen" />
		<link rel="stylesheet" href="css/jquery.treeview.css" />
		<!--[if IE]>
		<style type="text/css">
		.clearfix {
		zoom: 1;     /* triggers hasLayout */
		display: block;     /* resets display for IE/Win */
		}  /* Only IE can see inside the conditional comment
		and read this CSS rule. Don't ever use a normal HTML
		comment inside the CC or it will close prematurely. */
		</style>
		<![endif]-->
		<!-- JavaScript -->
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="js/hoverIntent.js"></script>
		<script type="text/javascript" src="js/superfish.js"></script>
		<script type="text/javascript">
			// initialise plugins
			jQuery(function(){
				jQuery('ul.sf-menu').superfish();
			});

		</script>
		<script type="text/javascript" src="js/excanvas.pack.js"></script>
		<script type="text/javascript" src="js/jquery.flot.pack.js"></script>
		<!--script type="text/javascript" src="js/custom.js"></script-->
		<script src="js/jquery.cookie.js" type="text/javascript"></script>
		<script src="js/jquery.treeview.js" type="text/javascript"></script>
		<script src="js/jquery.scrollTo.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="<?php echo ROOT; ?>javascript/jquery/tooltip.js"></script>
		<!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.pack.js"></script><![endif]-->
		<script type="text/javascript">
			$(document).ready(function(){

				// first example
				$("#treemenu").treeview({
					persist: "location",
					collapsed: true,
					unique: true
				});});
		</script>
	</head>
	<body>
		<div class="container" id="container">
			<div  id="header">
				<div id="profile_info">
					<?php
					$sql = mysql_query1("SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`='" . $_SESSION['username'] . "' LIMIT 1",360);
					echo avatar($sql['email'],41);
					?>
					<p><?php echo sprintf($lang['user']['hello'], '<strong>'.$_SESSION['username'].'</strong>'); ?>. <a href="<?php echo url('?id,999;do,logout');?>"><?php echo $lang['user']['logout']; ?></a></p>
					<p><?php echo $lang['system']['warning'].' '. $lang['admin']['logai']; ?>: <?php echo kiek('logai'); ?>. <a href="<?php echo url("?id,999;a,".$admin_pagesid['logai']); ?>"><?php echo $lang['system']['allcanread']; ?></a></p>
					<p class="last_login"><?php echo $lang['admin']['user_lastvisit']; ?>: <?php echo date('Y-m-d H:i:s',$_SESSION['lankesi']);?></p>
				</div>
				<div id="logo"><h1><a href="<?php echo adresas(); ?>main.php">AdmintTheme</a></h1></div>

			</div><!-- end header -->
			<div id="content" >

				<div id="top_menu" class="clearfix">
					<ul class="sf-menu"> <!-- DROPDOWN MENU -->
						<?php

						$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' ORDER BY `place` ASC");
						foreach ($res as $row) {
							if(teises($row['teises'],$_SESSION['level'])) {
								$data1[$row['parent']][] = $row;
							}
						}
						echo build_menu($data1);


						?>

					</ul>
					<a href="<?php echo adresas(); ?>../" id="visit" class="right"><?php echo $lang['system']['to_page']; ?></a>
				</div>
				<div id="content_main" class="clearfix">
					<div id="main_panel_container" class="left">
						<div id="dashboard">
							<h2 class="ico_mug"><?php echo $lang['system']['dashboard']; ?></h2>
							<div class="clearfix">
								<!--<div class="left quickview">
									<h3>Overview</h3>
									<ul>
										<li>Total Posts: <span class="number">15</span></li>
										<li>Total Comments: <span class="number">340</span></li>
										<li>Drafts: <span class="number">3</span></li>
										<li>Things to do: <span class="number">3</span></li>
										<li>Comments waiting for aproval: <span class="number">20</span></li>
										<li>Visits Today: <span class="number">230</span></li>
									</ul>
								</div>-->
								<div class="quickview left">
									<?php
										/*
										SELECT (SELECT COUNT(*) as total FROM 23_kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP()) as siandien,
										(SELECT COUNT(*) as total FROM 23_kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)) as vakar, (SELECT COUNT(*) as total FROM 23_kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY)) as uzvakar
										*/
									$stats = mysql_query1("SELECT
										(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP()) as siandien,
										(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)) as vakar,
										(SELECT COUNT(*) as total FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE timestamp BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY) AND UNIX_TIMESTAMP(NOW() - INTERVAL 2 DAY)) as uzvakar
										LIMIT 1");
									$sql = mysql_query1("SELECT count(id) as svec,
										(SELECT count(id) FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user!='Svečias') as users, 
										(SELECT count(id) FROM " . LENTELES_PRIESAGA . "users) as useriai, 
										(SELECT `nick` FROM " . LENTELES_PRIESAGA . "users order by id DESC LIMIT 1 ) as useris,
										(SELECT `id` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as userid,
										(SELECT `levelis` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as lvl
										FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user='Svečias'
										LIMIT 1");

									$progresas = procentai((!empty($stats['uzvakar'])?$stats['uzvakar']:1),(!empty($stats['vakar'])?$stats['vakar']:1));
									$memberis = user($sql['useris'], $sql['userid'], $sql['lvl']);

									$text = <<<HTML
					<ul>
					<li>{$lang['online']['users']} {$lang['online']['usrs']}: <span class="number">{$sql['users']}</span></li>
					<li>{$lang['online']['users']} {$lang['online']['guests']}: <span class="number">{$sql['svec']}</span></li>
					<li>{$lang['online']['traffic_in']}: <span class="number">{$progresas}%</span></li>
					<li>{$lang['online']['today']}: <span class="number">{$stats['siandien']}</span></li>
					<li>{$lang['online']['registeredmembers']}: <span class="number">{$sql['useriai']}</span></li>
					<li>{$lang['online']['lastregistered']}: <span class="number">{$memberis}</span></li>
					</ul>

HTML;

									//unset($sql);

									?>

									<h3><?php echo $lang['system']['some_data']; ?></h3>
									<?php echo $text; unset($text); ?>
								</div>
								<div id="chart" class="left">
									<h3><?php echo $lang['system']['visits']; ?></h3>
									<div id="placeholder" ></div><!-- CHART --><br />
									<!--<a href="#" class="ico_chart more">Click to see more</a>-->
								</div>
							</div>
						</div><!-- end #dashboard -->
						<?php
						$sqli = mysql_query1("SELECT FROM_UNIXTIME(`timestamp`,'%H') as `time`, count(*) as `viso` FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp` BETWEEN UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY) AND UNIX_TIMESTAMP() group by `time`");
						foreach($sqli as $sql) {
							$data[] = "[{$sql['time']}, {$sql['viso']}]";
						}
						//$data[0] = "[1, {$stats['uzvakar']}]";
						//$data[1] = "[2, {$stats['vakar']}]";
						//$data[2] = "[3, {$stats['siandien']}]";
						?>
						<script type="text/javascript">
							$.plot($("#placeholder"), [[<?php echo implode(', ',$data); ?>]], {
								grid: {
									color: "#000",
									borderWidth: 1
								}
								//bars: { show: 'bars' }
							});
						</script>

						<div id="shortcuts" class="clearfix">
							<h2 class="ico_mug"><?php echo $lang['system']['control']; ?></h2>
							<ul>
								<?php

								echo $admin_tools;
								?>


							</ul>
						</div><!-- end #shortcuts -->
					</div>
					<div id="sidebar" class="right">
						<h2 class="ico_mug"><?php echo $lang['system']['tree']; ?></h2>
						<ul id="treemenu">
							<?php

							$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` ORDER BY `place` ASC");
							foreach ($res as $row) {
								if(teises($row['teises'],$_SESSION['level'])) {
									$data2[$row['parent']][] = $row;
								}
							}
							echo build_tree($data2);


							?>



						</ul>

					</div><!-- end #sidebar -->
				</div><!-- end #content_main -->
				<?php if (isset($url['a']) && file_exists(dirname(__file__) . "/" . $admin_pages[(int)$url['a']].'.php') && isset($_SESSION['username']) && $_SESSION['level'] == 1 && defined("OK")) {
					include_once (dirname(__file__) . "/" . $admin_pages[(int)$url['a']].'.php');
				} else {

					include_once (dirname(__file__) . "/pokalbiai.php");
				}?>
				<!-- end #postedit -->



				<div id="panels" class="clearfix">
					<div class="panel photo left">
						<h2 class="ico_mug"><?php echo $lang['info']['unpics_title']; ?></h2>
						<ul class="clearfix">
							<?php
							if(isset($admin_pagesid['galerija'])) {
								$q = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id` ,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "galerija`

  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE
   `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'NE'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`data` DESC LIMIT 8
  ");
								foreach($q as $row) {
									echo 	'<li><img width="80" src="'.ROOT.'galerija/mini/' . $row['file'].'" alt="photo"/><span>
<a href="'.url("?id,999;a,{$admin_pagesid['galerija']};p," . $row['id'] ). '"><img src="img/accept.jpg" alt="accept"/></a><a href="'.url("?id,999;a,{$admin_pagesid['galerija']};t," . $row['id'] ).'"><img src="img/cancel.jpg"  alt="deny"/></a></span></li>';
								}
							}
							?>

						</ul>

					</div><!-- end #photo -->
					<div class="panel todo left">
						<h2 class="ico_mug"><?php echo $lang['info']['unpublished']; ?></h2>

						<ul>
							<?php

							$sqli = mysql_query1("SELECT count(id) as kom,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "naujienos WHERE " . LENTELES_PRIESAGA . "naujienos.rodoma='NE') as news,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "straipsniai WHERE " . LENTELES_PRIESAGA . "straipsniai.rodoma='NE') as straipsniai2,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "siuntiniai WHERE " . LENTELES_PRIESAGA . "siuntiniai.rodoma='NE') as siuntiniai,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "galerija WHERE " . LENTELES_PRIESAGA . "galerija.rodoma='NE') as foto,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "nuorodos WHERE " . LENTELES_PRIESAGA . "nuorodos.active='NE') as nuorodos
FROM " . LENTELES_PRIESAGA . "kom");
							//$sql = mysql_fetch_assoc($sql);
							foreach ($sqli as $sql) {
								$text = '';
								if (isset($conf['puslapiai']['naujienos.php']['id'])) {
									$text .= '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['naujienos'].';v,6').'">'.$lang['info']['unnews'].': ' . $sql['news'] . '</a></li>';
								}
								if (isset($conf['puslapiai']['siustis.php']['id'])) {
									$text .= '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['siustis'].';v,6').'">'.$lang['info']['undownloads'].': ' . $sql['siuntiniai'] . '</a></li>';
								}
								if (isset($conf['puslapiai']['nuorodos.php']['id'])) {
									$text .= '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['nuorodos'].';v,6').'">'.$lang['info']['unlinks'].': ' . $sql['nuorodos'] . '</a></li>';
								}
								if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
									$text .= '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['straipsnis'].';v,6').'">'.$lang['info']['unarticles'].': ' . $sql['straipsniai2'] . '</a></li>';
								}
								if (isset($conf['puslapiai']['galerija.php']['id'])) {
									$text .= '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['galerija'].';v,7').'">'.$lang['info']['unpics'].': ' . $sql['foto'] . '</a></li>';
								}

								echo $text;
							}

							?>
						</ul>

					</div><!-- end #todo -->
					<div class="panel calendar left">
						<h2 class="ico_mug"><?php echo $lang['admin']['logai']; ?></h2>
						<ul>
							<?php
							$sql = mysql_query1("
		SELECT `" . LENTELES_PRIESAGA . "logai`.`id`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) as ip, `" . LENTELES_PRIESAGA . "logai`.`action`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) AS ip1, `" . LENTELES_PRIESAGA . "logai`.`time`,
		IF(`" . LENTELES_PRIESAGA . "users`.`nick` <> '', `" . LENTELES_PRIESAGA . "users`.`nick`, 'Svečias') AS nick,
		IF(`" . LENTELES_PRIESAGA . "users`.`id` <> '', `" . LENTELES_PRIESAGA . "users`.`id`, '0') AS nick_id,
		IF(`" . LENTELES_PRIESAGA . "users`.`levelis` <> '', `" . LENTELES_PRIESAGA . "users`.`levelis`, '0') AS levelis
		FROM `" . LENTELES_PRIESAGA . "logai` Left Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "logai`.`ip` = `" . LENTELES_PRIESAGA . "users`.`ip`
	ORDER BY `id` DESC LIMIT 6
		");
							foreach($sql as $row) {
								echo '<li class="even"><a href="'.url('?id,999;a,'.$admin_pagesid['logai'].';v,'.$row['id']).'">'.trimlink($row['action'],25). '</a></li>';
							}						?>
						</ul>

					</div><!-- end #calendar -->
				</div><!-- end #panels -->
				<!-- end #dialog [if you don't want this, delete whole div and 6th line i custom.js -->


			</div><!-- end #content -->

			<div  id="footer" class="clearfix">
				<p class="left">AdminTheme - Ultimate Admin Panel Solution</p>
				<p class="right">© 2010 <a href="http://mightmedia.lt/">MightMedia</a></p>
			</div><!-- end #footer -->
		</div><!-- end container -->
		<?php if(isset($_GET['a'])) { ?>
		<script type="text/javascript">
			$('body').scrollTo(600, 800);
		</script>
		<?php } ?>
	</body>
</html>