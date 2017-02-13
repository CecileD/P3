$(function()
{
    var currentDate = new Date();

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
    });
    $('#registration_date').change(function ()
    {

    });
});