/**
 * Created by Ben-usr on 08/03/2017.
 */
$(function()
{
    $('.form-horizontal').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: 'L\'email ne doit pas être vide.'
                    },
                    emailAddress: {
                        message: 'L\'email entré n\'est pas valide'
                    }
                }
            },
            first_name: {
                validators: {
                    stringLength: {
                        min: 2,
                    },
                    notEmpty: {
                        message: 'Veuillez entrer un prénom'
                    }
                }
            },
            last_name: {
                validators: {
                    stringLength: {
                        min: 2,
                    },
                    notEmpty: {
                        message: 'Veuillez entrer un nom'
                    }
                }
            }
        }
    });
});