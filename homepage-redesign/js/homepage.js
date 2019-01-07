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


$(document).ready(function() {

  // Check for click events on the navbar burger icon
  $(".navbar-burger").click(function() {

      // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
      $(".navbar-burger").toggleClass("is-active");
      $(".navbar-menu").toggleClass("is-active");

  });
});