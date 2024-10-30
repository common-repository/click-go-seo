function limiting(obj, counterId, min, max) {
    var cnt = jQuery("#" + counterId);
    var txt = jQuery(obj).val();
    var len = txt.length;

    jQuery(cnt).html(len);
    if (len > max) {
        jQuery(obj).addClass("long");
        jQuery(obj).removeClass("short");
        jQuery(obj).removeClass("exact");
    }
    if (len < min) {
        jQuery(obj).addClass("short");
        jQuery(obj).removeClass("exact");
        jQuery(obj).removeClass("long");
    }
    if (len <= max && len >= min) {
        jQuery(obj).removeClass("short");
        jQuery(obj).removeClass("long");
        jQuery(obj).addClass("exact");
    }
}

jQuery(document).ready(function () {

    jQuery('#metatitle').keyup(function () {
        limiting(jQuery(this),"counterInput", 45, 65);
    }).keyup();
    jQuery('#metadesc').keyup(function () {
        limiting(jQuery(this),"counterTextarea", 100, 155);
    }).keyup();
   
});