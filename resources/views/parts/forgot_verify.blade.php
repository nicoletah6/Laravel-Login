<div class="account-popup forgot-verify">
  <div class="title">Recuperare parola</div>
  <div class="desc">Nicio problema, o sa-ti trimitem un cod pe email pantru a modifica parola.</div>
  <form class="general-form-container" action='{{ action("UserController@forgotPasswordVerify") }}' method="post">
    @csrf
    <div class="input-container">
      <input type="email" name="email" placeholder="Email">
    </div>
    <div class="input-container">
      <input type="text" name="code" placeholder="cod de verificare">
    </div>
    <div class="input-container">
      <input type="password" name="password" placeholder="Parola noua">
    </div>
    <div class="input-container">
      <input type="password" name="password1" placeholder="Repeta parola">
    </div>
    <button class="general-green-button btnForgotVerify" type="submit">Schimba parola</button>
    <div class="other-button open-login">Inapoi la login</div>
  </form>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    
    $(".btnForgotVerify").on('click', function(event) {
      event.preventDefault();
      $(this).attr('disabled', 'disabled');
      $.ajax({
          method: 'POST',
          url: $(this).parent().attr("action"),
          data: $(this).parent().serializeArray(),
          context: this, async: true, cache: false, dataType: 'json'
      }).done(function(res) {
          if (res.success == true) {
              $.notify(res.msg, "success");
              var email_val = $(".account-popup.forgot-verify").find("input[name='email']").val();
              $(".account-popup.login").find("input[name='email']").val(email_val);
              $(this).parent().trigger('reset');
              $(".account-popup").fadeOut();
              $(".account-popup.login").fadeIn();
          } else { 
            $.notify(res.msg, "error");
            $(".btnForgotVerify").prop('disabled', false);
          }
      });
      return true;
    });
    
  });
</script>
@endpush