
<aside class="main-sidebar sidebar-light-danger elevation-4" style="background-color: #ebe1e1;height:2200px" >
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="{{ asset('assets/images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light" style="color:#000000"><b>AMM</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="color:#000000">{{Session::get('full_name')}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div><div class="sidebar-search-results"><div class="list-group"><a href="#" class="list-group-item"><div class="search-title"><strong class="text-light"></strong>N<strong class="text-light"></strong>o<strong class="text-light"></strong> <strong class="text-light"></strong>e<strong class="text-light"></strong>l<strong class="text-light"></strong>e<strong class="text-light"></strong>m<strong class="text-light"></strong>e<strong class="text-light"></strong>n<strong class="text-light"></strong>t<strong class="text-light"></strong> <strong class="text-light"></strong>f<strong class="text-light"></strong>o<strong class="text-light"></strong>u<strong class="text-light"></strong>n<strong class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong></div><div class="search-path"></div></a></div></div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="/" class="nav-link {{ $title == 'Dashboard' ? 'active' : '' }}">
              <i class="nav-icon fas fa-home" ></i>
              <p >
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/asset" class="nav-link  {{ $title == 'Asset' ? 'active' : '' }}">
            <i class="nav-icon fas fa fa-box-archive" ></i>
              <p >
                Asset
              </p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="/tinjauan_asset" class="nav-link  {{ $title == 'Berita Acara Tinjauan Asset' ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-text" ></i>
              <p >
                BA Tinjauan Asset
              </p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="/disposal_asset" class="nav-link  {{ $title == 'Disposal Asset' ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-text" ></i>
              <p >
                Disposal Asset
              </p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="/mutation_asset" class="nav-link  {{ $title == 'Mutasi Asset' ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-text" ></i>
              <p >
                Mutation Asset
              </p>
            </a>
          </li>  
          {{-- <li class="nav-item">
            <a href="/berita_acara" class="nav-link  {{ $title == 'Berita Acara' ? 'active' : '' }}">
            <i class="nav-icon fas fa-id-card-alt" ></i>
              <p >
                Berita Acara
              </p>
            </a>
          </li>   --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>