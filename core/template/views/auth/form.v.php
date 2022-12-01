<form action="{{ $route }}" method="POST">
    <div class="inputBox">
        @foreach($fields as $field_name => $field):
            <input type="{{$field['type'] ?: 'text'}}" name="{{$field_name}}" placeholder="{{ ucfirst($field_name) }}" value="{{ request()->has($field_name) }}" class="my-2" />
            @if($error = $errors[$field_name]):
                <p style="margin-top:-10px;" class="text-danger mb-1"><b>&times;</b> {{$error}}</p>
            @endif
        @endforeach
    </div>
    <button class="btn btn-framework w-100 my-2" type="submit">{{$form_submit ?: 'Submit'}}</button>
    <div class="text-center">
        @if($message):
        <p class="text-light">{{ $message }}</p>
        @endif
    </div>
</form>