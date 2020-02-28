@extends('parts.template')

@section('title', 'Homepage')

@section('content')

<div class="main">
    @if(Auth::check())
        <div class="general-title">Cont personal</div>
        <section class="account-section">
            <div class="account-title">Salut, {{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
            <div class="account-desc">Mai jos ai datele contului tau</div>
            <div class="date-personale1">
                <div class="date-item"><div class="name">Nume:</div> {{ Auth::user()->name }}</div>
                <div class="date-item"><div class="name">Prenume:</div> {{ Auth::user()->last_name }}</div>
                <div class="date-item"><div class="name">Email:</div> {{ Auth::user()->email }}</div>
                <div class="date-item"><div class="name">Telefon:</div> {{ Auth::user()->phone }}</div>
                <div class="date-item"><div class="name">Parola:</div> ****** 
            <!--         <div class="general-yellow-button">Modifica parola</div> -->
                </div>
                <div class="general-full-yellow-button modificaDatele">Modifica datele</div>
            </div>
            <div class="date-personale2">
                <form class="general-form-container" action='{{ action("UserController@do_edit_account") }}' method="POST">
                    @csrf
                    <div class="two-on-row">
                    <div class="input-container">
                        <label>Nume:</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="input-container">
                        <label>Prenume:</label>
                        <input type="text" name="last_name" value="{{ Auth::user()->last_name }}">
                    </div>
                    </div>
                    <div class="two-on-row">
                    <div class="input-container">
                        <label>Email:</label>
                        <input type="text" name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="input-container">
                        <label>Nr. telefon:</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}">
                    </div>
                    </div>
                    <div class="two-on-row">
                    <div class="input-container">
                        <label>Parola:</label>
                        <input type="password" name="password" placeholder="******">
                    </div>
                    <div class="input-container">
                        <button class="general-yellow-button btnModificaParola">Modifica parola</button>
                    </div>
                    </div>
                    <div class="input-container">
                    <label class="custom-radio-input" class="smaller">
                        <input type="radio" name="acord">
                        <span class="checkmark"></span>
                        Da, sunt de acord ca datele mele sa fie salvate, conform Politicii de confidentialitate.
                    </label>
                    </div>
                    <button class="general-green-button btnEdit">Salveaza datele</button>
                </form>
            </div>
        </section>
    @else
    <section class="account-section">
        <div class="not-loggedin-info">Creati un cont sau logati-va cu un cont deja existent pentru a va vedea datele personale!</div>
    </section>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
  
  $('.modificaDatele').click(function(){
    $('.date-personale1').slideUp();
    $('.date-personale2').slideDown();
  });
  
  $(".btnEdit").on('click', function(event) {
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
            $(".btnEdit").prop('disabled', false);
          }
      });
      return true;
    });
  
  $(".btnModificaParola").on('click', function(event) {
    event.preventDefault();
   $(this).attr('disabled', 'disabled');
      $.ajax({
          method: 'POST',
          url: '{{ action("UserController@do_edit_password") }}',
          data: $(this).closest('form').serializeArray(),
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
            $(".btnModificaParola").prop('disabled', false);
          }
      });
      return true;
    });
  
});
</script>
@endpush