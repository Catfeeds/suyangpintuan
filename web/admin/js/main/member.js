var member=function(){

};member.prototype={
    createAuth:function(uid){
        $post({
            url:'/manage/member/createUserAuthUrl',
            data:{uid:uid},
            callback:function(x){
                com.xshow('请复制到另外一个浏览器中打开',x);
            }
        });
    }
};var member=new member;