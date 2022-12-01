@extends:.src/:assets/app;

@yield:main;

<div class="container">
     <h1>User</h1>
     <div class="login-box resize-form">
          <h2>{{ ucfirst($username) }}</h2>
          <hr>
          <!-- HTML User Form (Form when it's the currently logged in user's profile) -->
          @if($is_mine): <form method="POST" action="{{ route('user',$username) }}"> @endif
          <div class="w-100 text-start">
               <!-- Foreach all the fields -->
          @foreach($fields as $key => $field):
               @if($key != 'username' && $key != 'id'):
                    <!-- Form Inputs -->
                    <div class="md-form">
                         <label>{{ucfirst($key)}}</label>
                         <input type="text" name="{{$key}}" class="p-2" value="{{ $field }}" {{ $is_mine && $key == 'email' ? '' : 'disabled' }} />
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
          @if($is_mine): <button class="btn btn-framework">Update</button> @endif
     </div>

     <!-- Form End Tag -->
     @if($is_mine): </form> @endif
</div>


@endyield:main;