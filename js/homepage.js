$(function() {
   $(window).scroll(function () {
      if ($(this).scrollTop() > $(document).height()*0.3) {
         $('body').addClass('changeColor')
      }
      if ($(this).scrollTop() < $(document).height()*0.3) {
         $('body').removeClass('changeColor')
      }
      if ($(this).scrollTop() > $(document).height()*0.703) {
         $('body').addClass('changeColor2')
      }
      if ($(this).scrollTop() < $(document).height()*0.703) {
         $('body').removeClass('changeColor2')
      }
   });
});