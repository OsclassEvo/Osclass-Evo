// $('body').find('input[type="checkbox"]').each(function(index, val){
//     $(val).addClass('form-check-input');
//     $(this).replaceWith("<div class='form-check'><label class='form-check-label'>" + val.outerHTML + '<span class="form-check-sign"><span class="check"></span></span></label></div>');
// });

$('select:not(.cs-select):not([multiple])').selectpicker({
    style: "btn btn-info btn-sm",
    dropupAuto: false,
    size: 7,
    with: "50%",
    showTick: true
});

$(document).ready(function() {
    $('ul#plugin_tree').addClass('hummingbird-base').parent().addClass('hummingbird-treeview');
    $('ul#plugin_tree').hummingbird();
});