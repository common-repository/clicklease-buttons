<?php

require_once(plugin_dir_path(__FILE__) . '../../form/CLSWP_FormSection.php');
require_once(plugin_dir_path(__FILE__) . '../../form/validation/CLSWP_Validator.php');
require_once(plugin_dir_path(__FILE__) . '../../form/validation/rules/CLSWP_NotEmpty.php');
require_once(plugin_dir_path(__FILE__) . '../../form/validation/rules/CLSWP_ValidToken.php');
require_once(plugin_dir_path(__FILE__) . '../../form/validation/rules/CLSWP_MinLenRule.php');
require_once(plugin_dir_path(__FILE__) . '../../form/validation/rules/CLSWP_MaxLenRule.php');

if(! class_exists('CLSWP_MainSettignsPage')):

class CLSWP_MainSettignsPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'render']);
        add_action('admin_init', [$this, 'createSettings']);
    }

    /**
     * Renders the form and the fields which were registered once plugin settings page is initialized.
     * Also, it places where validations error are going to be shown and set props for save options button 
     */
    public function createHTML(): string
    {
?>
        <div class="cl-stt-wrap">
            <h1>Clicklease Buttons Settings</h1>
            <?php settings_errors() ?>
            <form class="cl-stt" action="options.php" method="POST">
                <?php
                do_settings_sections('clk-btns-settings-page');
                settings_fields('randbtn_opts');
                submit_button('Save Options');
                ?>
            </form>
        </div>
<?php
        return '';
    }

    /**
     * Register the main menu page on wordpress admin views and set its options
     */
    public function render()
    {
        $clIcon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MS4xIDM3LjYyIj4KICAgIDxnIGlkPSJHcm91cF8xMDkiIGRhdGEtbmFtZT0iR3JvdXAgMTA5IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtNDcuNjMxIC00Ni4xOCkiPgogICAgICA8ZyBpZD0iR3JvdXBfOTgiIGRhdGEtbmFtZT0iR3JvdXAgOTgiPgogICAgICAgIDxwYXRoIGlkPSJQYXRoXzkwNyIgZGF0YS1uYW1lPSJQYXRoIDkwNyIgZD0iTTczLjM3LDcwLjU5YTguOTEsOC45MSwwLDEsMSwwLTExLjE4bDkuMTktNC4xYTE4LjgxLDE4LjgxLDAsMSwwLDAsMTkuMzZaIiBmaWxsPSIjMjY5MmYxIi8+CiAgICAgICAgPHBhdGggaWQ9IlhNTElEXzI0OTgwXyIgZD0iTTg4LjczLDU2LjcxbC05LjMsNC4xNEw3MC4xMyw2NWw5LjMsNC4xNSw5LjMsNC4xNEw4Ni4yNyw2NWgwWiIgZmlsbD0iIzI2OTJmMSIvPgogICAgICA8L2c+CiAgICA8L2c+CiAgPC9zdmc+';
        add_menu_page(
            __('Clicklease Buttons Settings', 'cl-plugin-text'),
            __('CL Buttons', 'cl-plugin-text'),
            'manage_options',
            'cl-buttons-settings-page',
            array($this, 'createHTML'),
            $clIcon,
            66
        );
    }

    /**
     * Renders the fields which are going to be shown. In addition, it adds some validations rules to he fields and
     * set props sucha as: label, default value and type 
     */
    public function createSettings()
    {
        $essentialsSection = new CLSWP_FormSection('clk-btns-settings-page', 'clk_essetials_settings_section', 'Essentials');
        $designSection = new CLSWP_FormSection('clk-btns-settings-page', 'clk_settings_design_section', 'Button Design');
        # E S S E N T I A L S  S E C T I O N
        $essentialsSection
            ->setField(
                'clk_token',
                new CLSWP_FieldType('Token: ', 'text', '00000000-0000-0000-0000-000000000000'),   # text, num, date, select, ratio, checkbox
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Token error: Token field is mandatory'))
                    ->addRule(new CLSWP_ValidToken("Token error: token formatt must be like this xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"))
            )
            ->setField(
                'clk_op_has_landing',
                new CLSWP_FieldType("Has Landing Page: ", 'checkbox', '', ['value' => 1]),
                (new CLSWP_Validator())
            )
            ->setField(
                'clk_op_redurl',
                new CLSWP_FieldType('Redirect URL: ', 'text', ''),   # text, num, date, select, ratio, checkbox
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Redirect URL field is mandatory'))
            )
            ->setField(
                'clk_op_max_price',
                new CLSWP_FieldType('Max Price Range: ', 'number', '20000'),   # text, num, date, select, ratio, checkbox
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Range max price is mandatory'))
                    ->addRule(new CLSWP_MinLenRule(get_option('clk_op_min_price'), 'Range max price must be over $' . get_option('clk_op_min_price')))
                    ->addRule(new CLSWP_MaxLenRule(get_option('clk_op_max_price_limit'), 'Range max price must be under $' . get_option('clk_op_max_price_limit')))
            )
            ->setField(
                'clk_op_position',
                new CLSWP_FieldType(
                    'Product Page position: ',
                    'select',
                    'pos_0',
                    [
                        ['value' => '10', 'label' => 'Over Price'],
                        ['value' => '15', 'label' => 'Under Price'],
                        ['value' => '30', 'label' => 'Over "Add to Cart" button'],
                        ['value' => '40', 'label' => 'Under "Add to Cart" button']
                    ] // 10(over price), 15(under price), 30(overcart), 40(under cart)
                ),
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Select the position of the button is required'))
            )
            ->setField(
                'clk_op_is_in_cart',
                new CLSWP_FieldType("Show in Shopping Cart: ", 'checkbox', '', ['value' => 1]),
                (new CLSWP_Validator())
            )
            ->setField(
                'clk_cart_pos',
                new CLSWP_FieldType(
                    'Shopping Cart Page Position: ',
                    'select',
                    '15',
                    [
                        ['value' => 'pos_0', 'label' => 'Under checkout button'],
                        ['value' => 'pos_1', 'label' => 'Above checkout button'],
                        ['value' => 'pos_2', 'label' => 'Above items list'],
                        ['value' => 'pos_3', 'label' => 'Under coupon button'],
                        ['value' => 'pos_4', 'label' => 'Under products table (left) '],
                        ['value' => 'pos_5', 'label' => 'Under products table (right)']
                    ] // 10(over price), 15(under price), 30(overcart), 40(under cart)
                ),
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Select the cart position of button is required'))
            )
            ->setField(
                'clk_op_is_in_category_page',
                new CLSWP_FieldType("Show in Category Page: ", 'checkbox', '', ['value' => 1]),
                (new CLSWP_Validator())
            )
            ->create();

        # D E S I G N  S E C T I O N
        $designSection
            ->setField(
                'clk_op_btn_style_desktop',
                new CLSWP_FieldType('', 'hidden', 'primary_light_price4'),
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Choose a desktop button type is required'))
            )
            ->setField(
                'clk_op_btn_style_mobile',
                new CLSWP_FieldType('', 'hidden', 'mbl-primary2'),
                (new CLSWP_Validator())
                    ->addRule(new CLSWP_NotEmpty('Choose a mobile button type is required'))
            )
            ->setField(
                'clk_op_dark_mode_desktop',
                new CLSWP_FieldType('', 'hidden', 'light'),
                (new CLSWP_Validator())
            )
            ->setField(
                'clk_op_dark_mode_mobile',
                new CLSWP_FieldType('', 'hidden', 'light'),
                (new CLSWP_Validator())
            )
            ->setField(
                'clk_op_theme_desktop',
                new CLSWP_FieldType('', 'hidden', 'PRIM'),
                (new CLSWP_Validator())
            )
            ->setField(
                'clk_op_theme_mobile',
                new CLSWP_FieldType('', 'hidden', 'PRIM'),
                (new CLSWP_Validator())
            )
            ->create();
    }
}

endif;
