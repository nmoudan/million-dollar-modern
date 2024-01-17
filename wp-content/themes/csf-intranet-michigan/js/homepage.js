function isElementInView (element, fullyInView) {
    var pageTop = jQuery(window).scrollTop();
    var pageBottom = pageTop + jQuery(window).height();
    var elementTop = jQuery(element).offset().top;
    var elementBottom = elementTop + jQuery(element).height();

    if (fullyInView === true) {
        return ((pageTop < elementTop) && (pageBottom > elementBottom));
    } else {
        return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
    }
}

function detectEcoles () {
    var isInView = isElementInView(jQuery('#notre-equipe'), false);
    if (isInView) {
 		jQuery("#menu-item-11027").addClass("current");
    } else {
        jQuery("#menu-item-11027").removeClass("current");
    }
}

jQuery(document).ready(function($) {
    detectEcoles();

    $(window).scroll(function(){
        setTimeout(function() {
        	detectEcoles();
        }, 300);
    });
});

