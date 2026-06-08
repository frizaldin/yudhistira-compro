<!-- leftbar-tab-menu -->
<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="{{ url('/') }}" class="logo">
            <span>
                <img src="{{ asset('/logo.png') }}" style="max-width: 100%;height:auto;" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="{{ asset('/logo.png') }}" style="max-width: 100%;height:auto;" alt="logo-large"
                    class="logo-lg logo-light">
                <img src="{{ asset('/logo.png') }}" style="max-width: 100%;height:auto;" alt="logo-large"
                    class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100" id="sidebar-menu" style="display:none">
                    <li class="menu-label mt-2">
                        <span>Main</span>
                    </li>

                    <x-backend.nav.sidebar.menu url="{{ url('backend/dashboard') }}" key="dashboard" :authority="$authority"
                        :badge="isset($badge_dashboard) ? $badge_dashboard : 0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/slider') }}" key="slider" :authority="$authority"
                        :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/about') }}" key="about" :authority="$authority"
                        :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/counter') }}" key="counter" :authority="$authority"
                        :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/digital-product') }}" key="digital_product"
                        :authority="$authority" :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/timeline') }}" key="timeline" :authority="$authority"
                        :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/branches') }}" key="branches" :authority="$authority"
                        :badge="0" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-book" :menu="[
                        ['url' => url('backend/service'), 'key' => 'service', 'icon' => 'iconoir-book'],
                        ['url' => url('backend/product'), 'key' => 'product', 'icon' => 'iconoir-box'],
                        ['url' => url('backend/category'), 'key' => 'category', 'icon' => 'iconoir-folder'],
                        ['url' => url('backend/subcategory'), 'key' => 'subcategory', 'icon' => 'iconoir-folder-minus'],
                        [
                            'url' => url('backend/sample-product'),
                            'key' => 'sample_product',
                            'icon' => 'iconoir-folder-minus',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Service & Product" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        ['url' => url('backend/catalogue'), 'key' => 'catalogue', 'icon' => 'iconoir-calendar'],
                        [
                            'url' => url('backend/category-catalogue'),
                            'key' => 'category_catalogue',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Katalog" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        ['url' => url('backend/blog'), 'key' => 'blog', 'icon' => 'iconoir-calendar'],
                        ['url' => url('backend/category-blog'), 'key' => 'category_blog', 'icon' => 'iconoir-folder'],
                    ]" :authority="$authority"
                        titlegroup="Blog" />


                    {{-- Teacher HUB --}}

                    <x-backend.nav.sidebar.menu url="{{ url('backend/serial-code-ebook') }}" key="serial-code-ebook"
                        :authority="$authority" :badge="0" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/serial-code-member') }}" key="serial-code-member"
                        :authority="$authority" :badge="0" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        [
                            'url' => url('backend/blog-teacher-hubs'),
                            'key' => 'blog_teacher_hubs',
                            'icon' => 'iconoir-calendar',
                        ],
                        [
                            'url' => url('backend/category-teacher-hubs'),
                            'key' => 'category_teacher_hubs',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Artikel Guru" />
                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        [
                            'url' => url('backend/announcement-teacher-hubs'),
                            'key' => 'announcement_teacher_hubs',
                            'icon' => 'iconoir-calendar',
                        ],
                        [
                            'url' => url('backend/category-announcement-teacher-hubs'),
                            'key' => 'category_announcement_teacher_hubs',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Pengumuman Guru" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        [
                            'url' => url('backend/event-teacher-hubs'),
                            'key' => 'event_teacher_hubs',
                            'icon' => 'iconoir-calendar',
                        ],
                        [
                            'url' => url('backend/category-event-teacher-hubs'),
                            'key' => 'category_event_teacher_hubs',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Event Guru" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-medal" :menu="[
                        [
                            'url' => url('backend/teacher-rewards'),
                            'key' => 'teacher_reward',
                            'icon' => 'iconoir-medal',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Reward Guru" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-book" :menu="[
                        [
                            'url' => url('backend/digital-learning-supports'),
                            'key' => 'digital_learning_support',
                            'icon' => 'iconoir-book',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Digital Learning Support" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-chat-bubble" :menu="[
                        [
                            'url' => url('backend/support-centers'),
                            'key' => 'support_centers',
                            'icon' => 'iconoir-chat-bubble',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Support Center" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-chat-bubble" :menu="[
                        [
                            'url' => url('backend/open-tickets'),
                            'key' => 'open_tickets',
                            'icon' => 'iconoir-chat-bubble',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Open Ticket" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-book" :menu="[
                        [
                            'url' => url('backend/request-books'),
                            'key' => 'request_books',
                            'icon' => 'iconoir-book',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Request Buku Digital" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-book-stack" :menu="[
                        [
                            'url' => url('backend/buku-buy'),
                            'key' => 'buku_buy',
                            'icon' => 'iconoir-book-stack',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Katalog Buku BuyBuku" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-folder-plus" :menu="[
                        [
                            'url' => url('backend/creative-teachers'),
                            'key' => 'creative_teachers',
                            'icon' => 'iconoir-folder-plus',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Creative Teacher" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-play" :menu="[
                        [
                            'url' => url('backend/video-learnings'),
                            'key' => 'video_learnings',
                            'icon' => 'iconoir-play',
                        ],
                        [
                            'url' => url('backend/category-video-learnings'),
                            'key' => 'category_video_learning',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Video Learning" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-play" :menu="[
                        [
                            'url' => url('backend/tutorial-videos'),
                            'key' => 'tutorial_videos',
                            'icon' => 'iconoir-play',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Tutorial Video" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-book" :menu="[
                        [
                            'url' => url('backend/guide-books'),
                            'key' => 'guide_book',
                            'icon' => 'iconoir-book',
                        ],
                        [
                            'url' => url('backend/category-guide-books'),
                            'key' => 'category_guide_book',
                            'icon' => 'iconoir-folder',
                        ],
                    ]" :authority="$authority"
                        titlegroup="Buku Panduan" />

                    <x-backend.nav.sidebar.menudropdown icon="iconoir-calendar" :menu="[
                        ['url' => url('backend/events'), 'key' => 'events', 'icon' => 'iconoir-calendar'],
                        ['url' => url('backend/category-event'), 'key' => 'category_event', 'icon' => 'iconoir-folder'],
                    ]" :authority="$authority"
                        titlegroup="Event" />

                    <x-backend.nav.sidebar.menu url="{{ url('backend/teachers') }}" key="teachers" icon="iconoir-user"
                        :authority="$authority" :badge="0" />
                    <x-backend.nav.sidebar.menudropdown icon="iconoir-group" :menu="[
                        ['url' => url('/user/superadmin'), 'key' => 'user.superadmin', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/supplier'), 'key' => 'user.supplier', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/logistik'), 'key' => 'user.logistik', 'icon' => 'iconoir-user'],
                        // ['url' => url('/user/pelaksana'), 'key' => 'user.pelaksana', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/keuangan'), 'key' => 'user.keuangan', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/perencanaan'), 'key' => 'user.perencanaan', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/direksi'), 'key' => 'user.direksi', 'icon' => 'iconoir-user'],
                        ['url' => url('/user/customer'), 'key' => 'user.customer', 'icon' => 'iconoir-user'],
                    ]" :authority="$authority"
                        titlegroup="Manajemen User" />

                    @if (auth()->user()->auhority_id == 1)
                        <li class="menu-label mt-2">
                            <span>Setting</span>
                        </li>
                    @endif

                    <x-backend.nav.sidebar.menu url="{{ url('backend/social_media') }}" key="social_media"
                        icon="iconoir-settings" :authority="$authority" />
                    <x-backend.nav.sidebar.menu url="{{ url('backend/configuration') }}" key="configuration"
                        icon="iconoir-settings" :authority="$authority" />
                    <x-backend.nav.sidebar.menu url="{{ url('profile') }}" key="profile" icon="iconoir-user"
                        :authority="$authority" />
                    <x-backend.nav.sidebar.menu url="{{ url('logout') }}" key="logout" icon="iconoir-log-out"
                        :authority="$authority" />

                </ul><!--end navbar-nav--->

                <l-hatch id="loader-sidebar" size="50" stroke="4" speed="3.5" color="white"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </l-hatch>

            </div>
        </div><!--end startbar-collapse-->
    </div><!--end startbar-menu-->
</div><!--end startbar-->
