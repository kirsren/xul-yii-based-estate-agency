<?php

class XulToolbarButton extends CWidget{
    
    public $accesskey = null;
    
    public $content = false;
    
    public $label = '';
    
    public $xulOptions = array();
    
    public function init(){
        
    }
    
    public function run(){
        
        $xulOptions = $this->xulOptions; // TODO extend options
        if(is_array($xulOptions['url'])){
            $xulOptions['url'] = $xulOptions['url'][0];
        }
        
        echo Xul::xtag('toolbarbutton', $xulOptions, $this->content);    
    }
    
}