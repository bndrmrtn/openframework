@extends:.src/:assets/app;

@yield:main;

<div class="container">
     <h1>Auth</h1>
     <div class="login-box resize-form">
          <h2>{{ $name }}</h2>
          <hr>
          {{
               view('.src/:auth/form',$form)
          }}
     </div>
</div>


@endyield:main;