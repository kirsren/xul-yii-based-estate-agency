
function db(){


}

function loadApplication() {

    Sys.debug('Init Yii app');
    
    //Sys.file.getFile('c:\\windows\\system32\\calc.exe').launch();
    
    Sys.log(Sys.services.runtime());

    var url = "http://localhost/szakdoga/yii/index.php?r=estateagency/default/initxul";
    
    var config = {
      initUrl : url  
    };
    
    var yii = new Yii.Connection(config);
    
    yii.run(window);
    
    db();
    
}



/**
 * Startup
 */
window.onload = loadApplication;

