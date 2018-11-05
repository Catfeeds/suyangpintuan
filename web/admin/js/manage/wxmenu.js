var wxmenu = function() {
    this.wxKeyData = {};
    this.currMenuKey = null;
    this.currMenuElement = null;
    this.moveOrderCallback = null;
    this.menuTree = {};
    this.editTabCallback = null;
    this.wxEditorCallback = null;
    this.wxreplyIndex = 0;
};
wxmenu.prototype = {

    stuffMenu: function() {
        var item, itemKey, str = '',
            xitem, xItemKey;
        for (var i = 0; i < this.menuTree.button.length; i++) {
            item = this.menuTree.button[i];
            itemKey = 'wxmenu_' + i;
            this.wxKeyData[itemKey] = typeof item.data != 'undefined' ? { type: item.type, data: item.data } : null;
            str += '<dl class="wx-menu">\
            <dt>\
                <a menu-key="' + itemKey + '" href="javascript:;" class="item-entity d2-wxmenu-editMenu"><span>' + item.name + '</span></a>\
                <a href="javascript:;" class="wx-add iconfont opera-btn e2-wxmenu-add">&#xe604;</a>\
            <a href="javascript:;" class="wx-edit iconfont opera-btn e2-wxmenu-edit">&#xe603;</a>\
            <a href="javascript:;" class="wx-del iconfont opera-btn e2-wxmenu-del">&#xe606;</a>\
            <a href="javascript:;" class="wx-move iconfont opera-btn d2-wxmenu-editMenu">&#xe605;</a>\
            </dt>';
            if (typeof item.sub_button != 'undefined') {
                for (var j = 0; j < item.sub_button.length; j++) {
                    xitem = item.sub_button[j];
                    xItemKey = 'wxmenu_' + i + '_' + j;
                    this.wxKeyData[xItemKey] = typeof xitem.data != 'undefined' ? { type: xitem.type, data: xitem.data } : null;
                    str += '<dd>\
            <a menu-key="' + xItemKey + '" href="javascript:;" class="item-entity d2-wxmenu-editMenu"><span>' + xitem.name + '</span></a>\
            <a href="javascript:;" class="wx-edit iconfont opera-btn e2-wxmenu-edit">&#xe603;</a>\
            <a href="javascript:;" class="wx-del iconfont opera-btn e2-wxmenu-del">&#xe606;</a>\
            <a href="javascript:;" class="wx-move iconfont opera-btn d2-wxmenu-editMenu">&#xe605;</a>\
            </dd>';

                }
            }
            str += '</dl>';
        }

        G('menu-box').innerHTML = str;
    },

    //新增菜单
    add: function(parent) {
        this.currDl = parent == '0' ? G('menu-box') : this._self.parentNode.parentNode;
        if (parent == '0') {
            var dls = $('#menu-box dl');
            if (dls.length > 2) {
                com.xtip('一级菜单最多只能加3个', { type: 1 });
                return;
            }
        } else {
            var entitys = $('dd .item-entity', this._self.parentNode.parentNode);
            if (entitys.length > 4) {
                com.xtip('二级菜单最多只能加5个', { type: 1 });
                return;
            }
        }

        com.xshow('添加菜单', this.editTpl(false), { trueEvent: 'e2-wxmenu-setMenu-add' });
        G('edit_menu_name').focus();
    },
    //编辑菜单
    edit: function(id) {
        this.currMenuElement = $('span', this._self.parentNode);
        var name = this.currMenuElement.html();
        com.xshow('编辑菜单', this.editTpl(name), { trueEvent: 'e2-wxmenu-setMenu-edit' });
        G('edit_menu_name').focus();
    },
    editTpl: function(name) {
        return '<div style="padding:50px 30px">\
        <div class="f-unit">\
            <label class="ui-label w60" for="edit_menu_name">菜单名称：</label>\
            <input class="form-i w220" type="text" name="name" id="edit_menu_name" value="' + (name ? name : '') + '" />\
            <div class="clear"></div>\
            <label class="ui-label w60">&nbsp;</label>\
            <div class="input-tip">菜单名称名字不多于8个汉字或16个字母</div>\
        </div>\
        </div>';
    },

    //更新,新建菜单
    setMenu: function(type) {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var edit_menu_name = G('edit_menu_name').value.trim();
        if (type == 'edit') {
            this.currMenuElement.html(edit_menu_name);
        } else {
            var dd = document.createElement('dd');
            //计算出当前的所属的index和随机ID,用于保存
            var d = new Date();
            var wxmenuKey = 'wxmenu_' + d.getTime() + '_' + Math.floor(Math.random() * 10000);
            dd.innerHTML = '' +
                '<a menu-key="' + wxmenuKey + '" href="javascript:;" class="item-entity d2-wxmenu-editMenu"><span>' + edit_menu_name + '</span></a>' +
                '<a href="javascript:;" class="wx-edit iconfont opera-btn e2-wxmenu-edit">&#xe603;</a>' +
                '<a href="javascript:;" class="wx-del iconfont opera-btn e2-wxmenu-del">&#xe606;</a>';
            this.currDl.appendChild(dd);
        }
        com.xhide();
        this.saveMenu();
    },
    setMenu: function(type) {
        var edit_menu_name = G('edit_menu_name').value.trim();
        if (type == 'edit') {
            this.currMenuElement.html(edit_menu_name);
        } else {

            //计算出当前的所属的index和随机ID,用于保存
            var d = new Date();
            var wxmenuKey = 'wxmenu_' + d.getTime() + '_' + Math.floor(Math.random() * 10000);


            if (this.currDl == G('menu-box')) {
                var dl = document.createElement('dl');
                dl.className = 'wx-menu';
                dl.innerHTML = '<dt>\
                <a menu-key="' + wxmenuKey + '" href="javascript:;" class="item-entity d2-wxmenu-editMenu"><span>' + edit_menu_name + '</span></a>\
                <a href="javascript:;" class="wx-add iconfont opera-btn e2-wxmenu-add">&#xe604;</a>\
                <a href="javascript:;" class="wx-edit iconfont opera-btn e2-wxmenu-edit">&#xe603;</a>\
                <a href="javascript:;" class="wx-del iconfont opera-btn e2-wxmenu-del">&#xe606;</a>\
                <a href="javascript:;" class="wx-move iconfont opera-btn d2-wxmenu-editMenu">&#xe605;</a></dt>';
                this.currDl.appendChild(dl);
            } else {
                var dd = document.createElement('dd');
                dd.innerHTML = '' +
                    '<a menu-key="' + wxmenuKey + '" href="javascript:;" class="item-entity d2-wxmenu-editMenu"><span>' + edit_menu_name + '</span></a>' +
                    '<a href="javascript:;" class="wx-edit iconfont opera-btn e2-wxmenu-edit">&#xe603;</a>' +
                    '<a href="javascript:;" class="wx-del iconfont opera-btn e2-wxmenu-del">&#xe606;</a>';
                this.currDl.appendChild(dd);
            }
        }
        com.xhide();
        this.saveMenu();
    },

    //保存当前的菜单结构
    saveMenu: function() {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var menus = { button: [] };
        var t = this,
            subdata;

        $('#menu-box dt .item-entity').each(function() {
            var mkey = this.getAttribute('menu-key');
            var data = {
                name: $('span', this).html(),
                key: mkey
            };
            var childs = $('dd .item-entity', this.parentNode.parentNode);
            if (childs.length == 0) {
                //如果无子节点,则可设置对应的类型和数据.
                if (typeof t.wxKeyData[mkey] != 'undefined' && t.wxKeyData[mkey] != null) {
                    data.type = t.wxKeyData[mkey].type;
                    data.data = t.wxKeyData[mkey].data;
                }
            } else {
                //有子按钮,当前主按钮不能设置相应事件.
                data.sub_button = [];
            }
            childs.each(function() {
                var xmkey = this.getAttribute('menu-key');
                if (typeof t.wxKeyData[xmkey] == 'undefined' || t.wxKeyData[xmkey] == null) {
                    subdata = {
                        name: $('span', this).html(),
                        key: xmkey
                    }
                } else {
                    subdata = {
                        name: $('span', this).html(),
                        type: t.wxKeyData[xmkey].type,
                        data: t.wxKeyData[xmkey].data
                    };
                    if (subdata.type != 'view') {
                        subdata.key = xmkey;
                    }
                }
                data.sub_button.push(subdata);
            });
            menus.button.push(data);
        });
        this.menuTree = menus;

        $post({
            data: menus,
            jsonData: true,
            url: 'wxmenu/save',
            callback: function(x) {
                //alert(x)
            }
        });
    },

    //提交菜单到微信
    postMenu: function() {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        $post({
            //jsonData:true,
            url: '/wxmenu/postMenu',
            callback: function(x) {
                //alert(x);
            }
        });
    },


    //删除菜单
    del: function() {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        //移除节点,并保存即可.
        if (!confirm("该菜单下有子菜单将一并删除，确定删除?")) return;

        var parentBox = this._self.parentNode;
        if (parentBox.tagName.toLowerCase() == 'dt') {
            [parentBox.parentNode].remove();
        } else {
            [this._self.parentNode].remove();
        }
        this.saveMenu();
    },
    //编辑菜单内容
    editMenu: function() {
        var sf = this._self;
        var t = this;
        if ($('#menu-box').hasClass('move-mode')) {
            this.moveMenuOrder(sf);
            this.moveOrderCallback = function() {
                //t.saveMenu();
            }
            return;
        }

        var mkey = sf.getAttribute('menu-key');
        this.currMenuKey = mkey;

        if (sf.parentNode.tagName.toLowerCase() == 'dt') {
            var items = $('dd .item-entity', sf.parentNode.parentNode);
            if (items.length > 0) {
                com.xtip('已有子菜单，无法设置动作', { type: 1 });
                return;
            }
        }

        $('.item-entity', sf.parentNode.parentNode.parentNode).removeClass('on');
        [sf].addClass('on');

        if (typeof this.wxKeyData[mkey] == 'undefined' || this.wxKeyData[mkey] == null) {
            //选择URL和click事件.
            this.showEventType();
            return;
        }

        var data = this.wxKeyData[mkey];

        var wxtype = data.type;
        if (wxtype == 'view') {
            this.showViewType(data.data);
        } else {
            this.showClickType(data.data, data.type);
        }
    },

    toMoveItem: function() {
        //this._event;

    },



    getDlPos: function() {
        var t = this;
        t.elPos = [];
        $('#menu-box dl').each(function() {
            var epos = $.ePos(this);
            t.elPos.push({ x: epos.x, y: epos.y, bottom: this.offsetHeight + epos.y, right: this.offsetWidth + epos.x, el: this, h: this.offsetHeight });
        });
    },

    getItemPos: function(dl) {
        var t = this;
        t.elPos = [];
        $('dd', dl).each(function() {
            var epos = $.ePos(this);
            t.elPos.push({ x: epos.x, y: epos.y, bottom: this.offsetHeight + epos.y, right: this.offsetWidth + epos.x, el: this, h: this.offsetHeight });
        });
    },
    unselect: function() { $.isIE ? document.selection.empty() : window.getSelection().removeAllRanges() },

    selMsgType: function(type) {
        if (type == 'view') {
            this.showViewType();
        } else {
            this.showClickType();
        }
    },

    showEmBox: function() {
        var embox = $('.wxem-box', this._self.parentNode);
        embox.show();
        GE.push(embox[0]);
        var emlist = $('.em-list', this._self.parentNode);

        var names = ['微笑', '撇嘴', '色', '发呆', '得意', '流泪', '害羞', '闭嘴', '睡', '大哭', '尴尬', '发怒', '调皮', '呲牙', '惊讶', '难过', '酷', '冷汗', '抓狂', '吐', '偷笑', '可爱', '白眼', '傲慢', '饥饿', '困', '惊恐', '流汗', '憨笑', '大兵', '奋斗', '咒骂', '疑问', '嘘', '晕', '折磨', '衰', '骷髅', '敲打', '再见', '擦汗', '抠鼻', '鼓掌', '糗大了', '坏笑', '左哼哼', '右哼哼', '哈欠', '鄙视', '委屈', '快哭了', '阴险', '亲亲', '吓', '可怜', '菜刀', '西瓜', '啤酒', '篮球', '乒乓', '咖啡', '饭', '猪头', '玫瑰', '凋谢', '示爱', '爱心', '心碎', '蛋糕', '闪电', '炸弹', '刀', '足球', '瓢虫', '便便', '月亮', '太阳', '礼物', '拥抱', '强', '弱', '握手', '胜利', '抱拳', '勾引', '拳头', '差劲', '爱你', 'NO', 'OK', '爱情', '飞吻', '跳跳', '发抖', '怄火', '转圈', '磕头', '回头', '跳绳', '挥手', '激动', '街舞', '献吻', '左太极', '右太极'];

        if (emlist.html() == '') {
            var str = '',
                step = 24,
                emurl, emname;
            for (var i = 0; i < names.length; i++) {
                emurl = 'https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/' + i + '.gif';
                emname = names[i];
                str += '<a href="javascript:;" emname="' + emname + '" emurl="' + emurl + '" style="background-position:-' + (i * 24) + 'px 0"></a>';
            }
            emlist.html(str);
            emlist.addClass('d1-com-stopEvt');
        }
    },

    showEventType: function() {
        var html = '<div class="wx-types">\
            <div class="wx-sel-tip">\
            请选择订阅者点击菜单后，公众号做出的相应动作\
        </div>\
        <div class="wx-sel clear">\
            <a href="javascript:;" class="e2-wxmenu-selMsgType-event">\
            <i class="iconfont">&#xe612;</i>\
            <span>发送消息</span>\
        </a>\
        <a href="javascript:;" class="e2-wxmenu-selMsgType-view">\
            <i class="iconfont">&#xe60d;</i>\
            <span>跳转网页</span>\
        </a>\
        </div>\
        </div>';
        $('#wxmenu-area').html(html);
    },
    //显示跳转网址输入框:html
    showViewType: function(data) {
        var html = '<div class="wxtype-view">\
            <div class="f-unit">\
            <label class="ui-label s14">订阅者点击该子菜单会跳到以下链接</label>\
        <div class="clear"></div>\
        <input class="form-i w360" id="wx-viewurl" value="' + (typeof data != 'undefined' ? data : '') + '">\
            </div>\
        <div class="f-unit">\
            <a href="javascript:;" class="uiBtn e2-wxmenu-showEventType">返回</a>\
        <a href="javascript:;" class="uiBtn BtnGreen e2-wxmenu-saveView">确定</a>\
        </div>\
        </div>';
        $('#wxmenu-area').html(html);
        G('wx-viewurl').focus();
    },
    //显示事件相应富文本输入框,可输表情.
    showClickType: function(data, type) {
        type = type || 'text';
        var texton = type == 'event' ? 'on' : '';
        var newson = type == 'news' ? 'on' : '';
        var html = '<div class="wxtype-event">\
            <div class="wxmsg-types clear" id="wxmsg-tabs">\
                <a href="javascript:;" rel="text" class="type-btn tab_text e2-wxmenu-editTab-text  ' + texton + '">\
                    <i class="iconfont">&#xe603;</i>\
                    <span>文本</span>\
                </a>\
                <a href="javascript:;" rel="image" class="type-btn tab_img e2-wxmenu-editTab-image" style="display: none">\
                    <i class="iconfont">&#xe611;</i>\
                    <span>图片</span>\
                </a>\
                <a href="javascript:;" rel="news" class="type-btn tab_news e2-wxmenu-editTab-news ' + newson + '">\
                    <i class="iconfont">&#xe602;</i>\
                    <span>图文</span>\
                </a>\
            </div>\
            <div id="wxmenu-itext">\
                <div class="wxmsg-editor" id="wxmsgEditBox" contenteditable="true">' + ((typeof data != 'undefined') && type == 'text' ? data : '') + '</div>\
                <div class="msg-em">\
                    <a href="javascript:;" class="e2-wxmenu-showEmBox"><i class="iconfont">&#xe616;</i></a>\
                    <div class="wxems clear wxem-box">\
                        <div class="em-list clear"></div>\
                    </div>\
                    <div class="wxmsg-word">还可输入<span>600</span>字</div>\
                </div>\
            </div>\
            <input type="hidden" id="wxmenu_dataid" value="" />\
            <div class="wxmsg-odata" id="wxmenu-odata" ' + (type != 'image' ? 'style="display:none"' : '') + '>\
            </div>\
            <div class="f-unit">\
                <a href="javascript:;" class="uiBtn e2-wxmenu-showEventType">返回</a>\
                <a href="javascript:;" class="uiBtn BtnGreen e2-wxmenu-saveEvent">确定</a>\
            </div>\
        </div>';
        $('#wxmenu-area').html(html);

        var wx_data_seg;
        if (type == 'text') {
            this.setMenuOn(type);
        } else {
            //news|wheel
            wx_data_seg = data.split('|');
            this.editTabSet(type, wx_data_seg[1])
        }
    },

    createWxEditor: function(editor_id, options) {
        var opts = {
            data: '',
            type: 'text',
            toolbar: {
                text: '<a href="javascript:;" rel="text" class="type-btn tab_text e2-wxmenu-editTab-text">\
                    <i class="iconfont">&#xe603;</i>\
                    <span>文本</span>\
                </a>',
                image: '<a href="javascript:;" rel="image" class="type-btn tab_img e2-wxmenu-editTab-image" style="display: none">\
                    <i class="iconfont">&#xe611;</i>\
                    <span>图片</span>\
                </a>',
                news: '<a href="javascript:;" rel="news" class="type-btn tab_news e2-wxmenu-editTab-news">\
                    <i class="iconfont">&#xe602;</i>\
                    <span>图文</span>\
                </a>'
            }
        };
        opts = Extend(opts, options || {});

        var html = '<div class="wxmsg-types clear" id="wxmsg-tabs">';
        for (var i in opts.toolbar) html += opts.toolbar[i];
        html += '</div>';
        html += '\
            <div id="wxmenu-itext">\
                <div class="wxmsg-editor" id="wxmsgEditBox" contenteditable="true">' + (opts.type == 'text' ? opts.data : '') + '</div>\
                <div class="msg-em">\
                    <a href="javascript:;" class="e2-wxmenu-showEmBox"><i class="iconfont">&#xe616;</i></a>\
                    <div class="wxems clear wxem-box">\
                        <div class="em-list clear"></div>\
                    </div>\
                    <div class="wxmsg-word">还可输入<span>600</span>字</div>\
                </div>\
            </div>\
            <input type="hidden" id="wxmenu_dataid" value="" />\
            <div class="wxmsg-odata" id="wxmenu-odata" ' + (opts.type != 'image' ? 'style="display:none"' : '') + '>\
            </div>';

        typeof editor_id != 'undefined' && $('#' + editor_id).html(html);

        if (opts.type == 'text') {
            this.setMenuOn(opts.type);
        } else {
            this.editTabSet(opts.type, opts.data)
        }
    },

    //上传图片
    /**
     * 选择商品图
     */
    selImage: function() {
        var t = this;
        var self = t._self;
        upload.show({
            maxnum: 1, //允许选择的图片数量.
            type: 'image',
            category: 'gallery', //所属分类.
            //返回的数据回调函数:data格式为:
            // [{ imgSrc:'原图路径',imgPath:'相对路径',imgName:'图片名称'}]
            callback: function(data) {
                data.each(function() {
                    t.stuffWechatImg(this);
                });
            },
            //在选择的时候做处理
            onselect: function(data) {
                var res = t.stuffWechatImg(data);
                if (res) upload.hidebox();
                return res;
            }
        });
    },
    /**
     * 图片上传完成后,处理显示
     */
    stuffWechatImg: function(data) {
        var t = this;
        var imgId = data.imgID;
        var imgurl_src = data.imgSrc;
        var imgSize = data.imgSize;
        var img_name = data.imgName;
        var img_path = data.imgPath;
        var img_thumb = img_path.replace('_src.', '_thumb.');
        $('#up_thumb img')[0].src = imgurl_src;
        $('#image').show();
        $("#news_list").hide();
        $("#wxmenu-itext").hide();

        return true;
    },


    //图文回复
    getNewsList: function(page) {
        $post({
            url: '/wxmenu/getNewsList/' + (page || 1),
            method: 'post',
            callback: function(x) {
                com.xshow('选择图文', x, { hideBtn: true, isText: false });
            }
        });
    },
    getNewsListPage: function() {
        var a = this._self,
            b = this._target;
        if (b.parentNode.tagName.toLowerCase() == 'a') b = b.parentNode;
        if (b.tagName.toLowerCase() == 'a') {
            if (b.rel != '') this.getNewsList(b.rel);
        }
    },

    getWheelList: function() {
        $post({
            url: '/event/lottery/getWheelList',
            method: 'post',
            callback: function(x) {
                com.xshow('选择活动', x, { hideBtn: true });
            }
        });
    },





    //保存自动回复内容.
    saveAutoReply: function() {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var data = this.getWxEditorData();
        data.reply_type = G('reply_type').value;
        $post({
            url: '/wxreply/submitform',
            data: data,
            callback: function(x) {

            }
        });

    },

    //保存微信菜单事件响应内容数据(文本,图片,图文,大转盘).
    saveEvent: function() {
        var wxData = this.getWxEditorData();

        this.wxKeyData[this.currMenuKey] = {
            type: wxData.type,
            data: wxData.type == 'text' ? wxData.data : wxData.type + '|' + wxData.data,
            key: this.currMenuKey
        };
        this.saveMenu();
    },

    //保存微信菜单URL类型信息(微信菜单中菜单的URL)
    saveView: function() {
        var type = 'view';
        var data = G('wx-viewurl').value.trim();
        this.wxKeyData[this.currMenuKey] = {
            type: type,
            data: data
        };
        this.saveMenu();
    },






    //===============================拖拽排序=============================
    /**
     * 取消排序和完成排序，根据按钮类型判断：BtnGreen.
     */
    moveMode: function() {
        var menuBox = $('#menu-box');
        if (menuBox.hasClass('move-mode')) {
            menuBox.removeClass('move-mode');
            $(this._self.parentNode).removeClass('mode-move').addClass('mode-normal');

            if ([this._self].hasClass('BtnGreen')) {
                //完成排序,保存
                this.saveMenu();
            } else {
                //取消排序,重置.
                this.stuffMenu();
            }

        } else {
            //设置为拖拽状态
            menuBox.addClass('move-mode');
            $('#menu-box .item-entity').removeClass('on');
            $(this._self.parentNode).removeClass('mode-normal').addClass('mode-move');
        }
    },

    //开始移动排序
    moveMenuOrder: function(obj) {
        var t = this;
        if (obj.parentNode.tagName.toLowerCase() == 'dt') {
            //移动块的位置
            t.getDlPos();
            t.moveOrder(obj);
        } else {
            //移动dl的位置
            t.getItemPos(obj.parentNode.parentNode);
            t.moveOrder(obj);
        }
    },
    //创建排序位置的线.
    getTmpLine: function(obj) {
        var tagName = obj.tagName.toLowerCase() == 'dd' ? 'dd' : 'div';
        var tmpLine = document.createElement(tagName);
        tmpLine.className = 'move-tmp-line';
        tmpLine.id = 'move-tmp-line';
        tmpLine.innerHTML = '<div></div>';
        return tmpLine;
    },
    //根据移动的节点类型,判断移动的对象是dd(子菜单)还是dl(一级菜单).
    moveOrder: function(obj) {
        var elType = obj.parentNode.tagName.toLowerCase() == 'dd' ? 'dd' : 'dt';
        var dragUnit = elType == 'dd' ? obj.parentNode : obj.parentNode.parentNode;
        var t = this;
        //页面滚动位置
        var scrTop = parseInt(G('main').scrollTop);

        //复制一个当前要拖拽的节点,装进跟随移动框.用于显示互动效果.
        var objDl = dragUnit.cloneNode(true);
        t.dlBox = document.createElement('div');
        t.dlBox.className = 'wx-move-box wx-menu';
        t.dlBox.appendChild(objDl);
        document.body.appendChild(t.dlBox);

        var dlPos = $.ePos(dragUnit);
        var mousepos = $.mPos(this._event);

        //IE浏览器要设置捕获.↓,拖拽交互优化,防止选中等问题
        if ($.isIE) obj.setCapture();
        if ($.isChrome) $('body').addClass('SelectNone');

        //鼠标点击时的坐标和元素dragUnit左上角坐标偏移.
        var offset = {
            x: mousepos.x - dlPos.x,
            y: mousepos.y - dlPos.y + scrTop
        };
        //拖拽位置线
        var tmpLine = this.getTmpLine(obj);

        document.onmousemove = function(e) {
            var pos_m = $.mPos(e || window.event);
            var posY = pos_m.y + scrTop;
            Extend(t.dlBox.style, {
                display: 'block',
                left: (pos_m.x - offset.x) + 'px',
                top: (pos_m.y - offset.y) + 'px'
            });
            var cur, curEl;

            for (var i = 0, l = t.elPos.length; i < l; i++) {
                cur = t.elPos[i];
                //只需要计算y轴位置即可.
                if (posY > cur.y && posY < (cur.y + cur.h)) {
                    curEl = cur.el;
                    if (posY > cur.y + cur.h / 2) {
                        //下半段
                        //做一个判断,看有没有下一个节点,总之就是要插入当前节点的下面
                        curEl.nextSibling ?
                            curEl.parentNode.insertBefore(tmpLine, curEl.nextSibling) :
                            curEl.parentNode.appendChild(tmpLine);
                    } else {
                        //上半段
                        curEl.parentNode.insertBefore(tmpLine, curEl);
                    }
                    break;
                }
            }
        }

        document.onmouseup = function(e) {
            this.onmousemove = this.onmouseup = null;
            if ($.isIE) obj.releaseCapture();
            if ($.isChrome) $('body').removeClass('SelectNone');
            var el = G('move-tmp-line');
            if (el) {
                dragUnit.parentNode.insertBefore(dragUnit, el);
                [el].remove();
            }
            [t.dlBox].remove();
            //拖拽结束,调用回调函数.
            if (typeof t.moveOrderCallback == 'function') {
                t.moveOrderCallback();
            }
        }
    },














    //点击编辑器标签:文本,图片,图文,大转盘
    editTab: function(type) {
        var typeUrls = {
            'news': {
                'wintag': '选择图文',
                'url': '/wxmenu/getNewsList'
            }, //图文
            'wheel': {
                'wintag': '选择大转盘',
                'url': '/event/lottery/getWheelList'
            } //大转盘
        };
        if (type == 'text') {
            this.setMenuOn(type);
        } else {
            if (typeof typeUrls[type] == 'undefined') {
                com.xtip('组件' + type + '不存在!', { type: 1 });
                return;
            }

            $post({
                url: typeUrls[type].url,
                method: 'post',
                callback: function(x) {
                    com.xshow(typeUrls[type].wintag, x, { hideBtn: true });
                }
            });
        }
    },
    //微信编辑器:选择图文,图片,大转盘
    editTabSet: function(type, id) {
        //wxmenu.getMenuNews|lottery.selectWheel
        //设置好对应的类型和ID，并读取相关的内容进行显示.
        var typeUrls = {
            'text': '/wxmedia/appmsg/getText/' + id,
            'news': '/wxmedia/appmsg/showAppmsgTpl/' + id, //图文
            'wheel': '/event/lottery/getWheelNews/' + id //大转盘
        };

        if (typeof this.editTabCallback == 'function') {
            this.editTabCallback(type, id);
            return;
        }

        var t = this;
        $post({
            url: typeUrls[type],
            method: 'post',
            newLink: true,
            callback: function(x) {

                if (typeof t.wxEditorCallback === 'function') {
                    t.wxEditorCallback({
                        html: x,
                        data: id,
                        type: type
                    });
                } else {
                    $('#wxmenu-odata').html(x);
                    t.setMenuOn(type);
                    G('wxmenu_dataid').value = id;
                    com.xhide();
                }


            }
        });
    },

    //设置事件内容的菜单样式.
    setMenuOn: function(btn_type) {
        //btn_type
        $('#wxmsg-tabs a').each(function() {
            if ($(this).hasClass('tab_' + btn_type)) {
                $(this).addClass('on');
            } else {
                $(this).removeClass('on');
            }
        });
        if (btn_type == 'text') {
            $('#wxmenu-itext').show();
            $('#wxmenu-odata').hide();
        } else {
            $('#wxmenu-itext').hide();
            $('#wxmenu-odata').show();
        }
    },


    //获取编辑器中的数据和数据类型
    getWxEditorData: function() {
        var data, type = $('#wxmsg-tabs .on')[0].rel;
        if (type == 'text') {
            data = G('wxmsgEditBox').innerHTML;
        } else {
            data = G('wxmenu_dataid').value.trim();
        }
        return { type: type, data: data }
    },





    //微信关键词回复编辑器
    textEditor: function(data) {
        return '<div class="wxmsg-editor" contenteditable="true" style="width:500px; height:200px; border-top:1px solid #ddd">' + (data || '') + '</div>\
                <div class="msg-em">\
                    <a href="javascript:;" class="e2-wxmenu-showEmBox"><i class="iconfont">&#xe616;</i></a>\
                    <div class="wxems clear wxem-box">\
                        <div class="em-list"></div>\
                    </div>\
                    <div class="wxmsg-word">还可输入<span>600</span>字</div>\
                </div>';
    },

    //step2:显示对应的弹出层,用于选择数据类型.
    showEditor: function(opts) {
        var options = {
            type: 'text',
            data: '', //文本内容
            callback: function(data) {}
        };
        options = Extend(options, opts || {});

        if (typeof options.callback === 'function') {
            this.wxEditorCallback = options.callback;
        } else {
            this.wxEditorCallback = null;
        }

        if (options.type == 'text') {
            if (typeof options.showTextWin != 'undefined') {
                com.xshow('添加回复文字', this.textEditor(opts.data), { trueEvent: 'e2-wxmenu-getTextData' });
                $('.wxmsg-editor', com.win)[0].focus();
                return;
            } else {
                //切换到div显示.
            }
        } else {
            this.editTab(options.type);
        }
    },
    //step3:文本数据回调
    getTextData: function() {
        //获取编辑器内容
        var editorEl = $('.wxmsg-editor', com.win);
        if (editorEl.length == 0) {
            return;
        }
        var data = editorEl[0].innerHTML;

        this.wxEditorCallback({
            html: data,
            data: data,
            type: 'text'
        });
    },
    //step5:编辑文本回复内容
    editReplyItem: function() {
        var dataEl = $('.reply_html', this._self.parentNode.parentNode);
        var iptEl = $('.reply_data', this._self.parentNode.parentNode);
        var text = '';
        if (dataEl.length == 1) {
            text = dataEl[0].innerHTML;
        }

        this.showEditor({
            type: 'text',
            data: text,
            showTextWin: true,
            status: 'edit',
            callback: function(data) {
                dataEl[0].innerHTML = data.html;
                iptEl[0].value = data.html;
                com.xhide();
                //t.appendMsgBox(data);
            }
        });

    },
    //删除回复选项
    rmReplyItem: function() {
        [this._self.parentNode.parentNode].remove();
    },
    //step4:将选择的数据进行显示.
    appendMsgBox: function(data) {
        $('#reply_list li').remove(); //目前仅支持回复一条信息,允许回复多条删除这句即可.
        var li = document.createElement('li');
        li.innerHTML = '<div class="reply_html">' + data.html + '</div><div class="wxreply-opera">' +
            (data.type == 'text' ?
                '<a href="javascript:;" class="uiBtn e2-wxmenu-editReplyItem"><i class="iconfont">&#xe603;</i></a> ' : '') +
            '<a href="javascript:;" class="uiBtn e2-wxmenu-rmReplyItem"><i class="iconfont">&#xe606;</i></a> ' +
            '</div>' +
            '<input type="hidden" name="reply_data[' + this.wxreplyIndex + ']" class="reply_data" value="' + data.data + '" />' +
            '<input type="hidden" name="reply_type[' + this.wxreplyIndex + ']" class="reply_type" value="' + data.type + '" />';

        G('reply_list').appendChild(li);
        this.wxreplyIndex++;
        com.xhide();
    },

    //step1:点击编辑器toolbar菜单
    editMsg: function(type, data) {
        var t = this;
        this.showEditor({
            type: type,
            showTextWin: true,
            callback: function(data) {
                t.appendMsgBox(data);
            }
        });
    },

    //编辑完成:提交规则
    submitRule: function() {
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var Data = xForm.getFormValues('smartreplyForm');

        $post({
            url: '/wxreply/smartreply/submit',
            data: Data,
            callback: function(x) {

            }
        });
    }
};
wxmenu = new wxmenu;
