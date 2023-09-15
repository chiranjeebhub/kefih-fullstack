      <style>
          .navProfile {
              border-radius: 50%;
          }
      </style>
      <nav class="navbar navbar-inverse">
          <div class="container">
          <div class="navbar-header"><button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand hidden-sm hidden-md hidden-lg" href=" {{ route('index') }} "><img src="{{ asset('public/fronted/images/logo.png') }}" /></a></div>
          
            <div class="logo scrolldownlogo">
                <a href=" {{ route('index') }} ">
                <img src="{{ asset('public/fronted/images/logo.png') }}" alt="">
                </a>
            </div>
          
          
          <div class="collapse navbar-collapse js-navbar-collapse">
              <ul class="nav navbar-nav">{!! App\Category::getNavLinks()!!}
                  <!--<li class="dropdown mega-dropdown"><a href="{{ route('customized') }}" class="rootCat">Customized</a></li>-->

              </ul>
          </div>
          <!-- /.nav-collapse -->
          </div>
      </nav>