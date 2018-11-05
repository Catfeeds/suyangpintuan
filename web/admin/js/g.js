function Extend(tgt,src){for(var i in src){tgt[i]=src[i]}return tgt};

function G(a){return typeof a==='string'?document.getElementById(a):a};

var loads=[];window.onload=function(){loads.each(function(){this()})};//加载完毕执行

Extend(String.prototype,{ltrim:function(){return this.replace(/^\s+/,'')},rtrim:function(){return this.replace(/\s+$/,'')},trim:function(){return this.replace(/^\s+|\s+$/g,'')}});

Extend(Array.prototype,{

    append:function(o){this[0].appendChild(o);return this},

    insertBefore:function(o){o.parentNode.insertBefore(this[0],o)},

    css:function(a){

        this.each(function(){

            if(typeof a==='string')this.style.cssText=a;

            if(typeof a==='object')for(var i in a)this.style[i] = a[i];

        });return this

    },

    has:function(e){for(var i in this){if(this[i]===e)return true;}return false},

    attr:function(a,b){this.each(function(){this.setAttribute(a,b)});return this},

    hide:function(){this.each(function(){this.style.display='none'});return this},

    show:function(){this.each(function(){this.style.display='block'});return this},

    each:function(f){for(var i=0,l=this.length;i<l;i++)if(f.call(this[i],i)===false)break;return this},//base

    remove:function(){this.each(function(){this.parentNode.removeChild(this)});return this},

    merge:function(arr){for(var i=0,l=arr.length;i<l;i++)this[this.length]=arr[i];return this},

    html:function(a){if(typeof a==='undefined')return this[0].innerHTML;else{this[0].innerHTML=a;return this}},

    click:function(fn){this.each(function(i){this.onclick=function(e){return fn.call(this,e||window.event,i)}});return this},

    mousedown:function(fn){this.each(function(i){this.onmousedown=function(e){fn.call(this,e||window.event,i)}});return this},

    siblings:function(){var e=this[0],n=e.parentNode.firstChild,r=[];for(;n;n=n.nextSibling)(n.nodeType===1&&n!==e)&&r.push(n);return r;},

    prev:function(){var o=this[0].previousSibling;if(!o)return null;while(typeof o.nodeType!='undefined' && o.nodeType!=1)o=o.previousSibling;return o;},

    next:function(){var o=this[0].nextSibling;if(!o)return null;while(typeof o.nodeType!='undefined' && o.nodeType!=1){o=o.nextSibling;if(o==null)return null};return o},

    hover:function(a,b){this.each(function(i){this.onmouseover=function(){a.call(this,i)};this.onmouseout=function(){b.call(this,i)}});return this},

    hasClass:function(b){return $.hasClass(this[0],b)},

    addClass:function(c){this.each(function(){if((' '+this.className+' ').indexOf(' '+c+' ')===-1)this.className=(this.className+' '+c).trim();});return this},

    removeClass:function(c){this.each(function(){if(typeof c=='undefined'){this.className=''}else{var a=(' '+this.className+' ').replace(' '+c+' ',' ');this.className=a.trim()}});return this},

    toggleClass:function(c){this.each(function(){var cn=this.className;if((' '+cn+' ').indexOf(' '+c+' ')===-1){[this].addClass(c);}else{[this].removeClass(c);}});return this},

    indexOf:function(a){for(var i=this.length-1;i>-1;i--){if(this[i]==a)return i}return -1},

    val:function(value){if(typeof value=='undefined')return this[0].value;else this.each(function(){this.value=value})},

    motion:function(options,times,callback){$motion.push(this[0],options,times,callback);return this}

});

var _global=function(){

    this.isIE=document.all?true:false;

    this.isIE6=this.isIE&&([/MSIE (\d+)\.0/i.exec(navigator.userAgent)][0][1]==6);

    this.isIE7=this.isIE&&([/MSIE (\d+)\.0/i.exec(navigator.userAgent)][0][1]==7);

    this.isChrome=/Chrome/.test(navigator.userAgent);

    this.isTouchPad = (/hp-tablet/gi).test(navigator.appVersion);

    this.hasTouch = 'ontouchstart' in window && !this.isTouchPad;

    this.xhr=this.xmlhttp();

    this.Data={};

    this.js=[];

    this.reqkey=null;

};

