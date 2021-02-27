$(document).ready(function () {

    /** Trick form */
    $('.edit-media-link>.bi-pencil-fill').on('click', function (e) {
        e.preventDefault();
        const mediaId = e.target.parentNode.dataset.id;
        $('#div_' + mediaId).removeClass('col-md-2');
        $('#' + mediaId + "_url").removeAttr('hidden');


    })

});
