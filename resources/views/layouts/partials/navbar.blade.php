<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('home')}}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif

            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item dropdown user user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-user"></i>
                    <span class="hidden-xs">{{ Auth::user()->name ?? "" }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="user-header">
                        <img src="{{ Auth::user()->gambaruser ? asset('storage/'.Auth::user()->gambaruser) : asset('storage/gambaruser/default.jpg') }}" class="user-image" alt="User Image">
                        <p>{{ Auth::user()->name ?? "" }}</p>
                    </li>
                    <li class="user-body">
                        <div class="row">
                            <div class="col text-center">
                                <p>{{$uraianbagian ?? "Unit Belum Didefinisikan"}}</p>
                            </div>
                            <div class="col text-center">
                                <p>{{$datarole ?? "Role Belum Didefinisikan"}}</p>
                            </div>
                        </div>
                    </li>
                    <li class="user-footer">
                        <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
        @endguest
    </ul>
</nav>
<!-- /.navbar -->
