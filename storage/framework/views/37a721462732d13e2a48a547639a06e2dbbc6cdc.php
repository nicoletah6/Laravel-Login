<div class="general-popup-container logout-confirm">
  <div class="content">
    <img src="images/icon-multiply-gray.png" alt="icon multiply">
    <div class="title">Confirma actiunea</div>
    <div class="desc">Esti sigur ca vrei sa iesi din contul tau? </div>
    <div class="buttons">
      <div class="general-product-button btnLogout">Da</div>
      <div class="general-green-button nu">Nu</div>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  $(document).ready(function() {
    
    $(".btnLogout").click(function() {
      $(this).attr('disabled', 'disabled');
      $.ajax({
          method: 'POST',
          url: '<?php echo e(action("UserController@logout")); ?>',
          context: this, async: true, cache: false, dataType: 'json'
      }).done(function(res) {
          if (res.success == true) {
              $.notify(res.msg, "success");
              setTimeout(function(){
                window.location.reload();
              }, 500);
          } else { 
            $.notify(res.msg, "error");
            $(".btnLogout").prop('disabled', false);
          }
      });
      return true;
   });
    
    $('.general-popup-container.logout-confirm .nu').click(function(){
      $('.general-popup-container.logout-confirm').fadeOut();
    })
    
  });
</script>

<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\project-login\resources\views/parts/logout_confirm.blade.php ENDPATH**/ ?>