<nav class="navbar navbar-expand-md navbar-dark ms-md-2 me-md-2 {{ (isset($float) && $float) ? 'floatbar' : '' }}">
    <div class="container p-3 rounded" style="background-color: #34354a;">
        <a href="https://open.mrtn.vip/#welcome" target="_blank" class="navbar-brand"><img src="{{url('/framework.svg')}}" width="27px" style="margin-top:-3px;"> {{ isset($title) ? $title : _env('NAME', 'OpenFramework') }}</a>

        @if(isset($links) && is_array($links)):
        <button class="navbar-toggler navbarsvgsize" type="button" aria-haspopup="true" aria-expanded="false" data-bs-toggle="collapse" data-bs-target="#navmenu">&#9780;</button>

        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
                @foreach($links as $link):
                        @if(!in_array('no-display',$link)):
                            <li><a href="{{ $link['href'] }}" class="nav-link {{ in_array('active',$link) ? 'active' : '' }}">{{ $link['title'] }}</a></li>
                        @endif
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</nav>