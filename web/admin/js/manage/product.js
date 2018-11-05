var product=function(){
    this.productCate = null;
    //this.getProductCates();
};
product.prototype={
    getProductCates:function(){
        var t=this;
        $post({
            url:'/goods/getProductCates',
            dataType:'json',
            callback:function(x){
                t.getProductCate(x);
            }
        });
    },
    getProductCate:function(data){
        var t=this;
        var cateid = G('x_cate').value;
        t.topCate = data['topCate'];
        t.subCate = data['subCate'];

        var str='<select name="cate" onchange="product.chCates(this.value)">',m;
        str+='<option value="">请选择分类</option>'
        for(var i=0;i< t.topCate.length;i++){
            m= t.topCate[i].split('#!');
            str+='<option value="'+ m[0]+'"'+(m[0]==cateid?' selected="selected"':'')+'>'+ m[1] +'</option>'
        }
        str+='</select>';
        $('#topCate').html(str);
        if(cateid!='')t.chCates(cateid);
    },

    chCates:function(parent){
        var subcateid=G('x_subcate').value;
        var t=this;
        var str='<select name="subcate">',m;
        for(var i=0;i< t.subCate[parent].length;i++){
            m= t.subCate[parent][i].split('#!');
            str+='<option value="'+ m[0]+'"'+(m[0]==subcateid?' selected="selected"':'')+'>'+ m[1] +'</option>'
        }
        str+='</select>';

        $('#subCates').html(str);
    },
    /**
     * 添加产品选项
     * @param namePre
     */
    addRuleOpt:function(namePre){
        var el=$('.rule-line',this._self.parentNode.parentNode);
        var box=el[1];
        var newEl = $('.main-tpl',this._self.parentNode.parentNode)[0].cloneNode(true);
        [newEl].removeClass('main-tpl');
        $('>input',newEl).val('');
        newEl.name = namePre+'[]';
        box.appendChild(newEl);
    },
    /**
     * 移除产品选项
     */
    rmRuleOpt:function(){
        var el = this._self.parentNode;
        if(![el].hasClass('main-tpl')){
            [this._self.parentNode].remove();
        }
    },

    /**
     * 上架
     */
    reshelf:function(product_id){

    },
    /**
     * 下架
     */
    offshelf:function(product_id){
        if(confirm("确定下架？"))$post({
            url:'/goods/offshelf/'+product_id,
            callback:function(x){
                location.href=location.href;
            }
        })
    },
    del:function(id){
        if(confirm("确定删除？"))$post({
            url:'/goods/del/'+id,
            callback:function(x){
                //location.href=location.href;
            }
        })
    },

    /**
     * 选择商品图
     */
    selGoodsImage:function(){
        var t=this;
        var self = t._self;
        upload.show({
            maxnum:1,//允许选择的图片数量.
            type:'image',
            category:'goods',//所属分类.
            //返回的数据回调函数:data格式为:
            // [{ imgSrc:'原图路径',imgPath:'相对路径',imgName:'图片名称'}]
            callback:function(data){
                console.log(data);
                //t.getImageData(data);
                data.each(function(){
                    t.addToProductImg(this);
                });
            },
            //在选择的时候做处理
            onselect:function(data){
                var isExists = t.checkImgExists(data);
                if(!isExists)return false;

                var res = t.addToProductImg(data);
                if(res)upload.hidebox();
                return res;
            }
        });
    },

    checkImgExists:function(data){
        var img_id = data.imgID;
        var imgs = $('#product-imgs img');
        var ipts = $('#product-imgs input');
        for(var i=0;i<imgs.length;i++){
            if(ipts[i].value==img_id){
                com.xtip('同一张图片不能多次添加',{type:1});return false;
            }
        }
        return true;
    },
    //将图片填充到商品图片div中.
    goodsImageResize:function(img,src,sizestr,width,height){
        var sizes = sizestr.split('x');
        if(sizes.length!=2)return;
        var w=sizes[0],h=sizes[1];

        if(width / w < height / h){
            height = Math.floor(width * h / w);
        }else{
            width = Math.floor(height * w / h);
        }
        //alert(height+'@'+width);
        img.src = src;
        img.width = width;
        img.height = height;
    },

    /**
     * 添加到商品图片列表中
     * @param data
     * @returns {boolean}
     */
    addToProductImg:function(data){

        var img_id = data.imgID;
        var imgsrc = data.imgSrc;
        var imgSize = data.imgSize;
        var t=this;

        var imgs = $('#product-imgs img');
        var ipts = $('#product-imgs input');
        var setedNum = $('#product-imgs .seted').length;

        if(setedNum==imgs.length){
            com.xtip("最多只能选择"+setedNum+'张',{type:1});return false;
        }

        var issetNum = 0;

        for(var i=0;i<imgs.length;i++){
            if(ipts[i].value==img_id){
                com.xtip('同一张图片不能多次添加',{type:1});return false;
            }
        }

        for(var i=0;i<imgs.length;i++){
            if(![ipts[i].parentNode].hasClass('seted')){
                //imgs[i].src = imgsrc;
                (function(img,src,size){
                    t.goodsImageResize(img,src,size,90,90);
                })(imgs[i],imgsrc,imgSize);

                [ipts[i].parentNode].addClass('seted');
                ipts[i].value = img_id;
                break;
            }
        }
        return true;
    },

    inOpera:function(obj){
        if([obj].hasClass('seted')){
            $('.imgop',obj).addClass('showop');
        }else{
            $('.imgop',obj).removeClass('showop');
        }
    },
    outOpera:function(obj){
        $('.imgop',obj).removeClass('showop');
    },

    moveToPrev:function(){
        var ipts = $('#product-imgs>input');
        var imgs = $('#product-imgs>img');
        var selfEl = this._self.parentNode.parentNode.parentNode;
        var curr_ipt = $('>input',selfEl)[0];
        var curr_img = $('>img',selfEl)[0];
        var index = ipts.indexOf(curr_ipt);
        if(index==0)return;
        this.swapNode(ipts[index-1],curr_ipt);
        this.swapNode(imgs[index-1],curr_img);
        this.swapSeted(ipts[index-1].parentNode,curr_ipt.parentNode);
    },
    /**
     * 移动当前图片到下一张图片
     */
    moveToNext:function(){
        var ipts = $('#product-imgs>input');
        var imgs = $('#product-imgs>img');
        var selfEl = this._self.parentNode.parentNode.parentNode;
        var curr_ipt = $('input',selfEl)[0];
        var curr_img = $('img',selfEl)[0];
        var index = ipts.indexOf(curr_ipt);
        if(index==ipts.length-1)return;
        //console.log();
        this.swapNode(ipts[index+1],curr_ipt);
        this.swapNode(imgs[index+1],curr_img);
        this.swapSeted(ipts[index+1].parentNode,curr_ipt.parentNode);
    },
    swapSeted:function(a,b){
        var hasa = [a].hasClass('seted');
        var hasb = [b].hasClass('seted');
        if(hasa && hasb)return;

        if([a].hasClass('seted')){
            [a].removeClass('seted');
            [b].addClass('seted');
        }else{
            [a].addClass('seted');
            [b].removeClass('seted');
        }
    },
    swapNode:function(node1,node2){
        var parent1 = node1.parentNode,parent2 = node2.parentNode;//父节点
        var t1 = node1.nextSibling,t2 = node2.nextSibling;
        //如果是插入到最后就用appendChild
        t1 ? parent1.insertBefore(node2,t1) : parent1.appendChild(node2);
        t2 ? parent2.insertBefore(node1,t2) : parent2.appendChild(node1);
    },
    /**
     * 移除产品图
     */
    rmProductImg:function(){
        var topEl = this._self.parentNode.parentNode.parentNode;
        [topEl].removeClass('seted');
        var img = $('img',topEl)[0];
        img.width = '90';
        img.height = '90';
        img.src = '';
        $('input',topEl).val('');
    }


};product=new product;