_global.prototype={

    extend:Extend,

    bind:function(o,fn){return function(){fn.apply(o)}},

    alert:function(s){alert('Thanks for use this Method: '+s)},

    jsonDecode:function(d){return (new Function("return"+d))();},

    print:function(D){var str='';for(var i in D)str+=i+':'+D[i]+"\n";alert(str)},

    bindEvt:function(o,fn){return function(e){return fn.call(o,(e||window.event))}},

    eval:function(d){d=''+d;if(d=='')return;(window.execScript||function(x){window.eval.call(window,x)})(d);},

    css:function(e){return e.currentStyle||document.defaultView.getComputedStyle(e,null)},

    addEvt:function(o,evt,fn){var z=function(){fn.call(o,arguments)};this.isIE?o.attachEvent("on"+evt,z):o.addEventListener(evt,z,false);},

    delEvt:function(o,evt,fn){this.isIE?o.detachEvent('on'+evt,fn):o.removeEventListener(evt,fn,false)},

    hasClass:function(o,c){return (' '+o.className+' ').indexOf(' '+c+' ')!==-1?true:false},

    xmlhttp:function(){return this.isIE?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest()},

    to_array:function(src){var dst = [];for(var i=0,l=src.length;i<l;i++){dst[i] = src[i];}return dst},

    map:function(arr,fn){var res=[];for(var i=0,len=arr.length;i<len;i++){res.push(fn(arr[i]))}return res;},

    ePos:function(e){e=G(e);var l=0,t=0;do{l+=e.offsetLeft;t+=e.offsetTop;}while(e=e.offsetParent);return{x:l,y:t}},

    mPos:function(e){e=e||window.event;return e.pageX?{x:e.pageX,y:e.pageY}:{x:e.clientX+document.body.scrollLeft-document.body.clientLeft,y:e.clientY+document.body.scrollTop-document.body.clientTop}},

    loadJs:function(src,callback,nocache){

        src=src.indexOf('http://')!==-1?src:src+_version;

        //src=src+_version+Math.random();

        if(!this.js.has(src) || nocache){

            this.js.push(src);

            var e=document.createElement('script');e.src=src;e.type='text/javascript';

            if(typeof callback==='function')$.isIE?e.onreadystatechange=function(){(!this.readyState||this.readyState=='loaded'||this.readyState=='complete')&&callback()}:e.onload=callback;

            $('>head')[0].appendChild(e);

        }else typeof callback==='function'&&callback();

    },

    encode:function(o){

        var c=[];

        for(var i in o)c.push(typeof o[i]=='object'?this.encode(o[i]):(i+'='+encodeURIComponent(o[i])));

        return c.join('&');

    },



    getDataType:function(obj){

        var typeString = Object.prototype.toString.call(obj);

        var pos = typeString.indexOf(']')-8;

        return typeString.substr(8,pos).toLowerCase();

    },

    deepEncode:function(data,key,parentKey){

        var dataType = this.getDataType(data);

        var i,segments=[];

        if(typeof parentKey!='undefined')key = parentKey+'['+key+']';



        switch(dataType){

            case 'string':segments.push(key+'='+data);break;

            case 'object':

                for(i in data)segments.push(this.deepEncode(data[i],i,key));

                break;

            case 'array':

                for(i=0;i<data.length;i++)segments.push(this.deepEncode(data[i],i,key));

                break;

            case 'number':segments.push(key+'='+data);break;

        }

        return segments.join('&');

    },

    ajax:function(){

        var o={url:null,method:'post',newLink:false,dataType:'',callback:function(x){alert(x)},cache:false,jsonData:false},T=this.Data;

        this.extend(o,arguments[0]);

        var x=o.newLink?this.xmlhttp():this.xhr,url='',data='',fields=[];

        var pos=o.url.indexOf('?');

        if(pos!==-1){url=o.url.substr(0,pos);data=o.url.substr(pos+1);}

        if(o.data!=null){

            url==''&&(url=o.url);

            data=(data==''?'':data+'&')+this.deepEncode(o.data);

        }

        var key=o.url+data.substr(0,120).replace(/=|&/g,'');

        this.reqkey = key;//用于后退

        if(T[key]!=undefined&&o.cache){o.callback(T[key]);return;}

        if(o.method=='get'){data==''||(url+='?'+data);data=null}

        x.abort();

        var isJson = o.dataType.toLowerCase()==='json';

        var funcName = 'callback_'+Math.floor(Math.random()*100000000);

        if(isJson)window[funcName] = o.callback;

        with(x){

            open(o.method,url?url:o.url,true);

            onreadystatechange=function(){

                if(readyState==4&&status==200){

                    T[key]=responseText;

                    isJson?$.eval(funcName+'('+responseText+');'):o.callback(responseText);

                }

            }

            if(o.method=='post'){setRequestHeader("Content-Type","application/x-www-form-urlencoded");setRequestHeader("charset","utf-8");}

            send(data);

        }

    },



    // fan 2016-04-20 start
    // 获取表单提交值的快捷方法

    formData: function(form) {
        var inputs = form.querySelectorAll('input,textarea,select'),
            i, res = {};
        for (i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled || (inputs[i].type == 'checkbox' || inputs[i].type == 'radio') && inputs[i].checked === false) {
                continue;
            }
            res[inputs[i].name] = inputs[i].value;
        }
        return res;
    },


    // fan 2016-04-20 end





    //选择器[元素A，元素B].each();

    //$('#id input').each(fun);

    rule:function(a,b){

        var e=[b||document];var types={

            '#':this.byId,'.':this.byClass,'>':this.byTagName,' ':this.byTagName,':':this.byType,'=':this.byName,'default':true

        },obj=[];

        if(typeof a==='object')return [a];

        if(typeof types[a.charAt(0)]!=='function')a='>'+a;



        for(var i=0,k=-1,l=a.length;i<l;i++){

            var s = a.charAt(i);

            if(typeof types[s]==='function'){k++;obj[k]='';}

            obj[k]+= s;

        }



        var rule;

        for(var i=0,l=obj.length;i<l;i++){

            rule = obj[i].trim();

            if(rule=='')continue;

            var index=rule.charAt(0);

            if(typeof types[index]!=='function')rule='>'+rule;

            e=types[rule.charAt(0)](e,rule.substr(1));

        }

        return e

    },

    byType:function(o,t){var tags=[];o.each(function(){if(this.type==t)tags.push(this);});return tags},

    byId:function(o,t){var e=o[0].getElementById(t);return e?[e]:[]},

    byTagName:function(o,t){var tags=[];o.each(function(i){tags.merge(o[i].getElementsByTagName(t))});return tags;},

    byClass:function(o,t){var _=[];o.each(function(i){var m=this.getElementsByTagName('*');for(var j=0,k=m.length;j<k;j++){if((' '+m[j].className+' ').indexOf(' '+t+' ')!==-1)_.push(m[j])}});return _;},

    byName:function(o,t){var tags=[];o.each(function(i){tags.merge(o[i].getElementsByName(t))});return tags}

};

