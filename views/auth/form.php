<form action="{{ $route }}" method="POST">
    <div class="inputBox">
        @foreach($fields as $field_name => $field):
            <input type="{{$field['type'] ?: 'text'}}" name="{{$field_name}}" placeholder="{{ ucfirst($field_name) }}" value="{{ request()->has($field_name) }}" />
            @if($error = $errors[$field_name]):
                <p style="margin-top:-20px;" class="text-danger"><b>&times;</b> {{$error}}</p>
            @endif
        @endforeach
    </div>
    <input type="submit" value="{{$form_submit ?: 'Submit'}}">
    <div class="text-center">
        @if($message):
        <p class="text-light">{{ $message }}</p>
        @endif
    </div>
</form>