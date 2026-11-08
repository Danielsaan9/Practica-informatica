<?php

/**
 * RegisteredID class
 * @since 0.85
 **/
class RegisteredID extends CommonDBChild
{
    // From CommonDBTM
    public bool $auto_message_on_action = false;

    // From CommonDBChild
    public static string $itemtype        = 'itemtype';
    public static string $items_id        = 'items_id';
    public bool $dohistory              = true;

    public static function getRegisteredIDTypes(): array
    {
        return [
            'PCI' => __('PCI'),
            'USB' => __('USB'),
        ];
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Registered ID (issued by PCI-SIG)', 'Registered IDs (issued by PCI-SIG)', $nb);
    }

    #[Override()]
    public static function getJSCodeToAddForItemChild($field_name, $child_count_js_var): string
    {
        $html = "<select name='" . htmlescape($field_name) . "_type[-__JS_PLACEHOLDER__]'>"
            . "<option value=''>" . htmlescape(Dropdown::EMPTY_VALUE) . "</option>";

        foreach (self::getRegisteredIDTypes() as $name => $label) {
            $name = htmlescape($name);
            $label = htmlescape($label);
            $html .= "<option value='$name'>$label</option>";
        }
        $html .= "</select> : ";
        $html .= "<input type='text' size='30' name='" . htmlescape($field_name) . "[-__JS_PLACEHOLDER__]'>";

        return str_replace(
            '__JS_PLACEHOLDER__',
            "'+{$child_count_js_var}+'", // string closing, + operator, JS variable name, + operator, string reopening
            jsescape($html)
        );
    }

    public function showChildForItemForm($canedit, $field_name, $id, bool $display = true)
    {
        if (self::isNewID($this->getID())) {
            $value = '';
        } else {
            $value = $this->getName();
        }
        $value             = htmlescape($value);
        $result            = "";
        $main_field        = htmlescape($field_name . "[$id]");
        $type_field        = htmlescape($field_name . "_type[$id]");
        $registeredIDTypes = self::getRegisteredIDTypes();

        if ($canedit) {
            $result .= "<select name='$type_field' class='form-select w-auto d-inline'>";
            $result .= "<option value=''>" . Dropdown::EMPTY_VALUE . "</option>";
            foreach ($registeredIDTypes as $name => $label) {
                $result .= sprintf(
                    "<option value='%s'%s>%s</option>",
                    htmlescape($name),
                    $this->fields['device_type'] === $name ? " selected" : "",
                    htmlescape($label)
                );
            }
            $result .= "</select> : <input type='text' size='30' name='$main_field' value='$value' class='form-control'>\n";
        } else {
            $result .= "<input type='hidden' name='$main_field' value='$value' class='form-control'>";
            if (!empty($this->fields['device_type'])) {
                $result .= sprintf(
                    __s('%1$s: %2$s'),
                    htmlescape($registeredIDTypes[$this->fields['device_type']]),
                    $value
                );
            } else {
                $result .= $value;
            }
        }

        if ($display) {
            echo $result;
        } else {
            return $result;
        }
    }
}
