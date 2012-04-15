<?php
/**
 * Xul class file. Similar to Yii CHtml class.
 *
 * @author Zsolt Lengyel <zsolt.lengyel.it@gmail.com>
 */
class Xul extends CHtml
{
    const ID_PREFIX = 'xe_';

    /**
     * @var for generated ID's
     */
    protected static $idCount = 0;

    /**
     * XUL namesapce
     */
    public static $namespace = 'x';

    /**
     * @var string the XUL code to be appended to the required label.
     * @see label
     */
    public static $afterRequiredLabel = ""; //<{self::$namespace}:description class=\"required\">*</{self::$namespace}:description>";

    /**
     * Generates unique id for XUL elements
     */
    public static function generateID()
    {
        return self::ID_PREFIX . self::$idCount++;
    }

    /**
     * Generates xul tag with namespace prefix
     * @see tag
     */
    public static function xtag($tag, $xulOptions = array(), $content = false, $closeTag = true)
    {
        if (self::$namespace) { // with namespace
            $html = '<' . self::$namespace . ':' . $tag . self::renderAttributes($xulOptions);

            if ($content === false)
                return $closeTag ? $html . ' />' : $html . '>';
            else
                return $closeTag ? $html . '>' . $content . '</' . self::$namespace . ':' . $tag .
                    '>' : $html . '>' . $content;

        } else {

            $html = '<' . $tag . self::renderAttributes($xulOptions);

            if ($content === false)
                return $closeTag ? $html . ' />' : $html . '>';
            else
                return $closeTag ? $html . '>' . $content . '</' . $tag . '>' : $html . '>' . $content;

        }
    }

    public static function xopenTag($tag, $xulOptions = array())
    {
        return self::xtag($tag, $xulOptions, false, false);
    }

    /**
     * Generates a close HTML element.
     * @param string $tag the tag name
     * @return string the generated HTML element tag
     */
    public static function xcloseTag($tag)
    {
        if (self::$namespace) {
            return '</' . self::$namespace . ':' . $tag . '>';
        } else {
            return '</' . $tag . '>';
        }
    }

    public static function css($text, $media = '')
    {
        return "<style>\n{$text}\n</style>";
    }
    /**
     * Encloses the given JavaScript within a script tag.
     * @param string $text the JavaScript to be enclosed
     * @return string the enclosed JavaScript
     */
    public static function script($text)
    {
        return "<script type=\"text/javascript\">\n //<![CDATA[\n {$text}\n //]]> \n</script>";
    }

    /**
     * Links to the specified CSS file.
     * @param string $url the CSS URL
     * @param string $media depracated in XUL.
     * @return string the CSS link.
     */
    public static function cssFile($url, $media = null)
    {
        return '<?xml-stylesheet href="' . self::encode($url) . '" type="text/css"?>';
    }


    /**
     * Generates XML head tags for XUL.
     * Sets XUL namespace too.
     * 
     * @param $ns namespace
     * @param $defaulSkin adds to the generated head skin tag.
     * 
     * @return genarated head tags
     */
    public static function defaultXULHead($ns = 'x', $defaultSkin = true)
    {

        self::$namespace = $ns;

        $head = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

        if ($defaultSkin)
            $head .= '<?xml-stylesheet href="chrome://global/skin" type="text/css"?>' . "\n";

        return $head;
    }

    /**
     * Generates a stateful form tag.
     * A stateful form tag is similar to {@link form} except that it renders an additional
     * hidden field for storing persistent page states. You should use this method to generate
     * a form tag if you want to access persistent page states when the form is submitted.
     * @param mixed $action the form action URL (see {@link normalizeUrl} for details about this parameter.)
     * @param string $method form method (e.g. post, get)
     * @param array $xulOptions additional XUL attributes (see {@link tag}).
     * @return string the generated form tag.
     */
    public static function statefulForm($action = '', $method = 'post', $xulOptions =
        array())
    {
        return self::form($action, $method, $xulOptions) . "\n" . self::xtag('description',
            array('style' => 'display:none'), self::pageStateField(''));
    }

    /**
     * Generates a hidden field for storing persistent page states.
     * This method is internally used by {@link statefulForm}.
     * @param string $value the persistent page states in serialized format
     * @return string the generated hidden field
     */
    public static function pageStateField($value)
    {
        return self::xtag('textbox', array(
            'style' => 'display:none',
            'id' => CController::STATE_INPUT_NAME,
            'value' => $value));
    }

    /**
     * Generates a hyperlink tag.
     * @param string $text link body. It will NOT be XUL-encoded. Therefore you can pass in XUL code such as an image tag.
     * @param mixed $url a URL or an action route that can be used to create a URL.
     * See {@link normalizeUrl} for more details about how to specify this parameter.
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated hyperlink
     * @see normalizeUrl
     * @see clientChange
     */
    public static function link($text, $url = '#', $xulOptions = array(), $pointerCursor = true)
    {
        if ($url !== '')
            $url = self::normalizeUrl($url);

        $xulOptions['href'] = $url;

        self::extendAttribute($xulOptions, 'onclick', 'location.href="' . $url . '";');

        self::extendAttribute($xulOptions, 'class', 'text-link');

        return self::xtag('label', $xulOptions, $text);
    }

    /**
     * Generates an image tag.
     * @param string $src the image URL
     * @param string $alt the alternative text display
     * @param array $xulOptions additional XUL attributes (see {@link tag}).
     * @return string the generated image tag
     */
    public static function image($src, $alt = '', $xulOptions = array())
    {
        $xulOptions['src'] = $src;
        if ($alt != '')
            $xulOptions['alt'] = $alt;

        return self::xtag('image', $xulOptions);
    }

    public static function textbox($xulOptions = array())
    {
        return self::xtag('textbox', $xulOptions);
    }

    public static function hbox($content = false, $xulOptions = array())
    {
        return self::xtag('hbox', $xulOptions, $content);
    }

    public static function openHbox($xulOptions = array())
    {
        return self::xopenTag('hbox', $xulOptions);
    }

    public static function closeHbox()
    {
        return self::xcloseTag('hbox');
    }

    public static function vbox($content = false, $xulOptions = array())
    {
        return self::xtag('vbox', $xulOptions, $content);
    }

    public static function openVbox($xulOptions = array())
    {
        return self::xopenTag('vbox', $xulOptions);
    }

    public static function closeVbox()
    {
        return self::xcloseTag('vbox');
    }

    /**
     * Generates a button.
     * @param string $label the button label
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button tag
     * @see clientChange
     */
    public static function button($label = 'button', $oncommand = null, $xulOptions =
        array())
    {
        if (!isset($xulOptions['id'])) {
            if (!array_key_exists('id', $xulOptions))
                $xulOptions['id'] = self::ID_PREFIX . self::$count++;
        }
        $xulOptions['label'] = $label;
        
        if(!is_null($oncommand))
            $xulOptions['oncommand'] = $command;

        return self::xtag('button', $xulOptions);
    }

    public static function form($action = '', $method = 'post', $htmlOptions = array
        ())
    {
        return self::beginForm($action, $method, $htmlOptions);
    }

