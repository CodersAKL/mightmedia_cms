<?php
    if ( isset( $_SESSION[SLAPTAS]['level'] ) && $_SESSION[SLAPTAS]['level'] == 1 ) {
        redirect( 'main.php' );
    }
?>
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

    <!-- Waves Effect Css -->
    <link href="themes/material/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="themes/material/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="themes/material/css/style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 97.3" class="admin-login-logo">
                    <ellipse fill="#ED983B" cx="12.3" cy="57" rx="12.3" ry="12.3"/>
                    <path fill="#E57B3D" d="M57 68.2c6.2-2.9 8.8-10.2 6-16.4L42.2 7.2C39.3 1 32-1.7 25.8 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.3 8.9 16.4 6z"/>
                    <path fill="#DD6A45" d="M84.2 68.2c6.2-2.9 8.8-10.2 6-16.4L69.3 7.1C66.4 1 59.1-1.7 53 1.2c-6.2 2.9-8.8 10.2-6 16.4l20.8 44.6c2.9 6.2 10.2 8.8 16.4 6z"/>
                    <g fill="#4F4F4F">
                        <path d="M2.5 89.4v4.5H0v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5H5.1v-4.5c0-1.5-.4-2.2-1.3-2.2s-1.3.6-1.3 2.2zM17 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3h2.5v8.3h-2.5zM21.9 94c-2.5 0-3.6-1.3-3.6-4.3s1.1-4.3 3.6-4.3c1.2 0 2.1.3 2.7 1l.3-.8h1.9V93c0 3-1.3 4.3-4.3 4.3-2.5 0-3.6-.8-3.9-2.8H21c.2.9.6 1.2 1.5 1.2 1.4 0 2-.7 2-2.4v-.2c-.6.7-1.5.9-2.6.9zm2.5-4.3c0-1.8-.5-2.5-1.8-2.5s-1.8.7-1.8 2.5.5 2.5 1.8 2.5 1.8-.7 1.8-2.5zM28.8 93.8V82.2h2.5V86c.5-.4 1.3-.6 2.2-.6 2.3 0 3.3 1.2 3.3 4v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.5-2.2s-1.5.6-1.5 2.2v4.5-.1.1h-2.5v-.1zM42.7 92h1.4v1.8h-2.3c-1.6 0-2.6-1-2.6-2.5v-4.2h-1.3v-1.5h1.3v-2.1h2.5v2.1h2.4v1.5h-2.4v4c0 .5.4.9 1 .9zM48 89.4v4.5h-2.5v-8.3h1.9l.4.9c.4-.8 1.2-1.1 2.3-1.1 1.1 0 1.9.4 2.4 1.2.5-.8 1.3-1.2 2.5-1.2 2.1 0 3.1 1.2 3.1 4v4.5h-2.5v-4.5c0-1.5-.3-2.1-1.1-2.2h-.1c-.9 0-1.3.7-1.3 2.2v4.5h-2.5v-4.5c0-1.5-.4-2.2-1.3-2.2-.8 0-1.2.6-1.3 2.2zM63.7 92.5c.9 0 1.4-.4 1.6-1.2h2.3c-.4 1.9-1.5 2.8-3.9 2.8-3 0-4.3-1.3-4.3-4.3s1.3-4.3 4.3-4.3 4.3 1.3 4.3 4.4v.7h-6.1c.1 1.3.7 1.9 1.8 1.9zm-1.8-3.4h3.7c0-1.5-.5-2.2-1.8-2.2s-1.8.7-1.9 2.2zM68.9 89.7c0-3 1.1-4.3 3.6-4.3 1.1 0 1.9.2 2.5.7v-3.9h2.5v11.6h-1.9l-.3-.8c-.6.6-1.5.9-2.7.9-2.6.1-3.7-1.2-3.7-4.2zm6.1 0c0-1.8-.5-2.5-1.8-2.5s-1.8.8-1.8 2.5c0 1.8.5 2.5 1.8 2.5s1.8-.8 1.8-2.5zM82.1 83.1c0 .9-.4 1.3-1.4 1.3-1 0-1.4-.4-1.4-1.3s.4-1.3 1.4-1.3c1 .1 1.4.5 1.4 1.3zm-2.6 10.7v-8.3H82v8.3h-2.5zM86.5 94c-2.2 0-3.1-.8-3.1-2.6 0-2 1.2-2.8 4-2.8h1.4v-.4c0-1-.4-1.4-1.4-1.4-1 0-1.4.4-1.4 1.3h-2.3c0-1.9 1.1-2.7 3.8-2.7 2.6 0 3.8.9 3.8 3.1v5.4h-1.9l-.3-.7c-.5.5-1.4.8-2.6.8zm1.3-4.1h-.3c-1.1 0-1.6.4-1.6 1.3 0 .8.4 1.1 1.3 1.1 1.1 0 1.6-.5 1.6-1.6V90h-1z"/>
                    </g>
                </svg>

            </a>
            <small><?php echo $conf['Pavadinimas']; ?> - <?php echo $conf['Apie']; ?></small>
        </div>
        <div class="card">
            <div class="body">
            <?php if ( !empty( $strError ) ): ?>
                <div class='klaida'>
                    <div class='info_ikona'></div>
                    <div class='info_pavadinimas'><?php echo $lang['system']['warning']; ?></div>
                    <div class='info_tekstas'><?php echo $strError; ?></div>
                </div>
                <br />
			<?php endif ?>
                <form name="loginform" id="sign_in" method="POST">
                    <div class="msg">
                        <?php echo $lang['system']['pleaselogin']; ?>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="vartotojas" placeholder="<?php echo $lang['user']['user'];?>" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="slaptazodis" placeholder="<?php echo $lang['user']['password']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <!-- <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label> -->
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-deep-orange waves-effect" type="submit">
                                <?php echo $lang['user']['login']; ?>
                            </button>
                        </div>
                    </div>
                    <!-- <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div> -->
                    <input type="hidden" name="action" value="prisijungimas" />
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="themes/material/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="themes/material/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="themes/material/plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="themes/material/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="themes/material/js/admin.js"></script>
    <script src="themes/material/js/pages/examples/sign-in.js"></script>
</body>

</html>