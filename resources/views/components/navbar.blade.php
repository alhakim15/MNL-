<header class="navbar">
    <div class="navbar-container">
        <h1 class="logo">Mutiara Nasional Line</h1>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('aboutus') }}">About Us</a></li>
                <li><a href="{{ route('contactus') }}">Contact Us</a></li>
                @auth
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit"
                            style="background:none; border:none; padding:0; margin:0; cursor:pointer; color:inherit; text-decoration:underline;">
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