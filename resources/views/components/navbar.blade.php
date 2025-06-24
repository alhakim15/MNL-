<header class="navbar">
    <div class="navbar-container">
        <h1 class="logo">Mutiara Nasional Line</h1>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                <li><a href="{{ route('contactus') }}">Contact Us</a></li>
                @can('create', App\Models\Delivery::class)
                <li><a href="{{ route('deliveries.create') }}">Deliver Book</a></li>
                @endcan
                <li><a href="{{ route('tracking') }}">Tracking</a></li>
                @auth
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
    </div>
</header>