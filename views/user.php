<!DOCTYPE html>
<html lang="en">
<!-- HTML Header -->
{{ view('assets/head',[ 'title' => ucfirst($username)."'s Profile" ]) }}
<body>
    <!-- HTML Navbar -->
    {{view('assets/nav',['links' => $links])}}

    <!-- HTML User Box Wrap -->
    <div class="ftext p-5 rounded text-white" style="background-color: #34354a;"> 
        <img class="user" src="{{ url('/framework.svg') }}" height="100px" width="100px">
        <h3><b>{{ $username }}</b></h3>

        <!-- HTML User Form (Form when it's the currently logged in user's profile) -->
        @if($is_mine): <form method="POST" action="{{ route('user',$username) }}"> @endif
        <div class="w-100 text-start">
            <!-- Foreach all the fields -->
        @foreach($fields as $key => $field):
            @if($key != 'username' && $key != 'id'):
                <!-- Form Inputs -->
                <div class="md-form">
                    <label>{{ucfirst($key)}}</label>
                    <input type="text" name="{{$key}}" class="form-control p-2" value="{{ $field }}" {{ $is_mine && $key == 'email' ? '' : 'disabled' }} />
                    <!-- If any session has been saved by the previous request -->
                    @if(isset(session()['errors'][$key])): <label class="text-danger">{{ session()['errors'][$key] }}</label> @endif
                </div>
            @endif
        @endforeach
            <!-- Input for request method, bc html only supports get and post -->
            <input type="hidden" name="_method" value="PUT">
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('index') }}" class="btn btn-secondary text-light">Back to home</a>
            <!-- Form Save Button -->
            @if($is_mine): <button class="btn btn-primary text-light">Update</button> @endif

        </div>
        <!-- Form End Tag -->
        @if($is_mine): </form> @endif

    </div>

    @if(_env('APP_DEV')):
    <div class="bottom">Render time: {{ getrtime() }}s</div>
    @endif
</body>
</html>