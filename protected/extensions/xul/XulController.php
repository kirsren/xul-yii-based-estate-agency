<?php

/**
 * XulController class
 * @author Zsolt Lengyel <zsolt.lengyel.it@gmail.com>
 */
class XulController extends CController{

    public $menu=array();
	public $breadcrumbs=array();
  
    /**
     * @return array of layouts, where key is the name of the action
     */
    public function layouts(){
       return array(); 
    }
  
    /**
    * Adds extra header tells XulRunner and Firefox, its an xul app.
    */
    public function beforeAction(){
        parent::beforeAction('*');
        
        $xulHeader = true;  
        
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
                if($tmp[1] != 'xul') $xulHeader = false;
            }
            
            $this->layout = $layout;
        }
        
        if($xulHeader)
            header('Content-type: application/vnd.mozilla.xul+xml; charset: UTF-8');
        
        return true;
    }
    
}