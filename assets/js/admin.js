"use strict";!function(e){function n(e,n){n||(n=window.location.href),e=e.replace(/[\[\]]/g,"\\$&");var t=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)"),o=t.exec(n);return o?o[2]?decodeURIComponent(o[2].replace(/\+/g," ")):"":null}e(document).ready(function(){return"admin_page_ldAdvQuiz"===adminpage&&("1"===n("id")&&void e(document).ajaxComplete(function(t,o,a){return"statisticLoadUser"===n("func",a.data)&&void e("#wpProQuiz_user_content tbody a").each(function(n,t){var o=e(t).html();o.length>55&&(o=o.substr(0,52)+"..."),e(t).html(o)})}))})}(jQuery);
//# sourceMappingURL=admin.js.map
