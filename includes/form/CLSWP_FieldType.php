<?php

if (!class_exists('CLSWP_FieldType')) : 

class CLSWP_FieldType
{

    private string $defaultValue;
    private string $label;
    private string $inType;
    private string $fldId;
    private string $tooltip;

    public function __construct(string $lbl, string $type, string $defaultValue, array $opts = [], string $tooltip = '')
    {
        $this->label = $lbl;
        $this->defaultValue = $defaultValue;
        $this->inType = $type;
        $this->inOpts = $opts;
        $this->tooltip = $tooltip;
    }

    public function render()
    {
        // TODO: these methods that renders fiedl could be in another class
        switch ($this->inType) {
            case 'text': ?>
                <input style="width: 330px;" type='text' class="cl-fld-text" id='<?php echo esc_attr($this->fldId) ?>' name="<?php echo esc_attr($this->fldId) ?>" value="<?php echo esc_attr((get_option($this->fldId, $this->defaultValue)))  ?>" placeholder="Token"/>
            <?php
                break;
            case 'number': ?>
                <input style="width: 330px;" type='number' class="cl-fld-text" id='<?php echo esc_attr($this->fldId) ?>' name="<?php echo esc_attr($this->fldId) ?>" value="<?php echo esc_attr((get_option($this->fldId, $this->defaultValue))) ?>" minlength="0" maxlength="30000" />
            <?php
                break;
            case 'color': ?>
                <input type="text" class="cl-fld-text" id="<?php echo esc_attr($this->fldId) ?>" name="<?php echo esc_attr($this->fldId) ?>" value="<?php echo esc_attr(get_option($this->fldId, $this->defaultValue)) ?>" style="display: none;" />

                <div class="cl-pickr pickr-<?php echo esc_attr($this->fldId) ?>" id="pickr-<?php echo esc_attr($this->fldId) ?>"></div>
            <?php
            case 'checkbox': ?>
                <input type='checkbox' id='<?php echo esc_attr($this->fldId) ?>' name="<?php echo esc_attr($this->fldId) ?>" value='<?php echo esc_attr($this->inOpts['value']) ?>' <?php checked(get_option($this->fldId), '1') ?> />
            <?php
                break;
            case 'select': ?>
                <div class="cl-select-field">
                    <select style="" class="cl-select--admin cl-fld-text" name="<?php echo esc_attr($this->fldId) ?>" id="<?php echo esc_attr($this->fldId) ?>">
                        <?php
                        foreach ($this->inOpts as $i => $optMap) {
                            // error_log(print_r('SELECTED: '.$optMap, true));
                            $isSelected = get_option($this->fldId) === $optMap['value'];
                            echo wp_kses(
                                '<option  value="' . $optMap['value']
                                    . '" ' . ($isSelected ? 'selected="selected"' : '') . ' >'
                                    . esc_html($optMap['label'])
                                    . '</option>',
                                [
                                    'option' => [
                                        'selected' => [],
                                        'value' => []
                                    ]
                                ]
                            );
                        }
                        ?>
                    </select>
                </div>
            <?php
                break;
            case 'hidden': ?>
                <input type='text' class="cl-fld--hidden" value='<?php echo esc_attr(get_option($this->fldId, $this->defaultValue)) ?>' id='<?php echo esc_attr($this->fldId) ?>' name="<?php echo esc_attr($this->fldId) ?>" />
            <?php
                break;

            default: ?>
                <input style="width: 330px;" type='text' id='clk_token' name="clk_token" value="<?php echo esc_attr((get_option('clk_token'))) ?>" />
        <?php
                break;
        }
    }

    private function renderInText()
    { ?>
        <input style="width: 330px;" type='text' id='clk_token' name="clk_token" value="<?php echo esc_attr((get_option('clk_token'))) ?>" />
<?php
    }

    public function setFldId(string $pFldId): void
    {
        $this->fldId = $pFldId;
    }

    public function getDefault(): string
    {
        return $this->defaultValue;
    }

    public function getLabel(): string
    {
        if($this->tooltip) {
            return $this->label . '<div class="cls-tooltip" cls-content="' . $this->tooltip . '"></div>';
        } else {
            return $this->label;
        }
    }
}

endif;