@extends:.src/:auth/template;

@yield:form;

<h3>Register</h3>
{{
     view('.src/:auth/form',[
          'route' => route('auth.register'),
          'fields' => [
          'username' => [ ],
          'email' => [ 'type' => 'email' ],
          'password' => [ 'type' => 'password' ]
          ],
          'message' => $message,
          'errors' => $errors,
          'form_submit' => 'Register'
     ])
}}

@endyield:form;