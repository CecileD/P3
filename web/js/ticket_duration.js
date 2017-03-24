$(function()
{
    $('#datepicker').on('changeDate', function() {
        var now = new Date();
        //On formate la date du jour pour être conforme au retour de datepicker

        //On ajoute un 0 au jour si le chiffre du jour est inférieur à 10
        var day;
        if(now.getDate()>9){day= now.getDate()}else{day = '0'+now.getDate()}

        //De même pour le mois
        var month;
        if(now.getMonth()>9){month = (now.getMonth()+1)}else{month = '0'+(now.getMonth()+1)}

        var year = now.getFullYear();

        //On concatène les trois éléments de la date
        var formatedDate = day+'/'+month+'/'+year;

        //Lors d'un changement de date à la date du jour courant et si l'heure a dépassé 14h
        if($('#datepicker').datepicker('getFormattedDate') == formatedDate && now.getHours()>=14 )
        {
            //On désactive l'option Journée et on sélectionne l'option demi-journée (pour éviter que l'option journée déjà sélectionnée s'applique).
            $("select option:contains('Journée')").attr("disabled","disabled");
            $("select option:contains('Journée')").attr("selected",false);
            $("select option:contains('Demi-journée')").attr('selected',true);
        }else{
            //Sinon on réactive l'option Journée si cette dernière n'est pas activé
            $("select option:contains('Journée')").prop("disabled", false);
        }
    });
});