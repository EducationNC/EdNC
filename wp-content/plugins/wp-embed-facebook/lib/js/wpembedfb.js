jQuery(document).ready(function () {
    jQuery(".wef-measure").each(function () {
        jQuery(this).next().attr("data-width", jQuery(this).outerWidth() + "px")
    })
});