
function loadApplication() {
    
    Sys.dump('init app');

    var url = "http://localhost/szakdoga/yii/index.php?r=estateagency/default/initxul";
    
    var config = {
      initUrl : url  
    };
    
    var yii = new Yii.Connection(config);
    
    onready = function(){
      
      uri = "chrome://myapp/content/"+ yii.config.appPath + '/' + yii.config.remote.mainWindow ;
      Sys.log(uri);
      
      var ww = Components.classes["@mozilla.org/embedcomp/window-watcher;1"]
                   .getService(Components.interfaces.nsIWindowWatcher);
       var win = ww.openWindow(null, uri, yii.config.appName, "chrome,centerscreen", null);
     
     window.close();
    };
    
    yii.init(onready);
    
}



/**
 * Startup
 */
window.onload = loadApplication;

