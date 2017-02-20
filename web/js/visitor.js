$(function()
{
        // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
        var $container = $('div#mdl_corebundle_registration_visitors');
        //$('div #mdl_corebundle_registration_visitors').after('<div class="form-group" style="margin-top: 10px"><a href="#" id="add_visitor" class="btn btn-default ">Ajouter un visiteur</a></div>');

        // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
        var index = $('#mdl_corebundle_registration_visitors').children().length;

        // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
        if (index == 0) {
            addVisitor($container);
        }

        // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
        $('#add_visitor').click(function(e) {
            addVisitor($container);
            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });


        // La fonction qui ajoute un formulaire CategoryType
        function addVisitor($container) {
            // Dans le contenu de l'attribut « data-prototype », on remplace :
            // - le texte "__name__label__" qu'il contient par le label du champ
            // - le texte "__name__" qu'il contient par le numéro du champ
            var template = $container.attr('data-prototype')
                    .replace(/__name__label__/g, 'Visiteur n°' + (index+1))
                    .replace(/__name__/g,        index)
                ;


            // On crée un objet jquery qui contient ce template
            var $prototype = $(template);

            // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
            addDeleteLink($prototype);
            // On ajoute le prototype modifié à la fin de la balise <div>
            $container.append($prototype);


            // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
            index++;

        }

        // La fonction qui ajoute un lien de suppression d'une catégorie
        function addDeleteLink($prototype) {
            // Création du lien
            var $deleteLink = $('<div class="col-sm-12"><a href="#" class="btn btn-danger">Supprimer</a></div>');

            // Ajout du lien
            $prototype.append($deleteLink);

            // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
            $deleteLink.click(function(e) {
                $prototype.remove();
                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                index--;
                return false;
            });
        }
});