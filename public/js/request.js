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

*/"use strict";
const debug_mode = false;
class Router {
    setup() {
        const $ = this;
        const linktags = document.querySelectorAll('a[rlink]');
        linktags.forEach((linktag) => {
            if(debug_mode) console.log('Link added: ', linktag)
            if (linktag.attributes.getNamedItem('rlink')) {
                linktag.addEventListener('click', function (e) {
                    if(debug_mode) console.log('Link clicked: ', e)
                    e.preventDefault();
                    let el = e.target;
                    if (el && el.nodeName && el.nodeName.toLowerCase() != 'a') {
                        while (el && el.nodeName.toLowerCase() != 'a') {
                            el = el.parentElement;
                        }
                    }
                    $.push(el.href || $.fullURL());
                });
            }
        });
        const rforms = document.querySelectorAll('form[rform]');
        rforms.forEach((form) => {
            if (form.attributes.getNamedItem('rform') != null) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formdata = new FormData(form);
                    let action = (form.attributes.getNamedItem('action')) ? form.attributes.getNamedItem('action').value : '';
                    const method = (form.attributes.getNamedItem('method')) ? form.attributes.getNamedItem('method').value : 'GET';
                    if(method.toLowerCase() == 'get') {
                        const query = new URLSearchParams(formdata).toString()
                        action += '?' + query
                    }
                    $.push(action, method, formdata);
                    form.reset();
                });
            }
        });
    }
    async push(url = '', method = 'GET', data = null, inbg = false) {
        document.body.innerHTML += `<div rlink-loader-div></div>`;
        const req = await this.makeRequest(method, url, data, inbg);
        return req
    }
    background = (url = '', method = 'GET', data = null) => {
        return this.push(url, method, data, true);
    };
    makeRequest(method, url, data = null, inBg = false) {
        this.createLoaderCss();
        const $ = this;
        return new Promise(function (resolve, reject) {
            const xhttp = new XMLHttpRequest();
            xhttp.open(method, url);
            xhttp.send(data);
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4) {
                    $.clearLoaders();
                    if (!inBg) {
                        history.pushState({}, $.fullURL(), url);
                        const page = document.querySelector('html');
                        if (page) {
                            page.innerHTML = xhttp.responseText;
                        }
                        const scripts = document.querySelectorAll('script');
                        scripts.forEach(script => {
                            if (script.innerHTML && script.innerHTML != '<empty string>' && script.innerHTML != '') {
                                try {
                                    eval(script.innerHTML);
                                }
                                catch (e) {
                                    console.error(e.message);
                                }
                            }
                        });
                        $.setup();
                        if (this.status !== 200) {
                            if (debug_mode) alert(`ERROR\nCODE: ${this.status}`);
                        }
                        else {
                            if (debug_mode) console.log('Route changed successfully.');
                        }
                        if (!inBg) setTimeout(function () {
                                document.body.scrollTop = 0;
                                document.documentElement.scrollTop = 0;
                            }, 10);
                    }
                    resolve({
                        headers: xhttp.getAllResponseHeaders(),
                        body: xhttp.responseText,
                    })
                }
            };
        });
    }
    fullURL() {
        return window.location.href;
    }
    createLoaderCss = () => {
        const rawcss = '.rlinkblur {filter:blur(5px);z-index:0;} [rlink-loader-div] {z-index:10;width: 0%;padding: 2px;position:fixed;top:0;background-image: linear-gradient(to right, #30f8a8, #47da58);background-size: 400% 100%;animation: rlinkloaderspin 3s ease infinite;}@keyframes rlinkloaderspin {0% {background-position: 0% 50%;} 50% {background-position: 100% 50%;}100% {background-position: 0% 50%;width:100%;}}';
        const style = document.createElement('style');
        style.innerHTML = rawcss;
        style.setAttribute('rlink-loader-css', '');
        document.getElementsByTagName('head')[0].appendChild(style);
    };
    clearLoaders = () => {
        const loader_css = document.querySelector('style[rlink-loader-css]');
        const loader_div = document.querySelector('div[rlink-loader-div]');
        if (loader_css && loader_div) {
            loader_css.remove();
            loader_div.remove();
        }
    };
}
const router = new Router();
router.setup();

window.addEventListener('popstate', function() {
    if(!window.location.hash){
        router.push(window.location.href);
    }
});