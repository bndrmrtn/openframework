@extends:.src/:auth/template;

@yield:form;

<h3>Login</h3>
{{
     view('.src/:auth/form',[
          'route' => route('auth.login'),
          'fields' => [
          'username' => [ ],
          'password' => [ 'type' => 'password' ]
          ],
          'message' => $message,
          'errors' => $errors,
          'form_submit' => 'Login'
     ])
}}

@endyield:form;