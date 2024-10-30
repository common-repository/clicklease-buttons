<?php
require_once(plugin_dir_path(__FILE__) . "../configurations/env_config.php");
$clBtnPageName = 'cl-buttons-settings-page';
// add script files
if (!function_exists('cl_add_scripts')) {
    function cl_add_scripts()
    {
        // add js files >> includes/public/js
        wp_enqueue_script('cl-btn-first-public-bundle', plugin_dir_url(__FILE__) . '../dist/public.bundle.js'); //public/js/api/ *** Its in public *** 
    }
}


if (!function_exists('cl_add_admin_scripts')) {
    function cl_add_admin_scripts($hook)
    {
        global $clBtnPageName;
        if ($hook !== null && strval(strpos($hook, $clBtnPageName))) {
            // SCRIPTS
            // error_log(print_r('SCRIPTS: ' . plugins_url() . '/' . basename(plugin_dir_path(dirname(__FILE__, 1))), true));
            //>> includes/admin/js 
            wp_enqueue_script('cl-btn-first-bundle', plugin_dir_url(__FILE__) . '../dist/admin.bundle.js'); //public/js/api/ *** Its in public *** 
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
            const CLS_PLUGIN_URL = "' . plugins_url() . '/' . basename(plugin_dir_path(dirname(__FILE__, 1))) . '"; 
            const CL_SVGS_URL = "' . plugins_url() . '/' . basename(plugin_dir_path(dirname(__FILE__, 1))) . '/src/assets/svg/";
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
