<?php
//Administravimo direktorijos keitimas
if (! empty($_POST) && isset($_POST['admin_dir'])) {
	if (! is_dir(ROOT . "dievai")){
		header("Location: index.php?step=6");
	}
}
//Administravimo direktorijos keitimas
if (! empty($_POST) && isset($_POST['admin_dir'])) {
	if (! is_dir(ROOT . "dievai")){
		header("Location: index.php?step=7");
	}
}
