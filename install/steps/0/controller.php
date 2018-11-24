<?php

if (isset($_POST['language'])) {
    $_SESSION['language'] = $_POST['language'];
    header('Location: index.php?step=1');
}