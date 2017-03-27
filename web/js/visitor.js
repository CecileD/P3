$(function()
{
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#mdl_corebundle_registration_visitors');

        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $('#mdl_corebundle_registration_visitors').children().length;

        // On ajoute un premier champ automatiquement si aucun n'est défini
        if (index == 0) {
            addVisitor($container);
        }

        //On initialise les champs visiteurs existants (nom des labels, boutons supprimer...)
        initializeLabel();



        // Ajoute un nouveau champ visiteur au clic
        $('#add_visitor').click(function(e) {
            addVisitor($container);
            e.preventDefault();
            return false;
        });


        // Fonction permettant l'ajout d'un champ visiteur
        function addVisitor($container) {

            var template = $container.attr('data-prototype')
                    .replace(/__name__label__/g, 'Visiteur n°' + (index+1))
                    .replace(/__name__/g,        index)
                ;


            var $prototype = $(template);

            // On ajoute au prototype un lien pour pouvoir supprimer le visiteur
            addDeleteLink($prototype);

            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);

            // On incrémente le compteur pour pouvoir connaître le nombre de visiteurs courants dans la page
            index++;

            //A chaque ajout on redéfini les numéros de label (permet d'éviter des numéros ne se suivant pas)
            countVisitors();

        }

        // La fonction qui ajoute un lien de suppression d'un visiteur
        function addDeleteLink($prototype) {
            // Création du lien
            var $deleteLink = $('<div class="col-sm-1"><a href="#" class="btn btn-danger supprimer" id="delete_'+index+'">Supprimer</a></div>');
            // Ajout du lien
            $prototype.append($deleteLink);

            //On écoute le clic sur le bouton de suppression, si le nombre de visiteur est supérieur à un on supprime le visiteur associé au bouton.
            $deleteLink.click(function(e) {
                if(index>1)
                {
                    $prototype.remove();
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    index--;
                    countVisitors();
                    return false;
                }else
                {
                    alert('Il doit obligatoirement y avoir au moins un visiteur');
                }

            });
        }

        //Fonction comptant le nombre de visiteurs et redéfinissant les numéros de label à l'ajout ou la suppression d'un visiteur
        function countVisitors()
        {
            $("label:contains('Visiteur n°')").each(function(cpt)
                {
                    $(this).text();
                    $(this).text('Visiteur n°'+(cpt+1));
                }
            );
        }

        //Fonction permettant d'initialiser les labels et les boutons suppression pour des visiteurs déjà existant lors du chargement de la page
        function initializeLabel()
        {
            var cpt =0;

            $("label").each(function()
            {
                var test = $(this).text();
                if($.isNumeric(test))
                {
                    $(this).text('Visiteur n°'+(cpt+1));
                    cpt++;

                    if(!$('#mdl_corebundle_registration_visitors_'+(cpt)+' > .supprimer').length)
                    {
                        addDeleteLink($(this).parent());
                    }
                }
            });
        }
});