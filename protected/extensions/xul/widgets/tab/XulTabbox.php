<?php

/**
 * Tabbox generator widget
 * 
 * Usages:
 * <code>
 * $this->widget('ext.xul.widgets.tab.XulTabbox',array(
 *      'xulOptions'=>array('id'=>'tab'),
 *      'tabs'=>array(
 *          array(
 *              'data'=>$data,
 *              'file'=>
 *          ),
 *          'content'
 *      )
 * ));
 * 
 * </code>
 */
class XulTabbox extends CWidget
{

    public $tabs = array();

    public $xulOptions = array();

    public function init()
    {
        parent::init();
    }

    public function run()
    {

        echo Xul::xopenTag('tabbox', $this->xulOptions);
        if (count($this->tabs) > 0) {

            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';

            echo "\n".Xul::xopenTag('tabs')."\n";

            foreach (array_keys($this->tabs) as $label) {
                echo Xul::xtag('tab', array('label' => $label));
            }

            echo "\n".Xul::xcloseTag('tabs')."\n";

            echo "\n".Xul::xopenTag('tabpanels')."\n";
            
            $i = 0;
            foreach ($this->tabs as $tab) {
                echo Xul::xopenTag('tabpanel')."\n";
                if (is_string($tab)) {
                    echo $tab;
                } else
                    if (is_array($tab) && array_key_exists('file', $tab)) { // file to render                    
                        $data = array_key_exists('data', $tab) ? $tab['data'] : array();

                        $data['index'] = $i++;
                        $data['data'] = $item;
                        $data['widget'] = $this;

                        $owner->$render($tab['file'], $data);
                    } else {
                        throw new XulException("Incorrect format of tab.\n" . CVarDumper::dumpAsString($tab));
                    }

                    echo "\n".Xul::xcloseTag('tabpanel')."\n";                
            }
            echo "\n".Xul::xcloseTag('tabpanels')."\n";

        }
        echo "\n".Xul::xcloseTag('tabbox')."\n";

    }

}
