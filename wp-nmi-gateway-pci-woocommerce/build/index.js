(()=>{"use strict";var e,t={915:(e,t,n)=>{const r=window.wc.wcBlocksRegistry,s=window.wp.i18n,o=window.wp.element,a=window.wc.wcSettings;var i=n(848);const l="nmi",c="wc-nmi",d=()=>{const e=(0,a.getSetting)(`${l}_data`,null);if(!e)throw new Error(`${l} initialization data is not available`);return e},p={ccnumber:{selector:`#${l}-card-number-element`,placeholder:"•••• •••• •••• ••••"},ccexp:{selector:`#${l}-card-expiry-element`,placeholder:d()?.placeholder_expiry},cvv:{display:"show",selector:`#${l}-card-cvc-element`,placeholder:d()?.placeholder_cvc}},w=e=>{var t;const{PaymentMethodLabel:n}=e.components,r=null!==(t=d()?.title)&&void 0!==t?t:__("Credit / Debit Card",c);return(0,i.jsx)(n,{text:r})},m=({billing:e,eventRegistration:t,emitResponse:n,components:r})=>{const a=(()=>{var e;return Object.entries(null!==(e=d()?.icons)&&void 0!==e?e:{}).map((([e,{src:t,alt:n}])=>({id:e,src:t,alt:n})))})(),{onPaymentSetup:w,onCheckoutFail:m}=t,{PaymentMethodIcons:u,ValidationInputError:y,LoadingMask:f}=r,[g,v]=(0,o.useState)(!1),[b,h]=(0,o.useState)(!1),[_,x]=(0,o.useState)();(0,o.useEffect)((()=>{if(null!=typeof window&&v(!0),!_){const e=d()?.public_key;x(e||null)}})),(0,o.useEffect)((()=>{_&&_.length>0&&g&&(()=>{try{if(void 0===window.CollectJS)throw new Error(d()?.collect_js_error);h(!0),window.CollectJS.configure({variant:"inline",customCss:{height:"20px","line-height":"20px",padding:"0px","border-width":"0px","pointer-events":"auto"},styleSniffer:"true",fields:{...p},validationCallback:function(e,t,n){h(!1),P({field:e,status:t,message:n})},timeoutDuration:2e4,timeoutCallback:function(){},fieldsAvailableCallback:function(){h(!1),window.collectResponse="",window.effectTriggerTimes=0},callback:k})}catch(t){document.querySelectorAll(".wc-block-nmi-gateway-container .wc-block-gateway-input, .wc-block-nmi-gateway-container label").forEach((e=>{e.style.display="none"}));var e=t.message;P({field:"ccnumber",status:!1,message:e})}})()}),[_]);const[R,S]=(0,o.useState)(""),k=e=>{window.collectResponse=e,S(e.token)},C=((e,t,n,r,s)=>{const[a,i]=(0,o.useState)(""),c=(0,o.useCallback)((e=>(console.log("onNMIError :",e),i(e),e||!1)),[]);return(0,o.useEffect)((()=>{const r=n((async()=>{try{var n,r;if(a)return{type:t.responseTypes.ERROR,message:a};if(window.collectResponse="",window.CollectJS.startPaymentRequest(),await(async()=>{for(;!window.collectResponse||window.collectResponse.length<5;){if(window.effectTriggerTimes=window.effectTriggerTimes+1,window.effectTriggerTimes>10)throw new Error(d()?.timeout_error);await new Promise((e=>setTimeout(e,2e3)))}})().catch((e=>{console.error("waitForCollectJsToken error: ",e)})),!window.collectResponse||null==window.collectResponse.card.type)return{type:t.responseTypes.ERROR,message:d()?.timeout_error};if(-1===d()?.allowed_card_types.indexOf(window.collectResponse.card.type.replace("diners","diners-club")))return{type:t.responseTypes.ERROR,message:d()?.card_disallowed_error};const s=e.billingAddress,o={nmi_token:window.collectResponse?.token,nmi_js_response:JSON.stringify(window.collectResponse)};return{type:t.responseTypes.SUCCESS,meta:{paymentMethodData:{...o,billing_email:s.email,billing_first_name:null!==(n=s?.first_name)&&void 0!==n?n:"",billing_last_name:null!==(r=s?.last_name)&&void 0!==r?r:"",paymentMethod:l,paymentRequestType:"cc"},billingAddress:s}}}catch(e){return{type:t.responseTypes.ERROR,message:e}}}));return()=>{r()}}),[n,e.billingAddress,c,a,t.noticeContexts.PAYMENTS,t.responseTypes.ERROR,t.responseTypes.SUCCESS,s]),(0,o.useEffect)((()=>{const e=r((({processingResponse:e})=>!e?.paymentDetails?.errorMessage||{type:t.responseTypes.ERROR,message:e.paymentDetails.errorMessage,messageContext:t.noticeContexts.PAYMENTS}));return()=>{e()}}),[r,t.noticeContexts.PAYMENTS,t.responseTypes.ERROR]),c})(e,n,w,m,R),[E,O]=(0,o.useState)(""),[j,T]=(0,o.useState)(""),[M,N]=(0,o.useState)(""),P=e=>{const{status:t,message:n,field:r}=e;h(!1),"ccnumber"===r&&O(t?"":n),"ccexp"===r&&T(t?"":n),"cvv"===r&&N(t?"":n),!1===t?C(n):""===E&&""===M&&""===j&&C("")};return(0,i.jsxs)(f,{isLoading:b,showSpinner:"true",screenReaderLabel:(0,s.__)("Loading payment fields…",c),children:[_?(0,i.jsxs)("div",{className:"wc-block-card-elements wc-block-nmi-gateway-container",children:[(0,i.jsxs)("div",{className:"wc-block-gateway-container wc-card-number-element",children:[(0,i.jsx)("div",{id:"nmi-card-number-element",className:"wc-block-gateway-input"}),(0,i.jsx)("label",{htmlFor:"nmi-card-number-element",children:(0,s.__)("Card Number",c)}),E&&(0,i.jsx)(y,{errorMessage:E})]}),(0,i.jsxs)("div",{className:"wc-block-gateway-container wc-card-expiry-element",children:[(0,i.jsx)("div",{id:"nmi-card-expiry-element",className:"wc-block-gateway-input"}),(0,i.jsx)("label",{htmlFor:"nmi-card-expiry-element",children:(0,s.__)("Expiry Date",c)}),j&&(0,i.jsx)(y,{errorMessage:j})]}),(0,i.jsxs)("div",{className:"wc-block-gateway-container wc-card-cvc-element",children:[(0,i.jsx)("div",{id:"nmi-card-cvc-element",className:"wc-block-gateway-input"}),(0,i.jsx)("label",{htmlFor:"nmi-card-cvc-element",children:(0,s.__)("Card Code (CVC)",c)}),M&&(0,i.jsx)(y,{errorMessage:M})]})]}):"public key not available yet",u&&a.length&&(0,i.jsx)(u,{icons:a,align:"left"})]})};var u,y,f;const g=e=>(0,i.jsx)(m,{...e}),v={name:l,label:(0,i.jsx)(w,{}),content:(0,i.jsx)(g,{}),edit:(0,i.jsx)(g,{}),canMakePayment:()=>!0,ariaLabel:(0,s.__)("NMI payment method",c),supports:{showSavedCards:null!==(u=d()?.showSavedCards)&&void 0!==u&&u,showSaveOption:null!==(y=d()?.showSaveOption)&&void 0!==y&&y,features:null!==(f=d()?.supports)&&void 0!==f?f:[]}};(0,r.registerPaymentMethod)(v)},20:(e,t,n)=>{var r=n(609),s=Symbol.for("react.element"),o=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),a=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,i={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,n){var r,l={},c=null,d=null;for(r in void 0!==n&&(c=""+n),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(d=t.ref),t)o.call(t,r)&&!i.hasOwnProperty(r)&&(l[r]=t[r]);if(e&&e.defaultProps)for(r in t=e.defaultProps)void 0===l[r]&&(l[r]=t[r]);return{$$typeof:s,type:e,key:c,ref:d,props:l,_owner:a.current}}t.jsx=l,t.jsxs=l},848:(e,t,n)=>{e.exports=n(20)},609:e=>{e.exports=window.React}},n={};function r(e){var s=n[e];if(void 0!==s)return s.exports;var o=n[e]={exports:{}};return t[e](o,o.exports,r),o.exports}r.m=t,e=[],r.O=(t,n,s,o)=>{if(!n){var a=1/0;for(d=0;d<e.length;d++){for(var[n,s,o]=e[d],i=!0,l=0;l<n.length;l++)(!1&o||a>=o)&&Object.keys(r.O).every((e=>r.O[e](n[l])))?n.splice(l--,1):(i=!1,o<a&&(a=o));if(i){e.splice(d--,1);var c=s();void 0!==c&&(t=c)}}return t}o=o||0;for(var d=e.length;d>0&&e[d-1][2]>o;d--)e[d]=e[d-1];e[d]=[n,s,o]},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={57:0,350:0};r.O.j=t=>0===e[t];var t=(t,n)=>{var s,o,[a,i,l]=n,c=0;if(a.some((t=>0!==e[t]))){for(s in i)r.o(i,s)&&(r.m[s]=i[s]);if(l)var d=l(r)}for(t&&t(n);c<a.length;c++)o=a[c],r.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return r.O(d)},n=globalThis.webpackChunkwp_nmi_gateway_pci_woocommerce=globalThis.webpackChunkwp_nmi_gateway_pci_woocommerce||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var s=r.O(void 0,[350],(()=>r(915)));s=r.O(s)})();