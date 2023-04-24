@extends:.src/:pkgs/fwm/resources/layout;

@yield:fwmapp;

<h2>Models</h2>

<div>
@foreach($models as $model):
     <div class="data-code my-2">
          <div class="d-flex justify-content-between- align-items-center">
               <div class="route-path mx-2 text-code text-danger">{{ $model['class_name'] }}<span class="text-warning">::class</span></div>
               <div class="ms-auto">
                    <button
                    onclick="toggle(`controller__id__{{ base64_encode($model['class_name']) }}`)" 
                    class="btn btn-framework">
                         <svg class="feather feather-maximize-2" fill="none" height="15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                    </button>
               </div>
          </div>
          <div class="mt-3" id="controller__id__{{ base64_encode($model['class_name']) }}" hidden>
               <hr>
               <h5>Database</h5>
               <div class="my-3 text-code data-code bg-app-dark-3">
               <span class="text-danger">DB</span><span class="text-warning">::class</span> <span class="text-info">-></span> "{{$model['table']}}"
               </div>
               <h5>Properties</h5>
               @if(!empty($model['properties'])):
               @foreach($model['properties'] as $key => $prop):
                    <div class="my-3 text-code data-code bg-app-dark-3">
                         <h5>{{ ucfirst($key) }}:</h5>
                         [<br>
                         @foreach($prop as $field):
                              <span class="text-warning ms-3">"{{$field}}"</span>,<br>
                         @endforeach
                         ]
                    </div>
               @endforeach
               @else:
                    <h6 class="text-secondary">Nothing to show</h6>
               @endif
          </div>
     </div>
@endforeach
</div>

<style>
     .route-path {
          width: 90%;
          overflow-x: scroll;
          white-space: nowrap;
     }
</style>

@endyield:fwmapp;