var linkage=function(){

};linkage.prototype={

    //菜单联动
    chang_parent:function(id,hidetop,field,start,end){
        id=id?parseInt(id):0;
        hidetop=hidetop?hidetop:0;
        field=field?field:'';
        start=start?start:'';
        end=end?end:'';

        var D={id:id,hidetop:hidetop,field:field,start:start,end:end};
        $.ajax({
            url:'/home/chang_parent',
            type:'post',
            data:D,
            success:function(x){
                if(x){					
                    $('#select_linkage').html(x);	
					if($("#select_linkage").find('dl>dt').eq(1).length>0)$("#select_linkage").find('dl>dt').eq(1).html("市/区："); 					
					if($("#select_linkage").find('dl>dt').eq(2).length>0)$("#select_linkage").find('dl>dt').eq(2).html("区/县："); 	
                }
            }
        });
    }

};linkage = new linkage;