var $set=new _global();var $=function(a,b){return $set.rule(a,b);};Extend($,$set);

if(!$.isIE)HTMLElement.prototype.contains=function(b){return this.compareDocumentPosition(b)==20||this.compareDocumentPosition(b)==0};



var T={quad:{0:function(t,b,c,d){return c*(t/=d)*t+b;},1:function(t,b,c,d){return -c*(t/=d)*(t-2)+b;},2:function(t,b,c,d){if((t/=d/2)<1)return c/2*t*t+b;return -c/2*((--t)*(t-2)-1)+b;}},cubic:{0:function(t,b,c,d){return c*(t/=d)*t*t+b;},1:function(t,b,c,d){return c*((t=t/d-1)*t*t+1)+b;},2:function(t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t+b;return c/2*((t-=2)*t*t + 2)+b;}},quart:{0:function(t,b,c,d){return c*(t/=d)*t*t*t+b;},1:function(t,b,c,d){return -c*((t=t/d-1)*t*t*t-1)+b;},2:function(t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t+b;return -c/2*((t-=2)*t*t*t-2)+b;}},quint:{0:function(t,b,c,d){return c*(t/=d)*t*t*t*t+b;},1:function(t,b,c,d){return c*((t=t/d-1)*t*t*t*t+1)+b;},2:function(t,b,c,d){if((t/=d/2)<1)return c/2*t*t*t*t*t+b;return c/2*((t-=2)*t*t*t*t+2)+b;}},sine:{0:function(t,b,c,d){return -c*Math.cos(t/d*(Math.PI/2))+c+b;},1:function(t,b,c,d){return c*Math.sin(t/d*(Math.PI/2))+b;},2:function(t,b,c,d){return -c/2*(Math.cos(Math.PI*t/d)-1)+b;}},expo:{0:function(t,b,c,d){return (t==0)?b:c*Math.pow(2,10*(t/d-1))+b;},1:function(t,b,c,d){return (t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b;},2:function(t,b,c,d){if(t==0)return b;if(t==d)return b+c;if((t/=d/2)<1)return c/2*Math.pow(2,10*(t-1))+b;return c/2*(-Math.pow(2,-10*--t)+2)+b;}},circ:{0:function(t,b,c,d){return -c*(Math.sqrt(1-(t/=d)*t)-1)+b;},1:function(t,b,c,d){return c*Math.sqrt(1-(t=t/d-1)*t)+b;},2:function(t,b,c,d){if((t/=d/2)<1)return -c/2*(Math.sqrt(1-t*t)-1)+b;return c/2*(Math.sqrt(1-(t-=2)*t)+1)+b;}},elastic:{0:function(t,b,c,d){if(t==0)return b;if((t/=d)==1)return b+c;if(!p)var p=d*.3;if(!a||a<Math.abs(c)){var a=c;var s=p/4;}else var s=p/(2*Math.PI)*Math.asin(c/a);return -(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;},1:function(t,b,c,d){if(t==0)return b;if((t/=d)==1)return b+c;if(!p)var p=d*.3;if(!a||a<Math.abs(c)){var a=c;var s=p/4;}else var s=p/(2*Math.PI)*Math.asin(c/a);return (a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b);},2:function(t,b,c,d){if(t==0)return b;if((t/=d/2)==2)return b+c;if(!p)var p=d*(.3*1.5);if(!a||a<Math.abs(c)){var a=c;var s=p/4;}else var s=p/(2*Math.PI)*Math.asin(c/a);if(t<1)return -.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*.5+c+b;}},back:{0:function(t,b,c,d){if(s==undefined)var s=1.70158;return c*(t/=d)*t*((s+1)*t-s)+b;},1:function(t,b,c,d){if(s==undefined)var s=1.70158;return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b;},2:function(t,b,c,d){if(s==undefined)var s=1.70158; if((t/=d/2)<1)return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b;}},bounce:{0:function(t,b,c,d){return c-T.bounce[1](d-t,0,c,d)+b;},1:function(t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b;}else if(t<(2/2.75)) {return c*(7.5625*(t-=(1.5/2.75))*t+.75)+b;}else if(t<(2.5/2.75)) {return c*(7.5625*(t-=(2.25/2.75))*t+.9375)+b;}else{return c*(7.5625*(t-=(2.625/2.75))*t+.984375)+b;}},2:function(t,b,c,d){if(t<d/2)return T.bounce[0](t*2,0,c,d)*.5+b;else return T.bounce[1](t*2-d,0,c,d)*.5+c*.5+b;}},linear:{1:function(t,b,c,d){ return c*t/d + b; }}};

var $motion=function(){

    var t=this;

    t.globalTimer=0;//定时器

    t.items=[];//运动对象集合

    t.delay=$.isIE?15:15;//

    t.animate=['quad','cubic','quart','quint','sine','expo','circ','elastic','back','bounce','linear'];

    t.unusual=' backgroundPosition opacity ';

    t.objlib={};

    t.onLoad();

};$motion.prototype={

    onLoad:function(){

        var t=this;

        t.globalTimer=setInterval(function(){t.interval.call(t)},t.delay);

    },

    interval:function(){

        var arr=this.items;

        //完成运动的对象,从数组中移除(splice)时,倒叙循环中的键值不会受数组更新而影响

        for(var j=(arr.length-1);j>=0;j--){

            for(var i in this.items[j][1])this[this.items[j][1][i].fn](j,i);

            //this.items[j][0].style.cssText=str;

            if(++this.items[j][2]>this.items[j][3]){

                var fn=this.items[j][4];

                this.items.splice(j,1);//从对象集合中移除

                if(typeof fn==='function')fn();

            }

            //var date2=new Date();

            //$('#dates').html($('#dates').html()+'<br />'+(date2.getTime()-date1)+':'+date2.getTime()+':'+date2.getMilliseconds());

        }



    },

    push:function(obj,options,times,callback){

        //检测,如果要运动的目标值与当前值一样,则不操作,主要是下面有个display:block操作会导致部分问题.

        obj=G(obj);

        callback=callback||false;

        obj.style.display='block';

        for(var i in options){

            options[i]=Extend({from:false,ease:10,mode:1},options[i]);

            options[i].fn=this.unusual.indexOf(' '+i+' ')===-1?'general':i;

            options[i].motion = T[this.animate[options[i].ease]][options[i].mode];



            if(options[i].from===false){

                options[i].from = i=='backgroundPosition'?this.getBgPosVal(obj):parseInt($.css(obj)[i]);

                if(isNaN(options[i].from)){

                    options[i].from = i=='height'?obj.offsetHeight:i=='width'?obj.offsetWidth:0;

                }

            }

            if(i=='backgroundPosition')options[i].to=[options[i].to[0]-options[i].from[0],options[i].to[1]-options[i].from[1]];

            else{options[i].to = options[i].to-options[i].from;}

            //if(i=='opacity')alert($.css(obj)[$.isIE?'filter':'opacity']);

        }

        var opts=[obj,options,0,times,callback];

        for(var i=0,l=this.items.length;i<l;i++){if(this.items[i][0]==obj){this.items[i] = opts;return}}

        this.items.push(opts);

    },

    getBgPosVal:function(obj){

        var css=$.css(obj);

        if($.isIE){

            var o=[parseInt(css.backgroundPositionX),parseInt(css.backgroundPositionY)];

        }else{

            var o=css.backgroundPosition.split(' ');

            var o=[parseInt(o[0]),parseInt(o[1])];

        }

        return o;

    },

    //一般样式计算

    general:function(j,i){

        //alert(this.items[j][1][i].from+':'+this.items[j][1][i].to+':');

        var e=this.items[j][1][i].motion(this.items[j][2],this.items[j][1][i].from,this.items[j][1][i].to,this.items[j][3]);

        if((i=='width'||i=='height')&&e<0)e=0;

        this.items[j][0].style[i] = e+'px';

    },

    opacity:function(j,i){

        var e=this.items[j][1][i].motion(this.items[j][2],this.items[j][1][i].from,this.items[j][1][i].to,this.items[j][3]);

        var obj=$.isIE?['filter','alpha(opacity='+(e*100)+')']:['opacity',e];

        this.items[j][0].style[obj[0]] = obj[1];

    },

    backgroundPosition:function(j,i){

        var a=this.items[j][1][i].motion(this.items[j][2],this.items[j][1][i].from[0],this.items[j][1][i].to[0],this.items[j][3]);

        var b=this.items[j][1][i].motion(this.items[j][2],this.items[j][1][i].from[1],this.items[j][1][i].to[1],this.items[j][3]);

        this.items[j][0].style.backgroundPosition = a+'px '+b+'px';

    },

    stop:function(){clearInterval(this.globalTimer)}

};$motion=new $motion;



/*>>>>>Event-Handle*/

/*--------------事件处理机制----------------*/

var GE=function(){

    var t=this;

    t.tipBox=[];

    t.fnBox=[];

    t.mousedown = $set.hasTouch ? 'ontouchstart' : 'onmousedown';

    t.mousemove = $set.hasTouch ? 'ontouchmove' : 'onmousemove';

    t.mouseup = $set.hasTouch ? 'ontouchend' : 'onmouseup';



    t.rule={mouseover:/o[\d]-([^\s]*)/g,click:/e[\d]-([^\s]*)/g,mousedown:/d[\d]-([^\s]*)/g,mouseout:/u[\d]-([^\s]*)/g};

    document.documentElement.onclick = function(e){e=e||window.event;t.finder(e.target||e.srcElement,e,t.rule.click)};

    document.onmousedown = function(e){e=e||window.event;t.finder(e.target||e.srcElement,e,t.rule.mousedown)};

    document.onkeydown=function(e){var e=e||window.event;var key=e.keyCode||e.which;if(key==27){com.hide()}}

    t.EvtBubble={

        //(class="ea-class-func")[禁止冒泡]

        1:function(){return true},

        //(class="eb-class-func")[阻止返回默认值]

        2:function(e){$.isIE?e.returnValue=false:e.preventDefault();return false},

        //(class="ec-class-func")[禁止冒泡并阻止返回默认值]

        3:function(e){$.isIE?e.returnValue=false:e.preventDefault();return true;}

    };

};

GE.prototype={

    checkTag:function(el){

        //el还未读取到html标签时,就已经是null的时候,可能是先执行了click事件移除了本元素导致.

        return (el==null||el.tagName.toLowerCase()=='html')?false:true

    },

    finder:function(target,event,rule){

        var FuncList=[];

        var c,el=target,isStop=false;

        //G('test').innerHTML+='<hr />';

        while(this.checkTag(el)){

            //G('test').innerHTML+=event.type+':'+el.tagName+'@'+el.className+'<br />';

            if(c=el.className.match(rule)){

                for(var i=0,l=c.length;i<l;i++){

                    var a=c[i].replace(/[eod]([\d])-/,'').split('-');

                    var sign=RegExp.$1;

                    var cls=a[0],fn=a[1];

                    var cname=window[cls];

                    if(cname==undefined)return;

                    var func=cname[fn];

                    if(typeof func==='function'){

                        FuncList.push(function(_el,cname,func){

                            return function(){

                                cname._event=event;//当前事件event

                                cname._target=target;//触发事件的标签

                                cname._self=_el;//设置事件的标签

                                func.apply(cname,a.slice(2));

                            }

                        }(el,cname,func));

                    }else alert('undefined:'+cls+'.'+fn);

                    if(this.EvtBubble[sign]!=undefined)isStop=this.EvtBubble[sign](event);

                }

                if(isStop)break;else el=el.parentNode;

            }else el=el.parentNode;

        }



        if(!isStop && event.type=='mousedown')this.clear();

        FuncList.each(function(){this()});

    },

    clear:function(){for(var i=0,l=this.tipBox.length;i<l;i++)this.tipBox[i].style.display='none';this.tipBox=[];this.fnBox.each(function(){this()});this.fnBox=[]},

    callback:function(fn){typeof fn==='function'&&this.fnBox.push(fn)},

    push:function(el){el=G(el);if(!el)return;if(!this.tipBox.has(el))this.tipBox.push(el)}

};GE=new GE;

/*Event-Handle<<<<<*/



var rjs=/<script.*?(?:src="([^"]*)")?>([\w\W]*?)<\/script>/gi;

