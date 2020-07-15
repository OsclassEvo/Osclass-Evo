<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.'); ?>
            </div>
        </div>
        <?php osc_run_hook('admin_content_footer'); ?>
    </div>
</div>

<div class="fixed-plugin">
    <div class="dropdown show-dropdown">
        <a href="javascript:;" data-toggle="dropdown">
            <i class="fa fa-cog fa-2x"> </i>
        </a>
        <ul class="dropdown-menu">
            <li class="header-title"> Sidebar Filters</li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger active-color">
                    <?php $filter_color = osc_get_preference('sidebar_filters', 'osclass'); ?>
                    <div class="badge-colors ml-auto mr-auto">
                        <span class="badge filter badge-purple <?php if($filter_color == 'purple') echo 'active'; ?>" data-color="purple"></span>
                        <span class="badge filter badge-azure <?php if($filter_color == 'azure') echo 'active'; ?>" data-color="azure"></span>
                        <span class="badge filter badge-green <?php if($filter_color == 'green') echo 'active'; ?>" data-color="green"></span>
                        <span class="badge filter badge-warning <?php if($filter_color == 'orange') echo 'active'; ?>" data-color="orange"></span>
                        <span class="badge filter badge-danger <?php if($filter_color == 'danger') echo 'active'; ?>" data-color="danger"></span>
                        <span class="badge filter badge-rose  <?php if($filter_color == 'rose' || !$filter_color) echo 'active'; ?>" data-color="rose"></span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="header-title">Sidebar Background</li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <?php $bg_color = osc_get_preference('sidebar_background', 'osclass'); ?>
                    <div class="ml-auto mr-auto">
                        <span class="badge filter badge-black <?php if($bg_color == 'black' || !$bg_color) echo 'active'; ?>" data-background-color="black"></span>
                        <span class="badge filter badge-white <?php if($bg_color == 'white') echo 'active'; ?>" data-background-color="white"></span>
                        <span class="badge filter badge-red <?php if($bg_color == 'red') echo 'active'; ?>" data-background-color="red"></span>
                        <span class="badge filter badge-purple <?php if($bg_color == 'purple') echo 'active'; ?>" data-background-color="purple"></span>
                        <span class="badge filter badge-azure <?php if($bg_color == 'azure') echo 'active'; ?>" data-background-color="azure"></span>
                        <span class="badge filter badge-green <?php if($bg_color == 'green') echo 'active'; ?>" data-background-color="green"></span>
                        <span class="badge filter badge-warning <?php if($bg_color == 'orange') echo 'active'; ?>" data-background-color="orange"></span>
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
            <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger">
                    <p>Sidebar Images</p>
                    <label class="switch-mini ml-auto">
                        <div class="togglebutton switch-sidebar-image">
                            <label>
                                <input type="checkbox" value="1" <?php if(osc_get_preference('sidebar_image_show', 'osclass')) echo 'checked'; ?>>
                                <span class="toggle"></span>
                            </label>
                        </div>
                    </label>
                    <div class="clearfix"></div>
                </a>
            </li>

            <li class="header-title">Images</li>
            <?php $image = osc_get_preference('sidebar_image', 'osclass'); ?>
            <li id="sb-images" <?php if($image == osc_current_admin_theme_url('img/sidebar-1.jpg') || !$image): ?>class="active"<?php endif; ?>>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="<?php echo osc_current_admin_theme_url('img/'); ?>sidebar-1.jpg" alt="">
                </a>
            </li>
            <li id="sb-images" <?php if($image == osc_current_admin_theme_url('img/sidebar-2.jpg')): ?>class="active"<?php endif; ?>>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="<?php echo osc_current_admin_theme_url('img/'); ?>sidebar-2.jpg" alt="">
                </a>
            </li>
            <li id="sb-images" <?php if($image == osc_current_admin_theme_url('img/sidebar-3.jpg')): ?>class="active"<?php endif; ?>>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="<?php echo osc_current_admin_theme_url('img/'); ?>sidebar-3.jpg" alt="">
                </a>
            </li>
            <li id="sb-images" <?php if($image == osc_current_admin_theme_url('img/sidebar-4.jpg')): ?>class="active"<?php endif; ?>>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                    <img src="<?php echo osc_current_admin_theme_url('img/'); ?>sidebar-4.jpg" alt="">
                </a>
            </li>

            <li class="button-container">
                <a href="https://osclass.market/products/themes" target="_blank" class="btn btn-rose btn-block btn-fill">
                    <?php _e('Osclass Themes'); ?>
                </a>
                <a href="https://osclass.market/products/plugins" target="_blank" class="btn btn-info btn-block">
                    <?php _e('Osclass Plugins'); ?>
                </a>
                <a href="https://osclass.market/" target="_blank" class="btn btn-success btn-block">
                    <?php _e('Want to sell your products?'); ?>
                </a>
                <a href="https://forum.osclass-evo.com/forums/suggestions/" target="_blank" class="btn btn-warning btn-block">
                    <?php _e('Suggestions'); ?>
                </a>
                <a href="https://forum.osclass-evo.com/forums/bugs/" target="_blank" class="btn btn-dark btn-block">
                    <?php _e('Bugs Report'); ?>
                </a>
            </li>
        </ul>
    </div>
