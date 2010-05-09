<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
      <?php header_info(); ?>
      <link rel="stylesheet" type="text/css" href="stiliai/<?php echo $conf['Stilius']; ?>/superfish.css" media="screen" />
      <script type="text/javascript" src="stiliai/<?php echo $conf['Stilius']; ?>/superfish.js"></script>
      <script type="text/javascript">
			$(document).ready(function(){
				$('ul.sf-menu').superfish();
      });			
		</script>


  </head>
  <body>
	  <div id="admin_root">
		  <div id="admin_main">

			  <div id="admin_header">

				 <div style="text-align: right;color: #666;font-weight: bold;"><?php echo date('Y-m-d, H:i:s'); ?></div>

				  <a href="#" id="admin_logo"><img src="stiliai/<?php echo $conf['Stilius']; ?>/images/mm_logo.png" alt="MightMedia TVS" /></a>
				  
			  </div>

			  <div id="admin_hmenu">

				  <ul class="sf-menu">
            <?php
               $res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' AND `lang` = ".escape(lang())." ORDER BY `place` ASC");
               if (sizeof($res) > 0) {
                 foreach ($res as $row) {
                    if(teises($row['teises'], $_SESSION['level']))
                        $data[$row['parent']][] = $row;
                 }
                 echo build_menu($data);
               } 
						?>
				  </ul>
			  </div>
		  </div>
      <div id="content">
      <div id="top">
      <?php if(puslapis('search.php')){?>
        <div class="search">
            <form method="post" action="<?php echo url('?id,'.puslapis('search.php')); ?>">
						<input name="vis" value="vis" type="hidden" />
						<input type="text" name="s"  value="" />
					</form>
        </div>
      <?php }?>
      <div class="msg" id="version_check">
            <img src="stiliai/<?php echo $conf['Stilius']; ?>/images/lightbulb.png" alt="" /> <?php echo $conf['Apie'];?>
          </div>
       </div>
<div style="clear: both;"></div>
        <div id="left">          
          <div class="buttons">
            <button onclick="window.location='http://localhost/MM2/v1/dievai/../dievai/admin'"><img src="images/icons/home.png" alt="" /></button>

            <button title="Antivirusine" onclick="window.location='http://localhost/MM2/v1/dievai/../dievai/admin;m,3'"><img src="images/icons/product-1.png" alt="" /></button>
            <button title="Valyti „sandeliuka“" onclick="window.location='http://localhost/MM2/v1/dievai/../dievai/admin;m,1'"><img src="images/icons/publish.png" alt="" /></button>
            <button title="Užrašine" onclick="window.location='http://localhost/MM2/v1/dievai/../dievai/admin;m,2'"><img src="images/icons/finished-work.png" alt="" /></button>
          </div>

       
      <?php
    	
			include("priedai/kairespaneles.php");
			?>

        </div>
                

        <div id="right">
          		
          <div id="container"> 
            <div class="where"><img src="images/bullet.png" alt="" /> <a href="?"><?php echo $lang['admin']['homepage'];  ?></a> > <a href="<?php echo url('?id,'.$_GET['id']);?>"><?php echo $page_pavadinimas;  ?></a> </div>
				<?php
          if (isset($strError) && !empty($strError)) { klaida("Klaida",$strError); }
          include($page.".php");
        ?>
        </div>
      </div>
      <div style="clear: both;"></div>
      <div id="footer">
         <div class="c">©</div>

         <div class="text">
          <div class="copy">
            <div class="links"><a href="http://mightmedia.lt">MightMedia</a> | <a href="http://mightmedia.lt/Kontaktai">Kontaktai</a> | <a href="http://www.gnu.org/licenses/gpl.html">GNU</a></div><?php echo $conf['Copyright']; ?></div>
            
         </div>

          </div>
      </div>
	  </div>
  </body>
</html>
