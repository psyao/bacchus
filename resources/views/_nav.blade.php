<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
        @if (Auth::guest())
            <li>{!! link_to_route('recipes.index', 'Recipes') !!}</li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Recipes <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li>{!! link_to_route('recipes.index', 'List') !!}</li>
                    <li>{!! link_to_route('recipes.create', 'Add') !!}</li>
                    <li>{!! link_to_route('recipes.provide', 'Import') !!}</li>
                </ul>
            </li>
        @endif
    </ul>

    <ul class="nav navbar-nav navbar-right">
        @if (Auth::guest())
            <li><a href="/auth/login">Login</a></li>
            <li><a href="/auth/register">Register</a></li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/auth/logout">Logout</a></li>
                </ul>
            </li>
        @endif
    </ul>
</div>
