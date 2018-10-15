<?php include 'header.php'; ?>
<div id="admin_root">
	<div id="content">

		<div id="left">

			<div class="fixed">

				<div id="virslogo"></div>
				<a href="<?php echo adresas(); ?>">
					<div id="admin_logo"></div>
				</a>

				<div class="search">
					<div class="sonas">
						<a href="style-switcher.php?style=diena">
							<div class="pirmas"></div>
						</a>
						<a href="style-switcher.php?style=naktis">
							<div class="antras"></div>
						</a>
					</div>
					<form method="post" action="<?php echo url( '?id,999;m,4' );?>">
						<input name="vis" value="vis" type="hidden" />
						<input type="text" name="s" value="" />
					</form>
					<div style="clear: both;"></div>
				</div>

			</div>

			<div class="nav">
				<ul>
					<li>
						<a href="<?php echo url( '?id,999' );?>"><img src="images/icons/home.png" alt="" /> <?php echo $lang['admin']['homepage']; ?>
						</a>
                    </li>
					<li>
						<a href="<?php echo url( '?id,999;m,3' );?>"><img src="images/icons/product-1.png" alt="" /> <?php echo $lang['admin']['antivirus']; ?>
						</a>
                    </li>
					<li>
						<a href="<?php echo url( '?id,999;m,2' );?>"><img src="images/icons/finished-work.png" alt="" /> <?php echo $lang['admin']['admin_chat']; ?>
						</a>
                    </li>
					<?php if ( !empty( $conf['keshas'] ) ) : ?>
					    <li>
                            <a href="<?php echo url( '?id,999;m,1' );?>"><img src="images/icons/publish.png" alt="" /><?php echo $lang['admin']['uncache']; ?>
                            </a>
                        </li>
					<?php endif ?>

                    <?php foreach (getAdminPages() as $id => $file) { ?>
                        <?php
                            $file                 = basename( $file, '.php' );
                            $image                = ( is_file( "images/icons/{$file}.png" ) ? "images/icons/{$file}.png" : 'images/icons/module.png' );
                            $inArray = array( 'config', 'meniu', 'logai', 'paneles', 'vartotojai', 'komentarai', 'banai', 'balsavimas' );
                            $notArray = array( 'index', 'pokalbiai', 'main', 'search', 'antivirus' );
                        ?>
                        <?php if ((isset( $conf['puslapiai'][$file . '.php']['id']) || in_array( $file, $inArray)) && !in_array($file, $notArray)) { ?>
                            <li <?php echo ( isset( $_GET['a'] ) && $_GET['a'] == $id ? 'class="active"' : '' ); ?>>
                                <a href="<?php echo url( '?id,999;a,' . $id ); ?>">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $file; ?>" />
                                    <?php echo ( isset( $lang['admin'][$file] ) ? $lang['admin'][$file] : nice_name( $file ) ); ?>
                                </a>
                                <?php if(isset( $_GET['a'] ) && $_GET['a'] == $id) { ?>
                                    <ul id="sub-menu-admin"></ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    <?php } ?>
				</ul>
			</div>

		</div>

		<div id="right">

			<div id="controls">
                <div class="admin_user down">
					<?php echo $lang['admin']['user_lastvisit']; ?>: <b><?php echo date( 'H:i:s' ); ?></b>
				</div>
                <div class="admin_user down">
					<a href="<?php echo url( '?id,999;do,logout' );?>" title="<?php echo $lang['user']['logout']; ?>">
						<img src="images/icons/logout.png" alt="off" />
						<?php echo $_SESSION[SLAPTAS]['username']; ?>
					</a>
				</div>
				<div id="admin_lang" class="down"><?php echo $language; ?></div>
			</div>

			<div id="container">
				<div class="where">
					<img src="images/bullet.png" alt="" />
					<a href="<?php echo url( '?id,999' );?>">Admin</a> &raquo;
					<a href="<?php echo url( '?id,999' . ( isset( $_GET['a'] ) ? ';a,' . $_GET['a'] : '' ) );?>">
						<?php echo( isset( $_GET['a'] ) ? ( isset( $admin_pages[$_GET['a']] ) && isset( $lang['admin'][$admin_pages[$_GET['a']]] ) ? $lang['admin'][$admin_pages[$_GET['a']]] : $lang['admin']['homepage'] ) : $lang['admin']['homepage'] ); ?>
					</a>
				</div>
				<!--[if IE]>
				<?php klaida( '', 'Internet Explorer nėra gera naršyklė bei yra nepatogi, ji iškraipo dauguma dizaino funkcijų, siūlome naudoti: <a targer="_blank" href="https://www.google.com/chrome">Google Chrome</a>, <a targer="_blank" href="http://apple.com/safari">Safari</a>, <a targer="_blank" href="http://www.mozilla.org/firefox/">Mozilla Firefox</a>, <a targer="_blank" href="http://opera.com">Opera</a>' );?>
				<![endif]-->

                <br />

                <div id="version_check"></div>
                <script type="text/javascript">
                    $.getJSON('<?php echo $update_url; ?>');
                    function versija(data) {
                        if (<?php echo versija();?> < data.version) {
                            $('#version_check').attr('class', 'msg');
                            $('#version_check').html('<img src="images/icons/lightbulb.png" alt="" /><b>' + data.title + '</b> ' + '' + data.version + ' - ' + '' + data.about + ' ' + (data.log ? '<a href="' + data.log + '" target="_blank" title="' + data.log + '">[Informacija]</a>' : '') + (data.url ? ' <span class="number" style="display:inline;"><a href="' + data.url + '" target="_blank"> Atsisiuntimas MM.TVS v' + data.version + '</a></span>' : ''));
                        }
                    }
                </script>

				<?php
					adminPages();
				?>
			</div>
			<div style="clear: both;"></div>
			<div id="footer">

				<div class="copy">
					<div class="c">&copy;</div>
					<div class="links"><a target="_blank" href="http://mightmedia.lt">MightMedia</a> |
						<a target="_blank" href="http://mightmedia.lt/Kontaktai"><?php echo $lang['pages']['kontaktas.php']; ?></a> |
						<a target="_blank" href="http://www.gnu.org/licenses/gpl.html">GNU</a></div>
					MightMedia TVS - atviro kodo turinio valdymo sistema, sukurta CodeRS komandos.
				</div>

				<div class="images">
					<a target="_blank" href="http://www.mysql.com" target="_blank"><img src="images/mysql.png" alt="" /></a>
					<a target="_blank" href="http://php.net" target="_blank"><img src="images/php.png" alt="" /></a>
					<a target="_blank" href="http://www.gnu.org" target="_blank"><img src="images/gnu.png" alt="" /></a>
				</div>
			</div>

			<div style="clear: both;"></div>
		</div>
		<div style="height:20px;"></div>
	</div>
</div>
<?php include 'footer.php'; ?>