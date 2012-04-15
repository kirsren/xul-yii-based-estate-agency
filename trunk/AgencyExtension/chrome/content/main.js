

function loadApplication() {
	
	DEBUG = true;
    Sys.debug('Init Yii app');
    
    
    //Sys.file.getFile('c:\\windows\\system32\\calc.exe').launch();
    tbox = document.getElementById("tbox");
	
   
	tbox.value = Sys.file.getPathOf('Desk');
	Sys.file.write(Sys.file.getPathOf('Desk') + "MYFILE", "someeeeeeeeeeeeeeee");

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

