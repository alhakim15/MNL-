<header class="navbar">
    <div class="navbar-container">
        <h1 class="logo">Mutiara Nasional Line</h1>

        <!-- Desktop Navigation -->
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                <li><a href="{{ route('contactus') }}">Contact Us</a></li>
                @can('create', App\Models\Delivery::class)
                <li><a href="{{ route('deliveries.create') }}">Deliver Book</a></li>
                <li><a href="{{ route('payment.dashboard') }}">Payments</a></li>
                <li><a href="{{ route('deliveries.history') }}">History</a></li>
                @endcan
                <li><a href="{{ route('tracking') }}">Tracking</a></li>
                @auth
                @if(is_null(auth()->user()->email_verified_at))
                <li>
                    <a href="{{ route('verification.notice') }}" class="verify-email-btn"
                        style="background: #ff9800; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">
                        📧 Verifikasi Email
                    </a>
                </li>
                @endif
                <li><a href="{{ route('profile.index') }}">Profil</a></li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-button">
                            Logout
                        </button>
                    </form>
                </li>
                @else
                <li><a href="{{ route('login') }}">Login</a></li>
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
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('aboutus') }}">About Us</a></li>
            <li><a href="{{ route('contactus') }}">Contact Us</a></li>
            @can('create', App\Models\Delivery::class)
            <li><a href="{{ route('deliveries.create') }}">Deliver Book</a></li>
            <li><a href="{{ route('payment.dashboard') }}">Payments</a></li>
            <li><a href="{{ route('deliveries.history') }}">History</a></li>
            @endcan
            <li><a href="{{ route('tracking') }}">Tracking</a></li>
            @auth
            @if(is_null(auth()->user()->email_verified_at))
            <li>
                <a href="{{ route('verification.notice') }}" class="verify-email-btn"
                    style="background: #ff9800; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">
                    📧 Verifikasi Email
                </a>
            </li>
            @endif
            <li><a href="{{ route('profile.index') }}">Profil</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </li>
            @else
            <li><a href="{{ route('login') }}">Login</a></li>
            @endauth
        </ul>
    </div>
</header>