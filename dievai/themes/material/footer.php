    <!-- Bootstrap Core Js -->
    <script src="themes/material/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="themes/material/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="themes/material/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="themes/material/plugins/autosize/autosize.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="themes/material/plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="themes/material/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="themes/material/plugins/raphael/raphael.min.js"></script>
    <script src="themes/material/plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="themes/material/plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="themes/material/plugins/flot-charts/jquery.flot.js"></script>
    <script src="themes/material/plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="themes/material/plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="themes/material/plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="themes/material/plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="themes/material/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="themes/material/js/admin.js"></script>
    <script src="themes/material/js/pages/index.js"></script>

    <!-- Demo Js -->
    <script src="themes/material/js/demo.js"></script>
    <?php
        if(! empty($_SESSION[SLAPTAS]['redirect'])) {
            notifyMsg($_SESSION[SLAPTAS]['redirect']);
        }
    ?>
</body>

</html>