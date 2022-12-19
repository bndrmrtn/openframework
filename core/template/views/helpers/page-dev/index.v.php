<!-- @for($i = 0;$i<50;$i++): {{ '-' }} @endfor -->
<!--
     Built-in PHP OpenFramework Development Tools for HTML
     Framework Version: {{ VERSION }} 
     DevTools Version: ALPHA
-->
<!-- START OF INSERTION -->
<div pd-app-wrapper-box><div pd-app-template :="Not recommended to edit this code" class="__pd_main_wrap" style="position: fixed;bottom:0;width:100%;z-index:10;display:none;"><script pd-app-template-init-script>{{ view('.src/:helpers/page-dev/index.js') }}</script><?= "<style>" ?>{{ view('.src/:helpers/page-dev/index.css') }}<?= "</style>" ?></div><div style="position: fixed;bottom:0;right:0;background-color:#22232e;padding:8px;border-top: 2px solid #47da58;border-left: 2px solid #ffffff;cursor:pointer;z-index:4;transition:.3s" onclick="document.querySelector('[pd-app-template]').style.display = 'block';"><img src="{{ asset('framework.svg') }}" width="40px" alt="Framework Logo"></div></div>
<!-- END OF INSERTION -->
<!-- @for($i = 0;$i<50;$i++): {{ '-' }} @endfor -->