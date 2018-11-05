// JavaScript Document
/*com公共函数<<<<<<*/
function $post(o){
    var tip=G('tips');
    if(tip)tip.style.display='block';

    var params={url:'',method:'post'};
    if(o.url!=''){
        if(o.url.trim().substr(0,1)=='/'){
            o.url = o.url.trim();
        }else{
            o.url = '/'+o.url
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
function $end(){var tip=G('tips');if(tip)tip.style.display='none';}
//com.initWin();com.initTip();