var com=function(){this.scripts=[];this.lmenus;this.topmenus;this.Keditor=null;this.currentEditor=null;};

com.prototype={

    stopEvt:function(){},

    ExeScript:function(x){

        rjs.lastIndex=0;var v=[[],''];while(rjs.exec(x)){

            /*alert(RegExp.$1+"\n"+RegExp.$2);*/

            RegExp.$1!=''&&v[0].push(RegExp.$1);RegExp.$2!=''&&(v[1]+=RegExp.$2+';');}return v

    },

    DP:function(){

        var t=this;$.DP==undefined?t.loadJs('js/adm/date.js',function(){t.DatePicker(t._self)}):t.DatePicker(t._self)},

    //日期选择器

    DatePicker:function(o){

        var pos=$(o).offset();

        new $.DP({target:o,from:[2011,1,1],to:[2020,12,30],index:'input'});

        $('#calendar').css({left:pos.left+'px',top:(pos.top+o.offsetHeight)+'px',display:'block'});

        $Evt.push(G('calendar'));

    },

    /*left-menu*/

    menul:function(){

        var a=this._self,b=this._target;

        if(a==b)return false;//不允许自己触发,只允许子节点触发

        while(b.tagName.toLowerCase()!='a')b=b.parentNode;//a的所有子节点都要能触发(包括a)

        $ini({url:b.getAttribute('url'),callback:function(x){$box.innerHTML=x}},true);

        //GE.stopBubble(this.event);

    },

    menufn:function(a,b){

        if(a==b)return false;//不允许自己触发,只允许子节点触发

        while(b.tagName.toLowerCase()!='a')b=b.parentNode;//a的所有子节点都要能触发(包括a

        var e=$('#menu.on')[0];

        $ini({url:b.href,data:{id:e.rel},callback:function(x){$box.innerHTML=x}},true);

    },

    /*top-menu*/

    menut:function(){

        var a=this._self,b=this._target;

        if(b.tagName.toLowerCase()=='a'){

            var index=$('>a',a).indexOf(b);//当前菜单序号

            var url=$('.on',$('#oLeft>ul')[index])[0].getAttribute('url');

            $ini({url:url,method:'post',callback:function(x){$box.innerHTML=x;}},true);

        }

    },

    reset:function(key){

        var menu=G(key);

        if(menu){

            var tMenus=$('#menu>a'),aNavs=$('#oLeft.A-nav'),bNavs=$('#oLeft.B-nav'),operaTag=$('#opera_tag');

            var isLeft=menu.parentNode.tagName.toLowerCase()=='li';//是否是左边的菜单

            var index=isLeft?aNavs.indexOf(menu.parentNode.parentNode):tMenus.indexOf(menu);//获得位置

            //oLeft的A-nav模块显示处理,以及当前显示块内链接的on处理

            [aNavs.hide()[index]].show();

            if(isLeft){$('>a',aNavs[index]).removeClass('on');[menu].addClass('on');}//左边的菜单on

            //顶部菜单模块on

            var topMenu=tMenus.removeClass('on')[index];

            [topMenu].addClass('on');

            //---对应操作模块显示

            bNavs.hide();operaTag.hide();

            var fnel=G('mod_'+topMenu.type.toLowerCase());

            if(fnel){[fnel].show();operaTag.show()}

        }

    },

    iTab:function(){

        var e=this._target;

        //console.log([e].hasClass('tab-btn'));

        if([e].hasClass('tab-btn')){

            var num=$('.tab-btn',this._self).removeClass('on').indexOf(e);

            [e].addClass('on');

            var mods=$('.tab-mod',this._self.parentNode);

            if(mods.length==0)mods=$('.tab-mod',this._self.parentNode.parentNode);

            var El=mods.hide()[num];

            if(El)El.style.display='block';

        }

    },

    tab:function(a,b){

        if(b.className=='tabBtn'){

            var tabs=$('.tabBtn',a);

            $('.on',a).removeClass('on');

            [b].addClass('on');



            var i=tabs.indexOf(b);

            $('.tabMod',a.parentNode).each(function(index){this.style.display=index==i?'block':'none'});

        }

    },

    focus:function(o){o.value==o.defaultValue&&(o.value='')},

    blur:function(o){o.value==''&&(o.value=o.defaultValue)},

    show:function(tag,txt){

        G('overlay').style.display='block';

        var a=this.win;

        typeof txt=='string'?this.box.innerHTML=txt:this.box.appendChild(txt);

        this.tag.innerHTML=tag;

        a.style.top='100px';

        a.style.left='200px';

        a.style.display='block';

    },

    hideself:function(){

        [this._target.parentNode.parentNode].hide();

    },

    hide:function(){

        G('overlay').style.display='none';

        var win=$('.win');

        if(win.length==1)win[0].style.display='none';

    },

    initWin:function(){

        //交互框

        this.win = $('.win')[0];

        this.tag = $('.e-drag',this.win);

        this.txt = $('.xHtml',this.win);

        this.overlay = $('#overlay');

    },

    initTip:function(){

        //操作结果提示rTip

        this.rTip=$('#rTip');

        this.tipC=$('#rTip.c');

        this.tipTimer=null;

    },

    //操作结果提示

    xtip:function(msg,o){

        var t=this;

        var opts={time:3,overlay:false,type:0,hideoverlay:false};

        if(typeof o==='object')Extend(opts,o);

        clearTimeout(t.tipTimer);

        t.rTip[0].className='';

        t.rTip.addClass(['ok','warning','error'][opts.type]);

        t.tipC.html(msg);

        Extend(t.rTip[0].style,{display:'block',visibility:'hidden'});

        var w=t.rTip[0].offsetWidth;

        t.rTip.css('margin-left:-'+(w/2)+'px;visibility:visible;');

        t.rTip.show();

        if(opts.overlay)this.overlay.show();

        if(opts.time!=0)t.tipTimer=setTimeout(function(){t.rTip.hide();if(opts.hideoverlay)t.overlay.hide()},opts.time*1000);

    },

    xshow:function(tag,txt,_options,callback){

        //options={overlay:true,isText:true}

        var options={isText:true,overlay:true,btnCancel:true,btnTrue:true,trueEvent:'',cancelEvent:'',trueCallback:false,hideBtn:false};

        Extend(options,_options);

        this.tag.html(tag);

        this.txt.html(txt);

        $('.check',this.win).hide();

        this.win.style.display='block';

        this.win.style.visibility='hidden';

        options.isText?this.txt.addClass('xText'):this.txt.removeClass('xText');

        if(!options.hideBtn)$('.check',this.win).show();



        var h=this.win.offsetHeight,w=this.win.offsetWidth,winW=document.documentElement.clientWidth,winH=document.documentElement.clientHeight;

        var height = h;

        if(h>=winH) h=height=winH;

        else h='';



        var top=((winH-height)/2+(document.documentElement.scrollTop||document.body.scrollTop)),left=(winW-w)/2;

        //if(top<0) top=0;



        var setvars={top:top+'px',left:left+'px',visibility:'visible',display:'block',height:h?h+'px':'auto'};

        typeof callback==='function'?callback.call(this,setvars):Extend(this.win.style,setvars);

        options.overlay&&this.overlay.show();

        if(options.trueEvent!=''){

            $('.btn-true',this.win).removeClass().addClass('uiBtn btn-true btnFocus').addClass(options.trueEvent);

        }

        options.cancelEvent==''||$('.btn-cancel',this.win).addClass(options.cancelEvent);

    },

    xhide:function(){

        this.overlay.hide();

        this.win.style.height='';

        this.win.style.display='none';

    },

    ubox:function(mark){

        if(mark=='x'){

            [this._self.parentNode.parentNode].hide();

        }

    },

    move:function(){

        var e=this._event,b=this._target,a=this._self;

        if([b].hasClass('e-drag')){

            $('>body').addClass('SelectNone');

            $.isIE&&b.setCapture();//设置捕获

            var c=$.ePos(a);

            var m=$.mPos(e);

            //alert(c.x+':'+ c.y);

            var offx=m.x-c.x;

            var offy=m.y-c.y;

            var obj=a;

            document.onmousemove=function(e){

                //$.isIE?document.selection.empty():window.getSelection().removeAllRanges();

                var now=$.mPos(e||window.event);

                var cHeight = Math.max(document.documentElement.scrollHeight,document.documentElement.clientHeight);

                var left = Math.min(Math.max(now.x-offx,0),document.documentElement.clientWidth-obj.offsetWidth);

                var top = Math.min(Math.max(now.y-offy,0),cHeight-obj.offsetHeight);



                Extend(obj.style,{'left':left+'px','top':top+'px'});

            }

            document.onmouseup=function(){this.onmousemove=this.onmouseup=function(){};$.isIE&&b.releaseCapture();$('>body').removeClass('SelectNone');}

        }

    },

    initEdit:function(id,opts){

        id=id||'#editor';

        var edit='K'+id.substr(1);

        //com.editor_field

        //非IE下执行这个.

        var ConvertBackspace = $.isIE6 || $.isIE7 || $.isIE8;

        if(!ConvertBackspace){

            var baseid = id.substr(1);

            var val = $('.editorPre',G(baseid).parentNode)[0].innerHTML;

            G(baseid).value = val;

        }

        this[edit] = KindEditor.create(id,{designMode:true,resizeType:1,'items':opts||items,afterCreate:function(){}});



    },

    sw:function(){

        if([this._self].hasClass('sw-off')){

            [this._self].removeClass('sw-off').addClass('sw-on');

        }else{

            [this._self].removeClass('sw-on').addClass('sw-off');

        }

    },

    ExecStatus:function(x){G('oSide').innerHTML=x}

};var com=new com;



