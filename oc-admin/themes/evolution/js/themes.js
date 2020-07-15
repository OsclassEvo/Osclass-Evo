$('select:not([multiple]').selectpicker({
    style: "btn btn-info btn-sm",
    dropupAuto: false,
    size: 7,
    with: "50%",
    showTick: true
});

$('br').remove();

$('input[type="text"], textarea').on("focus", function() {
    $(this).parent('.form-controls').addClass("input-group-focus");
}).on("blur", function() {
    $(this).parent(".form-controls").removeClass("input-group-focus");
});

$('input[type="file"]').each(function() {
    var translate = $('#msg-choose-file').val();

    if($(this).parents('table').length) {
        $(this).before('<span class="fileinput-new btn btn-round btn-success btn-file">' + translate + '</span>');
    } else {
        $(this).wrap('<span class="btn btn-round btn-success btn-file"></span>').before('<span class="fileinput-new">' + translate + '</span>');
    }
});

$('input[type="radio"]').addClass('form-check-input').parents('div.form-controls').addClass('form-check').find('div').append('<span class="circle"><span class="check"></span></span>').replaceWith(function(index, oldHTML){
    return $("<label class='form-check-label'>").html(oldHTML);
});

$('input[type="checkbox"]').addClass('form-check-input').parents('div.form-controls').addClass('form-check').find('div').append('<span class="form-check-sign"><span class="check"></span></span>').replaceWith(function(index, oldHTML){
    return $("<label class='form-check-label'>").html(oldHTML);
});

$('body').on('change', 'input[type="file"]', function() {
    var file = $(this).val();
    console.log(file);
    file = file.replace(/\\/g, '/').split('/').pop();

    $(this).siblings('.fileinput-new').text(file);
});