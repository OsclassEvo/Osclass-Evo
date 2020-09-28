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
    $('#plugin_tree').addClass('hummingbird-base').parent().addClass('hummingbird-treeview');
    $('#plugin_tree, #category_tree').hummingbird();

    $("a#categories-check_all").click(function () {
        $("#category_tree").hummingbird("checkAll");
    });

    $("a#categories-uncheck_all").click(function () {
        $("#category_tree").hummingbird("uncheckAll");
    });
});