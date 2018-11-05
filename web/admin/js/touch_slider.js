// JavaScript Document
var pSupport = function (b) {
    var docuEl = document.documentElement;
    for (var c = docuEl.style,
             d = "Webkit Moz O ms".split(" "),
             e = b.charAt(0).toUpperCase() + b.substr(1),
             f = (e + " " + d.join(e + " ") + e).split(" "),
             g = null,
             h = 0,
             i = f.length;
         i > h;
         h++)
        if (f[h] in c) {
            g = f[h];
            break
        }
    return g
};


var loopImage=function(params, b) {
    this.config = {
        initIndex: 0,
        contentWrap: "",//ul,
        contentTag: "li",//li
        statusWrap: "",
        statusTag: "i",//无
        statusClass: "current",//当前current
        autoGenClass: "",//
        statusNow: !1,//0
        prev: "",
        next: "",
        btnDisClass: "disabled",
        btnTouchClass: "",//
        aniTime: 300,//运动时间
        autoTime: 5e3,//
        autoAdapt: !1,
        setWidth: !1,
        pageWidth: "",
        auto: !1,
        cycle: !1,//周期,
        autoGen: !0,
        margin: 0,
        loadImg: !1,
        imgProp: "back_src",
        loadNext: !1,
        offset: !1,
        leastDis: 20,
        cont: [],
        tabs: [],
        onInit: function () {
            return !0
        },
        onProcess: function () {
            return !0
        }
    };
    if(params){
        Extend(this.config, params);
        this.config.param = {};
        this.config.param.outContainer = [b];
        this.initEl().init();
        //this.bindEvent();
    }
}




