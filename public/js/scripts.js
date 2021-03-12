$(document).ready(function () {

    /** Trick Medias Show/form mobile */
    $('#dropdownMedias').on('click', function (e) {
        e.preventDefault();
        $('.trick-medias.no_mobile').slideToggle();
    });

    /** Edit Main Image btn */
    $('.edit-main-img-btns>a>.bi-pencil-fill').on('click', function (e) {
        e.preventDefault();
        var $medias = $('.medias').children();
        if ($medias.length > 0) {
            $('.trick-medias.no_mobile').slideDown(500, function () {
                $('#div_trick_medias_0').toggleClass('col-md-2').find('.upload-input').slideToggle();
            });
        }
    });

    /** Delete Main Image btn */
    $('.edit-main-img-btns>a>.bi-trash-fill').on('click', function (e) {
        e.preventDefault();
        $medias = $('.medias').children();
        if ($medias.length > 0) {
            $('.trick-medias.no_mobile').slideToggle(500, function () {
                $('#div_trick_medias_0').remove();
            });
            if ($medias.length <= 1) {
                $('.main-img').attr('src', '/img/tricks/default.jpg');
            }
        }

    });

    /** Trick Medias form */
    $('.edit-media-link>.bi-pencil-fill').on('click', function (e) {
        e.preventDefault();
        const mediaId = e.target.parentNode.dataset.id;
        $('#div_' + mediaId).toggleClass('col-md-2').find('.upload-input').slideToggle();
    });

    /** Add Media */
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

        var $newFormItem = $('<div class="row"></div>').append(newForm);

        ($newFormItem.find('input')[0]).setAttribute('placeholder', 'url...');

        $collectionHolder.append($newFormItem);

    }

    /** Delete Media */

    $('.delete-media-link>.bi-trash-fill').on('click', function (e) {
        e.preventDefault();
        const mediaId = e.target.parentNode.dataset.id;
        $('#div_' + mediaId).remove();

    });

});
