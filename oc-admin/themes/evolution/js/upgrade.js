var oscUpgrade = {
    el: function(a) {
        return $('#' + a);
    },
    fixTime: function(time) {
        if (time < 10) {
            time = '0' + time;
        }

        return time;
    },
    fixMonth: function(month) {
        month = month + 1;

        return oscUpgrade.fixTime(month);
    },
    strDate: function(date = new Date()) {
        return date.getDate()
            + '.' + oscUpgrade.fixMonth(date.getMonth())
            + '.' + date.getFullYear()
            + ' ' + oscUpgrade.fixTime(date.getHours())
            + ':' + oscUpgrade.fixTime(date.getMinutes())
            + ':' + oscUpgrade.fixTime(date.getSeconds());
    },
    preloader: {
        show: function(str) {
            return '<div id="preloading-block">' + str + '<div id="preloading-dots"><div id="preloading-dot_1" class="preloading-dot"></div><div id="preloading-dot_2" class="preloading-dot"></div><div id="preloading-dot_3" class="preloading-dot"></div></div></div>';
        },
        hide: function(str) {
            oscUpgrade.el('preloading-block').html(str).attr('id', 'preloading-completed');
        }
    },
    upgrade: {
        download: function(data) {
            oscUpgrade.preloader.hide(upgrade_translation.download_completed + ' ' + oscUpgrade.formatSize(data.download_content_length, 2));

            setTimeout(function() {
                oscUpgrade.upgrade.extract(data);
            }, 1500);
        },
        extract: function(data) {
            var table = oscUpgrade.el('upgrade-processing-block table');

            $.ajax({
                type: 'POST',
                url: osc.adm_base_ajax_url + 'core-upgrade-extract',
                data: {file: data.file},
                beforeSend: function() {
                    table.append(oscUpgrade.log.row(oscUpgrade.preloader.show(upgrade_translation.unpacking), oscUpgrade.strDate()));
                },
                success: function(res) {
                    var json = JSON.parse(res);

                    if(json.status == 'success') {
                        setTimeout(function() {
                            oscUpgrade.preloader.hide(upgrade_translation.unpacking_completed);
                        }, 3000);

                        setTimeout(function() {
                            oscUpgrade.upgrade.install(json.file);
                        }, 4500);
                    } else {
                        Swal.fire({
                            text: json.status,
                            type: 'error',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-info",
                            showCancelButton: false
                        });
                    }
                }
            });
        },
        install: function(file) {
            var table = oscUpgrade.el('upgrade-processing-block table');

            $.ajax({
                type: 'POST',
                url: osc.adm_base_ajax_url + 'core-upgrade-install',
                data: {file: file},
                beforeSend: function() {
                    table.append(oscUpgrade.log.row(oscUpgrade.preloader.show(upgrade_translation.installation), oscUpgrade.strDate()));
                },
                success: function(res) {
                    var json = JSON.parse(res);

                    if(json.status == 'success') {
                        setTimeout(function() {
                            oscUpgrade.preloader.hide(json.message);
                        }, 3000);

                        setTimeout(function() {
                            window.location = osc.adm_base_url + '?page=tools&action=version';
                        }, 4500);
                    } else {
                        Swal.fire({
                            text: json.message,
                            type: 'error',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-info",
                            showCancelButton: false
                        });
                    }
                }
            });
        }
    },
    log: {
        finished: function(type, records_str, records, file_size) {
            var finiish_date = oscUpgrade.strDate();
            var table = oscUpgrade.el('upgrade-processing-block table');
            var elem = document.getElementById('logs-block');

            row = oscUpgrade.log.row(upgrade_translation.task_completed, finiish_date);
            row += oscUpgrade.log.row(records_str + ': ' + records);
            row += oscUpgrade.log.row(upgrade_translation.file_size + ': ' + oscUpgrade.formatSize(file_size, 2));

            table.append(row);

            elem.scrollTop = elem.scrollHeight;

            oscUpgrade.el('upgrade-btn').text(upgrade_translation.next).attr('disabled', false).attr('upgrade-type', type);
        },
        processing: function(data, delay, total, log_str = '') {
            var start_date = oscUpgrade.strDate();
            var elem = document.getElementById('logs-block');
            var table = oscUpgrade.el('upgrade-processing-block table');

            oscUpgrade.progressBar.reset(total);
            oscUpgrade.log.clear();

            data.forEach(function(value, index) {
                setTimeout(function() {
                    var progress = (index + 1) / total * 100;
                    var row = oscUpgrade.log.row(log_str + ' ' + value, start_date);

                    table.append(row);
                    elem.scrollTop = elem.scrollHeight;

                    oscUpgrade.progressBar.processing(index + 1, progress, total);

                    if(total == index + 1) {
                        oscUpgrade.progressBar.finished();
                    }

                    start_date = '';
                }, delay * index);
            });
        },
        row: function( str = '', date = '') {
            return '<tr><td>' + date + '</td><td>' + str + '</td></tr>';
        },
        clear: function() {
            oscUpgrade.el('upgrade-processing-block table tbody tr')
                .remove();
        }
    },
    progressBar: {
        finished: function() {
            oscUpgrade.el('status-bar')
                .addClass('progress-bar-success')
                .text(upgrade_translation.completed);
        },
        reset: function(total) {
            oscUpgrade.el('progress-bar')
                .html('<div id="status-bar" class="progress-bar progress-bar-info progress-bar-animated progress-bar-striped font-weight-bold" role="progressbar" style="width: 0;"><span>' + upgrade_translation.progress + ': <span id="progress-current">0</span> ' + upgrade_translation._of + ' <span id="progress-total">' + total + '</span></span></div>');
        },
        hide: function() {
            oscUpgrade.el('progress-bar')
                .parent()
                .hide();
        },
        processing: function(index, progress, total) {
            oscUpgrade.el('progress-total')
                .text(total);
            oscUpgrade.el('status-bar')
                .css('width', progress + '%');
            oscUpgrade.el('progress-current')
                .text(index);
        }
    },
    formatSize: function (a, b) {
        var sizes = ['B', 'KB', 'MB', 'GB'];

        b = b || 0;
        if (0 <= a) {
            for (var d = 0; 999 < a && (a /= 1024);) d++;
            a = (0 < d ? a.toPrecision(3) : a) + " " + sizes[d];
            return 2 == b ? a : 1 == b ? " <span>[ " + a + " ]</span>" : "[ " + a + " ]"
        }
        return ""
    }
};