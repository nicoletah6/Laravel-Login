<header class="green-contact-header home">
  <div class="header-content-homepage">
    <div class="left">
      <div class="header-social-icons">
        <a href=""><img src="images/icon-facebook.png" alt="icon facebook"></a>
        <a href=""><img src="images/icon-tripadvisor.png" alt="icon tripadvisor"></a>
        <a href=""><img src="images/icon-instagram.png" alt="icon instagram"></a>
      </div>
    </div>
    <div class="right">
     
      @if(Auth::check())
        <div class="header-right-icon cont open-confirm-logout">
          Logout
        </div>
      @else
        <div class="header-right-icon cont open-login">
          <a><img src="images/icon-profile.png" alt="icon profile"></a>
        </div>
      @endif
    </div>
  </div>

</header>