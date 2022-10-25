var $collectionHolder;
var $boutonAjouter = $('<button type="button" class="add_sport_link">Ajouter élément</button>');
var $nouveau = $('<li></li>').append($boutonAjouter);

jQuery(document).ready(function () {
    $collectionHolder = $('ul.tags');
    $collectionHolder.find('li').each(function () {
        addSportFormDeleteLink($(this));
    });
    $collectionHolder.append($nouveau);
    $collectionHolder.data('index', $collectionHolder.find('input').
        length);
    $boutonAjouter.on('click', function (e) {
        addSportForm($collectionHolder, $nouveau);
    });
    function addSportForm($collectionHolder, $newLinkLi) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);
        addSportFormDeleteLink($newFormLi);
    }
    function addSportFormDeleteLink($tagFormLi) {
        var $removeFormButton = $('<button type="button">Supprimer élément</button>');
        $tagFormLi.append($removeFormButton);
        $removeFormButton.on('click', function (e) {
            $tagFormLi.remove();
        });
    }
});