
<?php
if (! defined('ROOT')) {
	define('ROOT', dirname(__DIR__) . '/');
}
//main functions
require ROOT . 'install/functions.php';

$out_page = TRUE;

// Diegimo stadijų registravimas
if (! isset($_GET['step']) || empty($_GET['step'])) {
	$_SESSION['step']   = 1;
    $currentStep        = 1;
} else {
	if ($_GET['step'] != 1) {
		$currentStep = (int)$_GET['step'];
		if ($_SESSION['step'] == ($currentStep - 1)) {
			$_SESSION['step'] = $currentStep;
		}
	} else {
		header( "Location: index.php?step=" . $_SESSION['step'] );
	}
}

$stepDirName = ROOT . 'install/steps/' . $currentStep .'/';

if (is_dir($stepDirName) && is_file($stepDirName . 'controller.php')) {
    include $stepDirName . 'controller.php'; 
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="/images/favicon/safari-pinned-tab.svg" color="#db7300">
	<meta name="msapplication-TileColor" content="#ff440e">
	<meta name="theme-color" content="#ffffff">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo siteUrl(); ?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo siteUrl(); ?>plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo siteUrl(); ?>plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo siteUrl(); ?>assets/css/style.css" rel="stylesheet">

    <!-- Jquery Core Js -->
    <script src="<?php echo siteUrl(); ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo siteUrl(); ?>plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <!-- Bootstrap Select Css -->
    <link href="<?php echo siteUrl(); ?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <title><?php echo $lang['setup']['heading']; ?></title>
</head>

<body class="page">
    <div class="page--logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 97.3" class="page--logo-svg">
                <ellipse fill="#F5A31C" cx="12.3" cy="57" rx="12.3" ry="12.3"/>
                <path fill="#F16622" d="M57 68.2c6.2-2.9 8.8-10.2 6-16.4L42.2 7.2C39.3 1 32-1.7 25.8 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.3 8.9 16.4 6z"/>
                <path fill="#EF512E" d="M84.2 68.2c6.2-2.9 8.8-10.2 6-16.4L69.3 7.1C66.4 1 59.1-1.7 53 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.2 8.8 16.4 6z"/>
                <path fill="#4F4F4F" d="M2.5 89.4v4.5H0v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5H5.1v-4.5c0-1.5-.4-2.2-1.3-2.2s-1.3.6-1.3 2.2zM17 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3h2.5v8.3h-2.5zM21.9 94c-2.5 0-3.6-1.3-3.6-4.3s1.1-4.3 3.6-4.3c1.2 0 2.1.3 2.7 1l.3-.8h1.9V93c0 3-1.3 4.3-4.3 4.3-2.5 0-3.6-.8-3.9-2.8H21c.2.9.6 1.2 1.5 1.2 1.4 0 2-.7 2-2.4v-.2c-.6.7-1.5.9-2.6.9zm2.5-4.3c0-1.8-.5-2.5-1.8-2.5s-1.8.7-1.8 2.5.5 2.5 1.8 2.5 1.8-.7 1.8-2.5zM28.8 93.8V82.2h2.5V86c.5-.4 1.3-.6 2.2-.6 2.3 0 3.3 1.2 3.3 4v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.5-2.2s-1.5.6-1.5 2.2v4.5-.1.1h-2.5v-.1zM42.7 92h1.4v1.8h-2.3c-1.6 0-2.6-1-2.6-2.5v-4.2h-1.3v-1.5h1.3v-2.1h2.5v2.1h2.4v1.5h-2.4v4c0 .5.4.9 1 .9zM48 89.4v4.5h-2.5v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.3-2.2-.8 0-1.2.6-1.3 2.2zM63.7 92.5c.9 0 1.4-.4 1.6-1.2h2.3c-.4 1.9-1.5 2.8-3.9 2.8-3 0-4.3-1.3-4.3-4.3s1.3-4.3 4.3-4.3 4.3 1.3 4.3 4.4v.7h-6.1c.1 1.3.7 1.9 1.8 1.9zm-1.8-3.4h3.7c0-1.5-.5-2.2-1.8-2.2s-1.8.7-1.9 2.2zM68.9 89.7c0-3 1.1-4.3 3.6-4.3 1.1 0 1.9.2 2.5.7v-3.9h2.5v11.6h-1.9l-.3-.8c-.6.6-1.5.9-2.7.9-2.6.1-3.7-1.2-3.7-4.2zm6.1 0c0-1.8-.5-2.5-1.8-2.5s-1.8.8-1.8 2.5c0 1.8.5 2.5 1.8 2.5s1.8-.8 1.8-2.5zM82.1 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3H82v8.3h-2.5zM86.5 94c-2.2 0-3.1-.8-3.1-2.6 0-2 1.2-2.8 4-2.8h1.4v-.4c0-1-.4-1.4-1.4-1.4-1 0-1.4.4-1.4 1.3h-2.3c0-1.9 1.1-2.7 3.8-2.7 2.6 0 3.8.9 3.8 3.1v5.4h-1.9l-.3-.7c-.5.5-1.4.8-2.6.8zm1.3-4.1h-.3c-1.1 0-1.6.4-1.6 1.3 0 .8.4 1.1 1.3 1.1 1.1 0 1.6-.5 1.6-1.6V90h-1z"/>
            </svg>
            <small>
                <?php echo $lang['setup']['heading']; ?>
            </small>
        </div>
    <div class="container">
       <div class="row">
       <div class="col-md-4">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?php echo $lang['setup']['steps']; ?>
                        </h2>
                    </div>
                    <div class="body">
                        <?php
                            $steps = [
                                1 => $lang['setup']['lang'] . '/' . $lang['setup']['time_zone'],
                                2 => $lang['setup']['liceanse'], 
                                3 => $lang['setup']['file_check'], 
                                4 => $lang['setup']['database'], 
                                5 => $lang['setup']['admin'], 
                                6 => $lang['setup']['admin_dir'], 
                                7 => $lang['setup']['url'], 
                                8 => $lang['setup']['end'] 
                            ];
                        ?>
                        
                        <div class="list-group">
                            <?php foreach ($steps as $key => $step) { ?>
                                <li class="list-group-item <?php echo stepClass($currentStep, $key); ?>">
                                    <?php if(stepClass($currentStep, $key) == 'list-group-item-success') { ?>
                                        <a href="?step=<?php echo $key; ?>">
                                            <?php echo $key; ?>. <?php echo $step; ?>
                                        </a>
                                    <?php } else { ?>
                                        <?php echo $key; ?>. <?php echo $step; ?>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?php echo $steps[$currentStep]; ?>
                        </h2>
                    </div>
                    <div class="body">
                        <?php
                            if (is_dir($stepDirName)) {
                                include $stepDirName . 'view.php'; 
                            }
                        ?>
                    </div>
                </div>
            </div>
       </div>
    </div>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo siteUrl(); ?>plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo siteUrl(); ?>plugins/node-waves/waves.js"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo siteUrl(); ?>plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo siteUrl(); ?>assets/js/admin.js"></script>
</body>

</html>