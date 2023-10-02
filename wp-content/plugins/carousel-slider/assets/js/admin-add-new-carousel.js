(()=>{var e={180:(e,t,a)=>{"use strict";a.d(t,{u:()=>o});const o=function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[],o=document.createElement(e);return Object.keys(t).length&&Object.entries(t).forEach((e=>{let[t,a]=e;o.setAttribute(t,a)})),a.length&&o.append(...a),o}},464:()=>{class e extends HTMLElement{constructor(){super(),this.attachShadow({mode:"open"});const t=document.createElement("style");t.textContent=e.getStyle(),this.shadowRoot.append(t,this.getElement())}getElement(){const e=document.createElement("button");return e.classList.add("shapla-cross"),this.hasAttribute("size")&&e.classList.add(`is-${this.getAttribute("size")}`),e}attributeChangedCallback(e,t,a){const o=this.shadowRoot.querySelector("button");"size"===e&&this.hasAttribute("size")&&o.classList.add(`is-${this.getAttribute("size")}`)}static get observedAttributes(){return["size"]}static getStyle(){return'.shapla-cross {\n  -webkit-appearance: none;\n  -moz-appearance: none;\n  appearance: none;\n  background-color: var(--delete-icon-background, hsla(0, 0%, 4%, .2));\n  border: none;\n  border-radius: 290486px;\n  cursor: pointer;\n  display: inline-block;\n  flex-grow: 0;\n  flex-shrink: 0;\n  font-size: 0;\n  height: var(--delete-icon-size, 20px);\n  outline: none;\n  pointer-events: auto;\n  position: relative;\n  -webkit-user-select: none;\n  user-select: none;\n  vertical-align: top;\n  width: var(--delete-icon-size, 20px)\n}\n\n.shapla-cross:after, .shapla-cross:before {\n  background-color: var(--delete-icon-color, #fff);\n  content: "";\n  display: block;\n  left: 50%;\n  position: absolute;\n  top: 50%;\n  transform: translateX(-50%) translateY(-50%) rotate(45deg);\n  transform-origin: center center\n}\n\n.shapla-cross:before {\n  height: 2px;\n  width: 50%\n}\n\n.shapla-cross:after {\n  height: 50%;\n  width: 2px\n}\n\n.shapla-cross:focus, .shapla-cross:hover {\n  background-color: var(--delete-icon-background-dark, hsla(0, 0%, 4%, .3))\n}\n\n.shapla-cross:active {\n  box-shadow: 0 3px 4px 0 rgba(0, 0, 0, .14), 0 3px 3px -2px rgba(0, 0, 0, .2), 0 1px 8px 0 rgba(0, 0, 0, .12)\n}\n\n.shapla-cross.is-small {\n  --delete-icon-size: 16px\n}\n\n.shapla-cross.is-medium {\n  --delete-icon-size: 24px\n}\n\n.shapla-cross.is-large {\n  --delete-icon-size: 32px\n}\n\n.shapla-cross.is-error {\n  --delete-icon-background: var(--shapla-error, #dc3545);\n  --delete-icon-background-dark: var(--shapla-error-variant, #d32535);\n  --delete-icon-color: var(--shapla-on-error, #fff)\n}'}}customElements.define("shapla-cross",e)}},t={};function a(o){var s=t[o];if(void 0!==s)return s.exports;var n=t[o]={exports:{}};return e[o](n,n.exports,a),n.exports}a.d=(e,t)=>{for(var o in t)a.o(t,o)&&!a.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{"use strict";a(464);var e=a(180);class t extends HTMLElement{el(t){let a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:[];return(0,e.u)(t,a,o)}triggerCustomEvent(e){this.dispatchEvent(new CustomEvent(e))}}var o=t;class s extends o{constructor(){super(),this.attachShadow({mode:"open"});const e=document.createElement("style");e.textContent=s.getStyle(),this.shadowRoot.append(e,...this.getWrapperTemplate())}attributeChangedCallback(e,t,a){const o=this.shadowRoot.querySelector(".shapla-modal");if("open"===e&&this.hasAttribute("open")?o.classList.add("is-active"):o.classList.remove("is-active"),"type"===e){const e=this.shadowRoot.querySelector(".shapla-modal-content");"box"===a&&(e.classList.contains("shapla-modal-box")||e.classList.add("shapla-modal-box"))}}static get observedAttributes(){return["open","type"]}connectedCallback(){const e=this.getAttribute("type");if("card"===e){this.renderCardTemplate();const e=this.shadowRoot.querySelector(".shapla-modal-card__footer");e.querySelector("slot").assignedNodes().length<1&&e.classList.add("no-content")}"confirm"===e&&this.updateConfirmDom();const t=this.shadowRoot.querySelector(".shapla-modal-close.is-fixed"),a=this.shadowRoot.querySelector(".shapla-modal-content");"confirm"===e?(t.remove(),a.classList.add("shapla-modal-confirm"),a.innerHTML="",a.append(...this.getConfirmTemplate())):"box"===e&&a.classList.add("shapla-modal-box");const o=this.shadowRoot.querySelector(".shapla-modal-background"),s=this.getAttribute("backdrop-theme");-1!==["dark","light"].indexOf(s)&&o.classList.add(`is-${s}`),this.updateContentSize(),this.closeOnEscape(),this.closeOnBackdropClick(),this.closeOnCrossClick()}renderCardTemplate(){const e=this.shadowRoot.querySelector(".shapla-modal-close.is-fixed"),t=this.shadowRoot.querySelector(".shapla-modal-content");e.remove(),t.classList.add("shapla-modal-card"),t.innerHTML="",t.append(...this.getCartTemplate());const a=this.getAttribute("heading");a&&(this.shadowRoot.querySelector(".shapla-modal-card__title").innerHTML=a)}updateContentSize(){const e=this.getAttribute("content-size");-1!==["small","medium","large","full","custom"].indexOf(e)&&this.shadowRoot.querySelector(".shapla-modal-content").classList.add(`is-${e}`)}updateConfirmDom(){const e=this.getAttribute("icon")??"primary",t=this.getAttribute("heading"),a=this.getAttribute("message")??"Are you sure?",o=this.getAttribute("confirm-button")??"Ok",s=this.getAttribute("cancel-button")??"Cancel",n=this.shadowRoot.querySelector(".button--confirm"),l=this.shadowRoot.querySelector(".button--cancel");o&&(n.innerHTML=o),s&&(l.innerHTML=s);const r=this.shadowRoot.querySelector(".shapla-modal-confirm");var i;-1!==["primary","success","error"].indexOf(e)&&(null===(i=r.querySelector(".shapla-modal-confirm__icon"))||void 0===i||i.classList.add(`is-${e}`));this.hasAttribute("content-size")||(this.setAttribute("content-size","small"),this.updateContentSize()),this.hasAttribute("disabled-backdrop-click")||this.setAttribute("disabled-backdrop-click",""),t.length&&(r.querySelector(".shapla-modal-confirm__title").innerHTML=t),a.length&&(r.querySelector(".shapla-modal-confirm__message").innerHTML=a),this.closeOnCrossClick()}closeOnCrossClick(){(this.shadowRoot.querySelectorAll(".shapla-modal-close, .button--cancel")||[]).forEach((e=>{e.addEventListener("click",(()=>this.triggerCloseEvent()))}))}closeOnBackdropClick(){const e=this.shadowRoot.querySelector(".shapla-modal-background");this.hasAttribute("disabled-backdrop-click")||"confirm"!==this.getAttribute("type")&&e.addEventListener("click",(()=>this.triggerCloseEvent()))}closeOnEscape(){document.addEventListener("keydown",(e=>{27===(e||window.event).keyCode&&this.hasAttribute("open")&&this.triggerCloseEvent()}))}triggerCloseEvent(){this.triggerCustomEvent("close")}getWrapperTemplate(){return[this.el("div",{class:"shapla-modal"},[this.el("div",{class:"shapla-modal-background"}),this.el("shapla-cross",{class:"shapla-modal-close is-fixed",size:"large"}),this.el("div",{class:"shapla-modal-content"},[this.el("slot")])])]}getCartTemplate(){return[this.el("header",{class:"shapla-modal-card__header"},[this.el("div",{class:"shapla-modal-card__title"},[this.el("slot",{name:"heading"})]),this.el("shapla-cross",{class:"shapla-modal-close",size:"medium"})]),this.el("section",{class:"shapla-modal-card__body"},[this.el("slot")]),this.el("footer",{class:"shapla-modal-card__footer is-pulled-right"},[this.el("slot",{name:"footer"})])]}getConfirmTemplate(){return[this.el("div",{class:"shapla-modal-confirm__content"},[this.el("div",{class:"shapla-modal-confirm__icon"},[this.el("div",{class:"shapla-modal-confirm__icon-content"},["!"])]),this.el("h3",{class:"shapla-modal-confirm__title"}),this.el("div",{class:"shapla-modal-confirm__message"})]),this.el("div",{class:"shapla-modal-confirm__actions"},[this.el("slot",{name:"actions"},[this.el("button",{class:"shapla-button button--cancel"}),this.el("button",{class:"shapla-button is-primary button--confirm"})])])]}static getStyle(){return".shapla-modal,.shapla-modal-background{bottom:0;left:0;position:absolute;right:0;top:0}\n    .shapla-modal{align-items:center;display:none;flex-direction:column;justify-content:center;overflow:hidden;\n    position:fixed;z-index:var(--modal-z-index,100000)}\n    .shapla-modal.is-active{display:flex}\n    .shapla-modal-background{background-color:var(--modal-backdrop-color,rgba(0,0,0,.5))}\n    .shapla-modal-background.is-light{--modal-backdrop-color:var(--modal-backdrop-color-light,hsla(0,0%,100%,.5))}\n    .shapla-modal .shapla-delete-icon.is-fixed,.shapla-modal .shapla-modal-close.is-fixed{\n    position:fixed;right:var(--modal-close-right,1.25rem);top:var(--modal-close-top,1.25rem)}\n    .shapla-modal-content{margin:0 var(--modal-content-margin,20px);\n    max-height:calc(100vh - var(--modal-content-spacing, 160px));overflow:auto;position:relative;\n    width:var(--modal-content-width,calc(100% - var(--modal-content-margin, 20px)*2))}\n    .shapla-modal-content.is-small{--modal-content-width:var(--modal-content-width-small,320px)}\n    .shapla-modal-content.is-full{height:calc(100vh - var(--modal-content-margin, 20px)*2);\n    width:calc(100vw - var(--modal-content-margin, 20px)*2)}\n    @media print,screen and (min-width:768px){\n    .shapla-modal-content{--modal-content-spacing:40px;margin:0 auto}\n    .shapla-modal-content:not(.is-small):not(.is-full):not(.is-large){\n    --modal-content-width:var(--modal-content-width-medium,640px)}}\n    @media screen and (min-width:1024px){\n    .shapla-modal-content.is-large{--modal-content-width:var(--modal-content-width-large,960px)}}\n    .shapla-modal-card{display:flex;flex-direction:column;max-height:calc(100vh - 40px);overflow:hidden}\n    .shapla-modal-card__footer,.shapla-modal-card__header{align-items:center;background-color:#fff;display:flex;\n    flex-shrink:0;justify-content:flex-start;padding:1rem;position:relative}\n    .shapla-modal-card__footer>*+*,.shapla-modal-card__header>*+*{margin-left:.5rem}\n    .shapla-modal-card__header{border-bottom:1px solid rgba(0,0,0,.12);border-top-left-radius:4px;\n    border-top-right-radius:4px}\n    .shapla-modal-card__title{flex-grow:1;flex-shrink:0;font-size:1.5rem;font-weight:400;line-height:1;margin:0}\n    .shapla-modal-card__footer{border-bottom-left-radius:4px;border-bottom-right-radius:4px;\n    border-top:1px solid rgba(0,0,0,.12)}\n    .shapla-modal-card__footer.is-pulled-right{justify-content:flex-end}\n    .shapla-modal-card__footer.no-content{border-top:none;padding:2px}\n    .shapla-modal-card__body{background-color:#fff;flex-grow:1;flex-shrink:1;overflow:auto;padding:1rem}\n    .shapla-modal-box,.shapla-modal-confirm{background-color:#fff;border-radius:4px;\n    box-shadow:0 9px 46px 8px rgba(0,0,0,.14),0 11px 15px -7px rgba(0,0,0,.12),0 24px 38px 3px rgba(0,0,0,.2);padding:1rem}\n    .shapla-modal-confirm__content{padding:1rem;text-align:center}\n    .shapla-modal-confirm__icon{border:.25em solid var(--shapla-primary,#0d6efd);border-radius:50%;\n    color:var(--shapla-primary,#0d6efd);cursor:default;display:flex;height:5em;justify-content:center;\n    margin:1.25em auto 1.875em;-webkit-user-select:none;user-select:none;width:5em}\n    .shapla-modal-confirm__icon.is-success{border-color:var(--shapla-success,#198754);color:var(--shapla-success,#198754)}\n    .shapla-modal-confirm__icon.is-error{border-color:var(--shapla-error,#dc3545);color:var(--shapla-error,#dc3545)}\n    .shapla-modal-confirm__icon-content{align-items:center;display:flex;font-size:3.75em}\n    .shapla-modal-confirm__title{font-size:1.875em;margin:0 0 .4em;text-align:center}\n    .shapla-modal-confirm__actions{display:flex;justify-content:center;padding:1rem}\n    .shapla-modal-confirm__actions>*+*{margin-left:.5rem}"}}customElements.define("shapla-dialog",s);const n=(e,t,a)=>{t.length>2&&a.length&&e.removeAttribute("disabled")},l=document.querySelectorAll("[href*='post-new.php?post_type=carousels']");if(l){var r;const t={title:"",type:""},a=(0,e.u)("button",{class:"shapla-button is-primary",disabled:""},["Next"]),o=(0,e.u)("button",{class:"shapla-button"},["Cancel"]),s=(0,e.u)("shapla-dialog",{type:"card","content-size":"large",heading:"Add New Carousel"},[(0,e.u)("div",{slot:"footer",class:"cs-flex cs-space-x-1"},[o,a])]);s.addEventListener("close",(()=>{s.removeAttribute("open")})),o.addEventListener("click",(()=>{s.removeAttribute("open")})),null===(r=document.querySelector("body"))||void 0===r||r.append(s);const i=(0,e.u)("div",{class:"shapla-columns"},[(0,e.u)("div",{class:"shapla-column is-12-tablet"},[(0,e.u)("input",{type:"text",name:"slider_title",size:"30",value:"",id:"title",spellcheck:"true",autocomplete:"Off",placeholder:"Add Title",class:"widefat cs-py-2"})])]);s.append(i);const d=(0,e.u)("div",{class:"shapla-columns is-multiline"});s.append(d);let c=[];window.CarouselSliderL10n.sliderTypes.forEach((t=>{c.push((t=>{let a=(0,e.u)("span",{class:"option-slider-type__icon"});a.innerHTML=t.icon;let o={type:"radio",name:"slider_type",id:`_slide_type__${t.slug}`,class:"screen-reader-text",value:t.slug};return t.enabled||(o.disabled=""),(0,e.u)("div",{class:"shapla-column is-6-tablet is-4-desktop is-3-fullhd"},[(0,e.u)("input",o),(0,e.u)("label",{class:"option-slider-type",for:`_slide_type__${t.slug}`},[(0,e.u)("span",{class:"option-slider-type__content"},[a,(0,e.u)("span",{class:"option-slider-type__label"},[t.label]),t.pro?(0,e.u)("span",{class:"option-slider-type__pro"},["Pro"]):""])])])})(t))})),d.append(...c),l.forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),s.setAttribute("open","")}))})),s.querySelectorAll('input[name="slider_title"]').forEach((e=>{e.addEventListener("input",(e=>{t.title=e.target.value,n(a,t.title,t.type)}))})),s.querySelectorAll('input[name="slider_type"]').forEach((e=>{e.addEventListener("change",(e=>{t.type=e.target.value,n(a,t.title,t.type)}))})),a.addEventListener("click",(e=>{a.hasAttribute("disabled")||(a.classList.add("is-loading"),fetch(window.CarouselSliderL10n.restRoot+"/carousels",{method:"POST",headers:{"Content-Type":"application/json","X-WP-Nonce":window.CarouselSliderL10n.restNonce},body:JSON.stringify(t)}).then((e=>e.json())).then((e=>{if(e.data.edit_link){let t=document.createElement("a");t.href=e.data.edit_link,t.click()}})).catch((e=>{console.error("Error:",e)})).finally((()=>{a.classList.remove("is-loading")})))}))}})()})();