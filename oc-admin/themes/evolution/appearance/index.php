<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function customPageTitle($string) {
    return sprintf(__('Appearance &raquo; %s'), $string);
}

function addHelp() {
    echo '<p>' . __('Change your site\'s look and feel by activating a theme among those available. You can download new themes from the <a href="%s">market</a>. <strong>Be careful</strong>: if your theme has been customized, you\'ll lose all changes if you change to a new theme.') . '</p>';
}

function customPageHeader() {
    _e('Appearance');
}

//customize Head
function customHead() {}

osc_add_filter('admin_title', 'customPageTitle');
osc_add_hook('help_box','addHelp');
osc_add_hook('admin_page_header','customPageHeader');
osc_add_hook('admin_header','customHead', 10);

/* Header Menu */
$header_menu  = '<a id="help" href="javascript:;" class="btn btn-info btn-fab"><i class="material-icons md-24">error_outline</i></a>';
$header_menu .= '<a id="add-field" href="' . osc_admin_base_url(true) . '?page=appearance&action=add" class="btn btn-success"><i class="material-icons md-18">add</i> ' . __('Add theme') . '</a>';

$themes = __get("themes");
$info   = WebThemes::newInstance()->loadThemeInfo(osc_theme());
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>

<div class="row no-gutters">
    <div class="col-md-12 text-center text-sm-right"><?php echo $header_menu; ?></div>
</div>

<div class="card">
    <div class="card-header card-header-rose card-header-icon">
        <div class="card-icon">
            <i class="material-icons">perm_media</i>
        </div>
        <h4 class="card-title"><?php _e('Current theme'); ?></h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card card-product">
                    <div class="card-header card-header-image" data-header-animation="false">
                        <a href="javascript:;">
                            <img class="img" src="<?php echo osc_base_url(); ?>/oc-content/themes/<?php echo osc_theme(); ?>/screenshot.png" title="<?php echo $info['name']; ?>" alt="<?php echo $info['name']; ?>">
                        </a>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="javascript:;"><?php echo $info['name']; ?></a>
                        </h4>
                        <div class="card-description">
                            <?php echo $info['description']; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="price">
                            <h4><?php _e('Version'); ?>: <?php echo $info['version']; ?></h4>
                        </div>
                        <div class="stats">
                            <p class="card-category"><i class="material-icons">person</i> <?php _e('by'); ?> <a target="_blank" href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(count($themes) > 1): ?>
    <?php
    $aThemesToUpdate = json_decode( osc_get_preference('themes_to_update') );
    $bThemesToUpdate = (is_array($aThemesToUpdate)) ? true : false;
    $csrf_token = osc_csrf_token_url();
    ?>

    <h3><?php _e('Available themes'); ?></h3>

    <div class="row themes-list">
        <?php foreach($themes as $theme): ?>
            <?php
            if( $theme == osc_theme() ) {
                continue;
            }

            $info = WebThemes::newInstance()->loadThemeInfo($theme);
            ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card card-product">
                    <div class="card-header card-header-image" data-header-animation="true">
                        <a href="#pablo">
                            <img class="img" src="<?php echo osc_base_url(); ?>/oc-content/themes/<?php echo $theme; ?>/screenshot.png" title="<?php echo $info['name']; ?>" alt="<?php echo $info['name']; ?>">
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="card-actions text-center">
                            <button onclick="location.href = '<?php echo osc_admin_base_url(true); ?>?page=appearance&action=activate&theme=<?php echo $theme; ?>&<?php echo $csrf_token; ?>'" type="button" class="btn btn-success btn-link" rel="tooltip" data-placement="bottom" title="<?php _e('Activate'); ?>">
                                <i class="material-icons">done</i>
                            </button>

                            <button onclick="location.href = '<?php echo osc_base_url(true); ?>?theme=<?php echo $theme; ?>'" type="button" class="btn btn-info btn-link" rel="tooltip" data-placement="bottom" title="<?php _e('Preview'); ?>">
                                <i class="material-icons">visibility</i>
                            </button>

                            <button id="theme-delete" data-theme-id="<?php echo $theme; ?>" type="button" class="btn btn-danger btn-link" rel="tooltip" data-placement="bottom" title="<?php _e('Delete'); ?>">
                                <i class="material-icons">close</i>
                            </button>

                            <?php if($bThemesToUpdate && in_array($theme, $aThemesToUpdate)): ?>
                                <button onclick="location.href = '#<?php echo htmlentities(@$info['theme_update_uri']); ?>'" type="button" class="btn btn-danger btn-link" rel="tooltip" data-placement="bottom" title="<?php _e('Update'); ?>">
                                    <i class="material-icons">get_app</i>
                                </button>
                            <?php endif; ?>
                        </div>

                        <h4 class="card-title">
                            <a href="javascript:;"><?php echo $info['name']; ?></a>
                        </h4>
                        <div class="card-description">
                            <?php echo $info['description']; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="price">
                            <h4><?php _e('Version'); ?>: <?php echo $info['version']; ?></h4>
                        </div>
                        <div class="stats">
                            <p class="card-category"><i class="material-icons">person</i> <?php _e('by'); ?> <a target="_blank" href="<?php echo $info['author_url']; ?>"><?php echo $info['author_name']; ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form id="theme-delete-form" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide">
    <input type="hidden" name="page" value="appearance" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="webtheme" value="" />
