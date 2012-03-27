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
};

// Wrapper object
var Sys = {};

// dump width break
Sys.dump = function(data){
  dump(data);
  dump('\n');  
};

// Services factory
Sys.services = (function(){
    
    var ServiceFactory = function(){ 
         var services = {};  
    
        return {
          
          console : function(){
              services.console = services.console || Components.classes["@mozilla.org/consoleservice;1"]
                .getService(Components.interfaces.nsIConsoleService);
                
              return services.console;
          },
          
          request : function(){
            return Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                  .createInstance(Components.interfaces.nsIXMLHttpRequest);
          },
          
          directory : function(){
            services.directory = services.directory || Components.classes["@mozilla.org/file/directory_service;1"].  
                getService(Components.interfaces.nsIProperties);
            
            return services.directory;
           },
           
          localfile : function(){
            return Components.classes["@mozilla.org/file/local;1"].  
                createInstance(Components.interfaces.nsILocalFile);
          },
          
          fileOutputStream : function(){
            return Components.classes["@mozilla.org/network/file-output-stream;1"]
                 .createInstance( Components.interfaces.nsIFileOutputStream );
          },
          
          os : function(){
            services.os = services.os || Components.classes["@mozilla.org/xre/app-info;1"]  
               .getService(Components.interfaces.nsIXULRuntime).OS;
            
            return services.os; 
          },
          
          runtime : function(){
            services.runtime = services.runtime || Components.classes["@mozilla.org/xre/app-info;1"]
                 .getService(Components.interfaces.nsIXULRuntime);
                 
            return services.runtime;
          },
          
          window : function(){
            services.window = services.window || Components.classes["@mozilla.org/embedcomp/window-watcher;1"]
                       .getService(Components.interfaces.nsIWindowWatcher);
            
            return services.window;
          }
            
        };
    };
    
    return new ServiceFactory(); 
    
})();

// Operating system detection
Sys.isWindows = function(){ return ( Sys.services.os().indexOf('WINNT') > -1 ); };
Sys.isLinux =  function(){ return ( Sys.services.os().indexOf('Linux') > -1 ); };
Sys.isMac =  function(){ return ( Sys.services.os().indexOf('Darwin') > -1 ); };

// CONSTATNTS, hangs on services
var DS = Sys.isWindows ? '\\' : '/'; // Directory separator 

// intelligent logger
// if isReturn is true, returns the merged string
Sys.log = function(obj, isReturn, depth){
    
    var consoleService = Sys.services.console();
    
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
    
    consoleService.logStringMessage(message);

};

Sys.debug = function(string){
    
    if(DEBUG){
        var console = Sys.services.console();
        console.logStringMessage('[DEBUG] ' + string);
    }
    
}

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
    
    request = Sys.services.request();
                  
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
    return Sys.services.directory().get(xulPath, Components.interfaces.nsIFile).path + DS;
};

Sys.file.touch = function(path, chmod){
    var file = Sys.services.localfile();

    file.initWithPath(path);
    if( !file.exists() || !file.isFile() ) {   // if it doesn't exist, create
       try{
            file.create(Components.interfaces.nsIFile.FILE_TYPE, chmod ? chmod : 0777);
        }catch(e){
            Sys.dump(e);
        }
    }
    return file;
}

// Mkdir, if path is undefined, trys recursive create dir
Sys.file.mkdir = function(dir, path, chmod){
    
    Sys.debug('Try make dir: ' + dir + ', here : ' + path);
    
    var file = Sys.services.localfile();
    file.initWithPath(path);
    file.append(dir);    
    if(!file.isDirectory() ) {   // if it doesn't exist, create
       file.create(Components.interfaces.nsIFile.DIRECTORY_TYPE, chmod ? chmod : 0755);
    }
};

// file writing
Sys.file.write = function (path, data, chmod){

    Sys.debug('Try write file : ' + path);

    var file = Sys.services.localfile();

    file.initWithPath(path);
    if( !file.exists() || !file.isFile() ) {   // if it doesn't exist, create
       try{
            file.create(Components.interfaces.nsIFile.FILE_TYPE, chmod ? chmod : 0777);
        }catch(e){
            Sys.dump(e);
        }
    }
    
    var outputStream = Sys.services.fileOutputStream();
    outputStream.init( file, 0x04 | 0x08 | 0x20, 420, 0 );
    var result = outputStream.write( data, data.length );
    outputStream.close();
    
};


