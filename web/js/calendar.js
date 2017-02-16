$(function()
{
    var currentDate = new Date();


    //Configuration du calendrier (langue, date désactivés
    $('.hidden_zone').hide();
    $('#datepicker').datepicker(
        {
            language :"fr-FR",
            datesDisabled : ["01/05/yyyy","01/11/yyyy","25/12/yyyy"],
            daysOfWeekDisabled : [2],
            startDate : currentDate
        }
    );
    $('#datepicker').on('changeDate', function() {
        $('#registration_date').val(
            $('#datepicker').datepicker('getFormattedDate')
        );
        $('#date_selected').text(
            $('#datepicker').datepicker('getFormattedDate')
        );
        $('.hidden_zone').show();
        $('select').css('display','');
    });
});