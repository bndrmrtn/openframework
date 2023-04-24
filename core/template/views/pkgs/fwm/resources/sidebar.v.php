{{*
     $links = [
          'Main' => 'pkg.fwm.index',
          'Routes' => 'pkg.fwm.routes',
          'Controllers' => 'pkg.fwm.controllers',
          'Models' => 'pkg.fwm.models',
          'Database' => 'pkg.fwm.database',
     ];
}}
<div class="aside sidebar p-3 min-vh-100">
     <h2>FWM</h2>
     <hr>
     <ul class="nav flex-column">
          @foreach($links as $name => $route):
          <li class="nav-item px-4 py-1 rounded my-1 {{ (routeName(true) == $route) ? 'bg-framework' : 'bg-app-dark' }}" >
               <a rlink class="nav-link active text-white" href="{{route($route)}}">{{$name}}</a>
          </li>
          @endforeach
     </ul>
</div>