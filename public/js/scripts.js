$(document).ready(function () {

    /** Trick Medias Show/form mobile */
    $('#dropdownMedias').on('click', function (e) {
        e.preventDefault();
        $('.trick-medias.no_mobile').slideToggle();
    });


    /** Trick Medias form */
    $('.edit-media-link>.bi-pencil-fill').on('click', function (e) {
        e.preventDefault();
        const mediaId = e.target.parentNode.dataset.id;
        this.remove();
        $('#div_' + mediaId).removeClass('col-md-2').addClass('col-12');
        $('#' + mediaId + "_url").removeAttr('hidden');

    });

    /**Allow add Media */
    var $mediasCollectionHolder = $('.medias');
    $mediasCollectionHolder.data('index', $mediasCollectionHolder.find('input').length);

    $('body').on('click', '.add_media_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);
    });

    function addFormToCollection($collectionHolderClass) {
        var $collectionHolder = $('.' + $collectionHolderClass);
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype;

        newForm = newForm.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);

        var $newFormItem = $('<div class="col-md-2"></div>').append(newForm);

        $collectionHolder.append($newFormItem);
    }


});