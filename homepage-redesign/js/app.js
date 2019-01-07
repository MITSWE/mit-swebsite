$(document).ready(function() {
  $('.dropdown-toggle').click(function() {
    $('.dropdown-toggle').toggleClass('isActive');
  });
  var form = $('#ajax-contact');

  //get messages from div
  var formMessages = $('#form-messages');

  //Event listener for contact form
  $(form).submit(function(event){
    event.preventDefault();
  });

  //Serialize form data
  var formData = $(form).serialize();

  //Submit form using AJAX
  $.ajax({
    type: 'POST',
    url: $(form).attr('action'),
    data: formData
  })

  .done(function(response) {
    // Make sure that the formMessages div has the 'success' class.
    $(formMessages).removeClass('error');
    $(formMessages).addClass('success');

    // Set the message text.
    $(formMessages).text(response);

    // Clear the form.
    $('#name').val('');
    $('#email').val('');
    $('#message').val('');
  })

  .fail(function(data) {
    // Make sure that the formMessages div has the 'error' class.
    $(formMessages).removeClass('success');
    $(formMessages).addClass('error');

    // Set the message text.
    if (data.responseText !== '') {
        $(formMessages).text(data.responseText);
    } else {
        $(formMessages).text('Oops! An error occured and your message could not be sent.');
    }
  });
});
  
  // $('.arrow-next').click(function() {
  //   var currentSlide = $('.active-slide');
  //   var nextSlide = currentSlide.next();

  //   var currentDot = $('.active-dot');
  //   var nextDot = currentDot.next();

  //   if(nextSlide.length === 0) {
  //     nextSlide = $('.slide').first();
  //     nextDot = $('.dot').first();
  //   }
    
  //   currentSlide.fadeOut(600).removeClass('active-slide');
  //   nextSlide.fadeIn(600).addClass('active-slide');

  //   currentDot.removeClass('active-dot');
  //   nextDot.addClass('active-dot');
  // });


  // $('.arrow-prev').click(function() {
  //   var currentSlide = $('.active-slide');
  //   var prevSlide = currentSlide.prev();

  //   var currentDot = $('.active-dot');
  //   var prevDot = currentDot.prev();

  //   if(prevSlide.length === 0) {
  //     prevSlide = $('.slide').last();
  //     prevDot = $('.dot').last();
  //   }
    
  //   currentSlide.fadeOut(600).removeClass('active-slide');
  //   prevSlide.fadeIn(600).addClass('active-slide');

  //   currentDot.removeClass('active-dot');
  //   prevDot.addClass('active-dot');
  // });
