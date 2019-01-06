$(function() {
   $(window).scroll(function () {
      if ($(this).scrollTop() > $(document).height()*0.33) {
         $('body').addClass('changeColor')
      }
      if ($(this).scrollTop() < $(document).height()*0.33) {
         $('body').removeClass('changeColor')
      }
      if ($(this).scrollTop() > $(document).height()*0.74) {
         $('body').addClass('changeColor2')
      }
      if ($(this).scrollTop() < $(document).height()*0.74) {
         $('body').removeClass('changeColor2')
      }
   });
});


