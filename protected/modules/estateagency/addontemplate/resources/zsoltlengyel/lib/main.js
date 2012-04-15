// The main module of the zsoltlengyel Add-on.

// Modules needed are `require`d, similar to CommonJS modules.
// In this case, creating a Widget that opens a new tab needs both the
// `widget` and the `tabs` modules.
var Widget = require("widget").Widget;
var notifications = require("notifications");
var Request = require("request").Request;



exports.main = function() {

  //  Components.utils.import("resource://gre/modules/Services.jsm");
  // var permissionManager = Services.perms;     
  // permissionManager.add("http://localhost", ALLOW_REMOTE_XUL, ALLOW);

    // var ww = Components.classes["@mozilla.org/embedcomp/window-watcher;1"].getService(Components.interfaces.nsIWindowWatcher);
    // var win = ww.openWindow(null, "chrome://myapp/content/main.xul", "Application", "chrome,centerscreen, resizable", null);
    
    
   
    notifications.notify({
        title: 'EstateAgency',
        text: "Az EstateAgency program sikeresen települt. A jobb alsó sarokba található ikonnal indíthatja.",
        iconURL: "http://localhost/szakdoga/yii/images/house32.png"
    });
	
    new Widget({
        // Mandatory string used to identify your widget in order to
        // save its location when the user moves it in the browser.
        // This string has to be unique and must not be changed over time.
        id: "zsoltlengyel-widget-1",

        // A required string description of the widget used for
        // accessibility, title bars, and error reporting.
        label: "Agency",


        // An optional string URL to content to load into the widget.
        // This can be local content or remote content, an image or
        // web content. Widgets must have either the content property
        // or the contentURL property set.
        //
        // If the content is an image, it is automatically scaled to
        // be 16x16 pixels.
        contentURL: "http://localhost/szakdoga/yii/images/house.png",

        // Add a function to trigger when the Widget is clicked.
        onClick: function(event) {
            var ww = Components.classes["@mozilla.org/embedcomp/window-watcher;1"].getService(Components.interfaces.nsIWindowWatcher);
			var wine = ww.openWindow(null, "chrome://myapp/content/main.xul", "Application", "chrome,centerscreen, resizable", null);
        }
    });
};
