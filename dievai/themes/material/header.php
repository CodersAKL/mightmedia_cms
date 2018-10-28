<!DOCTYPE html>
<html>

<head>
    <?php defaultHead(); ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
    <!-- Jquery Nestable -->
    <script src="themes/material/plugins/nestable/jquery.nestable.js"></script>
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 97.3" class="admin-logo">
                        <ellipse cx="12.3" cy="57" rx="12.3" ry="12.3"/>
                        <path d="M57 68.2c6.2-2.9 8.8-10.2 6-16.4L42.2 7.2C39.3 1 32-1.7 25.8 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.3 8.9 16.4 6z"/>
                        <path d="M84.2 68.2c6.2-2.9 8.8-10.2 6-16.4L69.3 7.1C66.4 1 59.1-1.7 53 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.2 8.8 16.4 6z"/>
                        <g>
                            <path d="M2.5 89.4v4.5H0v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5H5.1v-4.5c0-1.5-.4-2.2-1.3-2.2s-1.3.6-1.3 2.2zM17 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3h2.5v8.3h-2.5zM21.9 94c-2.5 0-3.6-1.3-3.6-4.3s1.1-4.3 3.6-4.3c1.2 0 2.1.3 2.7 1l.3-.8h1.9V93c0 3-1.3 4.3-4.3 4.3-2.5 0-3.6-.8-3.9-2.8H21c.2.9.6 1.2 1.5 1.2 1.4 0 2-.7 2-2.4v-.2c-.6.7-1.5.9-2.6.9zm2.5-4.3c0-1.8-.5-2.5-1.8-2.5s-1.8.7-1.8 2.5.5 2.5 1.8 2.5 1.8-.7 1.8-2.5zM28.8 93.8V82.2h2.5V86c.5-.4 1.3-.6 2.2-.6 2.3 0 3.3 1.2 3.3 4v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.5-2.2s-1.5.6-1.5 2.2v4.5-.1.1h-2.5v-.1zM42.7 92h1.4v1.8h-2.3c-1.6 0-2.6-1-2.6-2.5v-4.2h-1.3v-1.5h1.3v-2.1h2.5v2.1h2.4v1.5h-2.4v4c0 .5.4.9 1 .9zM48 89.4v4.5h-2.5v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.3-2.2-.8 0-1.2.6-1.3 2.2zM63.7 92.5c.9 0 1.4-.4 1.6-1.2h2.3c-.4 1.9-1.5 2.8-3.9 2.8-3 0-4.3-1.3-4.3-4.3s1.3-4.3 4.3-4.3 4.3 1.3 4.3 4.4v.7h-6.1c.1 1.3.7 1.9 1.8 1.9zm-1.8-3.4h3.7c0-1.5-.5-2.2-1.8-2.2s-1.8.7-1.9 2.2zM68.9 89.7c0-3 1.1-4.3 3.6-4.3 1.1 0 1.9.2 2.5.7v-3.9h2.5v11.6h-1.9l-.3-.8c-.6.6-1.5.9-2.7.9-2.6.1-3.7-1.2-3.7-4.2zm6.1 0c0-1.8-.5-2.5-1.8-2.5s-1.8.8-1.8 2.5c0 1.8.5 2.5 1.8 2.5s1.8-.8 1.8-2.5zM82.1 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3H82v8.3h-2.5zM86.5 94c-2.2 0-3.1-.8-3.1-2.6 0-2 1.2-2.8 4-2.8h1.4v-.4c0-1-.4-1.4-1.4-1.4-1 0-1.4.4-1.4 1.3h-2.3c0-1.9 1.1-2.7 3.8-2.7 2.6 0 3.8.9 3.8 3.1v5.4h-1.9l-.3-.7c-.5.5-1.4.8-2.6.8zm1.3-4.1h-.3c-1.1 0-1.6.4-1.6 1.3 0 .8.4 1.1 1.3 1.1 1.1 0 1.6-.5 1.6-1.6V90h-1z"/>
                        </g>
                    </svg>
                    <div class="logo-text">
                        <?php echo input(strip_tags($conf['Pavadinimas']) . ' - Admin'); ?>
                    </div>
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