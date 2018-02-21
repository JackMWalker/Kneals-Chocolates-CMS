/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function() {

    createAddEmbedForm('#product_image', 'add_product_image_link', 'Add Image', 'Delete Image');

    function createAddEmbedForm($holderSelector, $linkClass, $addText, $deleteText) {
        // Get the ul that holds the collection of tags
        var $collectionHolder = $($holderSelector);

        if($collectionHolder) {
            // setup an "add a tag" link
            var $addLink = $('<a href="#" class="' + $linkClass + '">' + $addText + '</a>');
            var $newLinkLi = $('<span></span>').append($addLink);

            // add the "add a tag" anchor and li to the tags ul
            $collectionHolder.append($newLinkLi);

            // count the current form inputs we have (e.g. 2), use that as the new
            // index when inserting a new item (e.g. 2)
            $collectionHolder.data('index', $collectionHolder.find(':input').length);

            $addLink.on('click', function(e) {
                // prevent the link from creating a "#" on the URL
                e.preventDefault();

                // add a new tag form (see next code block)
                addEmbededForm($collectionHolder, $newLinkLi, $deleteText);
            });

            $collectionHolder.find('li').each(function() {
                console.log($(this));
                addEmbededFormDeleteLink($(this), $deleteText);
            });
        }
    }

    function addEmbededForm($collectionHolder, $newLinkLi, $deleteText) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // You need this only if you didn't set 'label' => false in your tags field in TaskType
        // Replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        // newForm = newForm.replace(/__name__label__/g, index);

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a *" link li
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);

        addEmbededFormDeleteLink($newFormLi, $deleteText);
    }


    function addEmbededFormDeleteLink($formLi, $deleteText) {
        var $removeFormA = $('<a href="#">' + $deleteText + '</a>');
        $formLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $formLi.remove();
        });
    }
});