</div>

<input id="msg-success" type="hidden" value="<?php _e('Settings updated'); ?>" />
<input id="msg-no-data" type="hidden" value="<?php _e('No data available in table'); ?>" />
<input id="msg-confirm-delete-listing" type="hidden" value="<?php _e('Are you sure you want to delete this listing?'); ?>" />
<input id="msg-confirm-delete-user" type="hidden" value="<?php _e('Are you sure you want to delete this user?'); ?>" />
<input id="msg-confirm-delete-admin" type="hidden" value="<?php _e('Are you sure you want to delete this admin?'); ?>" />
<input id="msg-confirm-delete-media" type="hidden" value="<?php _e('Are you sure you want to delete this image?'); ?>" />
<input id="msg-confirm-delete-comment" type="hidden" value="<?php _e('Are you sure you want to delete this comment?'); ?>" />
<input id="msg-confirm-delete-theme" type="hidden" value="<?php _e('Are you sure you want to delete this theme?'); ?>" />
<input id="msg-confirm-delete-widget" type="hidden" value="<?php _e('Are you sure you want to delete this widget?'); ?>" />
<input id="msg-confirm-delete-rules" type="hidden" value="<?php _e('Are you sure you want to delete this rule?'); ?>" />
<input id="msg-confirm-delete-pages" type="hidden" value="<?php _e('Are you sure you want to delete this page?'); ?>" />
<input id="msg-confirm-delete-language" type="hidden" value="<?php _e('Are you sure you want to delete this language?'); ?>" />
<input id="msg-confirm-delete-currency" type="hidden" value="<?php _e('Are you sure you want to delete this currency?'); ?>" />
<input id="msg-confirm-delete-delete_country" type="hidden" value="<?php _e('Are you sure you want to delete this country?'); ?>" />
<input id="msg-confirm-delete-delete_region" type="hidden" value="<?php _e('Are you sure you want to delete this region?'); ?>" />
<input id="msg-confirm-delete-delete_city" type="hidden" value="<?php _e('Are you sure you want to delete this city?'); ?>" />
<input id="msg-confirm-delete-plugin" type="hidden" value="<?php _e('Are you sure you want to delete this plugin?'); ?>" />
<input id="msg-confirm-delete-category" type="hidden" value="<?php _e('This will also delete the listings under that category. This action cannot be undone. Are you sure you want to delete this category?'); ?>" />
<input id="msg-delete-btn" type="hidden" value="<?php _e('Delete'); ?>" />
<input id="msg-cancel-btn" type="hidden" value="<?php _e('Cancel'); ?>" />
<input id="msg-confirm-delete-field" type="hidden" value="<?php _e('Are you sure you want to delete this custom field?'); ?>" />
<input id="msg-confirm-action" type="hidden" value="<?php _e('Confirm action'); ?>" />
<input id="msg-deleted-successfull" type="hidden" value="<?php _e('Deleted successfully'); ?>" />
<input id="msg-stats-successfull" type="hidden" value="<?php _e('Сompleted successfully'); ?>" />
<input id="msg-autocomplete-nothing-found" type="hidden" value="<?php _e('No results'); ?>" />
<input id="msg-bulk-action-not-selected" type="hidden" value="<?php _e('Bulk action not selected!'); ?>" />
<input id="msg-listings-not-selected" type="hidden" value="<?php _e('At least one Listing must be selected!'); ?>" />
<input id="msg-users-not-selected" type="hidden" value="<?php _e('At least one User must be selected!'); ?>" />
<input id="msg-admins-not-selected" type="hidden" value="<?php _e('At least one Admin must be selected!'); ?>" />
<input id="msg-media-not-selected" type="hidden" value="<?php _e('At least one Media must be selected!'); ?>" />
<input id="msg-comments-not-selected" type="hidden" value="<?php _e('At least one Comment must be selected!'); ?>" />
<input id="msg-rules-not-selected" type="hidden" value="<?php _e('At least one Rule must be selected!'); ?>" />
<input id="msg-pages-not-selected" type="hidden" value="<?php _e('At least one Page must be selected!'); ?>" />
<input id="msg-languages-not-selected" type="hidden" value="<?php _e('At least one Language must be selected!'); ?>" />
<input id="msg-currencies-not-selected" type="hidden" value="<?php _e('At least one Currency must be selected!'); ?>" />
<input id="url-ajax" type="hidden" value="<?php echo osc_admin_base_url(true); ?>?page=ajax&action=runhook&hook=" />
<input id="url-admin-base-ajax" type="hidden" value="<?php echo osc_admin_base_url(true); ?>?page=ajax&action=" />
<input id="url-base-ajax" type="hidden" value="<?php echo osc_base_url(true); ?>?page=ajax&action=" />

