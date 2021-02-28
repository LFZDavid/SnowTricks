$(document).ready(function () {

    /** Trick Show/form mobile */

    $('#dropdownMedias').on('click', function (e) {
        e.preventDefault();
        $('.trick-medias.no_mobile').slideToggle();
    });


    /** Trick form */
    $('.edit-media-link>.bi-pencil-fill').on('click', function (e) {
        e.preventDefault();
        const mediaId = e.target.parentNode.dataset.id;
        this.remove();
        $('#div_' + mediaId).removeClass('col-md-2').addClass('col-12');
        $('#' + mediaId + "_url").removeAttr('hidden');

    })

});
