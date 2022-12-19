/** {{*

function json_atob($data, $json = false, $hightlight = false){
     if($json) $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
     if($hightlight) $data = highlightText($data);
     return base64_encode(json_encode($data));
}

}} DEVTools OpenFramework */
/** Application init */
const __pd_init_application = () => {
     __pd_build_application({
          'current_route': __pd_app_parser('{{ json_atob(urlPath()) }}'),
          'request': __pd_app_parser('{{ json_atob(request()->rawData(), true, true) }}'),
          'GET_params': __pd_app_parser('{{ json_atob($_GET, true, true) }}'),
          'session': __pd_app_parser('{{ json_atob(session(), true, true) }}'),
          'route_name': __pd_app_parser('{{ json_atob(routeName(true)) }}'),
          'views_data': __pd_app_parser('{{ json_atob($GLOBALS["views_data__info"],true,true) }}'),
          'user': __pd_app_parser('{{ json_atob(user(), true, true) }}'),
          'performance': __pd_app_parser('{{ json_atob(["Render Time" => getrtime() . "s", "Memory Usage" => formatBytes(memusage())], true, true) }}'),
          'included_files': __pd_app_parser('{{ json_atob(array_map(function($str) { return str_replace(ROOT, "", $str); }, get_included_files()), true, true) }}'),
     });
};
/** Application init end */

/** Application content */
const toSepWords=(str)=>{arr=str.split(/[\s_]+/);return arr.map(element=>{return element.charAt(0).toUpperCase()+element.slice(1)}).join(' ')};const __pd_app_parser=(data)=>{return JSON.parse(atob(data))};const __pd_build_application=(fields)=>{const push_to=document.querySelector('[pd-app-template]');const __app__=document.createElement('div');__app__.classList.add('__pd_main_app_div');const __app__fields__=document.createElement('div');const __app__fields__data__=document.createElement('div');const close_btn_el=document.createElement('button');__app__fields__data__.classList.add('__pd_main_content_wrap');close_btn_el.innerHTML='<b>&times;</b>';close_btn_el.style.marginLeft='auto';close_btn_el.setAttribute('onclick',"document.querySelector('[pd-app-template]').style.display = 'none';");__app__fields__.classList.add('__pd_main_app_fields');const logo_btn=document.createElement('button');logo_btn.setAttribute('disabled',true);const logo_img=document.createElement('img');logo_img.setAttribute('src','{{ asset("framework.svg") }}');logo_img.style.width='25px';logo_btn.append(logo_img);__app__fields__.append(logo_btn);let is_first=true;Object.keys(fields).forEach((field_name)=>{const data=fields[field_name];const name_el=document.createElement('button');const data_el=document.createElement('div');name_el.innerHTML=toSepWords(field_name);name_el.setAttribute('onclick','__pd_app_switchto("'+field_name+'", this)');data_el.innerHTML+='<h3>'+toSepWords(field_name)+'</h3>';__app__fields__.append(name_el);if(!is_first)data_el.style.display='none';else name_el.classList.add('active');data_el.append(__pd_app_generate__data(data));data_el.setAttribute('pd-app-'+field_name,'');__app__fields__data__.append(data_el);is_first=false});__app__fields__.append(close_btn_el);__app__.append(__app__fields__);__app__.append(__app__fields__data__);push_to.append(__app__)};const __pd_app_generate__data=(data)=>{type=typeof data;const data_wrap=document.createElement('pre');if(data===null||data===NaN||data===undefined){data=String(data);type='boolean'}data_wrap.setAttribute('pd-data-type',type);if(type=='object'){data=JSON.stringify(data,null,2)};data_wrap.innerHTML=data;return data_wrap};__pd_init_application();const __pd_app_switchto=(el_name,e)=>{let nav_fields=document.querySelector('.__pd_main_app_fields');nav_fields=nav_fields.childNodes;nav_fields.forEach(i=>{i.classList.remove('active')});e.classList.add('active');let datas=document.querySelector('.__pd_main_content_wrap');datas=datas.childNodes;datas.forEach(i=>{if(i.hasAttribute('pd-app-'+el_name)){i.style.display='block'}else{i.style.display='none'}})};
/** Application content end */
