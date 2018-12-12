<?php
// Install finish and redirect
if (! empty($_POST) && isset($_POST['finish'])) {
	header('Location: ' . $_SESSION['main_url']);
}
