/**
 * Other, used function
 */
 
String.prototype.repeat = function( num )
{
    return new Array( num + 1 ).join( this );
}

String.prototype.lastSplittedBy = function(separator){
    
    array = this.split(separator);
    return array[array.length-1];
    
}

String.prototype.getWithoutLast = function(separator){
    array = this.split(separator);
    var str = '';
    for(i=0; i<array.length-1; i++){
        str += array[i] + separator;
    }
    return str;
}

// Wrapper object
var Sys = {};

// dump width break
Sys.dump = function(data){
  dump(data);
  dump('\n');  
};

// intelligent logger
// if isReturn is true, returns the merged string
Sys.log = function(obj, isReturn, depth){
    if(!this.consoleService)
      this.consoleService = Components.classes["@mozilla.org/consoleservice;1"].
             getService(Components.interfaces.nsIConsoleService);
    
    var message = obj;
    var _depth = typeof depth == 'undefined' ? 1 : depth;;
    
    if(typeof obj == 'object'){
        message = '[Object]';
        
        jQuery.each(obj, function(i, e){
            message += '\n'+ '  '.repeat(_depth) + i + ' : ' + Sys.log(e, true, _depth+1);
        });
    }
    if(typeof obj == 'function'){
        message = '[Function]';        
    }
    if(typeof obj == 'string'){
        message = '[String:'+ obj.length +'] '+ obj;
    }
    
    if(isReturn) return message;
    
    this.consoleService.logStringMessage(message);

};

// data storage
Sys.store = function(key, value){
    if(!value){
        return jQuery.data(window, key);
    }
    return jQuery.data(window, key, value);
};



// simle ajax query
Sys.ajax = function(conf){
    
    
    var config = {
        url : "",
        error : function(){},
        success: function(){},
        method: 'GET',
        parse : false,        
    };
    
    jQuery.extend(config, conf);

   if(config.url == '') return;
    
    request = Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                  .createInstance(Components.interfaces.nsIXMLHttpRequest);
                  
    request.onload = function(event) {    
        var data = event.target.responseText;
        if(config.parse == 'json')        
            var data = jQuery.parseJSON( data );
        
        config.success(data);

    };
    
    request.onerror = function(aEvent) {
       config.error(aEvent);
    };    
    
    request.open(config.method, config.url, true);
    request.send(null);
 
};

//
// File section
//
Sys.file = {};

Sys.file.normalizeDirName = function(dir){
    return dir.replace(/[^a-zA-Z0-9\._]/i, '_');
};

Sys.file.getPathOf = function(xulPath){
    var ds= Components.classes["@mozilla.org/file/directory_service;1"].  
           getService(Components.interfaces.nsIProperties).  
           get(xulPath, Components.interfaces.nsIFile);
    return ds.path +'\\';
};

// Mkdir, if path is undefined, trys recursive create dir
Sys.file.mkdir = function(dir, path){
   
    /*var file = Components.classes['@mozilla.org/file/directory_service;1'].
               getService(Components.interfaces.nsIProperties).
               get(path, Components.interfaces.nsIFile);
    */
    var file = Components.classes["@mozilla.org/file/local;1"].  
           createInstance(Components.interfaces.nsILocalFile);

    file.initWithPath(Sys.file.getPathOf("AChrom"));
    
    file.append(dir);
    if( !file.exists() || !file.isDirectory() ) {   // if it doesn't exist, create
       file.create(Components.interfaces.nsIFile.DIRECTORY_TYPE, 0777);
    }
};

// file writing
Sys.file.write = function (path, data, chmod){
    
    var file = Components.classes["@mozilla.org/file/local;1"].  
           createInstance(Components.interfaces.nsILocalFile);

    file.initWithPath(path);
    if( !file.exists() || !file.isDirectory() || !file.isFile() ) {   // if it doesn't exist, create
       try{
            file.create(Components.interfaces.nsIFile.FILE_TYPE, chmod ? chmod : 0777);
        }catch(e){
            Sys.dump(e);
        }
    }
     
    var outputStream = Components.classes["@mozilla.org/network/file-output-stream;1"]
        .createInstance( Components.interfaces.nsIFileOutputStream );
    outputStream.init( file, 0x04 | 0x08 | 0x20, 420, 0 );
    var result = outputStream.write( data, data.length );
    outputStream.close();
    
};


