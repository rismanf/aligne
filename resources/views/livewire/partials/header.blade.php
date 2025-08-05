  <header id="header" class="header d-flex align-items-center sticky-top">
      <div class="container position-relative d-flex align-items-center">

          <a href="{{ route('/') }}" class="logo d-flex align-items-center me-auto">
              <!-- Uncomment the line below if you also wish to use an image logo -->
              <!-- <img src="assets/img/logo.png" alt=""> -->
              <h1 class="sitename">{{ config('app.name') }}</h1><span>.</span>
          </a>

          <nav id="navmenu" class="navmenu">
              <ul>

                  <li><a href="{{ route('/') }}">Home</a></li>
                  <li><a href="{{ route('classes') }}">Class</a></li>
                  <li><a href="{{ route('membership') }}">Membership</a></li>
                  <li><a href="{{ route('contact-us') }}">Contact</a></li>
                  <li>
                      <div style="padding-left: 20px">
                          @guest
                              <form action="{{ route('login') }}" method="GET">
                                  <button type="submit" class="cta-btn">Login</button>
                              </form>
                          @else
                              @hasrole('Admin')
                                  <form action="{{ route('admin.home') }}" method="GET">
                                      @csrf
                                      <button type="submit" class="cta-btn">{{ Auth::user()->name }}</button>
                                  </form>
                              @else
                                  <form action="{{ route('user.profile') }}" method="GET">
                                      @csrf
                                      <button type="submit"
                                          class="cta-btn">{{ Auth::user()->name . Auth::user()->role }}</button>
                                  </form>
                              @endhasrole
                          @endguest
                      </div>
                  </li>

              </ul>
              <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
          </nav>




      </div>
  </header>
