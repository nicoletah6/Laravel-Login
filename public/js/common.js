document.addEventListener("DOMContentLoaded", function() {

    $('.open-confirm-logout').click(function() {
        $(".general-popup-container").fadeOut();
        $(".general-popup-container.logout-confirm").css('display', 'flex');
    });

      // account popups
  $('.open-login').click(function() {
    if($('.account-popup.login').is(":hidden")) {
      $(".account-popup").fadeOut();
      $(".account-popup.login").fadeIn();
    } 
  });
  
  $('.open-register').click(function() {
    $(".account-popup").fadeOut();
    $(".account-popup.register").fadeIn();
  });
  
  $('.open-forgot').click(function() {
    var email_val = $(".account-popup.login").find("input[name='email']").val();
    $(".account-popup.forgot").find("input[name='email']").val(email_val);
    $(".account-popup").fadeOut();
    $(".account-popup.forgot").fadeIn();
  });

  $(document).click(function() {
    $target = $(event.target);
    if(!$target.closest('.account-popup').length && !$target.closest('.open-login').length) {
      $(".account-popup").fadeOut();
    }
  });

});