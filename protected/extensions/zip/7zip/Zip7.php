<?php

class Zip7{
    
    var $zipPath = '';
    
    public function __construct(){
        $this->zipPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'7z.exe';   
    }
    
    public function makezip($src, $dest){
       $src .= '*';      
      exec("del $dest");
       exec($this->zipPath." u -tzip $dest $src");
    }
    
}