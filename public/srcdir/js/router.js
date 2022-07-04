/**
@author Martin Binder
@website https://open.mrtn.vip/public/js/routerjs
@name RouterJS
*/

/**
@usage

add rlink attribute to your <a></a> tag and thats converts the link to a router link
the page uses an http request to get the new content and adds it to the original content
@note that won't refresh all the link and scripts in the head, so be careful with the usage of it
      * use the same head component on your website for the router proper work

*/

const debug_mode = false;


class Router {
    push = (url = '',type = 'GET',data = '') => {
        this.createLoaderCss();
        document.body.innerHTML += `<div rlink-loader-div></div>`;
        this.getSiteContent(url,type,data);
    };

    getSiteContent = (url,type,data) => {
        const $this = this;
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4){
                history.pushState({}, null, url);
                $this.clearLoaders();
                document.querySelector('html').innerHTML = xhttp.responseText;
                rsetup();
                if(this.status !== 200){
                    if(debug_mode) alert(`ERROR\nCODE: ${this.status}`);
                } else {
                    if(debug_mode) console.log('Route changed successfully.');
                }
                setTimeout(function(){
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                },10);
            }
        };
        xhttp.open(type, url, true);
        xhttp.send(data);
    };
 
    createLoaderCss = () => {
        const rawcss = '.rlinkblur {filter:blur(5px);z-index:0;} [rlink-loader-div] {z-index:10;width: 0%;padding: 2px;position:fixed;top:0;background-image: linear-gradient(to right, #30f8a8, blueviolet);background-size: 400% 100%;animation: rlinkloaderspin 3s ease infinite;}@keyframes rlinkloaderspin {0% {background-position: 0% 50%;} 50% {background-position: 100% 50%;}100% {background-position: 0% 50%;width:100%;}}';
        const style=document.createElement('style');
        style.innerHTML=rawcss;
        style.setAttribute('rlink-loader-css','');
        document.getElementsByTagName('head')[0].appendChild(style);
    };

    clearLoaders = () => {
        try {
            document.querySelector('style[rlink-loader-css]').remove();
            document.querySelector('div[rlink-loader-div]').remove();
        } catch(e){
            if(debug_mode) console.log('Router error: '+e.message);
        }
    };

}

const router = new Router;

const rsetup = () => {
    const linktags = document.querySelectorAll('a[rlink]');
    linktags.forEach(linktag => {
        if(linktag.attributes.getNamedItem('rlink') != null){
            linktag.addEventListener('click',function(e){
                e.preventDefault();
                let el = e.target;
                if(el.nodeName.toUpperCase() != 'A'){
                    while(el != null && el.nodeName.toUpperCase() != 'A'){
                        el = el.parentElement;
                    }
                }
                router.push(el.href);
            });
        }
    });
    const rforms =  document.querySelectorAll('form[rform]');
    rforms.forEach(form => {
        if(form.attributes.getNamedItem('rform') != null){
            form.addEventListener('submit',function(e){
                e.preventDefault();
                const formdata = new FormData(form);
                const action = (form.attributes.getNamedItem('action')) ? form.attributes.getNamedItem('action').value : '';
                const method = (form.attributes.getNamedItem('method')) ? form.attributes.getNamedItem('method').value : 'GET';
                router.push(action,method,formdata);
                form.reset();
            });
        }
    });
};

window.addEventListener('popstate', function() {
    if(!window.location.hash){
        router.push(window.location.href);
    }
});

rsetup();