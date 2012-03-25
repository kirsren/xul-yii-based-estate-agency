

var Yii = {};

/**
 * Connection function, returns connection Object
 */
Yii.Connection = function(config){
    
    _this = this;
    
    this.config = {
        initUrl : '',
        appName : 'APP',
        appPath : 'content/apps/APP'
        
    };
    
    jQuery.extend(this.config, config);
    
    this.storeInitFile = function(file, url){
        
        Sys.ajax({
            url : url,
            success: function(data){
                Sys.writeFile(file, data, 0755)
            }
        });
        
    };
    
    return {
      
      /**
        * Initializes application with given config paramaters
        */
      init : function(){
       url = _this.config.initUrl;
        Sys.ajax({
           url : url,
           parse: 'json',
           success: function(data){
                _this.config.remote = data;
                _this.config.appPath = 'content/apps/' + Sys.file.normalizeDirName(_this.config.remote.appName)+'/';
                
                Sys.file.mkdir(_this.config.appPath);
                
                jQuery.each(data.files, function(fileName, fileUrl){
                    _this.storeInitFile(_this.config.appPath+fileName, fileUrl);   
                });
            
           },
           error: function(event){
            window.alert(Sys.log(event,true));
           }
        });
        
      },
      
      /**
       * Sets/gets config key.
       * set: connection.config('key', 'value');
       * get: value = connection.config('key');
       */
      config : function(key, value){
        if(typeof value == 'undefinded'){ // get
            return _this.config[key];
        }else{
            var old = _this.config[key];
            _this.config[key] = value;
            return old
        }
      }
      
        
    }; 
    
    
}


