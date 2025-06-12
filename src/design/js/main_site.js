/*

Use this for any additional Jquery

*/

$(document).ready(function () {

    var $masonryContainer = $('.masonry');
    $masonryContainer.masonry({
        "columnWidth": ".grid-sizer",
        "itemSelector": ".masonry-item"
    });
    
    $('#notice').scrollbox();
    
    $(window).on('load', function(){
        $masonryContainer.masonry();
    });

    // fix bootstrap 3 modal hide bug
    $('.modal').on('hide.bs.modal', function (e) {
       $('body').removeClass('modal-open'); 
    });
    
});

$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});