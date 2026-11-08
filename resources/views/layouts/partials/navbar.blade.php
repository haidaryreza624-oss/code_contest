<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            CodeArena
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        Home
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        {{ auth()->user()['email'] }}
                    </a>
                </li>

                @endauth





                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </li>

            </ul>

        </div>
    </div>
</nav>