    /**
     * Generates an opening form tag.
     * Note, only the open tag is generated. A close tag should be placed manually
     * at the end of the form.
     * @param mixed $action the form action URL (see {@link normalizeUrl} for details about this parameter.)
     * @param string $method form method (e.g. post, get)
     * @param array $htmlOptions additional HTML attributes (see {@link tag}).
     * @return string the generated form tag.
     * @since 1.0.4
     * @see endForm
     */
    public static function beginForm($action = '', $method = 'post', $htmlOptions =
        array(), $tag = 'box')
    {
        $htmlOptions['action'] = $url = self::normalizeUrl($action);
        $htmlOptions['method'] = $method;
        $form = self::xtag($tag, $htmlOptions, false, false);
        $hiddens = array();
        if (!strcasecmp($method, 'get') && ($pos = strpos($url, '?')) !== false) {
            foreach (explode('&', substr($url, $pos + 1)) as $pair) {
                if (($pos = strpos($pair, '=')) !== false)
                    $hiddens[] = self::hiddenField(urldecode(substr($pair, 0, $pos)), urldecode(substr
                        ($pair, $pos + 1)), array('id' => false));
            }
        }
        $request = Yii::app()->request;
        if ($request->enableCsrfValidation && !strcasecmp($method, 'post'))
            $hiddens[] = self::hiddenField($request->csrfTokenName, $request->getCsrfToken(),
                array('id' => false));
        if ($hiddens !== array())
            $form .= "\n" . self::tag('div', array('style' => 'display:none'), implode("\n",
                $hiddens));
        return $form;
    }

    /**
     * Generates a closing form tag.
     * @return string the generated tag
     * @since 1.0.4
     * @see beginForm
     */
    public static function endForm($tag = 'box')
    {
        return self::xcloseTag($tag);
    }


    /**
     * Generates a submit button.
     * @param string $label the button label
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button tag
     * @see clientChange
     */
    public static function submitButton($label = 'submit', $xulOptions = array())
    {
        return self::button($label, $xulOptions);
    }

    /**
     * Generates a reset button.
     * @param string $label the button label
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button tag
     * @see clientChange
     */
    public static function resetButton($label = 'reset', $xulOptions = array())
    {
        $xulOptions['type'] = 'reset';
        return self::button($label, $xulOptions);
    }

    /**
     * Generates an image submit button.
     * @param string $src the image URL
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button tag
     * @see clientChange
     */
    public static function imageButton($src, $xulOptions = array())
    {
        $xulOptions['src'] = $src;
        $xulOptions['type'] = 'image';
        return self::button('submit', $xulOptions);
    }

    /**
     * Generates a link submit button.
     * @param string $label the button label
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button tag
     * @see clientChange
     */
    public static function linkButton($label = 'submit', $xulOptions = array())
    {
        if (!isset($xulOptions['submit']))
            $xulOptions['submit'] = isset($xulOptions['href']) ? $xulOptions['href'] : '';
        return self::link($label, '#', $xulOptions);
    }

    /**
     * Generates a label tag.
     */
    public static function label($label, $controller = false, $xulOptions = array())
    {
        if ($controller === false)
            unset($xulOptions['controller']);
        else
            $xulOptions['controller'] = $controller;
        if (isset($xulOptions['required'])) {
            if ($xulOptions['required']) {
                if (isset($xulOptions['class']))
                    $xulOptions['class'] .= ' ' . self::$requiredCss;
                else
                    $xulOptions['class'] = self::$requiredCss;
                $label = self::$beforeRequiredLabel . $label . self::$afterRequiredLabel;
            }
            unset($xulOptions['required']);
        }
        $xulOptions['value'] = self::encode($label);
        return self::xtag('label', $xulOptions);
    }

    public static function description($value, $xulOptions = array())
    {
        if (isset($xulOptions['value'])) {
            $xulOptions['value'] = $value;
            return self::xtag('description', $xulOptions);
        } else {
            return self::xtag('description', $xulOptions, $vale);
        }
    }

    public static function splitter($content = '', $xulOptions = array())
    {
        return Xul::xopenTag('splitter', $xulOptions) . $content . Xul::xcloseTag('splitter');
    }

    /**
     * Label - description pair generator
     */
    public static function labelAndDescription($label, $value, $separator = '', $xulOptions =
        array(), $id = false)
    {
        return self::label($label) . $separator . self::description($value, $xulOptions);
    }

    /**
     * Generates a text field input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see inputField
     */
    public static function textField($name, $value = '', $xulOptions = array())
    {
        self::clientChange('change', $xulOptions);
        $xulOptions['id']=$name;
        $xulOptions['value']=$value;
        return self::textbox($$xulOptions);
    }

    /**
     * Generates a hidden input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes (see {@link tag}).
     * @return string the generated input field
     * @see inputField
     */
    public static function hiddenField($name, $value = '', $xulOptions = array())
    {
        return self::inputField('hidden', $name, $value, $xulOptions);
    }

    /**
     * Generates a password field input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see inputField
     */
    public static function passwordField($id, $value = '', $xulOptions = array())
    {
        $xulOptions['type'] = 'password';
        $xulOptions['value'] = $value;
        $xulOptions['id'] = $name;
        return self::textbox($xulOptions);
    }

    /**
     * Generates a file input.
     * Note, you have to set the enclosing form's 'enctype' attribute to be 'multipart/form-data'.
     * After the form is submitted, the uploaded file information can be obtained via $_FILES[$name] (see
     * PHP documentation).
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes (see {@link tag}).
     * @return string the generated input field
     * @see inputField
     */
    public static function fileField($name, $value = '', $xulOptions = array())
    {
        return self::inputField('file', $name, $value, $xulOptions);
    }

    /**
     * Generates a text area input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated text area
     * @see clientChange
     * @see inputField
     */
    public static function textArea($name, $value = '', $xulOptions = array())
    {
        $xulOptions['name'] = $name;
        if (!isset($xulOptions['id']))
            $xulOptions['id'] = self::getIdByName($name);
        else
            if ($xulOptions['id'] === false)
                unset($xulOptions['id']);
        self::clientChange('change', $xulOptions);
        return self::tag('textarea', $xulOptions, isset($xulOptions['encode']) && !$xulOptions['encode'] ?
            $value : self::encode($value));
    }

    /**
     * Generates a radio button.
     * @param string $name the input name
     * @param boolean $checked whether the radio button is checked
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the radio button is not checked. When set, a hidden field is rendered so that
     * when the radio button is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
     * @return string the generated radio button
     * @see clientChange
     * @see inputField
     */
    public static function radioButton($name, $checked = false, $xulOptions = array
        ())
    {
        if ($checked)
            $xulOptions['checked'] = 'checked';
        else
            unset($xulOptions['checked']);
        $value = isset($xulOptions['value']) ? $xulOptions['value'] : 1;
        self::clientChange('click', $xulOptions);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = null;

        if ($uncheck !== null) {
            // add a hidden field so that if the radio button is not selected, it still submits a value
            if (isset($xulOptions['id']) && $xulOptions['id'] !== false)
                $uncheckOptions = array('id' => self::ID_PREFIX . $xulOptions['id']);
            else
                $uncheckOptions = array('id' => false);
            $hidden = self::hiddenField($name, $uncheck, $uncheckOptions);
        } else
            $hidden = '';

        // add a hidden field so that if the radio button is not selected, it still submits a value
        return $hidden . self::inputField('radio', $name, $value, $xulOptions);
    }

