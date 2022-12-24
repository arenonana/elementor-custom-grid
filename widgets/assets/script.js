jQuery(document).ready(function ($) {
    $('img.gallery-img').click(function () {

        $('img.modat-img').attr("src", $(this).attr('src'));
        console.log($('img.modat-img').attr("src"));
        $('.custom-modal').removeClass('hide');
    });

    $('.close-btn,.custom-modal').click(function () {
        $('.custom-modal').addClass('hide');
    });

});