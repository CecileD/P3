$(function()
{
    //On affecte l'attribut active à l'une ou l'autre des rubriques du site en fonction de la situation du visiteur sur le site
    $(".nav").find(".active").removeClass("active");
    if($(location).attr('href') == 'http://localhost/MDL/web/app_dev.php/' || $(location).attr('href') == 'http://localhost/MDL/web/' )
    {
        $('li:contains("Accueil")').addClass("active");
    }else{
        $('li:contains("Billetterie")').addClass("active");
    }
});