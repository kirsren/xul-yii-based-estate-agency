
window.onload = function(){

    var contentsrc = $('#content').attr('src');
    
    var tomb = $('#menu-list').children();
  
    $('#menu-list').click(function(){
      $('#content').attr("src", $(this.getSelectedItem(0)).attr('value'));
    });
    
};