</form>

<div id="market_installer" class="has-form-actions d-none">
    <form action="" method="post">
        <input type="hidden" name="market_code" id="market_code" value="" />
        <div class="osc-modal-content-market">
            <img src="" id="market_thumb" class="float-left"/>
            <table class="table" cellpadding="0" cellspacing="0">
                <tbody>
                <tr class="table-first-row">
                    <td><?php _e('Name'); ?></td>
                    <td><span id="market_name"><?php _e("Loading data"); ?></span></td>
                </tr>
                <tr class="even">
                    <td><?php _e('Version'); ?></td>
                    <td><span id="market_version"><?php _e("Loading data"); ?></span></td>
                </tr>
                <tr>
                    <td><?php _e('Author'); ?></td>
                    <td><span id="market_author"><?php _e("Loading data"); ?></span></td>
                </tr>
                <tr class="even">
                    <td><?php _e('URL'); ?></td>
                    <td><span id="market_url_span"><a id="market_url" href="#"><?php _e("Download manually"); ?></a></span></td>
                </tr>
                </tbody>
            </table>
            <div class="clear"></div>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <button id="market_cancel" class="btn btn-red" ><?php _e('Cancel'); ?></button>
                <button id="market_install" class="btn btn-submit" ><?php _e('Continue install'); ?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    //$(function() {
    //    $("#market_install").on("click", function() {
    //        $(".ui-dialog-content").dialog("close");
    //        $('<div id="downloading"><div class="osc-modal-content"><?php //echo osc_esc_js(__('Please wait until the download is completed')); ?>//</div></div>').dialog({title:'<?php //echo osc_esc_js(__('Downloading')); ?>//...',modal:true});
    //        $.getJSON(
    //            "<?php //echo osc_admin_base_url(true); ?>//?page=ajax&action=market&<?php //echo osc_csrf_token_url(); ?>//",
    //            {"code" : $("#market_code").attr("value"), "section" : 'themes'},
    //            function(data){
    //                var content = data.message;
    //                if(data.error == 0) { // no errors
    //                    content += '<h3><?php //echo osc_esc_js(__('The theme has been downloaded correctly, proceed to activate or preview it.')); ?>//</h3>';
    //                    content += "<p>";
    //                    content += '<a class="btn btn-mini btn-green" href="<?php //echo osc_admin_base_url(true); ?>//?page=appearance&marketError='+data.error+'&slug='+oscEscapeHTML(data.data['s_update_url'])+'"><?php //echo osc_esc_js(__('Ok')); ?>//</a>';
    //                    content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php //echo osc_esc_js(__('Close')); ?>//</a>';
    //                    content += "</p>";
    //                } else {
    //                    content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php //echo osc_esc_js(__('Close')); ?>//</a>';
    //                }
    //                $("#downloading .osc-modal-content").html(content);
    //            });
    //        return false;
    //    });
    //});
    //
    //$('.market-popup').on('click',function() {
    //    $.getJSON(
    //        "<?php //echo osc_admin_base_url(true); ?>//?page=ajax&action=check_market",
    //        {"code" : $(this).attr('href').replace('#',''), 'section' : 'themes'},
    //        function(data){
    //            if(data!=null) {
    //                $("#market_thumb").attr('src', data.s_thumbnail);
    //                $("#market_code").attr("value", data.s_update_url);
    //                $("#market_name").text(data.s_title);
    //                $("#market_version").text(data.s_version);
    //                $("#market_author").text(data.s_contact_name);
    //                $("#market_url").attr('href',data.s_source_file);
    //
    //                $('#market_install').text("<?php //echo osc_esc_js( __('Update') ); ?>//");
    //
    //                $('#market_installer').dialog({
    //                    modal:true,
    //                    title: '<?php //echo osc_esc_js( __('Osclass Market') ); ?>//',
    //                    width:485
    //                });
    //            }
    //        }
    //    );
    //
    //    return false;
    //});
</script>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>