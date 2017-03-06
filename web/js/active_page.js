$(function()
{

    $(".nav").find(".active").removeClass("active");

    console.log($(location).attr('href'));
    if($(location).attr('href') == 'http://localhost/MDL/web/app_dev.php/')
    {
        $('li:contains("Accueil")').addClass("active");
    }else{
        $('li:contains("Billetterie")').addClass("active");
    }
});