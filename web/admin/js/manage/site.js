var site=function(){};site.prototype={

    //新建轮播图弹出层
    addSlider:function(){
        $post({
            url:'/company/info/addSlider',
            callback:function(x){
                com.xshow('添加轮播图',x,{trueEvent:'e2-site-createSlider'});
                G('slider_name').focus();
            }
        });
    },
    //创建轮播图
    createSlider:function(){

        xForm.submit('sliderInfoForm',{
            url:'company/info/createSlider',
            callback:function(x){

                com.xhide();
            }
        });

    },
    //删除轮播图
    delSlider:function(){
        $post({
            url:'',
            data:{},
            callback:function(x){
                com.xshow('添加轮播图',x);
            }
        });
    },


    upsliderBack:function(){
        com.xtip('上传成功!');
        main.refresh();
    },

    //删除图片
    rmSlideImg:function(id){
        if(confirm('确定删除该图片?'))$post({
            url:'company/info/rmSlideImg/'+id,
            callback:function(x){
                main.refresh();
            }
        });
    },

    //删除轮播图
    rmSlide:function(id){
        if(confirm('确定删除?'))$post({
            url:'company/info/rmSlide/'+id,
            callback:function(x){
                main.refresh();
            }
        });
    }

};site=new site;