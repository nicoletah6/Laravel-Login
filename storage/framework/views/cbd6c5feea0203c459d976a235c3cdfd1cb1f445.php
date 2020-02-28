<div class="account-popup login">
  <div class="title">Login</div>
  <div class="desc">Ai deja un cont? Logheaza-te pentru a nu pierde istoricul tau.</div>
  <form class="general-form-container" action='<?php echo e(action("UserController@login")); ?>' method="post">
    <?php echo csrf_field(); ?>
    <div class="input-container">
      <input type="email" name="email" placeholder="Email">
    </div>
    <div class="input-container">
      <input type="password" name="password" placeholder="parola">
    </div>
    <div class="other-button">
      <a style="cursor: pointer" class="open-forgot">Ai uitat parola? Click aici.</a>
    </div>
    <button class="general-green-button full-width btnLogin" type="submit">Sign In</button>
    <div class="other-button open-register">Nu a inca un cont.</div>
  </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  $(document).ready(function() {
    
    $(".btnLogin").on('click', function(event) {
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
               setTimeout(function () { window.location.reload(); }, 200);
            } else { 
              if(Array.isArray(res.msg)) {
                res.msg.forEach(function(item) {
                  $.notify(item, "error");
                });
              } else {
                $.notify(res.msg, "error");
              }
              $(".btnLogin").prop('disabled', false);
            }
        });
        return true;
      });
   
  });
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\project-login\resources\views/parts/login.blade.php ENDPATH**/ ?>