<script src="<?php echo osc_current_admin_theme_js_url('dashboard.js?v=' . time()); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/zebra_dialog@latest/dist/zebra_dialog.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_dialog@latest/dist/css/flat/zebra_dialog.min.css">

<?php if(!getPreference('intro_msg')): ?>
    <?php
    osc_set_preference('intro_msg', 1, 'osclass', 'BOOLEAN');
    osc_set_preference('osclass_evo_installed', date('Y-m-d H:i:s'));
    ?>
    <div id="intro">
        <h3 class="mt-0"><?php _e('Thanks for installing the Osclass Evolution!'); ?></h3>
        <p class="mb-1"><?php _e('From today, the Osclass is moving to a whole new level, and we sincerely hope that our work has been done not in vain!'); ?></p>
        <p class="mb-1"><?php _e('We will make every effort to make this CMS better and better every day, but without your help it will be very difficult to do.'); ?></p>
        <p class="mb-1"><?php printf(__('Therefore, please report all bugs you found to us on our forum: %s'), '<a href="https://forum.osclass-evo.com/forums/bugs/" target="_blank">' . __('Bugs Report') . '</a>'); ?></p>
        <p class="mb-1"><?php printf(__('If you want to see new features here, please write to us on this page: %s'), '<a href="https://forum.osclass-evo.com/forums/suggestions/" target="_blank">' . __('Suggestions') . '</a>'); ?></p>
        <p class="mb-1"><?php printf(__('For all other questions, we will be glad to see you on our forum: %s or write to us by E-mail: %s'), '<a href="https://forum.osclass-evo.com/forums/" target="_blank">' . __('Forum') . '</a>', '<a href="mailto:support@osclass-evo.com" target="_blank">' . __('support@osclass-evo.com') . '</a>'); ?></p>
        <p class="mb-1"><?php _e('If you liked our work, buy us some beer - we will be very grateful:'); ?> <a id="ninja" href="javascript:;"><?php _e('Donate to us'); ?></a> :)</p>
        <hr class="mb-2">
        <p class="mb-1"><?php printf(__('Cheers, %s The %s team'), '<br>', '<a href="https://osclass-evo.com/" target="_blank">' . __('Osclass Evolution') . '</a>'); ?></p>
    </div>

    <script>
        new $.Zebra_Dialog({
            backdrop_close: false,
            buttons: false,
            type: false,
            width: 1000,
            source: {inline: $('#intro')}
        });
    </script>
