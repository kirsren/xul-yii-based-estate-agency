


function loadApplication() {

    var url = "http://localhost/szakdoga/yii/index.php?r=estateagency/default/initxul";
    
    var request = Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                  .createInstance(Components.interfaces.nsIXMLHttpRequest);
    request.onload = function(aEvent) {    
      
        var data = jQuery.parseJSON( aEvent.target.responseText );
        sys.log(data);
        jQuery.each(data, function(i, d){

         sys.log(i);

        
//           $.get(data[file], function(page){
//            alert('sdf');
//                writeFile(file, page);
//           });
           
        });
    };
    
    request.onerror = function(aEvent) {
       window.alert("Error Status: " + aEvent.target.status);
    };
    
    request.open("GET", url, true);
    request.send(null)
   
}



/**
 * Startup
 */
window.onload = function(){

    sys.dump('init app');
    
    loadApplication();
    
    sys.store('world', 'hello');
    //alert(sys.store('world'));
    
};

