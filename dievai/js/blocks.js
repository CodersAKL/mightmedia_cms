/* $('.add-block').on('click', function() {
    $.get(($(this).data('href')), function (data) {
        $("#page-builder-zone").append(data);
    });
}); */
/*
 
$('.add-block').on('click', function() {
    get = ($(this).data('href')).split(' ');
    console.log(get[0], get[1]);
    $.get("page-assembler.php",
    {insertBlock : get[0], blockType : get[1]},
    function(data) {
       //alert('page content: ' + data);
    }
    );
}); */

$('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    //e.preventDefault();
});
