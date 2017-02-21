$(function()
{
    var currentDate = new Date();

    //Configuration du calendrier (langue, date désactivés
    $('.hidden_zone').hide();
    $('#datepicker').datepicker(
        {
            autoclose : true,
            language :"fr-FR",
            datesDisabled : ["01/05/yyyy","01/11/yyyy","25/12/yyyy"],
            daysOfWeekDisabled : [2],
            startDate : currentDate
        }
    );
    $('#datepicker').val();
    var pickedDate = $('#datepicker').datepicker('getFormattedDate');
    $('#datepicker').on('changeDate', function() {
        $('#registration_date').val(pickedDate);
        $('#date_selected').text(
            $('#datepicker').datepicker('getFormattedDate')
        );
        $('.hidden_zone').show();
    });
});