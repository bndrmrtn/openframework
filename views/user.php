<!DOCTYPE html>
<html lang="en">
{{ view('assets/head',[ 'title' => ucfirst($username)."'s Profile" ]) }}
<body>
    {{view('assets/nav',['links' => $links])}}

    <div class="ftext p-5 rounded text-white" style="background-color: #34354a;"> 
        <img class="user" src="{{url('/framework.svg')}}" height="100px" width="100px">
        <h3><b>{{ $username }}</b></h3>
        <div class="w-100 text-start">
        @foreach($fields as $key => $field):
            @if($key != 'username' && $key != 'id'):
                <p>{{ucfirst($key)}}: {{ $field }}</p>
            @endif
        @endforeach
        </div>
        <div class="text-center">
                <a href="{{ route('index') }}" class="btn btn-secondary text-light">Back to home</a>
        </div>
    </div>
</body>
</html>