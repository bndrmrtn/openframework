@extends:auth/template;

@yield:form;

<h3>Register</h3>
{{
     import('form',[
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