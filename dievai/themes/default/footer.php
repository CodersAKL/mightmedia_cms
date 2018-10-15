<script type="text/javascript">
    //sub punktai
    $(document).ready(function() {
        $('.btns a').each(function(id, obj){
            $("#sub-menu-admin").append('<li><a href="'+obj.href+'">'+$(this).text()+'</a></li>');
        });
    });
</script>
</body>
</html>