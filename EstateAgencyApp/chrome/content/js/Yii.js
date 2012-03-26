

var Yii = {};

/**
 * Connection function, returns connection Object
 */
Yii.Connection = function(config){
    
    _this = this;
    
    this.inited = []; // semafor
    this.inProgress = 0;
    
    this.config = {
        initUrl : '',
        appPath : 'content/apps/APP'      
    };
    
    jQuery.extend(this.config, config);
    
    this.storeInitFile = function(file, url){
        
        Sys.log('Downloading '+ url);
        
        Sys.ajax({
            url : url,
            success: function(data){
                Sys.file.write(Sys.file.getPathOf('AChrom')+'content\\' +file, data, 0755);
                _this.inited[_this.inProgress++]=true;                
            }
        });
        
    };
    
    this.tryOnReady = function(cb){

            inited = true;
            for(i in _this.inited){
                if(_this.inited[i] == false){
                    inited = false;
                    break;
                }
            }
            if(inited){
                setTimeout(cb, 1000);
            }else{ // try again
                setTimeout(function(){
                    _this.tryOnReady(cb);
                }, 200);
            }
        
    }
    
    return {
      
      /**
        * Initializes application with given config paramaters
        */
      init : function(callback){
       url = _this.config.initUrl;
        Sys.ajax({
           url : url,
           parse: 'json',
           success: function(data){
                _this.config.remote = data;
                _this.config.appPath = Sys.file.normalizeDirName(_this.config.remote.appName);
                
                // init semafors setting
                num = data.files.length;
                _this.inited = new Array(num);
                for(i in _this.inited){
                    _this.inited[i]=false;
                }
                
                for(fileName in data.files){
                    fileUrl = data.files[fileName];
                    _this.storeInitFile(_this.config.appPath + '\\' +fileName, fileUrl);   
                }
                
                
                _this.tryOnReady(callback);
            
           },
           error: function(event){
            window.alert(Sys.log(event,true));
           }
        });
        
      },

      config : _this.config      
        
    }; 
    
    
}


