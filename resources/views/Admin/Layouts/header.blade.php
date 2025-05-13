    <!-- main-content -->
    <div class="main-content app-content">

        <!-- main-header -->
        <div class="main-header sticky side-header nav nav-item">
            <div class="container-fluid">
                <div class="main-header-left ">
                    <div class="app-sidebar__toggle" data-toggle="sidebar">
                        <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left" ></i></a>
                        <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
                    </div>
                    {{-- <a class="new nav-link" href="{{ route('home') }}"><i class="icon ion-ios-home"></i></a> --}}
                    {{-- <div class="main-header-center ml-3 d-sm-none d-md-none d-lg-block">
                        <input class="form-control" placeholder="Search for anything..." type="search"> <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
                    </div> --}}
                </div>
                <div class="main-header-right">
                    <div class="nav nav-item  navbar-nav-right ml-auto">
                        <div class="nav-link" id="bs-example-navbar-collapse-1">
                            <form class="navbar-form" role="search">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <span class="input-group-btn">
                                        <button type="reset" class="btn btn-default">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="submit" class="btn btn-default nav-link resp-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="dropdown nav-item main-header-notification">
                            <a class="new nav-link" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class=" pulse"></span></a>
                            <div class="dropdown-menu">
                                <div class="menu-header-content bg-primary text-left">
                                    <div class="d-flex">
                                        <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Notifications</h6>
                                        <span class="badge badge-pill badge-warning ml-auto my-auto float-right">Mark All Read</span>
                                    </div>
                                    <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread Notifications</p>
                                </div>
                                <div class="main-notification-list Notification-scroll">
                                    <a class="d-flex p-3 border-bottom" href="#">
                                        <div class="notifyimg bg-pink">
                                            <i class="la la-file-alt text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">New files available</h5>
                                            <div class="notification-subtext">10 hour ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                    <a class="d-flex p-3" href="#">
                                        <div class="notifyimg bg-purple">
                                            <i class="la la-gem text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">Updates Available</h5>
                                            <div class="notification-subtext">2 days ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                    <a class="d-flex p-3 border-bottom" href="#">
                                        <div class="notifyimg bg-success">
                                            <i class="la la-shopping-basket text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">New Order Received</h5>
                                            <div class="notification-subtext">1 hour ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                    <a class="d-flex p-3 border-bottom" href="#">
                                        <div class="notifyimg bg-warning">
                                            <i class="la la-envelope-open text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">New review received</h5>
                                            <div class="notification-subtext">1 day ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                    <a class="d-flex p-3 border-bottom" href="#">
                                        <div class="notifyimg bg-danger">
                                            <i class="la la-user-check text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">22 verified registrations</h5>
                                            <div class="notification-subtext">2 hour ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                    <a class="d-flex p-3 border-bottom" href="#">
                                        <div class="notifyimg bg-primary">
                                            <i class="la la-check-circle text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="notification-label mb-1">Project has been approved</h5>
                                            <div class="notification-subtext">4 hour ago</div>
                                        </div>
                                        <div class="ml-auto" >
                                            <i class="las la-angle-right text-right text-muted"></i>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-footer">
                                    <a href="">VIEW ALL</a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="nav-item full-screen fullscreen-button">
                            <a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg></a>
                        </div>
                        <div class="dropdown main-profile-menu nav nav-item nav-link">
                            <a class="profile-user d-flex" data-toggle="dropdown">
                                <img alt="" src="{{ Auth::user()->image ?? 'https://placehold.co/400x400?text=No+Image'}}" style="object-fit:cover;">
                            </a>
                            <div class="dropdown-menu">
                                <div class="main-header-profile bg-primary p-3">
                                    <div class="d-flex wd-100p">
                                        <div class="main-img-user"><img alt="" src="{{ Auth::user()->image ?? 'https://placehold.co/400x400?text=No+Image' }}" class=""></div>
                                        <div class="ml-3 my-auto">
                                            <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->group->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-user-circle"></i>Profile</a> --}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-cog"></i> Edit Profile</a> --}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bxs-inbox"></i>Inbox</a> --}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-envelope"></i>Messages</a> --}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-slider-alt"></i> Account Settings</a> --}}
                                <a class="dropdown-item" href="{{ route('logout') }}"><i class="bx bx-log-out"></i> Sign Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /main-header -->
                <!-- container -->
                <div class="container-fluid">
                    <div class="container mt-3">
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @if (!empty(session('error')['error']))
                                    <strong>Error :</strong> {{ session('error')['error'] }}
                                    <br>
                                    <strong>Code :</strong> {{ session('error')['code'] }}
                                @else
                                    <strong>Error :</strong> {{ session('error') }}
                                @endif
                            </div>
                        @elseif (session('success'))
                        <div class="alert alert-success" role="alert">
                            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success :</strong> {{ session('success') }}
                        </div>
                        @endif
                    </div>