loopImage.prototype = {
    initEl: function () {
        //初始化容器的各元素
        var cfg = this.config,param = cfg.param;
        //主体宽,默认宽度.
        param.scw = cfg.pageWidth || document.body.clientWidth;
        //cfg.autoAdapt && param.outContainer[0].clientWidth;

        //ul,li的容器
        param.container = $(cfg.contentWrap);
        //所有运动的li单张图片
        param.contentList = $(cfg.contentWrap+'>'+cfg.contentTag);
        //上一页按钮
        //param.prevNode = cfg.prev && param.outContainer.find(cfg.prev);
        //下一页按钮
        //param.nextNode = cfg.next && param.outContainer.find(cfg.next);
        //状态节点,未用到
        //param.statusNode = cfg.statusWrap && param.outContainer.find(cfg.statusWrap);
        //param.statusList = cfg.statusWrap && param.statusNode.children();

        param.counts = param.contentList.length;
        //默认单个元素的宽度.
        param.eleWidth = param.contentList[0].offsetWidth;
        if(param.eleWidth == 0){
            cfg.setWidth = true;
            param.eleWidth = document.documentElement.offsetWidth;
        }


        var support3d = this.has3d();
        //return
        //支持3D则使用"translate3d(x,y,0px),否则"translate(x,y);
        this.transform = pSupport("transform");
        this.tfpre = support3d ? "translate3d(" : "translate(";
        this.tfsufix = support3d ? ",0px)" : ")";

        return this;
        //this
    },
    //检查是否支持3d引擎.
    has3d: function () {
        //has3d = ‘WebKitCSSMatrix’ in window && ‘m11′ in new WebKitCSSMatrix(),
        var docEl = document.documentElement;
        var b = !!pSupport("perspective");
        b && ("webkitPerspective" in docEl.style) && this.injectStyle(
        "@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",
        function (a) {
            b = 9 === a.offsetLeft && 3 === a.offsetHeight;
            return b;
        });
        return b;
    },
    /**
     * 创建一个style样式标签,插入新属性@media (transform-3d),检查是否能获取其值
     * @param c
     * @param d
     * @returns {boolean}
     */
    injectStyle: function (c, d) {
        var b = "modernizr";
        var docEl = document.documentElement;
        var styleTag, f, newdiv = document.createElement("div"),
            docBody = document.body,
            bodyEl = docBody || document.createElement("body");
        styleTag = ["&#173;", '<style id="s', b, '">', c, "</style>"].join("");
        newdiv.id = 'modernizr';
        (docBody ? newdiv : bodyEl).innerHTML += styleTag;
        bodyEl.appendChild(newdiv);
        if(!docBody){
            bodyEl.style.background = "";
            docEl.appendChild(bodyEl);
        }
        f = d(newdiv, c);

        docBody ? newdiv.parentNode.removeChild(newdiv) : bodyEl.parentNode.removeChild(bodyEl);
        return !!f;
    },

    //初始化配置
    init: function () {
        var cfg = this.config,
			param = cfg.param,
			c = cfg.cycle && param.counts > 1 ? param.counts + 2 : param.counts;
        //return
        //设置默认的current
        this.current = cfg.initIndex =
        cfg.initIndex > param.counts ? param.counts - 1 : cfg.initIndex < 0 ? 0 : cfg.initIndex;

        //调用参数中设置的init函数
        cfg.onInit();

        //设置每个li的宽度
        cfg.setWidth && param.contentList.css({width:param.eleWidth+'px'});

        //设置每次运动步长宽度.
        param.statusStepWidth = cfg.statusNow && parseInt(param.eleWidth / param.counts, 10);

        //?
        //cfg.statusNow && param.statusList.width(param.statusStepWidth);

        var containercss = {
            width: parseInt((param.eleWidth + cfg.margin) * c, 10)+'px',
            left: cfg.offset ? parseInt((param.scw - param.eleWidth) / 2, 10)+'px' : "0px",
            "-webkit-backface-visibility": "hidden",
            "-webkit-transform": this.tfpre + -this.current * (1 * param.eleWidth + 1 * this.config.margin) + "px,0px" + this.tfsufix//初始化运动trans3d位置
        };
        //detail-slider
        console.log(containercss);
        //设置容器ul样式
        param.container.css(containercss);
        //设置容器父级样式
        [param.container[0].parentNode].css({
            "-webkit-transform": "translate3d(0,0,0)"
        });
        //设置每个运动元素的样式.
        param.contentList.css({
            "-webkit-transform": this.tfpre + "0px,0px" + this.tfsufix
        });
        cfg.statusNow && param.statusList.css({
            "-webkit-transform": this.tfpre + this.current * param.statusStepWidth + "px,0px" + this.tfsufix,
            "-webkit-backface-visibility": "hidden"
        });
        //初始化图片
        this.initImages();


        this.direct = "left";
        cfg.autoGen ? this.generateStatus() : "";
        cfg.cycle ? this.cloneNode() : "";
        this.updateStatus();
        cfg.auto && this.processAuto();

        this.bindEvent();
        return;

        if(param.counts <= 1){
            if(cfg.loadImg){
                this.loadSingleImg(this.getEle(this.current));
            }else{

            }
        }
    },
    //获取元素
    getEle: function (a) {
        return this.config.param.contentList[a];
    },
    //
    generateStatus: function () {
        for (var a = this.config, b = a.param, c = [], d = 0; d < b.counts; d++) c.push("<" + a.statusTag + " class=" + a.autoGenClass + "></" + a.statusTag + ">");
        b.statusNode.html(c.join("")), b.statusList = b.statusNode.children(), a.tabs = b.statusList, c = null
    },
    //绑定事件
    bindEvent: function () {
        var param = this.config.param,
			b = param.container[0];
        //b.ontouchstart = this.handleEvent;
        //b.ontouchmove= this.handleEvent;
        //b.ontouchend = this.handleEvent;
        //b.ontouchcancel = this.handleEvent;
        //b.onwebkitTransitionEnd = this.handleEvent;

        b.addEventListener("touchstart", this, !1);//开始
        b.addEventListener("touchmove", this, !1);//移动
        b.addEventListener("touchend", this, !1);//结束
        b.addEventListener("touchcancel", this, !1);//取消
        b.addEventListener("webkitTransitionEnd", this, !1);//动画结束事件

        var t=this;
        //G('xnext').onclick = function(){t.next.call(t,arguments)};
        //G('xprev').onclick = function(){t.prev.call(t,arguments)};

        //param.prevNode && param.prevNode.on("click", function(){t.prev.call(t,arguments)});
        //param.nextNode && param.nextNode.on("click", function(){t.next.call(t,arguments)})
    },
    //处理事件,addEventListener("touchstart"绑定该对象时,会默认调用该方法.
    handleEvent: function (a) {
        switch (a.type) {
            case "touchstart":
                this.eventStart(a);
                break;
            case "touchmove":
                this.eventMove(a);
                break;
            case "touchend":
            case "touchcancel":
                this.eventEnd(a);
                break;
            case "webkitTransitionEnd":
                this.processEnd()
        }
    },
    /**
     * 运动到上一张
     * @param a
     * @returns {}
     */
    prev: function (a) {
        //var b = $(a.target);
        //if (!b.hasClass(this.config.btnDisClass)){
        this.direct = "right";
        this.stop();
            //b.addClass(this.config.btnTouchClass);
        this.setPrevPage();
        return !1;
        //}
        //return !0;
    },

    next: function (a) {
        //var b = $(a.target);
        //if (!b.hasClass(this.config.btnDisClass)){
            this.direct = "left";
            this.stop();
            //b.addClass(this.config.btnTouchClass);
            this.setNextPage();
            return !1;
        //}
        //return !0;
    },
    /**
     * 设置上一页
     */
    setPrevPage: function () {
        var current = parseInt(this.current, 10),
			count = parseInt(this.config.param.counts, 10);
        switch (current) {
            case -1:
                current = count - 2;
                break;
            case count:
                current = -1;
                break;
            case 0:
                current = this.config.cycle ? -1 : 0;
                break;
            default:
                current -= 1
        }
        this.current = current;
        this.processScroll();
    },
    /**
     * 设置下一页
     */
    setNextPage: function () {
        var current = parseInt(this.current, 10),
			count = parseInt(this.config.param.counts, 10);
        switch (current) {
            case count:
                current = 1;
                break;
            case -1:
                current = count;
                break;
            case count - 1:
                current = this.config.cycle ? count : count - 1;
                break;
            default:
                current += 1
        }
        this.current = current;
        this.processScroll();
    },
    /**
     * touchStart
     * @param a
     */
    eventStart: function (a) {
        var cfg = this.config,
			param = cfg.param;
        this.sp = this.getPosition(a);
        var d = -1 == this.current ? param.counts - 1 : this.current == param.counts ? 0 : this.current;
        this.curOffset = -(d * (param.eleWidth + cfg.margin)), this.statusOffset = cfg.statusNow && param.statusStepWidth * d, this.stop()
    },
    /**
     * touchMove
     * @param a
     */
    eventMove: function (a) {
        if (null != this.curOffset) {
            var b = this.getPosition(a),
				c = b.x - this.sp.x;
            this.disx = c, Math.abs(c) > Math.abs(b.y - this.sp.y) && (a.preventDefault(), this.setOffset(c, 0))
        }
    },
    /**
     * touchEnd
     * @param a
     */
    eventEnd: function (a) {
        null != this.curOffset && (this.disx > this.config.leastDis ? (a.preventDefault(), this.setPrevPage(), this.direct = "right") : this.disx < -this.config.leastDis ? (a.preventDefault(), this.setNextPage(), this.direct = "left") : (this.setOffset(0, this.config.aniTime), this.config.auto && this.processAuto()), this.disx = null, this.curOffset = null, this.statusOffset = null, this.sp = null)
    },
    /**
     * 停止运动
     */
    stop: function () {
        this.ptr && clearInterval(this.ptr), this.ptr = null
    },

    setOffset: function (a, b) {
        var cfg = this.config,
			d = cfg.param;
        d.container.css({
            "-webkit-transform": this.tfpre + (this.curOffset + a) + "px,0px" + this.tfsufix,
            "-webkit-transition": b + "ms"
        }), cfg.statusNow && d.statusList.css({
            "-webkit-transform": this.tfpre + (this.statusOffset + -a / d.counts) + "px,0px" + this.tfsufix,
            "-webkit-transition": b + "ms"
        })
    },

    processScroll: function () {
        var a = this,
			b = this.current,
			param = this.config.param;
        param.container.css({
            "-webkit-transform": this.tfpre + -b * (1 * param.eleWidth + 1 * this.config.margin) + "px,0px" + this.tfsufix,
            "-webkit-transition": this.config.aniTime + "ms"
        }), this.config.statusNow && setTimeout(function () {
            var d = -1 == b ? param.counts - 1 : b == param.counts ? 0 : b;
            param.statusList.css({
                "-webkit-transform": a.tfpre + param.statusStepWidth * d + "px,0px" + a.tfsufix,
                "-webkit-transition": a.config.aniTime + "ms"
            })
        }, 0), this.config.loadImg && (this.config.loadNext ? this.loadNextImage(b) : this.loadSingleImg(param.contentList[b]))
    },
    /**
     * 动画运动结束
     */
    processEnd: function () {
        var cfg = this.config,
			param = cfg.param;
        this.updateStatus();
        this.current == param.counts ? this.moveElement() : -1 == this.current && param.container.css({
            "-webkit-transform": this.tfpre + -(1 * param.eleWidth + 1 * cfg.margin) * (param.counts - 1) + "px,0px" + this.tfsufix,
            "-webkit-transition": "0ms"
        });
        cfg.auto && this.processAuto();
    },

    /**
     * 使元素运动
     */
    moveElement: function () {
        var param = this.config.param,
            b = this.getEle(param.counts + 1),
            c = param.contentList,
            d = 1;
        for (var e = c.length - 2; e > d; d++){
            c.eq(d).remove().insertBefore(b);
        }

        if (c.eq(0).remove().insertBefore(b), this.transform) {
            var f = parseInt(param.contentList.css("margin-left"), 10);
            param.container.css({
                "-webkit-transform": "translate3d(" + -f + "px,0px,0px)",
                "-webkit-transition": ""
            })
        }
    },
    /**
     * 更新状态
     */
    updateStatus: function () {
        var cfg = this.config,
			b = cfg.cycle,
			c = this.current,
			param = cfg.param;
        c == param.counts ? c = 0 : -1 == c && (c = param.counts - 1);

        b || 0 != c ? param.prevNode && param.prevNode.removeClass(cfg.btnDisClass) : param.prevNode && param.prevNode.addClass(cfg.btnDisClass);
        b || c != param.counts - 1 ? param.nextNode && param.nextNode.removeClass(cfg.btnDisClass) : param.nextNode && param.nextNode.addClass(cfg.btnDisClass);
        param.statusList && param.statusList.eq(c).addClass(cfg.statusClass).siblings().removeClass(cfg.statusClass);
        cfg.onProcess(c);
    },
    //获取当前触摸位置.
    getPosition: function (a) {
        var b = a.changedTouches ? a.changedTouches[0] : a;
        return {
            x: b.pageX,
            y: b.pageY
        }
    },

    cloneNode: function () {
        var cfg = this.config,
			b = cfg.param;
        param.container.append(param.contentList[0].cloneNode());
        var c = param.contentList[param.counts - 1].cloneNode();
        c.css({
            position: "relative",
            left: -((param.eleWidth + cfg.margin) * (param.counts + 2))
        }), param.container.append(c), param.contentList = param.container.children()
    },

    processAuto: function () {
        var a = this;
        a.ptr && (clearInterval(a.ptr), a.ptr = null), a.ptr = setInterval(function () {
            a.config.cycle || a.current != a.config.param.counts - 1 ? a.setNextPage() : a.stop()
        }, a.config.autoTime)
    },

    //加载图片.
    loadSingleImg: function (a) {
        if (!a.getAttribute('l')) {
            var cfg = this.config;
            var imgs = $('>img',a);
            for(var i=0;i<imgs.length;i++){
                var img = imgs[i];
                var backsrc = img.getAttribute(cfg.imgProp);
                cfg.setWidth && (img.width = cfg.param.eleWidth);
                if(backsrc){
                    img.src = backsrc;
                    img.removeAttribute(cfg.imgProp);
                    a.setAttribute('l','true');
                }
            }
        }
    },

    /**
     * 初始化加载,根据屏幕宽度决定加载数量.
     */
    initImages: function () {
        var cfg = this.config,
			param = cfg.param,
			c = param.scw;//滚动宽(屏幕宽度).
        var d = c - (parseInt((c - param.eleWidth) / 2, 10) + param.eleWidth),
        e = Math.ceil(d / (param.eleWidth + 2 * cfg.margin)) + cfg.initIndex + 1;

        if (cfg.autoAdapt && cfg.loadImg && cfg.offset){
            //只加载显示在屏幕上的图片,优化页面显示速度.
            for(var f = 0; f<e; f++) {
                var g = this.getEle(f);
                this.loadSingleImg(g)
            }
        }else{
            //加载当前的图片
            this.loadSingleImg(this.getEle(this.current));
            if(cfg.cycle){
                this.loadSingleImg(this.getEle(param.counts - 1));
                0 != this.current && this.loadSingleImg(param.contentList[this.current - 1]);
            }
            cfg.loadNext && this.loadNextImage(this.current)
        }
    },

    loadNextImage: function (a) {
        var b, c = this.config,
			d = c.param;
        b = -1 == a ? d.counts - 2 : a == d.counts ? 1 : "right" == this.direct ? a - 1 : a + 1;
        var e = d.contentList.eq(b);
        this.loadSingleImg(e)
    },
    /**
     * 移除touchstart,touchend,touchcancel,touchmove等事件.
     */
    destroy: function () {
        var a = this.config.param,
			b = param.container[0];
        b.removeEventListener("touchstart", this, !1), b.removeEventListener("touchmove", this, !1), b.removeEventListener("touchend", this, !1), b.removeEventListener("toushcancel", this, !1), b.removeEventListener("WebkitTransitionEnd", this, !1), param.prevNode && param.prevNode.off("click", $.proxy(this.prev, this)), param.nextNode && param.nextNode.off("click", $.proxy(this.next, this))
    },

    /**
     * 设置当前进度.
     * @param a
     */
    setCurrent: function (a) {
        a = a<0 ? 0 : a >= this.config.param.counts ? this.config.param.counts - 1 : a;
        this.current = a;
        this.processScroll();
    }
};