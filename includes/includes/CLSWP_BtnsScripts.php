<?php
$clBtnPageName = 'cl-buttons-settings-page';
// add script files

if (!function_exists('cl_add_scripts')) {
    function cl_add_scripts()
    {
        // add js files >> includes/public/js
        wp_enqueue_script('cl-btn-util', plugin_dir_url(__FILE__) . '../src/public/js/buttons/util.js'); //public/js/buttons
        wp_enqueue_script('cl-btn-script', plugin_dir_url(__FILE__) . '../src/public/js/buttons/ClButton.js'); //public/js/buttons
        wp_enqueue_script('cl-btn-api-handlr', plugin_dir_url(__FILE__) . '../src/public/js/api/ClButtonsApiHandler.js'); //public/js/api/
        wp_enqueue_script('cl-btn-style-script', plugin_dir_url(__FILE__) . '../src/public/js/buttons/ClButtonStyle.js'); //public/js/buttons
        wp_enqueue_script('cl-main-script', plugin_dir_url(__FILE__) . '../src/public/js/main.js'); //includes/public/js/main.js
        // add css files >> includes/public/css
        wp_enqueue_style('cl-btn-svgs-style', plugin_dir_url(__FILE__) . '../src/public/css/cl-btn-svgs.css'); //includes/public/css/components
        wp_enqueue_style('cl-main-style', plugin_dir_url(__FILE__) . '../src/public/css/styles.css'); //includes/public/css/
        wp_enqueue_style('cl-font-style', plugin_dir_url(__FILE__) . '../src/admin/css/custom-font.css'); //includes/admin/css/
    }
}


if (!function_exists('cl_add_admin_scripts')) {
    function cl_add_admin_scripts($hook)
    {
        global $clBtnPageName;
        if ($hook !== null && strval(strpos($hook, $clBtnPageName))) {
            // wp_register_script('cl-popper', 'https://unpkg.com/@popperjs/core@2', [], '2');
            // wp_register_script('cl-lib-tooltip', 'https://unpkg.com/tippy.js@6', ['cl-popper'], '6');
            // SCRIPTS
            // error_log(print_r('SCRIPTS: ' . $hook . ' -> ' . 'random-button-settings-page', true));
            //>> LIBS
            wp_register_script('cl-popper-script', plugin_dir_url(__FILE__) . '../src/libs/popper/dist/umd/popper.min.js', [], '2');
            wp_register_script('cl-lib-tooltip-script', plugin_dir_url(__FILE__) . '../src/libs/tippy/dist/tippy-bundle.umd.min.js', ['cl-popper-script'], '6');
            //>> includes/admin/js 
            wp_enqueue_script('cl-btn-api-handlr', plugin_dir_url(__FILE__) . '../src/public/js/api/ClButtonsApiHandler.js'); //public/js/api/ *** Its in public *** 
            wp_enqueue_script('cl-btn-types-script', plugin_dir_url(__FILE__) . '../src/public/js/buttons/util.js'); //public/js/buttons *** Its in public ***
            wp_enqueue_script('cl-spinner-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/UI/ClSpinner/ClSpinner.js');
            wp_enqueue_script('cl-btn-util', plugin_dir_url(__FILE__) . '../src/public/js/buttons/ClButton.js'); //public/js/buttons *** Its in public ***  
            wp_enqueue_script('cl-btn-app', plugin_dir_url(__FILE__) . '../src/admin/js/components/ClAppButton/ClAppButton.js'); ///includes/admin/js/components/ClAppButton/
            wp_enqueue_script('cl-btn-style-script', plugin_dir_url(__FILE__) . '../src/public/js/buttons/ClButtonStyle.js'); //public/js/buttons *** Its in public ***
            wp_enqueue_script('cl-btn-list-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/BtnsStyleList/btnsStyleList.js'); //includes/admin/js/components/BtnsStyleList/
            wp_enqueue_script('cl-btn-card-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/BtnsStyleList/btnCard.js'); //includes/admin/js/components/BtnsStyleList/
            wp_enqueue_script('cl-select-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/Select/ClSelect.js'); //includes/admin/js/components/Select/
            wp_enqueue_script('cl-card-list-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/ClRadioFdl/ClRadioFdl.js'); //includes/admin/js/components/ClRadioFdl/
            wp_enqueue_script('cl-backdrop-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/backdrop.js'); //includes/admin/js/components/
            wp_enqueue_script('cl-modal-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/modal.js'); //includes/admin/js/components/
            wp_enqueue_script('cl-btn-setings-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/ButtonsViewer/ClButtonSettings.js'); //includes/admin/js/components/ButtonsViewer/
            wp_enqueue_script('cl-viewer-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/ButtonsViewer/ButtonsViewer.js', ['cl-lib-tooltip-script']); //includes/admin/js/components/ButtonsViewer/
            wp_enqueue_script('cl-eles-viewer-script', plugin_dir_url(__FILE__) . '../src/admin/js/components/ElementsViewer/ElementsViewer.js'); //includes/admin/js/components/ElementsViewer/
            wp_enqueue_script('cl-main-admin-script', plugin_dir_url(__FILE__) . '../src/admin/js/main-admin.js'); //includes/admin/js/
            // STYLES
            //>> includes/admin/css
            wp_enqueue_style('cl-btn-svgs-style',  plugin_dir_url(__FILE__) . '../src/public/css/cl-btn-svgs.css'); //includes/public/css/components/ *** Its in public ***
            wp_enqueue_style('cl-font-style', plugin_dir_url(__FILE__) . '../src/admin/css/custom-font.css'); //includes/admin/css/
            wp_enqueue_style('cl-eles-list-style', plugin_dir_url(__FILE__) . '../src/admin/js/components/BtnsStyleList/ClBtnsList.css');
            wp_enqueue_style('cl-spinner-style', plugin_dir_url(__FILE__) . '../src/admin/js/components/UI/ClSpinner/ClSpinner.css');
            wp_enqueue_style('cl-viewer-style', plugin_dir_url(__FILE__) . '../src/admin/css/components/ButtonsViewer/ButtonsViewer.css'); //includes/admin/css/components/ButtonsViewer/
            wp_enqueue_style('cl-app-btn-css', plugin_dir_url(__FILE__) . '../src/admin/css/components/ClAppButton/ClAppButton.css'); //includes/css/components/ClAppButton/
            wp_enqueue_style('cl-components-style', plugin_dir_url(__FILE__) . '../src/admin/css/components/components.css'); //includes/admin/css/components/
            wp_enqueue_style('cl-main-admin-style', plugin_dir_url(__FILE__) . '../src/admin/css/styles-admin.css'); //includes/admin/css/admin/
            wp_enqueue_style('cl-ele-radio', plugin_dir_url(__FILE__) . '../src/admin/css/components/ClRadioFdl/ClRadioFdl.css'); //includes/admin/css/components/ClRadioFdl
        }
    }
}


if (!function_exists('clAddCustomAdminCode')) {
    function clAddCustomAdminCode()
    {
        if (!is_admin()) {
            return;
        }
        echo wp_kses('
            <script>
                const CL_SVGS_URL = "' . plugins_url() . '/' . basename(plugin_dir_path(dirname(__FILE__, 1))) . '/src/assets/svg/";
                console.log(CL_SVGS_URL);
            </script>
        ', [
            'script' => []
        ]);
    }
}

add_action('wp_enqueue_scripts', 'cl_add_scripts');
add_action('admin_enqueue_scripts', 'cl_add_admin_scripts');
// add_action('wp_footer', 'clAddCustomAdminCode', 100);
add_action('admin_footer', 'clAddCustomAdminCode', 100);
