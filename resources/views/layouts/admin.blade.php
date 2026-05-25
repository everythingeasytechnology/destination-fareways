<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') | Destination Fareways</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6.4 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
    <!-- DataTables CSS (Bootstrap 5) -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom Style Sheet -->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar Section -->
    <aside id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <i class="fa-solid fa-plane-departure text-warning me-2 fs-4"></i>
                <span class="display-font text-white fs-5 fw-bold tracking-wide">Fareways</span>
            </a>
            <button class="btn btn-sm text-white d-lg-none" id="sidebar-close-btn">
                <i class="fa-solid fa-xmark fs-5"></i>
            </button>
        </div>

        <div class="py-2 overflow-y-auto" style="height: calc(100vh - 70px);">
            <!-- Dashboard Group -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <!-- Communications Group -->
            <div class="nav-group-title">Inquiries & Leads</div>
            <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-line"></i>
                <span>Leads Management</span>
            </a>
            <a href="{{ route('admin.enquiries.index') }}" class="nav-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i>
                <span>Flight Enquiries</span>
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-open-text"></i>
                <span>Contact Messages</span>
            </a>
            <a href="{{ route('admin.newsletter.index') }}" class="nav-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Subscribers</span>
            </a>

            <!-- CMS Content Group -->
            <div class="nav-group-title">CMS Management</div>
            <a href="{{ route('admin.offers.index') }}" class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Flight Offers</span>
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i>
                <span>Travel Blogs</span>
            </a>
            <a href="{{ route('admin.destinations.index') }}" class="nav-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-earth-americas"></i>
                <span>Destinations</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Pages Manager</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-question"></i>
                <span>FAQs</span>
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="fa-solid fa-star-half-stroke"></i>
                <span>Testimonials</span>
            </a>

            <!-- SEO & Schema Group -->
            <div class="nav-group-title">SEO & Metadata</div>
            <a href="{{ route('admin.seo.index') }}" class="nav-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
                <span>SEO Settings</span>
            </a>
            <a href="{{ route('admin.schema.index') }}" class="nav-link {{ request()->routeIs('admin.schema.*') ? 'active' : '' }}">
                <i class="fa-solid fa-code"></i>
                <span>Schema Markups</span>
            </a>

            <!-- Configuration Group -->
            <div class="nav-group-title">Settings & System</div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i>
                <span>Site Settings</span>
            </a>
            <a href="{{ route('admin.call-settings.index') }}" class="nav-link {{ request()->routeIs('admin.call-settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-phone-volume"></i>
                <span>Call Buttons</span>
            </a>
            <a href="{{ route('admin.api-settings.index') }}" class="nav-link {{ request()->routeIs('admin.api-settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-network-wired"></i>
                <span>API Configuration</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Users & Roles</span>
            </a>
            
            <div class="px-3 py-4 mt-2">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Section -->
    <div id="main-content">
        <header id="topbar" class="d-flex align-items-center justify-content-between px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-light border-0 me-3 d-lg-none" id="sidebar-toggle-btn">
                    <i class="fa-solid fa-bars fs-5 text-navy"></i>
                </button>
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="https://destinationfareways.com" target="_blank" class="btn btn-sm btn-outline-secondary d-none d-sm-flex align-items-center" title="Visit Live Site">
                    <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Live Website
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-light border-0 position-relative p-2" type="button" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-bell fs-5 text-navy"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 mt-2" style="width: 280px;">
                        <li class="px-3 py-2 border-bottom"><h6 class="mb-0 fw-bold">Recent Notifications</h6></li>
                        <li><a class="dropdown-item py-2 px-3 border-bottom" href="{{ route('admin.leads.index') }}"><i class="fa-solid fa-users-line text-primary me-2"></i> New lead received!</a></li>
                        <li><a class="dropdown-item py-2 px-3 border-bottom" href="{{ route('admin.enquiries.index') }}"><i class="fa-solid fa-ticket text-warning me-2"></i> Flight enquiry submitted</a></li>
                        <li><a class="dropdown-item py-2 px-3 text-center text-muted" href="{{ route('admin.dashboard') }}"><small>View all activities</small></a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-1 border-0" type="button" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user() && Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y' }}" alt="Avatar" class="rounded-circle shadow-sm" width="38" height="38">
                        <div class="text-start d-none d-md-block">
                            <h6 class="mb-0 text-navy fw-bold" style="font-size: 0.9rem;">{{ Auth::user() ? Auth::user()->name : 'Admin Profile' }}</h6>
                            <span class="mono-badge text-uppercase text-warning bg-navy px-2 py-0.5 rounded-pill">{{ Auth::user() ? Auth::user()->role : 'superadmin' }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item py-2 px-3" href="{{ route('admin.users.edit', Auth::id() ?? 1) }}"><i class="fa-solid fa-user-gear me-2 text-muted"></i> Account Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 px-3 text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="container-fluid p-4 flex-grow-1">
            <!-- Toast notification container -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;">
                @if (session('success'))
                    <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center">
                                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center">
                                <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                                {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        <footer class="footer bg-white border-top py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span class="text-muted small">&copy; {{ date('Y') }} Destination Fareways. Developed by EverythingEasy Technology.</span>
            <span class="text-muted small">System Version: <span class="mono-badge text-success font-monospace">v1.0.0 (Laravel 10)</span></span>
        </footer>
    </div>

    <!-- External Scripts JS -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap 5.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- TinyMCE 6 Rich Text Editor -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.4.2/tinymce.min.js"></script>
    
    <!-- DataTables JS (Bootstrap 5) -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

    <!-- Global Interactions Script -->
    <script>
        $(document).ready(function() {
            // Mobile Sidebar Toggle
            $('#sidebar-toggle-btn, #sidebar-overlay').on('click', function() {
                $('#sidebar, #sidebar-overlay').toggleClass('active');
            });
            $('#sidebar-close-btn').on('click', function() {
                $('#sidebar, #sidebar-overlay').removeClass('active');
            });

            // Initialize Flatpickr for dates
            $('.flatpickr-date').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true,
                theme: 'material_blue'
            });

            // Initialize TinyMCE for rich text areas
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.tinymce-editor',
                    height: 320,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic forecolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ' +
                        'removeformat | code help',
                    content_style: 'body { font-family: "DM Sans",sans-serif; font-size:14px }',
                    branding: false,
                    promotion: false
                });
            }

            // Auto-hide alerts/toasts after 5 seconds
            setTimeout(function() {
                $('.toast').each(function() {
                    var toast = bootstrap.Toast.getInstance(this);
                    if (toast) {
                        toast.hide();
                    } else {
                        $(this).fadeOut(500);
                    }
                });
            }, 5000);
        });
    </script>
    @yield('scripts')
</body>
</html>
