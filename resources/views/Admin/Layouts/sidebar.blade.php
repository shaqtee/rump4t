    <!-- main-sidebar -->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="{{ route('admin.home') }}">
                {{-- <img src="/images/logo-dgolf3.png" class="main-logo" alt="logo"> --}}
                <div class="h2">RUMP4T</div>
            </a>
            <a class="logo-icon mobile-logo icon-light active" href="{{ route('admin.home') }}">
                {{-- <img src="/images/logo-dgolf3.png" class="logo-icon" alt="logo"> --}}
                <div class="h1">R4</div>
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="app-sidebar__user clearfix">
                <div class="dropdown user-pro-body">
                    <div class="">
                        <img alt="user-img" class="avatar avatar-xl brround" src="{{ Auth::user()->image ?? 'https://placehold.co/400x400?text=No+Image'}}">
                        <span class="avatar-status profile-status bg-green"></span>
                    </div>
                    <div class="user-info">
                        <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                        <span class="mb-0 text-muted">{{ Auth::user()->group->name }}</span>
                    </div>
                </div>
            </div>
            <ul class="side-menu">
                <li class="side-item side-item-category">Menu</li>
                {{-- <li class="slide"> --}}
                    {{-- <a class="side-menu__item"  href="{{ route('users.semua') }}"><i class="mdi mdi-account"></i><span class="side-menu__label">Users</span></a> --}}
                    {{-- <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('users.semua') }}"><span class="sub-side-menu__label">Maintenance</span></a>
                        </li>
                    </ul> --}}
                {{-- </li> --}}
                
                <li class="slide">
                    {{-- <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Event</span><i class="angle fe fe-chevron-down"></i></a> --}}
                    {{-- <ul class="slide-menu"> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.semua') }}"><span class="sub-side-menu__label">List Events</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.registrant.semua') }}"><span class="sub-side-menu__label">Registrant</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.sponsor.semua') }}"><span class="sub-side-menu__label">Sponsor</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.winners.semua') }}"><span class="sub-side-menu__label">Winner Category</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide"> --}}
                            {{-- <a class="sub-side-menu__item" href="{{ route('event.album.semua') }}"><span class="sub-side-menu__label">Gallery</span></a> --}}
                            {{-- <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('event.album.semua') }}">Maintenance</a></li>
                            </ul> --}}
                        {{-- </li> --}}
                    {{-- </ul> --}}
                </li>

                {{-- konten --}}
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Konten</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('informations.index') }}"><span class="sub-side-menu__label">Information</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('elections.index') }}"><span class="sub-side-menu__label">Election</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('postingans.index') }}"><span class="sub-side-menu__label">Posting</span></a>
                        </li> --}}
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('news-admin.index') }}"><span class="sub-side-menu__label">Berita</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('events.admin.index') }}"><span class="sub-side-menu__label">Kegiatan</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('socialmedia.moderation.index') }}"><span class="sub-side-menu__label">Sosmed</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('banner-slide.index') }}"><span class="sub-side-menu__label">Banner Slide</span></a>
                        </li>

                    </ul>
                </li>

                {{-- Komunitas --}}
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Komunitas</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('community.semua') }}"><span class="sub-side-menu__label">List Komunitas</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('community.event.semua') }}"><span class="sub-side-menu__label">Event</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('community.posting.semua') }}"><span class="sub-side-menu__label">Posting</span></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('community.posting.semua') }}">Maintenance</a></li>
                            </ul>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('community.album.semua') }}"><span class="sub-side-menu__label">Album</span></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('community.album.semua') }}">Maintenance</a></li>
                            </ul>
                        </li>
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('community.sponsor.semua') }}"><span class="sub-side-menu__label">Supporting Partner</span></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('community.sponsor.semua') }}">Maintenance</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                </li>

                {{-- Grup --}}
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Grup</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('groups.semua') }}"><span class="sub-side-menu__label">List Grup</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('groups.posting.semua') }}"><span class="sub-side-menu__label">Posting</span></a>
                        </li>
                    </ul>
                </li>

                {{-- Profile --}}
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Profil</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('users.semua') }}"><span class="sub-side-menu__label">Anggota</span></a>
                        </li>
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Activate / Deactivate</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Handicap</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Game Score</span></a>
                        </li> --}}
                    </ul>
                </li>

                @if(auth()->user()->t_group_id != 3)
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('users.admin.semua') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" >
                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/>
                            <path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/>
                        </svg>
                        <span class="side-menu__label">Admin</span>
                    </a>
                </li>
                @endif
                {{-- <li class="slide"> --}}
                    {{-- <a class="side-menu__item" href="{{ route('letsplay.semua') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Let's Play management</span></a> --}}
                    {{-- <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Maintenance</span></a>
                        </li>
                    </ul> --}}
                {{-- </li> --}}

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">Master's</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('golf-course.index') }}"><span class="sub-side-menu__label">Golf Course</span></a>
                        </li> --}}
                        
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('winner-category.index') }}"><span class="sub-side-menu__label">Winner Category</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('rules-score.index') }}"><span class="sub-side-menu__label">Rules Score</span></a>
                        </li> --}}
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('regions.index') }}"><span class="sub-side-menu__label">Regions</span></a>
                        </li>
                    </ul>
                </li>
                
                {{-- MyGolf --}}
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg><span class="side-menu__label">MyGolf</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.semua') }}"><span class="sub-side-menu__label">List Events Golf</span></a>
                        </li> --}}

                        <!-- Play Golf -->
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
                                <span class="sub-side-menu__label">Play Golf</span>
                                <i class="sub-angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('event.semua') }}">Event</a></li>
                                <li><a class="sub-slide-item" href="{{ route('letsplay.semua') }}">Non-Event / Let's Play</a></li>
                            </ul>
                        </li>

                        <!-- Community -->
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
                                Community <i class="sub-angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('community.semua') }}">List Community</a></li>
                            </ul>
                        </li> --}}

                        <!-- Master -->
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
                                Master <i class="sub-angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="{{ route('golf-course.index') }}">Golf Course</a></li>
                                <li><a class="sub-slide-item" href="{{ route('rules-score.index') }}">Rules Score</a></li>
                            </ul>
                        </li>

                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.registrant.semua') }}"><span class="sub-side-menu__label">Registrant</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.sponsor.semua') }}"><span class="sub-side-menu__label">Sponsor</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.winners.semua') }}"><span class="sub-side-menu__label">Winner Category</span></a>
                        </li> --}}
                        {{-- <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('event.album.semua') }}"><span class="sub-side-menu__label">Gallery</span></a>
                        </li> --}}
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('polling.admin') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" >
                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/>
                            <path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/>
                        </svg>
                        <span class="side-menu__label">Polling</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
                        <span class="side-menu__label">Pemilu</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="{{ route('pemilu.semua') }}">
                                <span class="sub-side-menu__label">List Pemilu</span>
                            </a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Kandidat</span></a>
                        </li>
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" href="#"><span class="sub-side-menu__label">Polling</span></a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('donasi.admin') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" >
                            <path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/>
                            <path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/>
                        </svg>
                        <span class="side-menu__label">Donasi</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <!-- main-sidebar -->
