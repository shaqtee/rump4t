        <!-- main-sidebar -->
        <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
        <aside class="app-sidebar sidebar-scroll">
            <div class="main-sidebar-header active">
                <a class="desktop-logo logo-light active" href="{{ route('manage-event.home') }}"><img src="/images/logo-dgolf3.png" class="main-logo" alt="logo"></a>
                <a class="logo-icon mobile-logo icon-light active" href="{{ route('manage-event.home') }}"><img src="/images/logo-dgolf3.png" class="logo-icon" alt="logo"></a>
            </div>
            <div class="main-sidemenu">
                <div class="app-sidebar__user clearfix">
                    <div class="dropdown user-pro-body">
                        <div class="">
                            <img alt="user-img" class="avatar avatar-xl brround" src="{{ Auth::user()->image }}"><span class="avatar-status profile-status bg-green"></span>
                        </div>
                        <div class="user-info">
                            <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                            <span class="mb-0 text-muted">{{ Auth::user()->group->name }}</span>
                        </div>
                    </div>
                </div>
                <ul class="side-menu">
                    <li class="side-item side-item-category">Modules</li>
                    <li class="slide">
                        <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Event</span><i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            <li class="sub-slide">
                                <a class="sub-side-menu__item" href="{{ route('event.manage-event.semua') }}"><span class="sub-side-menu__label">List Events</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- main-sidebar -->