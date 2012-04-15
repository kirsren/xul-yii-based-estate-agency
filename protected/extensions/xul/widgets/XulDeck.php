<?php

class XulDeck extends CWidget{
    
    var $elemenets = array();
    
    var $xulOptions = array();
    
    var $selectedIndex = 0;
    
    var $wrapper = 'box';
    
    var $wrapperOptions = array();
    
    var $id;
    
    private $_wrapperTag;
    
    public function init(){
        parent::init();
        
        $this->xulOptions['id'] = is_null($this->id) ? 'deck_'.Xull::generateID() : $this->id;
        
        $this->xulOptions['selectedIndex'] = $this->selectedIndex;
        
        echo Xul::xopenTag('deck',$this->xulOptions). "\n";
                
        ob_start();
    }
    
    public function run(){        
        parent::run();
        
        if(is_string($this->wrapper) && strlen($this->wrapper)>0){
            foreach($this->elemenets as $element){
                echo $this->wrap($element). "\n";
            }
        }else{
           foreach($this->elemenets as $element){
                echo $element. "\n";
            } 
        }
        
        ob_end_flush();

        echo "\n".Xul::xcloseTag('deck') ."\n";
        
    }
    
    public function beginWrap($tag = null, $xulOptions = array()){
        
        $wrapper = is_null($tag) ? $this->wrapper : $tag;
        $this->_wrapperTag = $wrapper;
        
        $options = empty($xulOptions) ? $this->wrapperOptions : $xulOptions;
        
        echo Xul::xopenTag($wrapper, $options)
        ."\n <!-- START DECK ELEMENT -->\n";        
        ob_start();
        
    }
    
    public function endWrap(){
        
        ob_end_flush();
        echo "\n <!-- END DECK ELEMENT -->\n".
         Xul::xcloseTag($this->_wrapperTag) ."\n";
    }
    
    private function wrap($content){
        return Xul::xtag($this->wrapper, $this->wrapperOptions,$content);
    }
    
}