<?php endif; ?>

<?php if(getPreference('osclass_evo_installed')): ?>
    <?php
    $date_installed = new DateTime(getPreference('osclass_evo_installed'));
    $cur_date = new DateTime(date('Y-m-d H:i:s'));
    $interval = $date_installed->diff($cur_date);
    ?>

    <?php if($interval->format('%a') >= 30 && !getPreference('rate_us_msg')): ?>
        <?php osc_set_preference('rate_us_msg', 1, 'osclass', 'BOOLEAN'); ?>

        <div id="rate_us">
            <h3 class="mt-0"><?php _e('You have been using Osclass Evolution for a month now!'); ?></h3>
            <p class="mb-1"><?php _e('We noticed that you have been using the Osclass Evolution for a month now.'); ?></p>
            <p class="mb-1"><?php _e('We hope that during this time you were able to draw some conclusions on this script.'); ?></p>
            <p class="mb-1"><?php printf(__('Please leave your feedback and rate the Osclass Evolution on this page: %s'), '<a href="https://forum.osclass-evo.com/forums/feedback/" target="_blank">' . __('Post Feedback') . '</a>'); ?></p>
            <p class="mb-3"><?php _e('This will help us make the Oclass Evolution even better!'); ?></p>
            <p class="mb-1"><?php _e('And do not forget that:'); ?></p>
            <p class="mb-1"><?php printf(__('If you find a bug, let us know about it in our forum: %s'), '<a href="https://forum.osclass-evo.com/forums/bugs/" target="_blank">' . __('Bugs Report') . '</a>'); ?></p>
            <p class="mb-1"><?php printf(__('If you want to see new features here, please write to us on this page: %s'), '<a href="https://forum.osclass-evo.com/forums/suggestions/" target="_blank">' . __('Suggestions') . '</a>'); ?></p>
            <p class="mb-1"><?php printf(__('For all other questions, we will be glad to see you on our forum: %s or write to us by E-mail: %s'), '<a href="https://forum.osclass-evo.com/forums/" target="_blank">' . __('Forum') . '</a>', '<a href="mailto:support@osclass-evo.com" target="_blank">' . __('support@osclass-evo.com') . '</a>'); ?></p>
            <p class="mb-1"><?php _e('If you liked our work, buy us some beer - we will be very grateful:'); ?> <a id="ninja" href="javascript:;"><?php _e('Donate to us'); ?></a> :)</p>
            <hr class="mb-2">
            <p class="mb-1"><?php printf(__('Cheers, %s The %s team'), '<br>', '<a href="https://osclass-evo.com/" target="_blank">' . __('Osclass Evolution') . '</a>'); ?></p>
        </div>

        <script>
            new $.Zebra_Dialog({
                backdrop_close: false,
                buttons: false,
                type: false,
                width: 1000,
                source: {inline: $('#rate_us')}
            });
        </script>
    <?php endif; ?>
<?php endif; ?>

