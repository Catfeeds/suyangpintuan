// JavaScript Document

var main=function(){

    this.sideUl=$('#ext-menu .fn-menu');

    this.topMenu=$('#top-menu a');

};main.prototype={
    // fan 2016-05-10 start
    // 根据data-target 激活内部模态窗口
    modal:function(){
        var $this  = this._target,content,
            title = $this.getAttribute('data-title')||'弹窗',
            url = $this.getAttribute('data-url');

            if (url){
                $.ajax({
                    method: 'get',
                    url: url,
                    data: {
                        cache: +new Date()
                    },
                    callback: function(res) {
                        if (res){
                            com.xshow(title, res, { hideBtn: true });
                        }
                    }
                });
            }else{
                var target = $this.getAttribute('data-target');
                content = $(target).html();
                com.xshow(title, content, { hideBtn: true });
            }


    },
    // fan 2016-05-10 end

    //点击顶部菜单,自动设置相关样式.

    topmenu:function(){

        var a=this._target,ul=this._self;

        while(a.tagName.toLowerCase()!='a'){

            a= a.parentNode;

            if(a.tagName.toLowerCase() == ul.tagName.toLowerCase())return;

        }



        if([a].hasClass('on'))return;

        var index=this.topMenu.indexOf(a);

        [a].addClass('on').siblings().removeClass('on');

        [this.sideUl.hide()[index]].show();

        if($('.on',this.sideUl[index]).length==0){

            var item = $('a',this.sideUl[index])[0];

            if([item].hasClass('has-childs')){

                var subMenu = [item.parentNode].next();

                $($('a',subMenu)[0]).addClass('on');

                this.slideDownEl(subMenu);

            }else{

                [$('a',this.sideUl[index])[0]].addClass('on');

            }

        }

        //点击顶部菜单,触发hash

        this.gotoRequest();

    },

    //收起二级菜单动画

    slideUpEl:function(el){

        if(el.scrollHeight==0)return;

        [el].motion({height:{to:0,ease:5}},30,function(){

            [el].hide();

        });

    },

    //展开二级菜单动画.

    slideDownEl:function(el){

        //前提,该元素所在的BOX要已经显示.

        //el.style.display = 'block';el.style.height = 'auto';return;

        [el].show().motion({height:{to:el.scrollHeight,ease:5}},30,function(){

            //el.style.height='auto';

        });

    },

    //该操作类似点击主菜单,同时展开二级菜单,并选中.

    openByEl:function(fnli_a){

        var subMenu = [fnli_a.parentNode].next();

        this.slideDownEl(subMenu);

    },



    //点击左边栏菜,设置对应的响应样式.

    sidemenu:function(){

        var ul=this._self,a=this._target;

        if(ul==a)return;

        while(a.tagName.toLowerCase()!='a'){

            a= a.parentNode;

            if(a.tagName.toLowerCase() == ul.tagName.toLowerCase())return;

        }

        //点击主菜单,设置相关的菜单响应操作.

        var li = a.parentNode;



        if([li].hasClass('fn-li')){

            var childOnMenu = $('.child-on', li.parentNode);

            //如果点击的不是当前菜单,则将其他菜单的节点执行slideUp操作。

            if(childOnMenu.length>0){

                if(childOnMenu[0]!=li){

                    var t=this;

                    childOnMenu.removeClass('child-on').each(function(){

                        t.slideUpEl([this].next());

                    });

                }

            }



            if([a].hasClass('has-childs')){

                //展开收起.

                var childNodeBox = [li].next();

                //点击同一个菜单,当前展开就收起,当前收起就展开.

                if([li].hasClass('child-on')){

                    [li].removeClass('child-on');

                    this.slideUpEl(childNodeBox);

                }else{

                    [li].addClass('child-on');

                    this.slideDownEl(childNodeBox);

                }

                return;//不设置菜单为on状态.

            }

        }else{



        }

        //如果有子菜单,默认直接打开第一个菜单.



        //当前链接设为on，其他链接移除on

        $('a',a.parentNode.parentNode).removeClass('on');

        [a].addClass('on');

    },



    /**

     * 寻找最佳匹配路径

     */

    findMatchIndex:function(hash){

        for(var i=0;i<subMenus.length;i++){

            var menu=subMenus[i].split(':');

            if(hash.indexOf(menu[1])!==-1){

                return menu[0];

            }

        }

        return null;

    },

    //根据li标签内的a标签,打开对应子菜单,相关其他打开的菜单将收缩起来.

    autoMenu:function(li_a){

        var t=this;

        if([li_a.parentNode].hasClass('sub-li')){

            //该菜单在二级子菜单中.

            var subMenu = li_a.parentNode.parentNode.parentNode;

            var menuTag = [subMenu].prev();



            //li_a所在的菜单设置为打开状态,其他菜单全部关闭.

            //mark:点击菜单时,触发hash,这里会再执行一次动画.动画的处理机制,不会察觉到.

            $('.sub-menu',menuTag.parentNode).each(function(){

                if(this!=subMenu){

                    //关闭,

                    [[this].prev()].removeClass('child-on');

                    t.slideUpEl(this);

                }else{

                    //展开该菜单,并关闭其他已展开的菜单.

                    [menuTag].addClass('child-on');

                    t.slideDownEl(subMenu);

                }

            });

        }else{

            //该菜单为一级菜单.关闭其他所有子菜单,并设置当前菜单为on的状态.

            var fnLi = li_a.parentNode;



            $('.child-on',fnLi.parentNode).each(function(){

                //关闭,

                [this].removeClass('child-on');

                var nextMenuBox = [this].next();

                if([nextMenuBox].hasClass('sub-menu')){

                    t.slideUpEl(nextMenuBox);

                }

            });

        }



    },

    /**

     * 根据请求的url,进行样式设置.此处主要作为样式归位,保证每个菜单该有的样式.

     */

    setMenuOnByHash:function(hash){

        var menuid = this.findMatchIndex(hash);

        var menus = $('#ext-menu a'), findEl, i, parentUl;

        if(menuid==null){

            var pos = 0,mod='';

            for(i=0;i<menus.length;i++){

                pos = menus[i].href.indexOf('#!')+2;

                mod = menus[i].href.substr(pos);

                if(hash.indexOf(mod)!==-1){

                    findEl=menus[i];

                    break;

                }

            }

        }else{

            for(i=0;i<menus.length;i++){

                if(menus[i].rel==menuid){

                    findEl=menus[i];

                    break;

                }

            }

        }



        if(!findEl)return;



        parentUl = [findEl.parentNode].hasClass('sub-li') ?

            findEl.parentNode.parentNode.parentNode.parentNode :

            findEl.parentNode.parentNode;



        //设置菜单.on

        $('a',parentUl).removeClass('on');[findEl].addClass('on');

        this.sideUl.hide();[parentUl].show();



        this.autoMenu(findEl);



        //顶部菜单的on样式

        var ulIndex=this.sideUl.indexOf(parentUl);

        this.topMenu.removeClass('on');

        [this.topMenu[ulIndex]].addClass('on');

    },



    //选中当前的菜单,触发跳转.

    gotoRequest:function(){

        var topOn=$('#top-menu .on')[0];

        var index=this.topMenu.indexOf(topOn);

        location.href = $('.on',this.sideUl[index])[0].href;

    },

    //刷新当前菜单.

    refresh:function(){

        $hash.reload();

    },



    //多语言切换

    chang_lang:function(id){

        var D={l:id};

        $post({url:'index',method:'post',data:D,callback:function(x){main.refresh()}});

    },



    //删除单条数据

    confirm_del:function(url,id,msg){

        if(!confirm(msg?msg:'确认删除吗？')) return;

        var D={id:id};

        $post({url:url,method:'post',data:D,callback:function(x){main.refresh()}});

    },



    //更新状态

    chang_status:function(id,table,field,key){

        field=field?field:'status';

        key=key?key:'id';

        var D={id:id,table:table,action:'status',field:field,key:key};

        $post({url:'index',method:'post',data:D,callback:function(x){main.refresh()}});

    },



    //批量排序

    batch_order:function(formId,order,table,field,key){

        var Data=[

            'action='+order,

            'table='+table,

            'field='+(field?field:'listorder'),

            'key='+(key?key:'id')

        ],formBox;



        if(typeof document[formId]!='undefined'){

            formBox = document[formId];

        }else{

            formBox = G(formId);

        }



        $('input',formBox).each(function(){

            Data.push(this.name+'='+this.value);

        });



        $post({

            url:'index?'+Data.join('&'),

            method:'post',

            callback:function(x){

               // main.refresh();

            }

        });

    },



    //全局action操作

    action:function(action,options){

        options=options?options:{};

        var D={action:action,options:options};

        $post({url:'index',method:'post',data:D,callback:function(x){

            if(action=='backmap'){

                com.xshow('后台地图',x,{hideBtn:true});

            }

            else{

                main.refresh()

            }

        }});

    },



    //清空整站缓存

    clearCaches:function(){

        var D={};

        $post({url:'setting/clearCaches',method:'post',data:D,callback:function(x){

            main.refresh();

        }})

    },



    //全选

    allchecked:function(name){

        var check = document.getElementsByTagName('input');

        for (var i=0; i<check.length; i++) {

            if (check[i].type == 'checkbox' && check[i].name == name && !check[i].disabled) {

                check[i].checked = 'checked';

            }

        }

    },

    //反选

    unchecked:function(name){

        var check = document.getElementsByTagName('input');

        for (var i=0; i<check.length; i++) {

            if (check[i].type == 'checkbox' && check[i].name == name && !check[i].disabled) {

                check[i].checked = !check[i].checked;

            }

        }

    },

    //设置皮肤

    setskin:function(skin_id){

        $post({

            url:'setSkin/'+skin_id,

            callback:function(){}

        });

    },



    //根据邮箱地址打开邮箱网址

    openmail:function(email){

        $t = email.split('@')[1];

        $t = $t.toLowerCase().trim();

        if($t==''){ return; }



        var url = '';

        switch($t){

            default: //默认为http://mail.$t

                url = 'http://mail.'+$t;

                break;

        }



        open(url);

    },



    //查看图片

    seepic:function(obj){



    }



};