    /**
     * Generates a check box.
     * @param string $name the input name
     * @param boolean $checked whether the check box is checked
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the checkbox is not checked. When set, a hidden field is rendered so that
     * when the checkbox is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
     * @return string the generated check box
     * @see clientChange
     * @see inputField
     */
    public static function checkBox($name, $checked = false, $xulOptions = array())
    {
        if ($checked)
            $xulOptions['checked'] = 'checked';
        else
            unset($xulOptions['checked']);
        $value = isset($xulOptions['value']) ? $xulOptions['value'] : 1;
        self::clientChange('click', $xulOptions);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = null;

        if ($uncheck !== null) {
            // add a hidden field so that if the radio button is not selected, it still submits a value
            if (isset($xulOptions['id']) && $xulOptions['id'] !== false)
                $uncheckOptions = array('id' => self::ID_PREFIX . $xulOptions['id']);
            else
                $uncheckOptions = array('id' => false);
            $hidden = self::hiddenField($name, $uncheck, $uncheckOptions);
        } else
            $hidden = '';

        // add a hidden field so that if the checkbox  is not selected, it still submits a value
        return $hidden . self::inputField('checkbox', $name, $value, $xulOptions);
    }


    public static function dropDownList($id, $select=1, $data=array(), $xulOptions = array())
    {
        $xulOptions['id'] = $id;
        if (!isset($xulOptions['id']))
            $xulOptions['id'] = self::getIdByName($name);
        else
            if ($xulOptions['id'] === false)
                unset($xulOptions['id']);

        $xulOptions['selectedIndex'] = $select;

        
        $options = "\n" . self::listMenuPopup($data, $xulOptions);
        return self::xtag('menulist', $xulOptions, $options);
    }
    
    public static function listMenuPopup($data=array(), $xulOptions=array()){
        $cont = self::xopenTag('menupopup',$xulOptions);
        
        foreach($data as  $value => $label){
            $cont .= self::xtag('menuitem', array(
                'label'=>$label,
                'value'=>$value
            ));
        }
        
        $cont .= self::xcloseTag('menupopup');
        return $cont;
    }

  
    public static function listBox($name, $select, $data, $xulOptions = array())
    {
        if (!isset($xulOptions['size']))
            $xulOptions['size'] = 4;
        if (isset($xulOptions['multiple'])) {
            if (substr($name, -2) !== '[]')
                $name .= '[]';
        }
        return self::dropDownList($name, $select, $data, $xulOptions);
    }

    /**
     * Generates a check box list.
     * A check box list allows multiple selection, like {@link listBox}.
     * As a result, the corresponding POST value is an array.
     * @param string $name name of the check box list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param mixed $select selection of the check boxes. This can be either a string
     * for single selection or an array for multiple selections.
     * @param array $data value-label pairs used to generate the check box list.
     * Note, the values will be automatically XUL-encoded, while the labels will not.
     * @param array $xulOptions addtional XUL options. The options will be applied to
     * each checkbox input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each checkbox is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * check box input tag while "{label}" be replaced by the corresponding check box label.</li>
     * <li>separator: string, specifies the string that separates the generated check boxes.</li>
     * <li>checkAll: string, specifies the label for the "check all" checkbox.
     * If this option is specified, a 'check all' checkbox will be displayed. Clicking on
     * this checkbox will cause all checkboxes checked or unchecked. This option has been
     * available since version 1.0.4.</li>
     * <li>checkAllLast: boolean, specifies whether the 'check all' checkbox should be
     * displayed at the end of the checkbox list. If this option is not set (default)
     * or is false, the 'check all' checkbox will be displayed at the beginning of
     * the checkbox list. This option has been available since version 1.0.4.</li>
     * <li>labelOptions: array, specifies the additional XUL attributes to be rendered
     * for every label tag in the list. This option has been available since version 1.0.10.</li>
     * </ul>
     * @return string the generated check box list
     */
    public static function checkBoxList($name, $select, $data, $xulOptions = array())
    {
        $template = isset($xulOptions['template']) ? $xulOptions['template'] :
            '{input} {label}';
        $separator = isset($xulOptions['separator']) ? $xulOptions['separator'] : "<br/>\n";
        unset($xulOptions['template'], $xulOptions['separator']);

        if (substr($name, -2) !== '[]')
            $name .= '[]';

        if (isset($xulOptions['checkAll'])) {
            $checkAllLabel = $xulOptions['checkAll'];
            $checkAllLast = isset($xulOptions['checkAllLast']) && $xulOptions['checkAllLast'];
        }
        unset($xulOptions['checkAll'], $xulOptions['checkAllLast']);

        $labelOptions = isset($xulOptions['labelOptions']) ? $xulOptions['labelOptions'] :
            array();
        unset($xulOptions['labelOptions']);

        $items = array();
        $baseID = self::getIdByName($name);
        $id = 0;
        $checkAll = true;

        foreach ($data as $value => $label) {
            $checked = !is_array($select) && !strcmp($value, $select) || is_array($select) &&
                in_array($value, $select);
            $checkAll = $checkAll && $checked;
            $xulOptions['value'] = $value;
            $xulOptions['id'] = $baseID . '_' . $id++;
            $option = self::checkBox($name, $checked, $xulOptions);
            $label = self::label($label, $xulOptions['id'], $labelOptions);
            $items[] = strtr($template, array('{input}' => $option, '{label}' => $label));
        }

        if (isset($checkAllLabel)) {
            $xulOptions['value'] = 1;
            $xulOptions['id'] = $id = $baseID . '_all';
            $option = self::checkBox($id, $checkAll, $xulOptions);
            $label = self::label($checkAllLabel, $id, $labelOptions);
            $item = strtr($template, array('{input}' => $option, '{label}' => $label));
            if ($checkAllLast)
                $items[] = $item;
            else
                array_unshift($items, $item);
            $name = strtr($name, array('[' => '\\[', ']' => '\\]'));
            $js = <<< EOD
jQuery('#$id').click(function() {
	jQuery("input[name='$name']").attr('checked', this.checked);
});
jQuery("input[name='$name']").click(function() {
	jQuery('#$id').attr('checked', !jQuery("input[name='$name']:not(:checked)").length);
});
jQuery('#$id').attr('checked', !jQuery("input[name='$name']:not(:checked)").length);
EOD;
            $cs = Yii::app()->getClientScript();
            $cs->registerCoreScript('jquery');
            $cs->registerScript($id, $js);
        }

        return implode($separator, $items);
    }

