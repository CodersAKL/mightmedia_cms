<?php
require_once '../../../../../../priedai/conf.php';
require_once '../../../../../../priedai/funkcijos.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<base href="<?php echo str_replace('htmlarea/markitup/utils/manager/scripts/','',adresas()); ?>../" />
		<title>markItUp! preview template</title>
		<link rel="stylesheet" type="text/css" href="stiliai/<?php echo $conf['Stilius'];?>/default.css" />
	</head>
	<body>
		<!-- content -->
		<?php echo str_replace('===page===','<hr />',$_POST['data']); ?>
	</body>
</html>
