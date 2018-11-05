var lottery=function(){
    this.init();
};lottery.prototype={

    init:function(){

    },

    //创建幸运大转盘
    addwheel:function(){
        $post({
            url:'/event/lottery/addWhell',
            callback:function(x){
                com.xshow('创建大转盘',x,{trueEvent:'e2-lottery-createWheel'});
            }
        });
    },
    createWheel:function(){
        var D={};
        $('#wheelForm>input').each(function(){
            D[this.name] = this.value.trim();
        });
        //检查结束时间是否比开始时间迟,
        //检查关键词
        //检查活动名称

        $post({
            url:'/event/lottery/createWheel',
            data:D,
            callback:function(x){
                com.xtip('创建成功!');
                com.xhide();
                main.refresh();
            }
        });
    },

    /**
     * 上传抽奖图片
     * @param obj
     * @param area
     */
    upLotteryFile:function(obj,area){
        var pNode = obj.parentNode;
        var newNode = obj.cloneNode(true);
        newNode.name = 'upFile';
        newNode.onchange = function(){
            wxnews.upLotteryFile(this);
        }
        pNode.insertBefore(newNode,obj);
        var areaEl = document.createElement('input');
        Extend(areaEl,{
            value:area,
            type:'hidden',
            name:'area'
        });
        $('#innerFile').html('').append(obj).append(areaEl);
        G('upFileForm').submit();
    },
    /**
     * 上传抽奖图片完成后
     * @param img
     */
    upLotteryBack:function(img, area){
        console.log(img);
        //img['img']

        var img_thumb = img.imgurl_thumb;

        var editImg = $('#'+area+'>img')[0];
        var thumbImg = $('#'+area+'-thumb>img')[0];

        editImg.src = img.imgurl_src;
        thumbImg.src = img.imgurl_thumb;

        [editImg.parentNode].addClass('has-thumb');
        [thumbImg.parentNode].addClass('up_thumb');

        $('>input',thumbImg.parentNode)[0].value = img.id;
    },

    /**
     * 删除当前编辑的图片
     */
    rmImg:function(){
        var imgbox = this._self.parentNode;
        $('>input',imgbox)[0].value = '';
        [imgbox].removeClass('up_thumb');
        if(imgbox.id.toLowerCase() == 'msg-board1-thumb'){
            [$('#msg-board1>img')[0].parentNode].removeClass('has-thumb');
        }else{
            [$('#msg-board2>img')[0].parentNode].removeClass('has-thumb');
        }
    },

    submitWheelData:function(){
        var D={},xtype;
        $('#lotteryForm>input').concat($('#lotteryForm>textarea')).each(function(){
            xtype = this.type.toLowerCase();
            if(xtype=='radio'){
                if(this.checked)D[this.name] = this.value;
            }else{
                D[this.name] = this.value;
            }
        });

        console.log(D);

        $post({
            url:'/event/lottery/submitWheel',
            data:D,
            method:'post',
            callback:function(){

            }
        });
    }

};lottery = new lottery;