//前进_后退_刷新

var DCACHE=function(){this.URLS=[];this.CACHE={};this.ID=-1;this.MaxLen=10};

DCACHE.prototype={

    push:function(o){

        if(this.ID!=-1)if(this.URLS[this.ID].reqkey==o.reqkey)return;//重复的请求,不加入历史记录

        var newUrls = this.URLS.slice(0,this.ID+1);

        if(o.html!=undefined)o.html=$box.innerHTML;

        newUrls.push(o);

        if(newUrls.length==this.MaxLen){newUrls.shift();}else{this.ID++}

        //alert('key:'+this.ID+'=>'+newUrls.length);//键值对应

        this.URLS=newUrls;

        if(this.URLS.length>1)[$('#NiceBar>a')[0]].removeClass('disabled');

    },

    prev:function(){if(this.ID>0){this.History(--this.ID);if(this.ID==0)[$('#NiceBar>a')[0]].addClass('disabled');}else{/*alert('已到第一条记录');*/}},

    next:function(){if(this.ID<this.MaxLen-1){this.History(++this.ID);}else{/*alert('已到最后一条记录');*/}},

    History:function(id){

        var params=this.URLS[id];

        params.cache = false;

        if(params.html!=undefined){

            //用于box内的某个模块请求

            $box.innerHTML=params.html;

            com.reset(params.menu);//当前菜单

        }else $ini(params,false)

    },

    reload:function(){var params=this.URLS[this.ID];params.cache=false;$ini(params,false);}

};var $_C=new DCACHE;