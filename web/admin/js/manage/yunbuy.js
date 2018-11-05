/*>>>>>auction竞拍js*/
var yunbuy=function(){

};
yunbuy.prototype={

    //ajax搜索商品
    search_goods:function(){
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
                $('#ACT_NAME').val(x.name);
                $('#GOODS_NAME').val(x.name);
                $('#GOODS_PRICE').val(x.price);
            }
        });
    },

    //选择商品时，添加竞拍活动名称
    select_goods:function(obj){
        $('#ACT_NAME').val(obj.options[obj.selectedIndex].text);
        $('#GOODS_NAME').val(obj.options[obj.selectedIndex].text);
        $('#GOODS_PRICE').val(obj.options[obj.selectedIndex].getAttributeNode('price').value);
    }

};yunbuy = new yunbuy;