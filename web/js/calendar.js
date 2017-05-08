$(function()
{
    var currentDate = new Date();

    //Configuration du calendrier (langue, dates désactivés, fermeture automatique...)
    $('.hidden_zone').hide();
    $('#datepicker').datepicker(
        {
            autoclose : true,
            language :"fr-FR",
            datesDisabled : ["01/05/yyyy","01/11/yyyy","25/12/yyyy"],
            daysOfWeekDisabled : [2,0],
            startDate : currentDate
        }
    );

    //Récupération de la valeur choisie et formatage. Affichage de la suite du formulaire
    $('#datepicker').val();

    var pickedDate = $('#datepicker').datepicker('getFormattedDate');
    $('#datepicker').on('changeDate', function() {
        var date = $('#mdl_corebundle_registration_date').val();
        date = date.split("/").reverse().join("-");
        console.log(date);
        $.ajax({
            url: "{{path('mdl_core_checkdate')}}",
            method: "post",
            data : {date : date}
        }).done(function(msg)
        {
            dateChecking();
        });
        $('#registration_date').val(pickedDate);
        $('#date_selected').text(
            $('#datepicker').datepicker('getFormattedDate')
        );
        $('.hidden_zone').show();
    });


    function dateChecking() {
        $.each(JSON.parse(msg['data']), function (i, item) {
            $('#registration_date').append('<div class="col-sm-12"><p class="alert alert-warning">' + item.reponse + '</p></div>');
        });
    }
});