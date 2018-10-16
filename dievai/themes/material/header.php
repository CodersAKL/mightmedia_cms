<!DOCTYPE html>
<html>

<head>
    <?php defaultHead(); ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="themes/material/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="themes/material/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Wait Me Css -->
    <link href="themes/material/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- JQuery Nestable Css -->
    <link href="themes/material/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="themes/material/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Waves Effect Css -->
    <link href="themes/material/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="themes/material/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="themes/material/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="themes/material/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="themes/material/css/themes/all-themes.css" rel="stylesheet" />

    <!-- Jquery Core Js -->
    <script src="themes/material/plugins/jquery/jquery.min.js"></script>
    <!-- treview -->
    <link rel="stylesheet" href="css/jquery.treeview.css" />
    <script src="js/jquery.treeview.js" type="text/javascript"></script>
    <script type="text/javascript">
		$(document).ready(function () {
			$("#treemenu").treeview({
				persist:"location",
				collapsed:true,
				unique:true
			});
		});
	</script>
    
    <!-- Bootstrap Notify Plugin Js -->
    <script src="themes/material/plugins/bootstrap-notify/bootstrap-notify.js"></script>
</head>

<body class="theme-deep-orange">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p><?php echo $lang['sb']['loading']; ?></p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <form method="post" action="<?php echo url( '?id,999;m,4' ); ?>">
            <input name="vis" value="vis" type="hidden" >
            <input type="text" name="s" value="" placeholder="<?php echo $lang['search']['search']; ?>...">
        </form>
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="index.html">
                    <?php echo input(strip_tags($conf['Pavadinimas']) . ' - Admin'); ?>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->