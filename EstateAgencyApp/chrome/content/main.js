
function db(){


}


function getWebBrowserPrint()
{
return _content.QueryInterface(Components.interfaces.nsIInterfaceRequestor)
.getInterface(Components.interfaces.nsIWebBrowserPrint);
}

function loadApplication() {

    Sys.debug('Init Yii app');
    DEBUG = true;
    
    //Sys.file.getFile('c:\\windows\\system32\\calc.exe').launch();
    
    Sys.log(Sys.services.runtime());

    var url = "http://localhost/szakdoga/yii/index.php?r=estateagency/default/initxul";
    
    var config = {
      initUrl : url  
    };
    
    var yii = new Yii.Connection(config);
    
    yii.run(window);
    
    let prompts =
  Cc["@mozilla.org/embedcomp/prompt-service;1"].
    getService(Ci.nsIPromptService);
}



/**
 * Startup
 */
window.onload = loadApplication;