    /**
     * Generates a radio button list.
     * A radio button list is like a {@link checkBoxList check box list}, except that
     * it only allows single selection.
     * @param string $name name of the radio button list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param mixed $select selection of the radio buttons. This can be either a string
     * for single selection or an array for multiple selections.
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically XUL-encoded, while the labels will not.
     * @param array $xulOptions addtional XUL options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label.</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons.</li>
     * <li>labelOptions: array, specifies the additional XUL attributes to be rendered
     * for every label tag in the list. This option has been available since version 1.0.10.</li>
     * </ul>
     * @return string the generated radio button list
     */
    public static function radioButtonList($name, $select, $data, $xulOptions =
        array())
    {
        $template = isset($xulOptions['template']) ? $xulOptions['template'] :
            '{input} {label}';
        $separator = isset($xulOptions['separator']) ? $xulOptions['separator'] : "<br/>\n";
        unset($xulOptions['template'], $xulOptions['separator']);

        $labelOptions = isset($xulOptions['labelOptions']) ? $xulOptions['labelOptions'] :
            array();
        unset($xulOptions['labelOptions']);

        $items = array();
        $baseID = self::getIdByName($name);
        $id = 0;
        foreach ($data as $value => $label) {
            $checked = !strcmp($value, $select);
            $xulOptions['value'] = $value;
            $xulOptions['id'] = $baseID . '_' . $id++;
            $option = self::radioButton($name, $checked, $xulOptions);
            $label = self::label($label, $xulOptions['id'], $labelOptions);
            $items[] = strtr($template, array('{input}' => $option, '{label}' => $label));
        }
        return implode($separator, $items);
    }

    /**
     * Generates a link that can initiate AJAX requests.
     * @param string $text the link body (it will NOT be XUL-encoded.)
     * @param mixed $url the URL for the AJAX request. If empty, it is assumed to be the current URL. See {@link normalizeUrl} for more details.
     * @param array $ajaxOptions AJAX options (see {@link ajax})
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated link
     * @see normalizeUrl
     * @see ajax
     */
    public static function ajaxLink($text, $url, $ajaxOptions = array(), $xulOptions =
        array())
    {
        if (!isset($xulOptions['href']))
            $xulOptions['href'] = '#';
        $ajaxOptions['url'] = $url;
        $xulOptions['ajax'] = $ajaxOptions;
        self::clientChange('click', $xulOptions);
        return self::tag('a', $xulOptions, $text);
    }

    /**
     * Generates a push button that can initiate AJAX requests.
     * @param string $label the button label
     * @param mixed $url the URL for the AJAX request. If empty, it is assumed to be the current URL. See {@link normalizeUrl} for more details.
     * @param array $ajaxOptions AJAX options (see {@link ajax})
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button
     */
    public static function ajaxButton($label, $url, $ajaxOptions = array(), $xulOptions =
        array())
    {
        $ajaxOptions['url'] = $url;
        $xulOptions['ajax'] = $ajaxOptions;
        return self::button($label, $xulOptions);
    }

    /**
     * Generates a push button that can submit the current form in POST method.
     * @param string $label the button label
     * @param mixed $url the URL for the AJAX request. If empty, it is assumed to be the current URL. See {@link normalizeUrl} for more details.
     * @param array $ajaxOptions AJAX options (see {@link ajax})
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated button
     */
    public static function ajaxSubmitButton($label, $url, $ajaxOptions = array(), $xulOptions =
        array())
    {
        $ajaxOptions['type'] = 'POST';
        $xulOptions['type'] = 'submit';
        return self::ajaxButton($label, $url, $ajaxOptions, $xulOptions);
    }

    /**
     * Generates the JavaScript that initiates an AJAX request.
     * @param array $options AJAX options. The valid options are specified in the jQuery ajax documentation.
     * The following special options are added for convenience:
     * <ul>
     * <li>update: string, specifies the selector whose XUL content should be replaced
     *   by the AJAX request result.</li>
     * <li>replace: string, specifies the selector whose target should be replaced
     *   by the AJAX request result.</li>
     * </ul>
     * Note, if you specify the 'success' option, the above options will be ignored.
     * @return string the generated JavaScript
     * @see http://docs.jquery.com/Ajax/jQuery.ajax#options
     */
    public static function ajax($options)
    {
        Yii::app()->getClientScript()->registerCoreScript('jquery');
        if (!isset($options['url']))
            $options['url'] = 'js:location.href';
        else
            $options['url'] = self::normalizeUrl($options['url']);
        if (!isset($options['cache']))
            $options['cache'] = false;
        if (!isset($options['data']) && isset($options['type']))
            $options['data'] = 'js:jQuery(this).parents("form").serialize()';
        foreach (array(
            'beforeSend',
            'complete',
            'error',
            'success') as $name) {
            if (isset($options[$name]) && strpos($options[$name], 'js:') !== 0)
                $options[$name] = 'js:' . $options[$name];
        }
        if (isset($options['update'])) {
            if (!isset($options['success']))
                $options['success'] = 'js:function(XUL){jQuery("' . $options['update'] .
                    '").html(XUL)}';
            unset($options['update']);
        }
        if (isset($options['replace'])) {
            if (!isset($options['success']))
                $options['success'] = 'js:function(XUL){jQuery("' . $options['replace'] .
                    '").replaceWith(XUL)}';
            unset($options['replace']);
        }
        return 'jQuery.ajax(' . CJavaScript::encode($options) . ');';
    }

    /**
     * Generates the URL for the published assets.
     * @param string $path the path of the asset to be published
     * @param boolean $hashByName whether the published directory should be named as the hashed basename.
     * If false, the name will be the hashed dirname of the path being published.
     * Defaults to false. Set true if the path being published is shared among
     * different extensions.
     * @return string the asset URL
     */
    public static function asset($path, $hashByName = false)
    {
        return Yii::app()->getAssetManager()->publish($path, $hashByName);
    }

    /**
     * Generates an input XUL tag.
     * This method generates an input XUL tag based on the given input name and value.
     * @param string $type the input type (e.g. 'text', 'radio')
     * @param string $name the input name
     * @param string $value the input value
     * @param array $xulOptions additional XUL attributes for the XUL tag (see {@link tag}).
     * @return string the generated input tag
     */
    protected static function inputField($type, $name, $value, $xulOptions)
    {
        $xulOptions['type'] = $type;
        $xulOptions['value'] = $value;
        $xulOptions['name'] = $name;
        if (!isset($xulOptions['id']))
            $xulOptions['id'] = self::getIdByName($name);
        else
            if ($xulOptions['id'] === false)
                unset($xulOptions['id']);
        return self::tag('input', $xulOptions);
    }

