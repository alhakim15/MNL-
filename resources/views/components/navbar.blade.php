<header class="navbar">
    <div class="navbar-container">
        <h1 class="logo">Mutiara Nasional Line</h1>

        <!-- Desktop Navigation -->
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                <li><a href="{{ route('contactus') }}">Contact Us</a></li>
                <li><a href="{{ route('tracking') }}">Tracking</a></li>

                @can('create', App\Models\Delivery::class)
                <!-- Services Dropdown -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Services <span class="arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('deliveries.create') }}">Deliver Book</a></li>
                        <li><a href="{{ route('deliveries.history') }}">Delivery History</a></li>
                        <li><a href="{{ route('payment.dashboard') }}">Payment Dashboard</a></li>
                    </ul>
                </li>
                @endcan

                @auth
                @if(is_null(auth()->user()->email_verified_at))
                <li>
                    <a href="{{ route('verification.notice') }}" class="verify-email-btn">
                        📧 Verify Email
                    </a>
                </li>
                @endif

                <!-- Account Dropdown -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Account <span class="arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('profile.index') }}">Profile</a></li>
                        <li>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="logout-link">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li><a href="{{ route('login') }}" class="login-btn">Login</a></li>
                @endauth
            </ul>
        </nav>

        <!-- Hamburger Button -->
        <button class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu">

        <ul>
            <li><a href="{{ route('home') }}"> Home</a></li>
            <li><a href="{{ route('aboutus') }}"> About Us</a></li>
            <li><a href="{{ route('contactus') }}"> Contact Us</a></li>
            <li><a href="{{ route('tracking') }}"> Tracking</a></li>

            @can('create', App\Models\Delivery::class)
            <li class="mobile-dropdown">
                <div class="mobile-dropdown-header">
                    <span class="mobile-section-title"> Services</span>
                    <span class="mobile-arrow">▼</span>
                </div>
                <div class="mobile-dropdown-content">
                    <a href="{{ route('deliveries.create') }}"> Deliver Book</a>
                    <a href="{{ route('deliveries.history') }}"> Delivery History</a>
                    <a href="{{ route('payment.dashboard') }}"> Payment Dashboard</a>
                </div>
            </li>
            @endcan

            @auth
            @if(is_null(auth()->user()->email_verified_at))
            <li>
                <a href="{{ route('verification.notice') }}" class="verify-email-btn mobile-verify">
                    📧 Verify Email
                </a>
            </li>
            @endif

            <li class="mobile-dropdown">
                <div class="mobile-dropdown-header">
                    <span class="mobile-section-title"> Account</span>
                    <span class="mobile-arrow">▼</span>
                </div>
                <div class="mobile-dropdown-content">
                    <a href="{{ route('profile.index') }}">Profile</a>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                        class="logout-link">
                         Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            @else
            <li><a href="{{ route('login') }}" class="login-btn mobile-login"> Login</a></li>
            @endauth
        </ul>
    </div>
</header>