//Hash处理

var $hash=function(){

    var t=this;

    if($.isIE7){ //IE7-hash处理

        var iframe=document.createElement('iframe');

        iframe.style.display='none';

        document.body.appendChild(iframe);

        t.iframe = iframe.contentWindow;

        t.html='<!doctype html><html><body>#hash</body></html>';

        t.init();

    }else{

        window.onhashchange=function(){ t.hashchange(location.hash) };

        t.hashchange(location.hash);

    }

    if(typeof initialize_link!='undefined' && t.getHash().substr(2)=='')location.hash='#!'+initialize_link;

};

$hash.prototype={

    init:function(){

        var t=this;

        var topwinHash;//主窗口hash

        setInterval(function(){

            var hash=t.getHash();

            var iframeHash=t.iframe.document.body.innerText;

            if(hash!==topwinHash){ //如果是主窗口的hash发生变化

                t.setHistory(topwinHash=hash,iframeHash);

                t.hashchange(hash);

            }else if(iframeHash!==topwinHash){ //如果按下回退键,iframe中的历史地址回退,跟主窗口的hash作对比

                location.href = location.href.replace( /#.*/,'')+iframeHash;

            }



        },2);

    },

    //用于取得当前窗口或iframe窗口的hash值

    getHash:function(url){

        url = url||document.URL;

        return '#'+url.replace(/^[^#]*#?(.*)$/,'$1');

    },

    getHistory:function(){ return this.getHash(this.iframe.location)},

    setHistory:function(hash,iframeHash){

        var t=this;

        var doc=t.iframe.document;

        var str=t.html.replace('#hash',hash);

        if(hash!=iframeHash){

            doc.open();doc.write(str);doc.close();

        }

    },

    hashchange:function(hash){

        var t=this;

        if(hash.indexOf('#!')!==-1){

            hash.replace(/^#!(.*)/,function(a,b){

                if(b!=''){

                    main.setMenuOnByHash(b);

                    $post({

                        url:b,

                        newLink: true,

                        method:'get',

                        callback:function(x){

                            var n=hash.indexOf("com=xshow");

                            if(n!==-1){

                                var sp=hash.substr(n).split("&")[0].split("=")[1].split('|');

                                var title = sp[1] ? sp[1] : '弹窗标题';


                                // fan 2016-04-18
                                // 点击触发模态窗口的时候, 浏览器的当前url 会变成模态窗口的url
                                // 如果此时不关闭模态窗口. 直接刷新页面 将会造成背景主界面丢失
                                // 用 history.back(); 可以在模态窗口打开的时候,将浏览器的当前url 变回打开之前
                                // 但父类页面刷新,整个主控制器都重新执行了一次. 造成了完全不必要的性能损失
                                // todo 需要改变模态窗口的打开规则
                                // 最好的办法是:点击触发模态窗口的时候 原url不变, 同时也无需额外刷新页面
                                history.back();
                                // fan 2016-04-18 end
                                com.xshow(decodeURIComponent(title),x,{hideBtn:true});

                            }else{

                                $box.innerHTML = x;

                            }

                        }

                    });

                }

            });

        }else{

            if(hash=='#')return;

        }

    },

    reload:function(){

        this.hashchange(this.getHash());

    }

};



loads.push(function(){

    main = new main;

    $hash=new $hash;

});





// JavaScript Document

/*com公共函数<<<<<<*/

function $post(o){

    G('tips').style.display='block';

    var params={url:'',method:'post'};

    if(o.url!=''){

        if(o.url.trim().substr(0,1)=='/'){

            o.url = '/business'+o.url.trim();

        }else{

            o.url = '/business/'+o.url

        }

    }

    Extend(params,o);



    //ajax请求标志

    if(typeof params.data != 'undefined'){

        params.data.ajax = 1;

    }else{

        params.data = {ajax:1}

    }



    params.callback=function(x){

        if(typeof x=='string'){

            var _Js_=com.ExeScript(x);

            o.callback!=undefined&&o.callback(x.replace(rjs,''));

            _Js_[0].each(function(){$.loadJs(this)});

            $.eval(_Js_[1]);

        }else{

            o.callback(x);

        }



        $end();

    };

    $.ajax(params);

}

function $end(){G('tips').style.display='none';}

loads.push(function(){

    com.initTip();//提示信息框初始化

    com.initWin();//弹出层,半透明层初始化

});

//com.initWin();com.initTip();



var $Form=function(){};$Form.prototype={

    submit:function(formObj,options){

        formObj = G(formObj);

        var D = this.getFormValues(formObj);



        if(formObj.method.toLowerCase()=='get'){

            var querys = [];

            for(var key in D)querys.push(key+"="+D[key]);



            var requestUri;



            if(querys.length>0){

                requestUri = formObj.action+'?'+querys.join('&');

            }else{

                requestUri = formObj.action;

            }

            location.href = requestUri;

        }else{

            var params = {

                callback:function(x){}

            };



            if(typeof options!='undefined')Extend(params,options);



            var act = formObj.action;

            if(typeof params.url=='undefined'){

                var hashPos = act.indexOf('#!');

                params.url = act.substr(hashPos+2);

            }



            var callback = typeof params.callback=='function'?params.callback:function(){};



            $post({

                url:params.url,

                method:'post',

                data:D,

                callback:params.callback

            })

        }



        return false;

    },

    /**

     * 获取表单元素的值

     * @param obj

     * @returns {{}}

     */

    getFormValues:function(obj){

        obj = G(obj);

        var element,etype,ename,D={},evalue;

        for(var i=0;i<obj.length;i++){

            element = obj[i];

            etype = element.type.toLowerCase();

            ename = element.name.toLowerCase();

            evalue = element.value.trim();



            if(ename=='')continue;



            if(etype=='checkbox' || etype=='radio'){

                if(element.checked)D[ename] = evalue;

            }else{

                D[ename] = evalue;

            }

        }

        return D;

    }

};var xForm = new $Form();



/** 任务队列

 * @param queue 任务名

 * @param reset false只能执行一次

 */

var boxProcess = '#processBox';

var htmlProcess = '';

var cronProcess = [];

var $cron=function(){};$cron.prototype={

    init:function(queue,reset){

        //传入参数

        var D={};

        reset=(reset!=undefined)?reset:true;

        queue=(queue!=undefined)?queue:'';

        D.queue = (queue!=undefined)?queue:'';

        htmlProcess = $(boxProcess).html();

        if(reset==false && htmlProcess.indexOf('cron_'+queue)==1){

            com.xtip('当前队列正在运行中，不要重复运行！',{type:1});

            return false;

        }

        if(htmlProcess.indexOf('cron_'+queue)==-1){

            if(cronProcess['size']>0){

                com.xtip('当前存在运行中的队列！',{type:1});

                return false;

            }

            D.add = 1;

        }

        else{ D.add = 0; }



        //异步页面

        var url = '';

        if(queue=='test'){ url = 'timing/test'; }

        else if(queue=='frozen'){ url = 'timing/frozen'; }

        else if(queue=='sendtpl'){ url = 'templates/sendtpl'; }

        else{ return false; }



        $post({

            url:url,

            method:'post',

            data:D,

            dataType:'json',

            callback:function(x){

                //记录队列剩余长度

                cronProcess['size'] = x.size?x.size:0;

                //记录队列总长度

                if(x.count > 0){

                    cronProcess[queue+'count'] = x.count;

                    x.jd = 100-Math.round(x.size/x.count*10000)/100;

                }

                //剩余条数/总条数

                x.em = (cronProcess[queue+'count']-x.size)+'/'+cronProcess[queue+'count'];

                if(D.add==1){

                    //添加队列

                    $(boxProcess).html('' +

                        '<li id="cron_'+queue+'">' +

                        '<div class="head"><strong>'+x.head+'</strong><em>'+x.em+'</em></div>' +

                        '<div class="process"><span style="width:'+x.jd+'%"></span></div>' +

                        '<div class="processmsg">' + x.msg + '</div>' +

                        '</li>' +

                        htmlProcess);

                }else{

                    //更新队列

                    x.jd=100-Math.round(x.size/cronProcess[queue+'count']*10000)/100;

                    $('#cron_'+queue+' .head strong').html(x.head);

                    $('#cron_'+queue+' .head em').html(x.em);

                    $('#cron_'+queue+' .process span').css('width:'+x.jd+'%');

                    $('#cron_'+queue+' .processmsg').html(x.msg);

                }

                if(x.error=='0000'){

                    //继续轮循

                    cron.init(queue);

                }else{

                    //清除队列

                    if(x.msg) com.xtip(x.msg);

                    $('#cron_'+queue).remove();

                    main.refresh();

                }

            }

        });

    }

};var cron = new $cron();