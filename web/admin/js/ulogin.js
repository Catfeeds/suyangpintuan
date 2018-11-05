// JavaScript Document
var login=function(){
    G('username').focus();
};login.prototype={
    /**
     * 用户登录
     * @returns {boolean}
     */
    submit:function(){
        var username = G('username').value.trim();
        var password = G('password').value.trim();
        var scode = G('scode') ? G('scode').value.trim() : '-1';
        if(username==''){alert("用户名不能为空!");G('username').focus();return false;}
        if(password==''){alert("密码不能为空!");G('password').focus();return false;}
        if(G('scode') && scode==''){alert("验证码不能为空!");G('scode').focus();return false;}

        $post({
            url:'/manage/login/submit',
            data:{username:username,password:password,scode:scode},
            callback:function(x){}
        });
        return false;
    },
    /**
     * 登录成功
     */
    subSucc:function(){
        //alert('登录成功!');
        location.href = '/manage#!timing/index';
    },

    /**
     * 用户注册
     * @returns {boolean}
     */
    subReg:function(){
        var username = G('username').value.trim();
        var password = G('password').value.trim();
        var pass1 = G('pass1').value.trim();
        var invcode = G('invcode').value.trim();

        if(pass1!=password){
            alert("两次密码不一致!");G('pass1').focus();return false;
        }
        if(password.length<6 || password.length>16){
            alert("密码长度应为6-16位!");G('password').focus();return false;
        }
        if(username==''){alert("用户名不能为空!");G('username').focus();return false;}
        if(invcode==''){alert("请输入邀请码!");G('invcode').focus();return false;}

        $post({
            url:'/manage/login/subReg',
            data:{username:username,password:password,invcode:invcode},
            callback:function(x){}
        });
        return false;
    },
    regSucc:function(){
        alert('注册成功!');
        location.href = '/manage';
    },

    reloadScode:function(obj){
        obj.src = '/welcome/scode?xrand='+Math.random();
    }
};
loads.push(function(){
    login = new login;
});