<?php

/**
 * XulController class
 * @author Zsolt Lengyel <zsolt.lengyel.it@gmail.com>
 */
class XulController extends CController{
  
    public $xulHeader = true;
    
    public $forcePartialRender = false;
    /**
     * @return array of layouts, where key is the name of the action
     */
    public function layouts(){
       return array(); 
    }
    
    /**
     * Id $this->forecePartialRender is true, render partial and returns the content.
     */
    public function render($view, $data = null, $return = false){
        if($this->forcePartialRender){            
            return parent::renderPartial($view, $data, $return);
        }            
        
        return parent::render($view, $data, $return);
    }
  
    /**
    * Adds extra header tells XulRunner and Firefox, its an xul app.
    */
    public function beforeAction(){
        parent::beforeAction('*');
        
        //
        // Set the layout, if specified in layouts()
        //
        $layoutsTmp = $this->layouts();
        
        // preprocess the array
        $layouts = array();
        foreach($layoutsTmp as $actions => $layout){
            if(strpos($actions, ',') > -1 ){
                
                $tmp = explode(',', $actions);
                foreach($tmp as $actionkey){
                    $layouts[trim($actionkey)] = $layout;
                }
                
            }else{
                $layouts[$actions] = $layout; 
            }
        }
        
        // here set the layout 
        if(array_key_exists($this->action->id, $layouts)){
            
            $layout = $layouts[$this->action->id];
            
            // must chack extension            
            if(strpos($layout, '.')){
                $tmp = explode('.', $layout, 2);
                $layout = $tmp[0];                
                if($tmp[1] != 'xul') $this->xulHeader = false;
            }
            if(empty($layout) || is_null($layout)) $this->xulHeader = false;
            
            $this->layout = $layout;
        }
        
        
        if($this->xulHeader)
            header('Content-type: application/vnd.mozilla.xul+xml; charset: UTF-8');
        
        
        return true;
    }
    
}