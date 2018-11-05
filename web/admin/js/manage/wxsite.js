var wechat_site=function(){
};
wechat_site.prototype={

    /**
     * 上传文件
     */
    upfile:function(obj){
        var pNode = obj.parentNode;
        //this.removeCover = $('.js_removeCover',pNode.parentNode.parentNode)[0];
        var newNode = obj.cloneNode(true);
        newNode.name = 'upFile';
        newNode.onchange = function(){
            wxsite.upfile(this);
        }
        pNode.insertBefore(newNode,obj);
        $('#innerFile').html('').append(obj);
        G('upFileForm').submit();
    },
    upsliderBack:function(){
        com.xtip('上传成功!');
        main.refresh();
    },
    /**
     * 删除轮播图
     * @param id
     */
    rmSlide:function(id){
        if(confirm('确定删除该图片？'))$post({
            url:'/wxsite/rmSlideImg/'+id,
            callback:function(x){
                com.xtip('删除成功!');
                main.refresh();
            }
        });
    },

    addAd:function(){
        $post({
            url:'/wxsite/addAd',
            callback:function(x){
                com.xshow('新增广告栏目',x,{isText:false,hideBtn:true});
            }
        });
    },

    insertAd:function(type){
        $post({
            url:'/wxsite/insertAd/'+type,
            callback:function(x){
                com.xhide();
                main.refresh();
            }
        });
    },

    setImage:function(){
        var width = this._self.offsetWidth;
        var el = this._self;
        var layerIndex = 5;
        var adId = 0,parentBox;

        while(layerIndex--!=0){
            var findAd = el.getAttribute('ad-id');
            if(findAd){
                adId = findAd;
                parentBox = el;
                break;
            }
            el = el.parentNode;
        }
        var index = $('.ad-unit',parentBox).indexOf(this._self);

        $post({
            url:'/wxsite/setAd',
            data:{width:width,adid:adId,index:index},
            callback:function(x){
                com.xshow('栏目图片设置',x,{isText:false,hideBtn:true});
            }
        });
    },
    moveModDown:function(){
        var box = this._self.parentNode.parentNode;
        var wrps = $('.wrp-box',box.parentNode);
        var index = wrps.indexOf(box);
        var box2 = wrps[index+1];
        this.swapHtml(box,box2);
        this.setAdOrder();
    },
    swapHtml:function(node1,node2){
        var html1 = node1.innerHTML;
        node1.innerHTML = node2.innerHTML;
        node2.innerHTML = html1;
    },
    moveModUp:function(){
        var box = this._self.parentNode.parentNode;
        var wrps = $('.wrp-box',box.parentNode);
        var index = wrps.indexOf(box);
        var box2 = wrps[index-1];
        this.swapHtml(box,box2);
        this.setAdOrder();
    },
    setAdOrder:function(){
        var orders = [];
        var ads = $('#wxhome.ad');
        for(var i=0;i<ads.length;i++){
            orders.push(ads[i].getAttribute('ad-id'));
        }
        $post({
            url:'/wxsite/updateAdOrder',
            data:{ad_ids:orders.join(',')},
            callback:function(x){
                com.xtip('更新排序成功!');
            }
        });
        //console.log(orders);
    },

    setAdTitle:function(ad_id){
        var sbox = this._self;
        var span = $('>span',sbox);
        if(span.length==0)return;

        var iptval = $('>span',sbox)[0].innerHTML;

        sbox.innerHTML = '<input type="text" class="w200" value="'+iptval+'">';
        var el = $('>input',sbox)[0];
        el.onblur = function(){
            if(this.value!=this.defaultValue){
                //更新.
                $post({
                    url:'/wxsite/setAdTitle',
                    data:{ad_id:ad_id,name:this.value},
                    callback:function(x){
                    }
                });
            }
            sbox.innerHTML = '<span>'+this.value+'</span>';
        }
        com.xtip('输入完后,点击一下屏幕空白处即可保存!',{type:1});
        el.focus();
    },
    rmAd:function(ad_id){
        if(confirm('确定删除该广告栏目？'))$post({
            url:'/wxsite/delAd',
            data:{ad_id:ad_id},
            callback:function(x){
                main.refresh();
            }
        });
    }

};var wxsite=new wechat_site;