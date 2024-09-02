<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{env('APP_URL')}}" class="brand-link">
        <img src="{{env('APP_URL')."/".asset('logo/logodigitall.png')}}" alt="DigitAll" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">DigitAll</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @if(Auth::user())
                <div class="image">
                    <img src="{{ env('APP_URL')."/".asset('storage/'.Auth::user()->gambaruser)}}" class="img-circle elevation-2" alt="User Image">
                </div>
            @else
                <div class="image">
                    <img src="{{ env('APP_URL')."/".asset('storage/'.'gambaruser/default.jpg')}}" class="img-circle elevation-2" alt="User Image">
                </div>
            @endif
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name?? ""}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" id="menu" data-widget="treeview" role="menu" data-accordion="false">

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