    /**
     * Generates a label tag for a model attribute.
     * The label text is the attribute label and the label is associated with
     * the input for the attribute (see {@link CModel::getAttributeLabel}.
     * If the attribute has input error, the label's CSS class will be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. The following special options are recognized:
     * <ul>
     * <li>required: if this is set and is true, the label will be styled
     * with CSS class 'required' (customizable with CXUL::$requiredCss),
     * and be decorated with {@link CXUL::beforeRequiredLabel} and
     * {@link CXUL::afterRequiredLabel}. This option has been available since version 1.0.2.</li>
     * <li>label: this specifies the label to be displayed. If this is not set,
     * {@link CModel::getAttributeLabel} will be called to get the label for display.
     * If the label is specified as false, no label will be rendered.
     * This option has been available since version 1.0.4.</li>
     * </ul>
     * @return string the generated label tag
     */
    public static function activeLabel($model, $attribute, $xulOptions = array())
    {
        if (isset($xulOptions['for'])) {
            $for = $xulOptions['for'];
            unset($xulOptions['for']);
        } else
            $for = self::getIdByName(self::resolveName($model, $attribute));
        if (isset($xulOptions['label'])) {
            if (($label = $xulOptions['label']) === false)
                return '';
            unset($xulOptions['label']);
        } else
            $label = $model->getAttributeLabel($attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        return self::label($label, $for, $xulOptions);
    }

    /**
     * Generates a label tag for a model attribute.
     * This is an enhanced version of {@link activeLabel}. It will render additional
     * CSS class and mark when the attribute is required.
     * In particular, it calls {@link CModel::isAttributeRequired} to determine
     * if the attribute is required.
     * If so, it will add a CSS class {@link CXUL::requiredCss} to the label,
     * and decorate the label with {@link CXUL::beforeRequiredLabel} and
     * {@link CXUL::afterRequiredLabel}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes.
     * @return string the generated label tag
     * @since 1.0.2
     */
    public static function activeLabelEx($model, $attribute, $xulOptions = array())
    {
        $realAttribute = $attribute;
        self::resolveName($model, $attribute); // strip off square brackets if any
        $xulOptions['required'] = $model->isAttributeRequired($attribute);
        return self::activeLabel($model, $realAttribute, $xulOptions);
    }

    /**
     * Generates a text field input for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see activeInputField
     */
    public static function activeTextField($model, $attribute, $xulOptions = array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        self::clientChange('change', $xulOptions);
        return self::activeInputField('text', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a hidden input for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes.
     * @return string the generated input field
     * @see activeInputField
     */
    public static function activeHiddenField($model, $attribute, $xulOptions = array
        ())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        return self::activeInputField('hidden', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a password field input for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see activeInputField
     */
    public static function activePasswordField($model, $attribute, $xulOptions =
        array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        self::clientChange('change', $xulOptions);
        return self::activeInputField('password', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a text area input for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated text area
     * @see clientChange
     */
    public static function activeTextArea($model, $attribute, $xulOptions = array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        self::clientChange('change', $xulOptions);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        $text = self::resolveValue($model, $attribute);
        return self::tag('textarea', $xulOptions, isset($xulOptions['encode']) && !$xulOptions['encode'] ?
            $text : self::encode($text));
    }

    /**
     * Generates a file input for a model attribute.
     * Note, you have to set the enclosing form's 'enctype' attribute to be 'multipart/form-data'.
     * After the form is submitted, the uploaded file information can be obtained via $_FILES (see
     * PHP documentation).
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes (see {@link tag}).
     * @return string the generated input field
     * @see activeInputField
     */
    public static function activeFileField($model, $attribute, $xulOptions = array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        // add a hidden field so that if a model only has a file field, we can
        // still use isset($_POST[$modelClass]) to detect if the input is submitted
        $hiddenOptions = isset($xulOptions['id']) ? array('id' => self::ID_PREFIX . $xulOptions['id']) :
            array('id' => false);
        return self::hiddenField($xulOptions['name'], '', $hiddenOptions) . self::
            activeInputField('file', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a radio button for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.0.9, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the radio button is not checked. By default, this value is '0'.
     * Internally, a hidden field is rendered so that when the radio button is not checked,
     * we can still obtain the posted uncheck value.
     * If 'uncheckValue' is set as NULL, the hidden field will not be rendered.
     * @return string the generated radio button
     * @see clientChange
     * @see activeInputField
     */
    public static function activeRadioButton($model, $attribute, $xulOptions = array
        ())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        if (!isset($xulOptions['value']))
            $xulOptions['value'] = 1;
        if (!isset($xulOptions['checked']) && self::resolveValue($model, $attribute) ==
            $xulOptions['value'])
            $xulOptions['checked'] = 'checked';
        self::clientChange('click', $xulOptions);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = '0';

        $hiddenOptions = isset($xulOptions['id']) ? array('id' => self::ID_PREFIX . $xulOptions['id']) :
            array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($xulOptions['name'], $uncheck, $hiddenOptions) :
            '';

        // add a hidden field so that if the radio button is not selected, it still submits a value
        return $hidden . self::activeInputField('radio', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a check box for a model attribute.
     * The attribute is assumed to take either true or false value.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.0.2, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the checkbox is not checked. By default, this value is '0'.
     * Internally, a hidden field is rendered so that when the checkbox is not checked,
     * we can still obtain the posted uncheck value.
     * If 'uncheckValue' is set as NULL, the hidden field will not be rendered.
     * @return string the generated check box
     * @see clientChange
     * @see activeInputField
     */
    public static function activeCheckBox($model, $attribute, $xulOptions = array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        if (!isset($xulOptions['value']))
            $xulOptions['value'] = 1;
        if (!isset($xulOptions['checked']) && self::resolveValue($model, $attribute) ==
            $xulOptions['value'])
            $xulOptions['checked'] = 'checked';
        self::clientChange('click', $xulOptions);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = '0';

        $hiddenOptions = isset($xulOptions['id']) ? array('id' => self::ID_PREFIX . $xulOptions['id']) :
            array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($xulOptions['name'], $uncheck, $hiddenOptions) :
            '';

        return $hidden . self::activeInputField('checkbox', $model, $attribute, $xulOptions);
    }

    /**
     * Generates a drop down list for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data data for generating the list options (value=>display)
     * You may use {@link listData} to generate this data.
     * Please refer to {@link listOptions} on how this data is used to generate the list options.
     * Note, the values and labels will be automatically XUL-encoded by this method.
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are recognized. See {@link clientChange} and {@link tag} for more details.
     * In addition, the following options are also supported:
     * <ul>
     * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
     * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty.  Note, the prompt text will NOT be XUL-encoded.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be XUL-encoded.</li>
     * <li>options: array, specifies additional attributes for each OPTION tag.
     *     The array keys must be the option values, and the array values are the extra
     *     OPTION tag attributes in the name-value pairs. For example,
     * <pre>
     *     array(
     *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
     *         'value2'=>array('label'=>'value 2'),
     *     );
     * </pre>
     *     This option has been available since version 1.0.3.
     * </li>
     * </ul>
     * @return string the generated drop down list
     * @see clientChange
     * @see listData
     */
    public static function activeDropDownList($model, $attribute, $data, $xulOptions =
        array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        $selection = self::resolveValue($model, $attribute);
        $options = "\n" . self::listOptions($selection, $data, $xulOptions);
        self::clientChange('change', $xulOptions);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        if (isset($xulOptions['multiple'])) {
            if (substr($xulOptions['name'], -2) !== '[]')
                $xulOptions['name'] .= '[]';
        }
        return self::tag('select', $xulOptions, $options);
    }


    /**
     * Generates a list box for a model attribute.
     * The model attribute value is used as the selection.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data data for generating the list options (value=>display)
     * You may use {@link listData} to generate this data.
     * Please refer to {@link listOptions} on how this data is used to generate the list options.
     * Note, the values and labels will be automatically XUL-encoded by this method.
     * @param array $xulOptions additional XUL attributes. Besides normal XUL attributes, a few special
     * attributes are recognized. See {@link clientChange} and {@link tag} for more details.
     * In addition, the following options are also supported:
     * <ul>
     * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
     * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty. Note, the prompt text will NOT be XUL-encoded.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be XUL-encoded.</li>
     * <li>options: array, specifies additional attributes for each OPTION tag.
     *     The array keys must be the option values, and the array values are the extra
     *     OPTION tag attributes in the name-value pairs. For example,
     * <pre>
     *     array(
     *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
     *         'value2'=>array('label'=>'value 2'),
     *     );
     * </pre>
     *     This option has been available since version 1.0.3.
     * </li>
     * </ul>
     * @return string the generated list box
     * @see clientChange
     * @see listData
     */
    public static function activeListBox($model, $attribute, $data, $xulOptions =
        array())
    {
        if (!isset($xulOptions['size']))
            $xulOptions['size'] = 4;
        return self::activeDropDownList($model, $attribute, $data, $xulOptions);
    }

    /**
     * Generates a check box list for a model attribute.
     * The model attribute value is used as the selection.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * Note that a check box list allows multiple selection, like {@link listBox}.
     * As a result, the corresponding POST value is an array. In case no selection
     * is made, the corresponding POST value is an empty string.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the check box list.
     * Note, the values will be automatically XUL-encoded, while the labels will not.
     * @param array $xulOptions addtional XUL options. The options will be applied to
     * each checkbox input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each checkbox is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * check box input tag while "{label}" will be replaced by the corresponding check box label.</li>
     * <li>separator: string, specifies the string that separates the generated check boxes.</li>
     * <li>checkAll: string, specifies the label for the "check all" checkbox.
     * If this option is specified, a 'check all' checkbox will be displayed. Clicking on
     * this checkbox will cause all checkboxes checked or unchecked. This option has been
     * available since version 1.0.4.</li>
     * <li>checkAllLast: boolean, specifies whether the 'check all' checkbox should be
     * displayed at the end of the checkbox list. If this option is not set (default)
     * or is false, the 'check all' checkbox will be displayed at the beginning of
     * the checkbox list. This option has been available since version 1.0.4.</li>
     * <li>encode: boolean, specifies whether to encode XUL-encode tag attributes and values. Defaults to true.
     * This option has been available since version 1.0.5.</li>
     * </ul>
     * Since 1.1.7, a special option named 'uncheckValue' is available. It can be used to set the value
     * that will be returned when the checkbox is not checked. By default, this value is ''.
     * Internally, a hidden field is rendered so when the checkbox is not checked, we can still
     * obtain the value. If 'uncheckValue' is set to NULL, there will be no hidden field rendered.
     * @return string the generated check box list
     * @see checkBoxList
     */
    public static function activeCheckBoxList($model, $attribute, $data, $xulOptions =
        array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        $selection = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        $name = $xulOptions['name'];
        unset($xulOptions['name']);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = '';

        $hiddenOptions = isset($xulOptions['id']) ? array('id' => self::ID_PREFIX . $xulOptions['id']) :
            array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) :
            '';

        return $hidden . self::checkBoxList($name, $selection, $data, $xulOptions);
    }

    /**
     * Generates a radio button list for a model attribute.
     * The model attribute value is used as the selection.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically XUL-encoded, while the labels will not.
     * @param array $xulOptions addtional XUL options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label.</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons.</li>
     * <li>encode: boolean, specifies whether to encode XUL-encode tag attributes and values. Defaults to true.
     * This option has been available since version 1.0.5.</li>
     * </ul>
     * Since version 1.1.7, a special option named 'uncheckValue' is available that can be used to specify the value
     * returned when the radio button is not checked. By default, this value is ''. Internally, a hidden field is
     * rendered so that when the radio button is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is set as NULL, the hidden field will not be rendered.
     * @return string the generated radio button list
     * @see radioButtonList
     */
    public static function activeRadioButtonList($model, $attribute, $data, $xulOptions =
        array())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        $selection = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        $name = $xulOptions['name'];
        unset($xulOptions['name']);

        if (array_key_exists('uncheckValue', $xulOptions)) {
            $uncheck = $xulOptions['uncheckValue'];
            unset($xulOptions['uncheckValue']);
        } else
            $uncheck = '';

        $hiddenOptions = isset($xulOptions['id']) ? array('id' => self::ID_PREFIX . $xulOptions['id']) :
            array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) :
            '';

        return $hidden . self::radioButtonList($name, $selection, $data, $xulOptions);
    }

    /**
     * Generates XUL specific textbox tag
     */
    public static function activeNumberfield($model, $attribute, $xulOptions = array
        ())
    {
        self::resolveNameID($model, $attribute, $xulOptions);
        self::clientChange('change', $xulOptions);
        return self::activeTextbox('number', $model, $attribute, $xulOptions);
    }

    /**
     * Returns the element ID that is used by methods such as {@link activeTextField}.
     * This method has been deprecated since version 1.0.5. Please use {@link activeId} instead.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the element ID for the active field corresponding to the specified model and attribute.
     * @deprecated 1.0.5
     */
    public static function getActiveId($model, $attribute)
    {
        return self::activeId($model, $attribute);
    }

    /**
     * Displays a summary of validation errors for one or several models.
     * @param mixed $model the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of XUL code that appears in front of the errors
     * @param string $footer a piece of XUL code that appears at the end of the errors
     * @param array $xulOptions additional XUL attributes to be rendered in the container div tag.
     * This parameter has been available since version 1.0.7.
     * A special option named 'firstError' is recognized, which when set true, will
     * make the error summary to show only the first error message of each attribute.
     * If this is not set or is false, all error messages will be displayed.
     * This option has been available since version 1.1.3.
     * @return string the error summary. Empty if no errors are found.
     * @see CModel::getErrors
     * @see errorSummaryCss
     */
    public static function errorSummary($model, $header = null, $footer = null, $xulOptions =
        array())
    {
        $content = '';
        if (!is_array($model))
            $model = array($model);
        if (isset($xulOptions['firstError'])) {
            $firstError = $xulOptions['firstError'];
            unset($xulOptions['firstError']);
        } else
            $firstError = false;
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '')
                        $content .= "<li>$error</li>\n";
                    if ($firstError)
                        break;
                }
            }
        }
        if ($content !== '') {
            if ($header === null)
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') .
                    '</p>';
            if (!isset($xulOptions['class']))
                $xulOptions['class'] = self::$errorSummaryCss;
            return self::tag('div', $xulOptions, $header . "\n<ul>\n$content</ul>" . $footer);
        } else
            return '';
    }

    /**
     * Displays the first validation error for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute name
     * @param array $xulOptions additional XUL attributes to be rendered in the container div tag.
     * This parameter has been available since version 1.0.7.
     * @return string the error display. Empty if no errors are found.
     * @see CModel::getErrors
     * @see errorMessageCss
     */
    public static function error($model, $attribute, $xulOptions = array())
    {
        $error = $model->getError($attribute);
        if ($error != '') {
            if (!isset($xulOptions['class']))
                $xulOptions['class'] = self::$errorMessageCss;
            return self::xtag('box', $xulOptions, $error);
        } else
            return '';
    }

    /**
     * Generates the data suitable for list-based XUL elements.
     * The generated data can be used in {@link dropDownList}, {@link listBox}, {@link checkBoxList},
     * {@link radioButtonList}, and their active-versions (such as {@link activeDropDownList}).
     * Note, this method does not XUL-encode the generated data. You may call {@link encodeArray} to
     * encode it if needed.
     * Please refer to the {@link value} method on how to specify value field, text field and group field.
     * @param array $models a list of model objects. Starting from version 1.0.3, this parameter
     * can also be an array of associative arrays (e.g. results of {@link CDbCommand::queryAll}).
     * @param string $valueField the attribute name for list option values
     * @param string $textField the attribute name for list option texts
     * @param string $groupField the attribute name for list option group names. If empty, no group will be generated.
     * @return array the list data that can be used in {@link dropDownList}, {@link listBox}, etc.
     */
    public static function listData($models, $valueField, $textField, $groupField =
        '')
    {
        $listData = array();
        if ($groupField === '') {
            foreach ($models as $model) {
                $value = self::value($model, $valueField);
                $text = self::value($model, $textField);
                $listData[$value] = $text;
            }
        } else {
            foreach ($models as $model) {
                $group = self::value($model, $groupField);
                $value = self::value($model, $valueField);
                $text = self::value($model, $textField);
                $listData[$group][$value] = $text;
            }
        }
        return $listData;
    }

    /**
     * Evaluates the value of the specified attribute for the given model.
     * The attribute name can be given in a dot syntax. For example, if the attribute
     * is "author.firstName", this method will return the value of "$model->author->firstName".
     * A default value (passed as the last parameter) will be returned if the attribute does
     * not exist or is broken in the middle (e.g. $model->author is null).
     * The model can be either an object or an array. If the latter, the attribute is treated
     * as a key of the array. For the example of "author.firstName", if would mean the array value
     * "$model['author']['firstName']".
     * @param mixed $model the model. This can be either an object or an array.
     * @param string $attribute the attribute name (use dot to concatenate multiple attributes)
     * @param mixed $defaultValue the default value to return when the attribute does not exist
     * @return mixed the attribute value
     * @since 1.0.5
     */
    public static function value($model, $attribute, $defaultValue = null)
    {
        foreach (explode('.', $attribute) as $name) {
            if (is_object($model))
                $model = $model->$name;
            else
                if (is_array($model) && isset($model[$name]))
                    $model = $model[$name];
                else
                    return $defaultValue;
        }
        return $model;
    }

    /**
     * Generates a valid XUL ID based on name.
     * @param string $name name from which to generate XUL ID
     * @return string the ID generated based on name.
     */
    public static function getIdByName($name)
    {
        return str_replace(array(
            '[]',
            '][',
            '[',
            ']'), array(
            '',
            '_',
            '_',
            ''), $name);
    }

    /**
     * Generates input field ID for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the generated input field ID
     * @since 1.0.1
     */
    public static function activeId($model, $attribute)
    {
        return self::getIdByName(self::activeName($model, $attribute));
    }

    /**
     * Generates input field name for a model attribute.
     * Unlike {@link resolveName}, this method does NOT modify the attribute name.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the generated input field name
     * @since 1.0.1
     */
    public static function activeName($model, $attribute)
    {
        $a = $attribute; // because the attribute name may be changed by resolveName
        return self::resolveName($model, $a);
    }

    /**
     * Generates an input XUL tag for a model attribute.
     * This method generates an input XUL tag based on the given data model and attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * This enables highlighting the incorrect input.
     * @param string $type the input type (e.g. 'text', 'radio')
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $xulOptions additional XUL attributes for the XUL tag
     * @return string the generated input tag
     */
    protected static function activeInputField($type, $model, $attribute, $xulOptions)
    {
        $xulOptions['type'] = $type;
        if ($type === 'text' || $type === 'password') {
            if (!isset($xulOptions['maxlength'])) {
                foreach ($model->getValidators($attribute) as $validator) {
                    if ($validator instanceof CStringValidator && $validator->max !== null) {
                        $xulOptions['maxlength'] = $validator->max;
                        break;
                    }
                }
            } else
                if ($xulOptions['maxlength'] === false)
                    unset($xulOptions['maxlength']);
        }

        if ($type === 'file')
            unset($xulOptions['value']);
        else
            if (!isset($xulOptions['value']))
                $xulOptions['value'] = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        return self::tag('input', $xulOptions);
    }

    protected static function activeTextbox($type, $model, $attribute, $xulOptions)
    {
        $xulOptions['type'] = $type;
        if ($type === 'text' || $type === 'password') {
            if (!isset($xulOptions['maxlength'])) {
                foreach ($model->getValidators($attribute) as $validator) {
                    if ($validator instanceof CStringValidator && $validator->max !== null) {
                        $xulOptions['maxlength'] = $validator->max;
                        break;
                    }
                }
            } else
                if ($xulOptions['maxlength'] === false)
                    unset($xulOptions['maxlength']);
        }

        if ($type === 'file')
            unset($xulOptions['value']);
        else
            if (!isset($xulOptions['value']))
                $xulOptions['value'] = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($xulOptions);
        return self::xtag('textbox', $xulOptions);
    }
    /**
     * Generates the list options.
     * @param mixed $selection the selected value(s). This can be either a string for single selection or an array for multiple selections.
     * @param array $listData the option data (see {@link listData})
     * @param array $xulOptions additional XUL attributes. The following two special attributes are recognized:
     * <ul>
     * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
     * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty. Note, the prompt text will NOT be XUL-encoded.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be XUL-encoded.</li>
     * <li>options: array, specifies additional attributes for each OPTION tag.
     *     The array keys must be the option values, and the array values are the extra
     *     OPTION tag attributes in the name-value pairs. For example,
     * <pre>
     *     array(
     *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
     *         'value2'=>array('label'=>'value 2'),
     *     );
     * </pre>
     *     This option has been available since version 1.0.3.
     * </li>
     * <li>key: string, specifies the name of key attribute of the selection object(s).
     * This is used when the selection is represented in terms of objects. In this case,
     * the property named by the key option of the objects will be treated as the actual selection value.
     * This option defaults to 'primaryKey', meaning using the 'primaryKey' property value of the objects in the selection.
     * This option has been available since version 1.1.3.</li>
     * </ul>
     * @return string the generated list options
     */
    public static function listOptions($selection, $listData, &$xulOptions)
    {
        $raw = isset($xulOptions['encode']) && !$xulOptions['encode'];
        $content = '';
        if (isset($xulOptions['prompt'])) {
            $content .= '<option value="">' . strtr($xulOptions['prompt'], array('<' =>
                    '&lt;', '>' => '&gt;')) . "</option>\n";
            unset($xulOptions['prompt']);
        }
        if (isset($xulOptions['empty'])) {
            if (!is_array($xulOptions['empty']))
                $xulOptions['empty'] = array('' => $xulOptions['empty']);
            foreach ($xulOptions['empty'] as $value => $label)
                $content .= '<option value="' . self::encode($value) . '">' . strtr($label,
                    array('<' => '&lt;', '>' => '&gt;')) . "</option>\n";
            unset($xulOptions['empty']);
        }

        if (isset($xulOptions['options'])) {
            $options = $xulOptions['options'];
            unset($xulOptions['options']);
        } else
            $options = array();

        $key = isset($xulOptions['key']) ? $xulOptions['key'] : 'primaryKey';
        if (is_array($selection)) {
            foreach ($selection as $i => $item) {
                if (is_object($item))
                    $selection[$i] = $item->$key;
            }
        } else
            if (is_object($selection))
                $selection = $selection->$key;

        foreach ($listData as $key => $value) {
            if (is_array($value)) {
                $content .= '<optgroup label="' . ($raw ? $key : self::encode($key)) . "\">\n";
                $dummy = array('options' => $options);
                if (isset($xulOptions['encode']))
                    $dummy['encode'] = $xulOptions['encode'];
                $content .= self::listOptions($selection, $value, $dummy);
                $content .= '</optgroup>' . "\n";
            } else {
                $attributes = array('value' => (string )$key, 'encode' => !$raw);
                if (!is_array($selection) && !strcmp($key, $selection) || is_array($selection) &&
                    in_array($key, $selection))
                    $attributes['selected'] = 'selected';
                if (isset($options[$key]))
                    $attributes = array_merge($attributes, $options[$key]);
                $content .= self::tag('option', $attributes, $raw ? (string )$value : self::
                    encode((string )$value)) . "\n";
            }
        }

        unset($xulOptions['key']);

        return $content;
    }

    /**
     * Generates the JavaScript with the specified client changes.
     * @param string $event event name (without 'on')
     * @param array $xulOptions XUL attributes which may contain the following special attributes
     * specifying the client change behaviors:
     * <ul>
     * <li>submit: string, specifies the URL that the button should submit to. If empty, the current requested URL will be used.</li>
     * <li>params: array, name-value pairs that should be submitted together with the form. This is only used when 'submit' option is specified.</li>
     * <li>csrf: boolean, whether a CSRF token should be submitted when {@link CHttpRequest::enableCsrfValidation} is true. Defaults to false.
     * This option has been available since version 1.0.7. You may want to set this to be true if there is no enclosing
     * form around this element. This option is meaningful only when 'submit' option is set.</li>
     * <li>return: boolean, the return value of the javascript. Defaults to false, meaning that the execution of
     * javascript would not cause the default behavior of the event. This option has been available since version 1.0.2.</li>
     * <li>confirm: string, specifies the message that should show in a pop-up confirmation dialog.</li>
     * <li>ajax: array, specifies the AJAX options (see {@link ajax}).</li>
     * <li>live: boolean, whether the event handler should be bound in "live" (a jquery event concept). Defaults to true. This option has been available since version 1.1.6.</li>
     * </ul>
     * This parameter has been available since version 1.1.1.
     */
    protected static function clientChange($event, &$xulOptions)
    {
        if (!isset($xulOptions['submit']) && !isset($xulOptions['confirm']) && !isset($xulOptions['ajax']))
            return;

        if (isset($xulOptions['live'])) {
            $live = $xulOptions['live'];
            unset($xulOptions['live']);
        } else
            $live = true;

        if (isset($xulOptions['return']) && $xulOptions['return'])
            $return = 'return true';
        else
            $return = 'return false';

        if (isset($xulOptions['on' . $event])) {
            $handler = trim($xulOptions['on' . $event], ';') . ';';
            unset($xulOptions['on' . $event]);
        } else
            $handler = '';

        if (isset($xulOptions['id']))
            $id = $xulOptions['id'];
        else
            $id = $xulOptions['id'] = isset($xulOptions['name']) ? $xulOptions['name'] :
                self::ID_PREFIX . self::$count++;

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        if (isset($xulOptions['submit'])) {
            $cs->registerCoreScript('yii');
            $request = Yii::app()->getRequest();
            if ($request->enableCsrfValidation && isset($xulOptions['csrf']) && $xulOptions['csrf'])
                $xulOptions['params'][$request->csrfTokenName] = $request->getCsrfToken();
            if (isset($xulOptions['params']))
                $params = CJavaScript::encode($xulOptions['params']);
            else
                $params = '{}';
            if ($xulOptions['submit'] !== '')
                $url = CJavaScript::quote(self::normalizeUrl($xulOptions['submit']));
            else
                $url = '';
            $handler .= "jQuery.yii.submitForm(this,'$url',$params);{$return};";
        }

        if (isset($xulOptions['ajax']))
            $handler .= self::ajax($xulOptions['ajax']) . "{$return};";

        if (isset($xulOptions['confirm'])) {
            $confirm = 'confirm(\'' . CJavaScript::quote($xulOptions['confirm']) . '\')';
            if ($handler !== '')
                $handler = "if($confirm) {" . $handler . "} else return false;";
            else
                $handler = "return $confirm;";
        }

        if ($live)
            $cs->registerScript('Yii.XUL.#' . $id, "jQuery('body').undelegate('#$id','$event').delegate('#$id','$event',function(){{$handler}});");
        else
            $cs->registerScript('Yii.XUL.#' . $id, "jQuery('#$id').$event(function(){{$handler}});");
        unset($xulOptions['params'], $xulOptions['submit'], $xulOptions['ajax'], $xulOptions['confirm'],
            $xulOptions['return'], $xulOptions['csrf']);
    }

    /**
     * Appends {@link errorCss} to the 'class' attribute.
     * @param array $xulOptions XUL options to be modified
     */
    protected static function addErrorCss(&$xulOptions)
    {
        self::extendAttribute($xulOptions, 'class', self::$errorCss);
    }

    /**
     * Renders the XUL tag attributes.
     * Since version 1.1.5, attributes whose value is null will not be rendered.
     * Special attributes, such as 'checked', 'disabled', 'readonly', will be rendered
     * properly based on their corresponding boolean value.
     * @param array $xulOptions attributes to be rendered
     * @return string the rendering result
     * @since 1.0.5
     */
    public static function renderAttributes($xulOptions)
    {
        static $specialAttributes = array(
            'checked' => 1,
            'declare' => 1,
            'defer' => 1,
            'disabled' => 1,
            'ismap' => 1,
            'multiple' => 1,
            'nohref' => 1,
            'noresize' => 1,
            'readonly' => 1,
            'selected' => 1,
            );

        if ($xulOptions === array() || $xulOptions === null)
            return '';

        $XUL = '';
        if (isset($xulOptions['encode'])) {
            $raw = !$xulOptions['encode'];
            unset($xulOptions['encode']);
        } else
            $raw = false;

        if ($raw) {
            foreach ($xulOptions as $name => $value) {
                if (isset($specialAttributes[$name])) {
                    if ($value)
                        $XUL .= ' ' . $name . '="' . $name . '"';
                } else
                    if ($value !== null)
                        $XUL .= ' ' . $name . '="' . $value . '"';
            }
        } else {
            foreach ($xulOptions as $name => $value) {
                if (isset($specialAttributes[$name])) {
                    if ($value)
                        $XUL .= ' ' . $name . '="' . $name . '"';
                } else
                    if ($value !== null)
                        $XUL .= ' ' . $name . '="' . self::encode($value) . '"';
            }
        }
        return $XUL;
    }

    protected static function extendAttribute(&$xulOptions, $attribute, $value)
    {

        $separator = '';

        if (strtolower(substr($attribute, 0, 2)) == 'on')
            $separator = ';';
        if ($attribute == 'class')
            $separator = ' ';

        if (isset($xulOptions[$attribute])) {
            $xulOptions[$attribute] .= $separator . $value;
        } else {
            $xulOptions[$attribute] = $value;
        }

    }
}
