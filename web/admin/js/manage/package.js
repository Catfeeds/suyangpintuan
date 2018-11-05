/*>>>>>auction竞拍js*/
var package=function(){

};
package.prototype={

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
        var obj = $('#CAT_ID');
        var obj2 = document.getElementById('CAT_ID');
        var cat_id = obj.val();
        var cat_name = obj2.options[obj2.selectedIndex].text;
        var cat_number = 1;
        var cat_unit = '份';
        if(!cat_id){
            com.xtip('请选择一个分类',{type:1});return;
        }

        var items=getByClass("cat_item","cat_"+id);
        var item=items[0].innerHTML;

        //不能重复添加
        for(var i=0;i<items.length;i++){
            if(items[i].innerHTML.indexOf('cat_id="" value="'+cat_id+'"')!=-1){
                com.xtip('该分类已关联，请重新选择',{type:1});return;
            }
        }

        var pattern=/cat_id="" value=""/gi;
        item=item.replace(pattern,'cat_id value="'+cat_id+'"');

        pattern=/cat_name="" value="/gi;
        item=item.replace(pattern,'cat_name value="'+cat_name+'"');

        pattern=/cat_number="" value="/gi;
        item=item.replace(pattern,'cat_number value="'+cat_number+'"');

        pattern=/cat_unit="" value="/gi;
        item=item.replace(pattern,'cat_unit value="'+cat_unit+'"');

        var el=document.createElement('p');
        el.className='cat_item';
        el.innerHTML=item;

        G("cat_"+id).appendChild(el);
        com.xreset();
    },
    //删除套餐商品
    delGoods:function(obj){
        obj.parentNode.remove();
        com.xreset();
    }

};package = new package;

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