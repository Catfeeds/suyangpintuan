var category=function(){

};category.prototype={

    chang_module:function(moduleid,listtype){
        moduleid = parseInt(moduleid);
        listtype = listtype?parseInt(listtype):0;

        $('#type').val((moduleid==0)?1:0);
        $('.h').hide();
        $('.s').css('display','');
        if(moduleid==0){
            $('.s'+moduleid).css('display','');
            $('.h'+moduleid).hide();
        }
        else if(moduleid==1){
            $('.s'+moduleid).css('display','');
            $('.h'+moduleid).hide();
        }

        if(moduleid==1){
            listtype=1;
            $('#listtype0').hide();
            $('#listtype2').hide();
        }
        else{
            $('#listtype0').css('display','');
            $('#listtype2').css('display','');
        }

        $('.listtype')[listtype].checked=true;
        this.showtemplist(moduleid,listtype);
    },

    templatetype:function(listtype){
        var moduleid = $('#moduleid').val();
        this.showtemplist(moduleid,listtype);
    },

    showtemplist:function(moduleid,listtype){
        if(!moduleid){
            return;
        }
        var D={moduleid:(moduleid?parseInt(moduleid):0),listtype:(listtype?parseInt(listtype):0),template_list:template_list,template_show:template_show};
        $post({
            url:'category/template_file',
            method:'post',
            data:D,
            dataType:'json',
            newLink:true,
            callback:function(x){
                if(moduleid){
                    $('#template_list').html(x.listdata);
                    $('#template_show').html(x.showdata);
                }else{
                    $('#template_list').html('<select name="template_list"></select>');
                    $('#template_show').html('<select name="template_show"></select>');
                }
            }
        });
    }

};category = new category;