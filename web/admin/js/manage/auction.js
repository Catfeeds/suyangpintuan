/*>>>>>auction竞拍js*/
var auction=function(){

};
auction.prototype={

    //ajax搜索商品
    search_goods:function(type){
        type=(type!=undefined)?type:'0';
        var k = $('#SEARCH_K').val();
        var q = $('#SEARCH_Q').val();
        var cid = $('#SEARCH_CID').val();

        var D={k:k,q:q,cid:cid};
        $post({
            url:'goods/search_goods',
            method:'post',
            data:D,
            dataType:'json',
            callback:function(x){
                $('#GOODS_ID').html(x.html);
                $('#GOODS_NAME').val(x.name);
                if(type!='4'){
                    $('#ACT_NAME').val(x.name);
                    if(type!='5'){
                        $('#GOODS_PRICE').val(x.price);
                    }
                }
            }
        });
    },

    //选择商品时，添加竞拍活动名称
    select_goods:function(obj,type){
        type=(type!=undefined)?type:0;
        $('#GOODS_NAME').val(obj.options[obj.selectedIndex].text);
        if(type!='4'){
            $('#ACT_NAME').val(obj.options[obj.selectedIndex].text);
            if(type!='5'){
                $('#GOODS_PRICE').val(obj.options[obj.selectedIndex].getAttributeNode('price').value);
            }
        }
    },

    //获取套餐商品
    changGoods:function(act_id){
        act_id=(act_id!="undefined")?parseInt(act_id):0;
        var D={act_id:act_id};
        $post({
            url:'package/changGoods',
            method:'post',
            data:D,
            callback:function(x){
                $('#goods_step').html(x);
                com.xreset();
            }
        });
    },

    //添加商品
    addGoods:function(id){
        var goods_id = $('#GOODS_ID').val();
        var goods_name = $('#GOODS_NAME').val();
        var goods_number = 1;
        if(!goods_id){
            com.xtip('请选择一个商品',{type:1});return;
        }

        var items=getByClass("goods_item","goods_"+id);
        var item=items[0].innerHTML;

        //不能重复添加
        for(var i=0;i<items.length;i++){
            if(items[i].innerHTML.indexOf('goods_id="" value="'+goods_id+'"')!=-1){
                com.xtip('该商品已关联，请重新选择',{type:1});return;
            }
        }

        var pattern=/goods_id="" value=""/gi;
        item=item.replace(pattern,'goods_id value="'+goods_id+'"');

        pattern=/goods_name="" value="/gi;
        item=item.replace(pattern,'goods_name value="'+goods_name+'"');

        pattern=/goods_number="" value="/gi;
        item=item.replace(pattern,'goods_number value="'+goods_number+'"');

        var el=document.createElement('p');
        el.className='goods_item';
        el.innerHTML=item;

        G("goods_"+id).appendChild(el);
        com.xreset();
    },
    //删除套餐商品
    delGoods:function(obj){
        obj.parentNode.remove();
        com.xreset();
    }

};auction = new auction;

function getByClass(sClass,sId){
    var aResult=[];
    var aEle=document.getElementById(sId).getElementsByTagName('*');
    for(var i=0;i<aEle.length;i++){
        /*当className相等时添加到数组中*/
        if(aEle[i].className==sClass){
            aResult.push(aEle[i]);
        }
    }
    return aResult;
}