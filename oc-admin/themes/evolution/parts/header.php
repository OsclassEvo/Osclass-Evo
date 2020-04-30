<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.'); ?>
<!DOCTYPE html>
<html lang="<?php echo substr(osc_current_admin_locale(), 0, 2); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo osc_apply_filter('admin_title', osc_page_title() . ' - Osclass Evolution'); ?></title>
    <meta name="title" content="<?php echo osc_apply_filter('admin_title', osc_page_title() . ' - Osclass Evolution'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="<?php echo osc_current_admin_locale(); ?>" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <script type="text/javascript">
        var osc = window.osc || {};
<?php
    /* TODO: enqueue js lang strings */
    $lang = array(
        'nochange_expiration' => __('No change expiration'),
        'without_expiration' => __('Without expiration'),
        'expiration_day' => __('1 day'),
        'expiration_days' => __('%d days'),
        'select_category' => __('Select category'),
        'no_subcategory' => __('No subcategory'),
        'select_subcategory' => __('Select subcategory')
    );
    $locales = osc_get_locales();
    $codes   = array();
    foreach($locales as $locale) {
        $codes[] = '\''. osc_esc_js($locale['pk_c_code']) . '\'';
    }
?>
        osc.locales = {};
        osc.locales._default = '<?php echo osc_language(); ?>';
        osc.locales.current = '<?php echo osc_current_admin_locale(); ?>';
        osc.locales.codes   = new Array(<?php echo join(',', $codes); ?>);
        osc.locales.string  = '[name*="' + osc.locales.codes.join('"],[name*="') + '"],.' + osc.locales.codes.join(',.');
        osc.langs = <?php echo json_encode($lang); ?>;
    </script>

    <style>
        .main-panel.loading {
            background-color: #fff;
        }
        .main-panel.loading .navbar-wrapper,
        .main-panel.loading .card {
            opacity: 0 !important;
        }
        .wait {
            background-color: #fff;
            bottom: 0;
            left: 260px;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 2147483647;
        }
        .wait .preloader {
            background-position: center center;
            background-repeat: no-repeat;
            position: absolute;
            left: 0;
            right: 0;
            margin: 0 auto;
            top: 50%;
            text-align: center;
        }
        @media (max-width: 768px) {
            .wait {
                left: 0;
            }
        }
    </style>

    <?php osc_run_hook('admin_header'); ?>
</head>

<body class="<?php if(osc_get_preference('compact_mode', 'modern_admin_theme')) echo 'sidebar-mini'; ?>">

  <div id="help-box" class="fc-limited"><?php osc_run_hook('help_box'); ?></div>
  <div class="wrapper ">
    <div class="sidebar" data-color="<?php echo osc_get_preference('sidebar_filters', 'osclass') ? osc_get_preference('sidebar_filters', 'osclass') : 'rose'; ?>" data-background-color="<?php echo osc_get_preference('sidebar_background', 'osclass') ? osc_get_preference('sidebar_background', 'osclass') : 'black'; ?>" <?php if(osc_get_preference('sidebar_image_show', 'osclass')): ?>data-image="<?php echo osc_get_preference('sidebar_image', 'osclass') ? osc_get_preference('sidebar_image', 'osclass') : osc_current_admin_theme_url('img/sidebar-1.jpg'); ?>"<?php endif; ?>>
      <div class="logo">
        <a href="https://osclass-evo.com/" class="simple-text logo-mini" target="_blank">
          OE
        </a>
        <a href="https://osclass-evo.com/" class="simple-text logo-normal" target="_blank">
          <?php $logo = osc_get_preference('sidebar_background', 'osclass') == 'white' ? 'logo-dark.png' : 'logo-light.png'; ?>  
          <img data-img-url="<?php echo osc_current_admin_theme_url('img/'); ?>" src="<?php echo osc_current_admin_theme_url('img/' . $logo); ?>" />
        </a>
      </div>
      <div class="sidebar-wrapper">
        <?php osc_draw_admin_evolution_menu(); ?>
      </div>
      
      <div class="sidebar-background" <?php if(osc_get_preference('sidebar_image_show', 'osclass')): ?>style="background-image: url(<?php echo osc_get_preference('sidebar_image', 'osclass') ? osc_get_preference('sidebar_image', 'osclass') : osc_current_admin_theme_url('img/sidebar-1.jpg'); ?>);"<?php endif; ?>></div>
    </div>
    <div class="main-panel loading">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-minimize">
              <button id="minimizeSidebar" data-url="<?php echo osc_admin_base_url(true); ?>?page=ajax&action=runhook&hook=compactmode" class="btn btn-just-icon btn-white btn-fab btn-round">
                <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;"><?php osc_run_hook('admin_page_header'); ?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo osc_base_url(); ?>" target="_blank" title="<?php echo osc_page_title() . ' - ' . __('Home page'); ?>">
                  <i class="material-icons">home</i>
                  <p class="d-lg-none d-md-block">
                    <?php _e('Home page'); ?>
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification"><?php echo AdminToolbar::newInstance()->notificationsCount(); ?></span>
                  <p class="d-lg-none d-md-block">
                      <?php _e('Notifications'); ?>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <?php osc_draw_admin_toolbar(); ?>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="<?php echo osc_admin_base_url(true);  ?>?page=admins&action=edit"><?php _e('Profile'); ?></a>
                  <a class="dropdown-item" href="<?php echo osc_admin_base_url(true);  ?>?page=settings"><?php _e('Settings'); ?></a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo osc_admin_base_url(true);  ?>?action=logout" title="<?php _e('Logout'); ?>"><?php _e('Logout'); ?></a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div id="flash-message"><?php osc_show_flash_message('admin'); ?></div>