<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li><a href="#">MY TIME CLOCK</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            TIME SHEETS <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    View Time Sheets
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Who's Working Now?
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li><a href="#">SCHEDULES</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            TIME SHEETS <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    Time Sheets
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Employees Summary
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Job Summary
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Employees Details
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Job Details
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Task Details
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Task Summary
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    CSV Export
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            ADMIN <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    Company Settings
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Notifications
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Audit Logs
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Jobs
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Tasks
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Employees
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->fullname }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>