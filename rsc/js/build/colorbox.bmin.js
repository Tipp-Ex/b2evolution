/* This includes 4 files: jquery.colorbox.js, voting.js, jquery.touchswipe.js, colorbox.init.js */
function init_voting_bar(a,b,c,d){function e(){if(a.closest("#colorbox").width()<=480?jQuery("#colorbox .vote_title_text").hide():jQuery("#colorbox .vote_title_text").show(),"cboxVoting"==a.attr("id")){var b=jQuery("#colorbox").width(),c=a.width();c>b&&jQuery("#colorbox").css({left:jQuery("#colorbox").position().left-Math.round(c-b)/2,width:c})}}function f(c,d,f,g){var h=a.find("#voting_action"),i=h.length?h.val():b;a.find("#votingID").length>0&&(i+="&vote_ID="+a.find("#votingID").val()),a.find("#widgetID").length>0&&(i+="&widget_ID="+a.find("#widgetID").val()),a.find("#skinID").length>0&&(i+="&skin_ID="+a.find("#skinID").val());var j=a.css("backgroundColor");jQuery(c).is(":checkbox")?jQuery(c).is(":checked")?(i+="&checked=1",votingFadeIn(a,f)):(i+="&checked=0",votingFadeIn(a,g)):(jQuery(c).removeAttr("id"),votingFadeIn(a,f)),jQuery.ajax({type:"POST",url:i+"&vote_action="+d,success:function(b){jQuery(c).is(":checkbox")||(a.html(ajax_debug_clear(b)),e()),votingFadeIn(a,j)}})}d&&(a.html('<div class="loading">&nbsp;</div>'),jQuery.ajax({type:"POST",url:b+"&vote_ID="+c,success:function(b){a.html(ajax_debug_clear(b)),e()}})),"undefined"==typeof a.is_inited&&(a.on("click","a.action_icon",function(){return!1}),a.on("click","#votingLike",function(){f(this,"like","#bcffb5")}),a.on("click","#votingNoopinion",function(){f(this,"noopinion","#bbb")}),a.on("click","#votingDontlike",function(){f(this,"dontlike","#ffc9c9")}),a.on("click","#votingInappropriate",function(){f(this,"inappropriate","#dcc","#bbb")}),a.on("click","#votingSpam",function(){f(this,"spam","#dcc","#bbb")}),a.is_inited=!0)}function votingFadeIn(a,b){if("transparent"==b||"rgba(0, 0, 0, 0)"==b){for(var c=a.parent(),d=b;c&&("transparent"==d||"rgba(0, 0, 0, 0)"==d);)d=c.css("backgroundColor"),c=c.parent();"HTML"!=c[0].tagName&&(b=d)}a.animate({backgroundColor:b},200)}function init_colorbox(a){if("object"==typeof a&&0!=a.length){var b=a.attr("rel").match(/lightbox\[([a-z]+)/i);switch(b=b?b[1]:"",b[1]){case"p":a.colorbox(b2evo_colorbox_params_post);break;case"c":a.colorbox(b2evo_colorbox_params_cmnt);break;case"user":a.colorbox(b2evo_colorbox_params_user);break;default:a.colorbox(b2evo_colorbox_params)}}}!function(a,b,c){function d(c,d,e){return e=b.createElement("div"),c&&(e.id=Q+c),e.style.cssText=d||"",a(e)}function e(a,b){return Math.round((/%/.test(a)?("x"===b?p.width():p.height())/100:1)*parseInt(a,10))}function f(a){return A.photo||/\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i.test(a)}function g(b){A=a.extend({},a.data(F,P));for(b in A)a.isFunction(A[b])&&"on"!==b.substring(0,2)&&(A[b]=A[b].call(F));A.rel=A.rel||F.rel||"nofollow",A.href=A.href||a(F).attr("href"),A.title=A.title||F.title,"string"==typeof A.href&&(A.href=a.trim(A.href))}function h(b,c){c&&c.call(F),a.event.trigger(b)}function i(){var a,b,c,d=Q+"Slideshow_",e="click."+Q;A.slideshow&&o[1]?(b=function(){v.text(A.slideshowStop).unbind(e).bind(U,function(){(G<o.length-1||A.loop)&&(a=setTimeout(N.next,A.slideshowSpeed))}).bind(T,function(){clearTimeout(a)}).one(e+" "+V,c),l.removeClass(d+"off").addClass(d+"on"),a=setTimeout(N.next,A.slideshowSpeed)},c=function(){clearTimeout(a),v.text(A.slideshowStart).unbind([U,T,V,e].join(" ")).one(e,b),l.removeClass(d+"on").addClass(d+"off")},A.slideshowAuto?b():c()):l.removeClass(d+"off "+d+"on")}function j(b){if(!K){if(F=b,g(),o=a(F),G=0,"nofollow"!==A.rel&&(o=a("."+R).filter(function(){var b=a.data(this,P).rel||this.rel;return b===A.rel}),G=o.index(F),G===-1&&(o=o.add(F),G=o.length-1)),!I){if(I=J=!0,l.show(),A.returnFocus)try{F.blur(),a(F).one(W,function(){try{this.focus()}catch(a){}})}catch(c){}k.css({cursor:A.overlayClose?"pointer":"auto"}).show(),A.w=e(A.initialWidth,"x"),A.h=e(A.initialHeight,"y"),N.position(),h(S,A.onOpen),z.add(t).hide(),y.html(A.close).show()}N.load(!0)}}var k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O={transition:"elastic",speed:300,width:!1,initialWidth:"600",innerWidth:!1,minWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,minHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,preloading:!0,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",openNewWindowText:"open in new window",open:!1,returnFocus:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:!1,displayVoting:!1,votingUrl:""},P="colorbox",Q="cbox",R=Q+"Element",S=Q+"_open",T=Q+"_load",U=Q+"_complete",V=Q+"_cleanup",W=Q+"_closed",X=Q+"_purge",Y=!a.support.opacity;N=a.fn[P]=a[P]=function(b,c){var d=this;if(b=b||{},!d[0]){if(d.selector)return d;d=a("<a/>"),b.open=!0}return c&&(b.onComplete=c),d.each(function(){a.data(this,P,a.extend({},a.data(this,P)||O,b)),a(this).addClass(R)}),(a.isFunction(b.open)&&b.open.call(d)||b.open)&&j(d[0]),d},N.init=function(){p=a(c),l=d().attr({id:P,"class":Y?Q+"IE":""}),k=d("Overlay").hide(),m=d("Wrapper"),n=d("Content").append(q=d("LoadedContent","width:0; height:0; overflow:hidden"),s=d("LoadingOverlay").add(d("LoadingGraphic")),t=d("Title"),$voting=d("Voting"),u=d("Current"),w=d("Next"),x=d("Previous"),v=d("Slideshow").bind(S,i),y=d("Close"),$open=d("Open")),m.append(n),r=d(!1,"position:absolute; width:9999px; visibility:hidden; display:none"),a("body").prepend(k,l.append(m,r)),$voting.data("voting_positions_done",0),previous_title="",n.children().hover(function(){a(this).addClass("hover")},function(){a(this).removeClass("hover")}).addClass("hover"),B=n.outerHeight(!0)-n.height(),C=n.outerWidth(!0)-n.width(),D=q.outerHeight(!0),E=q.outerWidth(!0),l.css({"padding-bottom":B,"padding-right":C}).hide(),w.click(function(){N.next()}),x.click(function(){N.prev()}),y.click(function(){N.close()}),$open.click(function(){N.close()}),z=w.add(x).add(u).add(v),n.children().removeClass("hover"),k.click(function(){A.overlayClose&&N.close()}),a(b).bind("keydown."+Q,function(a){var b=a.keyCode;I&&A.escKey&&27===b&&(a.preventDefault(),N.close()),I&&A.arrowKey&&o[1]&&(37===b?(a.preventDefault(),x.click()):39===b&&(a.preventDefault(),w.click()))})},N.remove=function(){l.add(k).remove(),a("."+R).removeData(P).removeClass(R)},N.position=function(a,c){function d(a){n[0].style.width=a.style.width,s[0].style.height=s[1].style.height=n[0].style.height=a.style.height}var f=0,g=0;p.unbind("resize."+Q),l.hide(),A.fixed?l.css({position:"fixed"}):(f=p.scrollTop(),g=p.scrollLeft(),l.css({position:"absolute"})),g+=A.right!==!1?Math.max(p.width()-A.w-E-C-e(A.right,"x"),0):A.left!==!1?e(A.left,"x"):Math.round(Math.max(p.width()-A.w-E-C,0)/2),f+=A.bottom!==!1?Math.max(b.documentElement.clientHeight-A.h-D-B-e(A.bottom,"y"),0):A.top!==!1?e(A.top,"y"):Math.round(Math.max(b.documentElement.clientHeight-A.h-D-B,0)/2),l.show(),a=l.width()===A.w+E&&l.height()===A.h+D?0:a||0,m[0].style.width=m[0].style.height="9999px",l.dequeue().animate({width:A.w+E,height:A.h+D,top:f,left:g},{duration:a,complete:function(){d(this),J=!1,m[0].style.width=A.w+E+C+"px",m[0].style.height=A.h+D+B+"px",c&&c(),setTimeout(function(){p.bind("resize."+Q,N.position)},1),m.parent().width()<=480?u.hide():u.show(),m.parent().width()<380?v.hide():v.show()},step:function(){d(this)}})},N.resize=function(a){if(I){if(a=a||{},a.width&&(A.w=e(a.width,"x")-E-C),a.innerWidth&&(A.w=e(a.innerWidth,"x")),q.css({width:A.w}),a.height&&(A.h=e(a.height,"y")-D-B),a.innerHeight&&(A.h=e(a.innerHeight,"y")),!a.innerHeight&&!a.height){var b=q.wrapInner("<div style='overflow:auto'></div>").children();A.h=b.height(),b.replaceWith(b.children())}q.css({height:A.h}),N.position("none"===A.transition?0:A.speed)}},N.prep=function(b){function c(){return A.w=A.w||q.width(),A.w=A.mw&&A.mw<A.w?A.mw:A.w,A.w=A.minWidth&&A.minWidth>A.w?A.minWidth:A.w,A.w}function e(){return A.h=A.h||q.height(),A.h=A.mh&&A.mh<A.h?A.mh:A.h,A.h=A.minHeight&&A.minHeight>A.h?A.minHeight:A.h,A.h}if(I){var g,i="none"===A.transition?0:A.speed;q.remove(),q=d("LoadedContent").append(b),q.hide().appendTo(r.show()).css({width:c(),overflow:A.scrolling?"auto":"hidden"}).css({height:e()}).prependTo(n),r.hide(),a(H).css({"float":"none"}),g=function(){function b(){Y&&l[0].style.removeAttribute("filter")}var c,d,e,g,j,k,m=o.length;I&&(k=function(){clearTimeout(M),s.hide(),h(U,A.onComplete)},Y&&H&&q.fadeIn(100),t.attr("title",A.title),t.html(A.title).add(q).show(),m>1?("string"==typeof A.current&&q.width()>380&&u.html(A.current.replace("{current}",G+1).replace("{total}",m)).show(),w[A.loop||G<m-1?"show":"hide"]().html(A.next),x[A.loop||G?"show":"hide"]().html(A.previous),c=G?o[G-1]:o[m-1],e=G<m-1?o[G+1]:o[0],A.slideshow&&q.width()>380&&v.show(),A.preloading&&(g=a.data(e,P).href||e.href,d=a.data(c,P).href||c.href,g=a.isFunction(g)?g.call(e):g,d=a.isFunction(d)?d.call(c):d,f(g)&&(a("<img/>")[0].src=g),f(d)&&(a("<img/>")[0].src=d))):z.hide(),A.iframe?(j=a("<iframe/>").addClass(Q+"Iframe")[0],A.fastIframe?k():a(j).one("load",k),j.name=Q+ +new Date,j.src=A.href,A.scrolling||(j.scrolling="no"),Y&&(j.frameBorder=0,j.allowTransparency="true"),a(j).appendTo(q).one(X,function(){j.src="//about:blank"})):k(),"fade"===A.transition?l.fadeTo(i,1,b):b())},"fade"===A.transition?l.fadeTo(i,0,function(){N.position(0,g)}):N.position(i,g)}},N.load=function(b){var c,i,j=N.prep;if(J=!0,H=!1,F=o[G],b||g(),h(X),h(T,A.onLoad),""==previous_title&&""!=A.title||""!=previous_title&&""==A.title){var k=0;if(""!==A.title){var l=0;A.displayVoting&&""!=A.votingUrl&&(l=$voting.outerHeight(),$voting.css("bottom","9px")),t.css("margin-bottom",l+y.outerHeight()-3),k=15}else k=-15,$voting.css("bottom","25px");q.css("margin-bottom",parseInt(q.css("margin-bottom"))+k),D+=k}previous_title=A.title,A.displayVoting&&""!=A.votingUrl&&""!=F.id?(0==$voting.data("voting_positions_done")&&(0==D&&(D=q.outerHeight(!0)),D+=$voting.outerHeight(),$voting.data("voting_positions_done",1)),$voting.show(),init_voting_bar($voting,A.votingUrl,F.id,!0)):""!=$voting.html()&&(D-=$voting.outerHeight(),$voting.html("").hide(),$voting.data("voting_positions_done",0)),A.h=A.height?e(A.height,"y")-D-B:A.innerHeight&&e(A.innerHeight,"y"),A.w=A.width?e(A.width,"x")-E-C:A.innerWidth&&e(A.innerWidth,"x"),A.mw=A.w,A.mh=A.h,A.maxWidth&&(A.mw=e(A.maxWidth,"x")-E-C,A.mw=A.w&&A.w<A.mw?A.w:A.mw),A.maxHeight&&(A.mh=e(A.maxHeight,"y")-D-B,A.mh=A.h&&A.h<A.mh?A.h:A.mh),c=A.href,M=setTimeout(function(){s.show()},100),A.inline?(d().hide().insertBefore(a(c)[0]).one(X,function(){a(this).replaceWith(q.children())}),j(a(c))):A.iframe?j(" "):A.html?j(A.html):f(c)?(a(H=new Image).addClass(Q+"Photo").error(function(){A.title=!1,j(d("Error").text("This image could not be loaded"))}).load(function(){var a;H.onload=null,A.scalePhotos&&(i=function(){H.height-=H.height*a,H.width-=H.width*a},A.mw&&H.width>A.mw&&(a=(H.width-A.mw)/H.width,i()),A.mh&&H.height>A.mh&&(a=(H.height-A.mh)/H.height,i())),A.h&&(H.style.marginTop=Math.max(A.h-H.height,0)/2+"px"),jQuery(H).removeClass("zoomin zoomout"),colorbox_is_zoomed=!1;var b=0,c=0,d=H.naturalWidth>1.1*H.width||H.naturalHeight>1.1*H.height;d&&(H.className=H.className+" zoomin"),!d&&o[1]&&(G<o.length-1||A.loop)&&(H.onclick=function(a){N.next()}),d&&jQuery(H).bind("click dblclick",function(a,d){if(colorbox_is_zoomed)H.className=H.className.replace(/zoomout/,""),H.width=b,H.height=c,jQuery(this).parent().scrollLeft(0).scrollTop(0),jQuery(this).css({position:"relative",top:"0",left:"0"});else{var e=jQuery(this).offset(),f="undefined"!=typeof a.pageX?a.pageX:d.originalEvent.touches[0].pageX,g="undefined"!=typeof a.pageY?a.pageY:d.originalEvent.touches[0].pageY,h=(f-e.left)/jQuery(this).width(),i=(g-e.top)/jQuery(this).height();H.className=H.className+" zoomout",b=H.width,c=H.height,H.removeAttribute("width"),H.removeAttribute("height");var j=jQuery(this).parent()[0];jQuery(this).parent().scrollLeft(h*(j.scrollWidth-j.clientWidth)).scrollTop(i*(j.scrollHeight-j.clientHeight))}colorbox_is_zoomed=!colorbox_is_zoomed}),Y&&(H.style.msInterpolationMode="bicubic"),setTimeout(function(){j(H)},1)}),setTimeout(function(){H.src=c},1),$open.html('<a href="'+c+'" target="_blank">'+A.openNewWindowText+"</a>").show()):c&&r.load(c,A.data,function(b,c,e){j("error"===c?d("Error").text("Request unsuccessful: "+e.statusText):a(this).contents())})},N.next=function(){!J&&o[1]&&(G<o.length-1||A.loop)&&(G=G<o.length-1?G+1:0,N.load())},N.prev=function(){!J&&o[1]&&(G||A.loop)&&(G=G?G-1:o.length-1,N.load())},N.close=function(){I&&!K&&(K=!0,I=!1,h(V,A.onCleanup),p.unbind("."+Q),k.fadeTo(200,0),l.stop().fadeTo(300,0,function(){l.add(k).css({opacity:1,cursor:"auto"}).hide(),h(X),q.remove(),setTimeout(function(){K=!1,h(W,A.onClosed)},1)}))},N.element=function(){return a(F)},N.settings=O,L=function(a){0!==a.button&&"undefined"!=typeof a.button||a.ctrlKey||a.shiftKey||a.altKey||(a.preventDefault(),j(this))},a.fn.delegate?a(b).delegate("."+R,"click",L):a(b).on("click","."+R,L),a(N.init)}(jQuery,document,this),jQuery.event.special.dblclick={setup:function(a,b){var c=this,d=jQuery(c);d.bind("touchstart.dblclick",jQuery.event.special.dblclick.handler)},teardown:function(a){var b=this,c=jQuery(b);c.unbind("touchstart.dblclick")},handler:function(a){var b=a.target,c=jQuery(b),d=c.data("lastTouch")||0,e=(new Date).getTime(),f=e-d;f>20&&f<500?(c.data("lastTouch",0),c.trigger("dblclick",a)):c.data("lastTouch",e)}},function(a){"function"==typeof define&&define.amd&&define.amd.jQuery?define(["jquery"],a):a(jQuery)}(function(a){"use strict";function b(b){return!b||void 0!==b.allowPageScroll||void 0===b.swipe&&void 0===b.swipeStatus||(b.allowPageScroll=j),void 0!==b.click&&void 0===b.tap&&(b.tap=b.click),b||(b={}),b=a.extend({},a.fn.swipe.defaults,b),this.each(function(){var d=a(this),e=d.data(z);e||(e=new c(this,b),d.data(z,e))})}function c(b,c){function A(b){if(!(ha()||a(b.target).closest(c.excludedElements,Qa).length>0)){var d,e=b.originalEvent?b.originalEvent:b,f=y?e.touches[0]:e;return Ra=u,y?Sa=e.touches.length:b.preventDefault(),Ha=0,Ia=null,Oa=null,Ja=0,Ka=0,La=0,Ma=1,Na=0,Ta=ma(),Pa=pa(),fa(),!y||Sa===c.fingers||c.fingers===s||P()?(ja(0,f),Ua=ya(),2==Sa&&(ja(1,e.touches[1]),Ka=La=sa(Ta[0].start,Ta[1].start)),(c.swipeStatus||c.pinchStatus)&&(d=H(e,Ra))):d=!1,d===!1?(Ra=x,H(e,Ra),d):(ia(!0),null)}}function B(a){var b=a.originalEvent?a.originalEvent:a;if(Ra!==w&&Ra!==x&&!ga()){var d,e=y?b.touches[0]:b,f=ka(e);if(Va=ya(),y&&(Sa=b.touches.length),Ra=v,2==Sa&&(0==Ka?(ja(1,b.touches[1]),Ka=La=sa(Ta[0].start,Ta[1].start)):(ka(b.touches[1]),La=sa(Ta[0].end,Ta[1].end),Oa=ua(Ta[0].end,Ta[1].end)),Ma=ta(Ka,La),Na=Math.abs(Ka-La)),Sa===c.fingers||c.fingers===s||!y||P()){if(Ia=xa(f.start,f.end),N(a,Ia),Ha=va(f.start,f.end),Ja=ra(),na(Ia,Ha),(c.swipeStatus||c.pinchStatus)&&(d=H(b,Ra)),!c.triggerOnTouchEnd||c.triggerOnTouchLeave){var g=!0;if(c.triggerOnTouchLeave){var h=za(this);g=Aa(f.end,h)}!c.triggerOnTouchEnd&&g?Ra=G(v):c.triggerOnTouchLeave&&!g&&(Ra=G(w)),Ra!=x&&Ra!=w||H(b,Ra)}}else Ra=x,H(b,Ra);d===!1&&(Ra=x,H(b,Ra))}}function C(a){var b=a.originalEvent;return y&&b.touches.length>0?(ea(),!0):(ga()&&(Sa=Xa),a.preventDefault(),Va=ya(),Ja=ra(),K()?(Ra=x,H(b,Ra)):c.triggerOnTouchEnd||0==c.triggerOnTouchEnd&&Ra===v?(Ra=w,H(b,Ra)):!c.triggerOnTouchEnd&&W()?(Ra=w,I(b,Ra,n)):Ra===v&&(Ra=x,H(b,Ra)),ia(!1),null)}function D(){Sa=0,Va=0,Ua=0,Ka=0,La=0,Ma=1,fa(),ia(!1)}function E(a){var b=a.originalEvent;c.triggerOnTouchLeave&&(Ra=G(w),H(b,Ra))}function F(){Qa.unbind(Ca,A),Qa.unbind(Ga,D),Qa.unbind(Da,B),Qa.unbind(Ea,C),Fa&&Qa.unbind(Fa,E),ia(!1)}function G(a){var b=a,d=M(),e=J(),f=K();return!d||f?b=x:!e||a!=v||c.triggerOnTouchEnd&&!c.triggerOnTouchLeave?!e&&a==w&&c.triggerOnTouchLeave&&(b=x):b=w,b}function H(a,b){var c=void 0;return T()||S()?c=I(a,b,l):(Q()||P())&&c!==!1&&(c=I(a,b,m)),ca()&&c!==!1?c=I(a,b,o):da()&&c!==!1?c=I(a,b,p):ba()&&c!==!1&&(c=I(a,b,n)),b===x&&D(a),b===w&&(y?0==a.touches.length&&D(a):D(a)),c}function I(b,j,k){var q=void 0;if(k==l){if(Qa.trigger("swipeStatus",[j,Ia||null,Ha||0,Ja||0,Sa]),c.swipeStatus&&(q=c.swipeStatus.call(Qa,b,j,Ia||null,Ha||0,Ja||0,Sa),q===!1))return!1;if(j==w&&R()){if(Qa.trigger("swipe",[Ia,Ha,Ja,Sa]),c.swipe&&(q=c.swipe.call(Qa,b,Ia,Ha,Ja,Sa),q===!1))return!1;switch(Ia){case d:Qa.trigger("swipeLeft",[Ia,Ha,Ja,Sa]),c.swipeLeft&&(q=c.swipeLeft.call(Qa,b,Ia,Ha,Ja,Sa));break;case e:Qa.trigger("swipeRight",[Ia,Ha,Ja,Sa]),c.swipeRight&&(q=c.swipeRight.call(Qa,b,Ia,Ha,Ja,Sa));break;case f:Qa.trigger("swipeUp",[Ia,Ha,Ja,Sa]),c.swipeUp&&(q=c.swipeUp.call(Qa,b,Ia,Ha,Ja,Sa));break;case g:Qa.trigger("swipeDown",[Ia,Ha,Ja,Sa]),c.swipeDown&&(q=c.swipeDown.call(Qa,b,Ia,Ha,Ja,Sa))}}}if(k==m){if(Qa.trigger("pinchStatus",[j,Oa||null,Na||0,Ja||0,Sa,Ma]),c.pinchStatus&&(q=c.pinchStatus.call(Qa,b,j,Oa||null,Na||0,Ja||0,Sa,Ma),q===!1))return!1;if(j==w&&O())switch(Oa){case h:Qa.trigger("pinchIn",[Oa||null,Na||0,Ja||0,Sa,Ma]),c.pinchIn&&(q=c.pinchIn.call(Qa,b,Oa||null,Na||0,Ja||0,Sa,Ma));break;case i:Qa.trigger("pinchOut",[Oa||null,Na||0,Ja||0,Sa,Ma]),c.pinchOut&&(q=c.pinchOut.call(Qa,b,Oa||null,Na||0,Ja||0,Sa,Ma))}}return k==n?j!==x&&j!==w||(clearTimeout(Za),X()&&!$()?(Ya=ya(),Za=setTimeout(a.proxy(function(){Ya=null,Qa.trigger("tap",[b.target]),c.tap&&(q=c.tap.call(Qa,b,b.target))},this),c.doubleTapThreshold)):(Ya=null,Qa.trigger("tap",[b.target]),c.tap&&(q=c.tap.call(Qa,b,b.target)))):k==o?j!==x&&j!==w||(clearTimeout(Za),Ya=null,Qa.trigger("doubletap",[b.target]),c.doubleTap&&(q=c.doubleTap.call(Qa,b,b.target))):k==p&&(j!==x&&j!==w||(clearTimeout(Za),Ya=null,Qa.trigger("longtap",[b.target]),c.longTap&&(q=c.longTap.call(Qa,b,b.target)))),q}function J(){var a=!0;return null!==c.threshold&&(a=Ha>=c.threshold),a}function K(){var a=!1;return null!==c.cancelThreshold&&null!==Ia&&(a=oa(Ia)-Ha>=c.cancelThreshold),a}function L(){return null===c.pinchThreshold||Na>=c.pinchThreshold}function M(){var a;return a=!c.maxTimeThreshold||!(Ja>=c.maxTimeThreshold)}function N(a,b){if(c.allowPageScroll===j||P())a.preventDefault();else{var h=c.allowPageScroll===k;switch(b){case d:(c.swipeLeft&&h||!h&&c.allowPageScroll!=q)&&a.preventDefault();break;case e:(c.swipeRight&&h||!h&&c.allowPageScroll!=q)&&a.preventDefault();break;case f:(c.swipeUp&&h||!h&&c.allowPageScroll!=r)&&a.preventDefault();break;case g:(c.swipeDown&&h||!h&&c.allowPageScroll!=r)&&a.preventDefault()}}}function O(){var a=U(),b=V(),c=L();return a&&b&&c}function P(){return!!(c.pinchStatus||c.pinchIn||c.pinchOut)}function Q(){return!(!O()||!P())}function R(){var a=M(),b=J(),c=U(),d=V(),e=K(),f=!e&&d&&c&&b&&a;return f}function S(){return!!(c.swipe||c.swipeStatus||c.swipeLeft||c.swipeRight||c.swipeUp||c.swipeDown)}function T(){return!(!R()||!S())}function U(){return Sa===c.fingers||c.fingers===s||!y}function V(){return 0!==Ta[0].end.x}function W(){return!!c.tap}function X(){return!!c.doubleTap}function Y(){return!!c.longTap}function Z(){if(null==Ya)return!1;var a=ya();return X()&&a-Ya<=c.doubleTapThreshold}function $(){return Z()}function _(){return(1===Sa||!y)&&(isNaN(Ha)||0===Ha)}function aa(){return Ja>c.longTapThreshold&&Ha<t}function ba(){return!(!_()||!W())}function ca(){return!(!Z()||!X())}function da(){return!(!aa()||!Y())}function ea(){Wa=ya(),Xa=event.touches.length+1}function fa(){Wa=0,Xa=0}function ga(){var a=!1;if(Wa){var b=ya()-Wa;b<=c.fingerReleaseThreshold&&(a=!0)}return a}function ha(){return!(Qa.data(z+"_intouch")!==!0)}function ia(a){a===!0?(Qa.bind(Da,B),Qa.bind(Ea,C),Fa&&Qa.bind(Fa,E)):(Qa.unbind(Da,B,!1),Qa.unbind(Ea,C,!1),Fa&&Qa.unbind(Fa,E,!1)),Qa.data(z+"_intouch",a===!0)}function ja(a,b){var c=void 0!==b.identifier?b.identifier:0;return Ta[a].identifier=c,Ta[a].start.x=Ta[a].end.x=b.pageX||b.clientX,Ta[a].start.y=Ta[a].end.y=b.pageY||b.clientY,Ta[a]}function ka(a){var b=void 0!==a.identifier?a.identifier:0,c=la(b);return c.end.x=a.pageX||a.clientX,c.end.y=a.pageY||a.clientY,c}function la(a){for(var b=0;b<Ta.length;b++)if(Ta[b].identifier==a)return Ta[b]}function ma(){for(var a=[],b=0;b<=5;b++)a.push({start:{x:0,y:0},end:{x:0,y:0},identifier:0});return a}function na(a,b){b=Math.max(b,oa(a)),Pa[a].distance=b}function oa(a){if(Pa[a])return Pa[a].distance}function pa(){var a={};return a[d]=qa(d),a[e]=qa(e),a[f]=qa(f),a[g]=qa(g),a}function qa(a){return{direction:a,distance:0}}function ra(){return Va-Ua}function sa(a,b){var c=Math.abs(a.x-b.x),d=Math.abs(a.y-b.y);return Math.round(Math.sqrt(c*c+d*d))}function ta(a,b){var c=b/a*1;return c.toFixed(2)}function ua(){return Ma<1?i:h}function va(a,b){return Math.round(Math.sqrt(Math.pow(b.x-a.x,2)+Math.pow(b.y-a.y,2)))}function wa(a,b){var c=a.x-b.x,d=b.y-a.y,e=Math.atan2(d,c),f=Math.round(180*e/Math.PI);return f<0&&(f=360-Math.abs(f)),f}function xa(a,b){var c=wa(a,b);return c<=45&&c>=0?d:c<=360&&c>=315?d:c>=135&&c<=225?e:c>45&&c<135?g:f}function ya(){var a=new Date;return a.getTime()}function za(b){b=a(b);var c=b.offset(),d={left:c.left,right:c.left+b.outerWidth(),top:c.top,bottom:c.top+b.outerHeight()};return d}function Aa(a,b){return a.x>b.left&&a.x<b.right&&a.y>b.top&&a.y<b.bottom}var Ba=y||!c.fallbackToMouseEvents,Ca=Ba?"touchstart":"mousedown",Da=Ba?"touchmove":"mousemove",Ea=Ba?"touchend":"mouseup",Fa=Ba?null:"mouseleave",Ga="touchcancel",Ha=0,Ia=null,Ja=0,Ka=0,La=0,Ma=1,Na=0,Oa=0,Pa=null,Qa=a(b),Ra="start",Sa=0,Ta=null,Ua=0,Va=0,Wa=0,Xa=0,Ya=0,Za=null;try{Qa.bind(Ca,A),Qa.bind(Ga,D)}catch($a){a.error("events not supported "+Ca+","+Ga+" on jQuery.swipe")}this.enable=function(){return Qa.bind(Ca,A),Qa.bind(Ga,D),Qa},this.disable=function(){return F(),Qa},this.destroy=function(){return F(),Qa.data(z,null),Qa},this.option=function(b,d){if(void 0!==c[b]){if(void 0===d)return c[b];c[b]=d}else a.error("Option "+b+" does not exist on jQuery.swipe.options");return null}}var d="left",e="right",f="up",g="down",h="in",i="out",j="none",k="auto",l="swipe",m="pinch",n="tap",o="doubletap",p="longtap",q="horizontal",r="vertical",s="all",t=10,u="start",v="move",w="end",x="cancel",y="ontouchstart"in window,z="TouchSwipe",A={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,triggerOnTouchEnd:!0,triggerOnTouchLeave:!1,allowPageScroll:"auto",fallbackToMouseEvents:!0,excludedElements:"label, button, input, select, textarea, a, .noSwipe"};a.fn.swipe=function(c){var d=a(this),e=d.data(z);if(e&&"string"==typeof c){if(e[c])return e[c].apply(this,Array.prototype.slice.call(arguments,1));a.error("Method "+c+" does not exist on jQuery.swipe")}else if(!(e||"object"!=typeof c&&c))return b.apply(this,arguments);return d},a.fn.swipe.defaults=A,a.fn.swipe.phases={PHASE_START:u,PHASE_MOVE:v,PHASE_END:w,PHASE_CANCEL:x},a.fn.swipe.directions={LEFT:d,RIGHT:e,UP:f,DOWN:g,IN:h,OUT:i},a.fn.swipe.pageScroll={NONE:j,HORIZONTAL:q,VERTICAL:r,AUTO:k},a.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:s}}),jQuery(document).ready(function(){jQuery('a[rel^="lightbox"]').each(function(){init_colorbox(jQuery(this))}),jQuery("#colorbox").swipe({swipeLeft:function(a,b,c,d,e){"undefined"!=typeof colorbox_is_zoomed&&colorbox_is_zoomed||jQuery.colorbox.next()},swipeRight:function(a,b,c,d,e){"undefined"!=typeof colorbox_is_zoomed&&colorbox_is_zoomed||jQuery.colorbox.prev()}}),jQuery(document).on("click","#colorbox img.cboxPhoto",function(){jQuery(this).hasClass("zoomout")?jQuery("#colorbox").swipe("disable"):jQuery("#colorbox").swipe("enable")})});