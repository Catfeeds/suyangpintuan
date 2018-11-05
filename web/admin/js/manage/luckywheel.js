// JavaScript Document
window.requestAnimFrame = (function () {

    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||

        function (callback) {

            window.setTimeout(callback, 1000 / 60)

        }

})();

var LuckyWhell=function(options){

    this.totalDeg = 360*3+0;
    this.lostDeg = [36, 96, 156, 216, 276, 336];
    this.prizeDeg = [6, 66, 126, 186, 246, 306];
    this.stepIndex = 0;
    this.steps = [];
    this.a = 0.01;

    this.rotor = G(options.rotor);//大转盘
    this.pointer = G(options.pointer);//指针

    this.running = false;

    this.prize = null;
    this.sncode = '';
    this.prizeItems = {'1':'一等奖','2':'二等奖','3':'三等奖','4':'四等奖','5':'五等奖','6':'六等奖'};

    this.timer = null;

    //count
    this.usedCount = 0;
    //初始化
    this.init();
    //this.start(this.prizeDeg[5 - 1]);//旋转的时候设置下奖项,this.prize = 5;
    this.start();

};LuckyWhell.prototype={

    init:function(){
        var t=this;
        t.pointer.onclick = function(){
            if(t.running)return;
            if(t.usedCount >= 1){
                alert("您今天已经抽了 1 次奖,不能再抽了,明天再来吧!");
                return
            }

            if(t.usedCount >= 1){
                alert("您已经抽了 1 次奖,不能再抽了,下次再来吧!");
                return
            }

            if(t.prize != null){
                alert("亲，你不能再参加本次活动了喔！下次再来吧~");
                return
            }
        }
    },

    //开始
    start:function(deg){
        var t=this;
        deg = deg || this.lostDeg[parseInt(this.lostDeg.length * Math.random())];
        this.running = true;
        clearInterval(this.timer);

        this.totalDeg = 360 * 2 + deg;
        this.steps = [];

        this.stepIndex = 0;//当前执行到的步骤.
        this.countStep();//开始计算旋转步骤.

        requestAnimFrame(function(){t.run.call(t)});
    },

    //转动
    run:function(){
        var t=this;
        this.stepIndex++;
        this.rotor.style.webkitTransform = 'rotate(' + this.steps[this.stepIndex] + 'deg)';
        this.rotor.style.MozTransform = 'rotate(' + this.steps[this.stepIndex] + 'deg)';

        if(this.stepIndex < this.steps.length){
            t.running = true;
            requestAnimFrame(function(){t.run.call(t)});
        }else{
            t.running = false;
            setTimeout(function(){
                if (t.prize != null){
                    //显示SN码
                    $("#sncode").text(t.sncode);
                    var type = typeof t.prizeItems[t.prize] == 'undefined' ? "" : t.prizeItems[t.prize];
                    $("#prizetype").text(type);//显示奖项

                    //$("#result").slideToggle(500);
                    //$("#outercont").slideUp(500)

                }else{
                    alert("亲，继续努力哦！")
                }
            },200);
        }
    },

    //统计转动步长.
    countStep:function(){

        var t = Math.sqrt(2 * this.totalDeg / this.a);
        var v = this.a * t;

        for (var i = 0; i < t; i++) {
            this.steps.push((2 * v * i - this.a * i * i) / 2)
        }

        this.steps.push(this.totalDeg)
    },

    request:function(){
        $post({
            url:'/event/whell/subWin',
            data: {
                Username: "oupUjuIm-XF00ZA3DdH6WThuSGjo",
                id: "16387",
                sdid: "155859",
                tk:"4a12207616277f4cc516383a02e07870",
                pid:"75371",
                t: Math.random()
            },
            callback:function(){

            }
        });
        return;

        $.ajax({

            url: "/Activity/BigWin",

            dataType: "json",

            data: {

                Username: "oupUjuIm-XF00ZA3DdH6WThuSGjo",

                id: "16387",

                sdid: "155859",

                tk:"4a12207616277f4cc516383a02e07870",

                pid:"75371",

                t: Math.random()

            },
            //请求前就开始旋转.
            beforeSend: function () {

                running = true;

                timer = setInterval(function () {

                    i += 5;

                    outter.style.webkitTransform = 'rotate(' + i + 'deg)';

                    outter.style.MozTransform = 'rotate(' + i + 'deg)'

                },1);

            },

            success: function (data) {

                if (data.error == "invalid") {

                    alert(data.msg);

                    count = 3;

                    clearInterval(timer);

                    return

                }

                if (data.error == "getsn") {



                    $("#tel").val(data.tel);

                    if (data.state == 2) {

                        $("#closedj").css("display", "none");



                    }

                    $("#red").text(data.msg);

                    alert(data.msg + data.sn);

                    count = 3;

                    clearInterval(timer);

                    prize = data.prizetype;

                    sncode = data.sn;

                    start(prizeDeg[data.prizetype - 1]);

                    return

                }

                if (data.success) {
                    //接收服务器设置好的奖项进行旋转。
                    prize = data.prizetype;

                    sncode = data.sn;

                    start(prizeDeg[data.prizetype - 1])

                } else {
                    //随便使用一个未中奖的角度进行旋转。
                    prize = null;

                    start()

                }

                running = false;

                count++

            },

            error: function () {

                prize = null;

                start();

                running = false;

                count++

            },

            timeout: 15000

        })
    }

};