<script>
$(document).ready(function() {
    $sidebar = $('.sidebar');

    $sidebar_img_container = $sidebar.find('.sidebar-background');

    $full_page = $('.full-page');

    $sidebar_responsive = $('body > .navbar-collapse');

    window_width = $(window).width();

    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

    if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
        if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
        }
    }

    $('.fixed-plugin a').click(function(event) {
        if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
                event.stopPropagation();
            } else if (window.event) {
                window.event.cancelBubble = true;
            }
        }
    });

    $('.fixed-plugin .active-color span').click(function() {
        $full_page_background = $('.full-page-background');

        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        var new_color = $(this).data('color');

        if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
        }

        if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
        }

        if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
        }

        $.ajax({
            url: $('#url-ajax').val() + 'sbfilters',
            data: {'color' : new_color}
        });

        $.notify({
            icon: "add_alert",
            message: $('#msg-success').val()
        },{
            type: 'success',
            newest_on_top: true,
            spacing: 30,
            delay: 500,
            timer: 1000,
        });
    });

    $('.fixed-plugin .background-color .badge').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');

        var new_color = $(this).data('background-color');
        var img_url = $('a.logo-normal img').attr('data-img-url');

        if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
        }

        if(new_color == 'white') {
            $('a.logo-normal img').attr('src', img_url + 'logo-dark.png');
        } else {
            $('a.logo-normal img').attr('src', img_url + 'logo-light.png');
        }

        $.ajax({
            url: $('#url-ajax').val() + 'sbbackground',
            data: {'color' : new_color}
        });

        $.notify({
            icon: "add_alert",
            message: $('#msg-success').val()
        },{
            type: 'success',
            newest_on_top: true,
            spacing: 30,
            delay: 500,
            timer: 1000,
        });
    });

    $('.fixed-plugin .img-holder').click(function() {
      $full_page_background = $('.full-page-background');

      $(this).parent('li').siblings().removeClass('active');
      $(this).parent('li').addClass('active');


      var new_image = $(this).find("img").attr('src');

      if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        $sidebar_img_container.fadeOut('fast', function() {
          $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
          $sidebar_img_container.fadeIn('fast');
        });
      }

      if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

        $full_page_background.fadeOut('fast', function() {
          $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          $full_page_background.fadeIn('fast');
        });
      }

      if ($('.switch-sidebar-image input:checked').length == 0) {
        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
      }

      if ($sidebar_responsive.length != 0) {
        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
      }

      $.ajax({
            url: $('#url-ajax').val() + 'sbimage',
            data: {'img' : new_image}
        });

        $.notify({
            icon: "add_alert",
            message: $('#msg-success').val()
        },{
            type: 'success',
            newest_on_top: true,
            spacing: 30,
            delay: 500,
            timer: 1000,
        });
    });

    $('.switch-sidebar-image input').change(function() {
        $full_page_background = $('.full-page-background');

        $input = $(this);

        var img_status,
            img_active = $('li#sb-images.active img').attr('src');

        if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
                $sidebar_img_container.fadeIn('fast');
                $sidebar.attr('data-image', img_active);
            }

            if ($full_page_background.length != 0) {
                $full_page_background.fadeIn('fast');
                $full_page.attr('data-image', img_active);
            } else {
                $sidebar_img_container.css('background-image', 'url("' + img_active + '")');
                $sidebar_img_container.fadeIn('fast');
                $sidebar.attr('data-image', img_active);
            }

            img_status = 1;
            background_image = true;
        } else {
            if ($sidebar_img_container.length != 0) {
                $sidebar.removeAttr('data-image');
                $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
                $full_page.removeAttr('data-image');
                $full_page_background.fadeOut('fast');
            }

            img_status = 0;
            background_image = false;
        }

        $.ajax({
            url: $('#url-ajax').val() + 'sbimage_show',
            data: {'img_status' : img_status}
        });

        $.notify({
            icon: "add_alert",
            message: $('#msg-success').val()
        },{
            type: 'success',
            newest_on_top: true,
            spacing: 30,
            delay: 500,
            timer: 1000,
        });
    });
});
</script>

<?php osc_run_hook('admin_footer'); ?>

<?php if(osc_admin_pages_preloading()): ?>
    <div class="wait">
        <div class="preloader">
            <img src="<?php echo osc_current_admin_theme_url(); ?>img/page-preloader.gif?v=<?php echo time(); ?>" alt="loading">
        </div>
    </div>
<?php endif; ?>

</body>
</html>          