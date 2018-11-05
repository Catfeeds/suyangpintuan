var select_cat=function(){

};select_cat.prototype={

    //菜单联动
    chang_parent:function(id,table,field,str,etr,zone){
        id=id?parseInt(id):0;
        zone=zone?parseInt(zone):0;
        table=table?table:'goods_cat';
        field=field?field:'';

        if(id){
            $('#markTR').hide();
        }else{
            $('#markTR').css('display','');
        }

        var D={id:id,table:table,field:field,zone:zone};
        $post({
            url:'linkage/select_cat',
            method:'post',
            newLink: true,
            data:D,
            callback:function(x){
                $('#select_'+table).html(x);
            }
        });
    }

};select_cat = new select_cat;

