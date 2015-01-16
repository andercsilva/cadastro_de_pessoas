/**
* Arquivo geral de scripts
* @package Cadastro de Pessoas
* @category scripts
* @name scripts
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/
//Alerta
$.showAlert = function(message,alerttype) {
  $('#alertas').append('<div id="alertdiv" class="alert in alert-block fade alert-' +  alerttype + '"><span>'+message+'</span></div>')
  setTimeout(function() {
    $("#alertdiv").remove();
  }, 5000);
}
//Remove o alerta
$(document).on('click','.alert', function(){
	$(this).remove();	
});
//Função para limpar formulários
$.fn.clearForm = function() {
  // iterate each matching form
  return this.each(function() {
 // iterate the elements within the form
 $(':input', this).each(function() {
   var type = this.type, tag = this.tagName.toLowerCase();
   if (type == 'text' || type == 'password' || tag == 'textarea')
  this.value = '';
   else if (type == 'checkbox' )
  this.checked = false;
   else if (tag == 'select')
  this.selectedIndex = 0;
 });
  });
};
//Deixa um elemento centralizado na tela
// Center an element on the screen
(function($){
  $.fn.extend({
    center: function (x,y) {
      // var options =  $.extend({transition:300, minX:0, minY:0}, options);
      return this.each(function() {
                if (x == undefined) {
                    x = true;
                }
                if (y == undefined) {
                    y = true;
                }
                var $this = $(this);
                var $window = $(window);
                $this.css({
                    position: "absolute",
                });
                if (x) {
                    var left = ($window.width() - $this.outerWidth())/2+$window.scrollLeft();
                    $this.css('left',left)
                }
                if (!y == false) {
            var top = ($window.height() - $this.outerHeight())/2+$window.scrollTop();   
                    $this.css('top',top);
                }
        // $(this).animate({
        //   top: (top > options.minY ? top : options.minY)+'px',
        //   left: (left > options.minX ? left : options.minX)+'px'
        // }, options.transition);
        return $(this);
      });
    }
  });
})(jQuery);
