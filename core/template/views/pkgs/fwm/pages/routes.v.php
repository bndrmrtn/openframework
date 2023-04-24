@extends:.src/:pkgs/fwm/resources/layout;
@yield:fwmapp;
<h2>Routes</h2>
{{*
     function method_class($m){
          $li = [
               'get' => 'bg-primary',
               'post' => 'bg-success',
               'put' => 'bg-warning',
               'delete' => 'bg-danger',
          ];
          return $li[$m];
     }
}}
<div>
{{ view('.src/:pkgs/fwm/resources/route-search-box') }}

@foreach($routes as $route):

     <div class="data-code my-2">
          <div class="d-flex align-items-center">
               <span class="badge {{ method_class($route['method']) }} rounded-pill">{{ strtoupper($route['method']) }}</span>     
               <div class="route-path mx-2 text-code">{{$route['fullpath']}}</div>
               <div class="ms-auto">
                    <button 
                    onclick="toggle(`route__id__{{$route['key']}}`)" 
                    class="btn btn-framework">
                         <svg class="feather feather-maximize-2" fill="none" height="15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                    </button>
               </div>
          </div>
          <div class="mt-3" id="route__id__{{ $route['key'] }}" hidden>
               <hr>
               <div class="my-1"><b>Key:</b> <span class="cbox">{{ $route['key'] }}</span><br></div>
               <div class="my-1"><b>Authentication:</b> @if($route['authorize']): <span class="text-code cbox"><span class="text-danger">{{$route['authorize']}}</span><span class="text-warning">::class</span></span> @else: None @endif<br></div>
               <div class="my-1"><b>Controller:</b> <span class="text-code cbox">@if($route['call-function']): <span class="text-warning">{{ is_string($route['call']) ? $route['call'] . '()' : 'Closure {}' }}</span> @elseif(true): <span class="text-danger">{{ $route['call'][0] }}</span><span class="text-warning">-></span><b class="text-info">{{ $route['call'][1] }}<span class="text-warning">()</span></b> @endif</span></div>
               <div class="my-2">
                    <h5>Make {{ strtoupper($route['method']) }} request</h5>
                    <div class="d-flex text-code route-path" id="route__id__propmap__{{ $route['key'] }}">
                         <span class="route__path__span rounded-start" no-use>&nbsp;</span>     
                         <span class="route__path__span">{{url('/')}}</span>
                         @foreach($route['array'] as $k => $part):
                              @if($part['is_prop']):
                                   <input 
                                   class="__route__input"
                                   @if(str_starts_with($part['path'], 'int:')):
                                        type="number"
                                   @elseif(str_starts_with($part['path'], 'bool:') || str_starts_with($part['path'], 'boolean:')):
                                        type="number"
                                        min="1"
                                        max="2"
                                   @elseif(true):
                                        type="text"
                                   @endif
                                   placeholder="Prop: {{ $part['path'] }}">
                              @elseif(true):
                                   <span class="route__path__span text-framework">{{ $part['path'] }}</span>
                              @endif
                              @if(array_key_last($route['array']) != $k):
                                   <span class="route__path__span">/</span>
                              @elseif(true):
                                   <span class="route__path__span rounded-end" no-use>&nbsp;</span>
                              @endif
                         @endforeach
                    </div>
                    <div class="my-2">
                         @if($route['method'] != 'get'):
                              <textarea id="request__body__{{ $route['key'] }}" class="__route__input w-100" placeholder="Request body (json)" rows="3"></textarea>
                              <br>
                         @endif
                         <button class="btn btn-framework mt-2" onclick="requestResource(`{{ $route['key'] }}`, `{{$route['method']}}`)">Send request</button>
                    </div>
                    <div class="iframe-wrap my-5" hidden id="response_wrapper__{{ $route['key'] }}">
                         <h4>Server response:</h4>
                         <div id="req_response_headers__{{ $route['key'] }}" class="text-code cbox route-path my-2 w-full" style="white-space:pre-wrap;text-transform: capitalize;"></div>
                         <iframe id="iframe_response__{{ $route['key'] }}"></iframe>
                    </div>
               </div>
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