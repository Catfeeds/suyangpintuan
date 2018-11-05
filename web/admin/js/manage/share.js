/*>>>>>auction竞拍js*/
var share=function(){

};
share.prototype={


    //设置精华
    is_rec:function(id,val){
        $post({
            url:'/share/is_rec',
            data : {'id' : id,'val' : val},
            callback:function(x){
                com.xhide();
                main.refresh();
            }
        });
    }

};share = new share;