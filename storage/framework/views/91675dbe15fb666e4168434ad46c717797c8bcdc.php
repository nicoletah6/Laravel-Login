<div class="account-popup register">
  <div class="title">Cont nou</div>
  <div class="desc">Creeaza un cont pentru a face mai rapid o comanda.</div>
  <form class="general-form-container" action='<?php echo e(action("UserController@register")); ?>' method="post">
    <?php echo csrf_field(); ?>
    <div class="input-container">
      <input type="text" name="name" placeholder="Nume">
    </div>
    <div class="input-container">
      <input type="email" name="email" placeholder="Email">
    </div>
    <div class="input-container">
      <input type="text" name="phone" placeholder="Nr. telefon">
    </div>
    <div class="input-container">
      <input type="password" name="password" placeholder="Parola">
    </div>
    <div class="input-container">
      <label class="custom-radio-input" class="smaller">
        <input type="radio" name="acord">
        <span class="checkmark"></span>
        Da, sunt de acord cu termenii si conditiile din Politica de confidentialitate
      </label>
    </div>
    <button class="general-product-button btnRegister" type="submit">Creeaza cont</button>
    <div class="other-button open-login">Am eja cont</div>
  </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  $(document).ready(function() {
    
    $(".btnRegister").on('click', function(event) {
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
              var email_val = $(".account-popup.register").find("input[name='email']").val();
              $(".account-popup.login").find("input[name='email']").val(email_val);
              $(this).parent().trigger('reset');
              $(".account-popup").fadeOut();
              $(".account-popup.login").fadeIn();
          } else { 
            if(Array.isArray(res.msg)) {
                res.msg.forEach(function(item) {
                  $.notify(item, "error");
                });
              } else {
                $.notify(res.msg, "error");
              }
            $(".btnRegister").prop('disabled', false);
          }
      });
      return true;
    });
    
  });
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\project-login\resources\views/parts/register.blade.php ENDPATH**/ ?>