@extends:.src/:pkgs/fwm/resources/layout;

@yield:fwmapp;

<h2>Controllers</h2>

<div>
@foreach($controllers as $controller):
     <div class="data-code my-2">
          <div class="d-flex justify-content-between- align-items-center">
               <div class="route-path mx-2 text-code text-danger">{{ $controller['class_name'] }}<span class="text-warning">::class</span></div>
               <div class="ms-auto">
                    <button
                    onclick="toggle(`controller__id__{{ base64_encode($controller['class_name']) }}`)" 
                    class="btn btn-framework">
                         <svg class="feather feather-maximize-2" fill="none" height="15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                    </button>
               </div>
          </div>
          <div class="mt-3" id="controller__id__{{ base64_encode($controller['class_name']) }}" hidden>
               <hr>
               <h5>Methods</h5>
               @if(!empty($controller['methods'])):
               @foreach($controller['methods'] as $method):
                    <div class="my-3 text-code data-code bg-app-dark-3">
                         <span class="text-info">
                              <span class="text-warning">{{ !empty($method['modifiers']) ? implode(' ',$method['modifiers']) . ' ' : '' }} function </span>{{ $method['name'] }}()<span class="text-warning">{{ $method['return_type'] ? ':' . $method['return_type'] : '' }} { <span class="text-white">...</span> }</span>
                         </span>
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