<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ $title ?: 'Dashboard' }} - FWM</title>
     <style>body{border:none!important;}</style>
     {{ stylesheet('css/bootstrap.min.css') }}
     {{ stylesheet('css/demo.css') }}
     {{ script('js/bootstrap.min.js') }}
     {{ script('js/request.js', ' defer') }}
     {{ view('.src/:pkgs/fwm/resources/styling') }}
</head>
<body>
     <div class="container-fluid">
          <div class="row">
               <div class="col-md-3 col-lg-3 nav-app" style="border:none!important;border-radius:0!important;">
                    {{ view('.src/:pkgs/fwm/resources/sidebar') }}
               </div>
               <div class="col-md-9 col-lg-9 py-3">
                    @section:fwmapp;
               </div>
          </div>
     </div>
     <script>
          function toggle(id){
               const el = document.getElementById(id)
               if(el.hasAttribute('hidden')){
                    el.removeAttribute('hidden')
               } else {
                    el.setAttribute('hidden', true)
               }
          }
          async function requestResource(rid, method){
               const el = document.getElementById(`response_wrapper__${rid}`)
               el.setAttribute('hidden', true)

               let allow = true
               let Route = ''
               const route_path_els = Array.from(document.getElementById(`route__id__propmap__${rid}`).children)
               route_path_els.forEach(child => {
                    if(!child.hasAttribute('no-use')){
                         if(child.nodeName == 'SPAN'){
                              Route += child.innerHTML
                         } else if(child.nodeName == 'INPUT'){
                              if(!child.value){
                                   alert('Hey!\nPlease fill out every prop!')    
                                   allow = false
                              }
                              Route += child.value
                         }
                    }
               })

               if(!allow) return

               let rbody = null
               const __rb = document.getElementById(`request__body__${rid}`)
               el.removeAttribute('hidden')
               if(__rb) rbody = __rb.innerHTML;
               const data = await router.background(Route, method, rbody)
               const headers = document.getElementById(`req_response_headers__${rid}`)
               const iframe = document.getElementById(`iframe_response__${rid}`)
               headers.innerHTML = data.headers
               let page_data = data.body
               let isJson = false
               try {
                    JSON.parse(data.body)
                    isJson = true
               } catch(e){
                    console.error('Content is not JSON, error:' + e.message)
               }
               if(isJson){
                    const json_data = syntaxHighlight(JSON.stringify(JSON.parse(data.body), null, 2))
                    let numbers = ''
                    let sep = ''
                    for(let i = 1;i<json_data.split("\n").length+2;i++){
                         numbers += i + '\n'
                         sep += '\n'
                    }
                    page_data = `<head>{{ stylesheet('css/bootstrap.min.css') }}<style>#@import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&display=swap');</style></head>
                    <body style="color:white;background-color:#262335;font-family: Arial, Helvetica, sans-serif;padding:25px;font-size:20px;">
                    <div style="display:flex;">
                    <div style="font-weight:bold;white-space:pre-wrap;color:#888690;-webkit-user-select: none;-ms-user-select: none;user-select: none;text-align:right;font-family: 'Fira Code', monospace;">${numbers}</div>
                    <div style="margin-left:7px;white-space:pre-wrap;border-right:1.3px solid #63346f;"></div>
                    <div style="color:#da70d6;margin-left:7px;white-space:pre-wrap;word-break: keep-all;font-family: 'Fira Code', monospace;">${json_data}</div>
                    </div>
                    </body>`
               }
               iframe.srcdoc = page_data
          }
          function syntaxHighlight(json) {
               if (typeof json != 'string') {
                    json = JSON.stringify(json, undefined, 2);
               }
               json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
               return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    let cls = 'number';
                    let styleColor = '#f97c70'
                    if (/^"/.test(match)) {
                         if (/:$/.test(match)) {
                              cls = 'key';
                              styleColor = '#72f1b8';
                              lc = match
                              match = lc.slice(0, -1);
                              match += '<span style="color:#bbbbbb;">:</span>'
                         } else {
                              cls = 'string';
                              styleColor = '#fa8b39';
                         }
                    } else if (/true|false/.test(match)) {
                         cls = 'boolean';
                    } else if (/null/.test(match)) {
                         cls = 'null';
                    }
                    return '<span style="color:'+ styleColor + ';" class="' + cls + '">' + match + '</span>';
               });
          }
     </script>
</body>
</html>