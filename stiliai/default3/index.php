<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
    <?php header_info();?>
    <link rel="stylesheet" type="text/css" href="stiliai/<?php echo $conf['Stilius']; ?>/superfish.css" media="screen" />
      <script type="text/javascript" src="stiliai/<?php echo $conf['Stilius']; ?>/superfish.js"></script>
      <script type="text/javascript">
			$(document).ready(function(){
				$('ul.sf-menu').superfish();
				$('#navigation ul').superfish();
      });			
		</script>
</head>
<body>
  <div id="main">
    <div id="left">
      <div class="logo"></div>
      <div class="left1"></div>
     </div>
    <div class="bar">
      <div class="start"></div>
       <div class="nav">
          <ul class="sf-menu">
            <?php
               $res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' AND `lang` = ".escape(lang())." ORDER BY `place` ASC");
               $i = 0;
               $finish = false;
               if (sizeof($res) > 0) {
                 foreach ($res as $row) {
                    if(teises($row['teises'], $_SESSION['level'])){
                      if($i > 4 && $row['parent'] == 0)
                        $row['parent'] = 998;
                        $data[$row['parent']][] = $row;
                      if($row['parent'] == 0)
                        $i++;
                      
                      if($i > 4 && !$finish){
                         $data[0][] = array('id' => 998, 'parent' => 0, 'pavadinimas' => '»');
                         $finish = true;
                       }

                    }
                 }
                 //print_r($data);
                 echo build_menu($data);
               } 
               
               unset($data);
						?>
          </ul>
       </div>
    </div>
    <div class="login">
      <?php include("stiliai/".$conf['Stilius']."/vartotojas.php"); echo $text; unset($title, $text);?>
    </div>
    <div id="content">
      <div class="left_blocks">
          <?php
            include("priedai/kairespaneles.php");
          ?>
      </div>
      <div class="right_content" id="cont">
          <?php
            if (isset($strError) && !empty($strError)) { klaida("Klaida",$strError); }
            include($page.".php");
          ?>        
      </div>
      <div class="right_blocks">
        <?php
            include("priedai/desinespaneles.php");
        ?>
      </div>
    </div>
    <div class="footer1"></div>
    <div class="footer2">Dizainas: <a href="http://paulius.pasikark.eu">Paulius D.</a></div>
    <div class="copyright"><?php
copyright($conf['Copyright']); ?></div>
    </div>
</body>
</html>