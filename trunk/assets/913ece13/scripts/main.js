
window.onload = function(){

    var contentsrc = $('#content').attr('src');
    
    var menuList = document.getElementById("menu-list");
    var contentDeck = document.getElementById("content-deck");
    
    menuList.onselect = function(event){
        var index = menuList.selectedIndex; 
        contentDeck.selectedIndex = index;
    };
    
    $('#loginLink').click(function(){
        window.openDialog("chrome://content/dialog.xul", "loginDialog");
    });
    
};