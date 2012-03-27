
function loadApplication() {

    Sys.debug('Init Yii app');
    
    Sys.log(Sys.services.runtime());

    var url = "http://localhost/szakdoga/yii/index.php?r=estateagency/default/initxul";
    
    var config = {
      initUrl : url  
    };
    
    var yii = new Yii.Connection(config);
    
    yii.init(function(){yii.startAppAndCloseWin(window);});
    
}



/**
 * Startup
 */
window.onload = loadApplication;

