var template_msg = function() {};
template_msg.prototype = {
    checkStatus: function(index) {
        var i, status = true,
            $item = $('.checklist-item-' + index),
            $all = $('.checklist-all-' + index);
        for (i = 0; i < $item.length; i++) {
            if (false === $item[i].checked) {
                status = false;
                break;
            }
        }
        if ($all.length) {
            $all[0].checked = status;
        }
    },
    checkStatusAll: function() {
        for (var i = 0; i < 4; i++) {
            template_msg.checkStatus(i);
        }
    },
    checkList: function(index, id) {
        var $this = this._self,
            status = $this.checked ? true : false;

        $.ajax({
            dataType: 'json',
            url: '/manage/template_msg/setStatus',
            data: { id: id, index: index, status: status ? 1 : 0 },
            callback: function(res) {
                if (res.error > 0) {
                    $this.checked = !status;
                    com.xtip(res.msg, {
                        type: 2
                    });
                } else {
                    template_msg.checkStatus(index);
                }
            }
        });

    },
    checkListAll: function(index) {
        var i, id = [],
            $all = this._self,
            status = $all.checked ? true : false,
            $item = $('.checklist-item-' + index);

        for (i = 0; i < $item.length; i++) {
            // if ($item[i].checked != status) {
            id.push($item[i].value);
            // }
        }


        $.ajax({
            dataType: 'json',
            url: '/manage/template_msg/setStatus',
            data: { id: id, index: index, status: status ? 1 : 0 },
            callback: function(res) {
                if (res.error > 0) {
                    $all.checked = !status;
                    com.xtip(res.msg, {
                        type: 2
                    });
                } else {
                    for (i = 0; i < $item.length; i++) {
                        $item[i].checked = status;
                    }
                }
            }
        });


    },
    setvariable: function(index) {
        var $content = $('.template_content')[0];

        $content.value += this._self.innerHTML;
        template_msg.checkContent($content.value);

    },
    checkContent: function(string) {
        var $notice = $('.template_content_notice')[0],
            length = string.length,
            total = 120;

        if (length > total) {
            $notice.innerHTML = '<p style="color:red;">对不起,您已经输入<b>' + length + '</b>个字符.超出消息发送最大长度<b>' + (length - total) + '</b>个字符</p>';
            return false;
        } else {
            $notice.innerHTML = '当前已输入<b>' + length + '</b>个字符, 您还可以输入<b>' + (total - length) + '</b>个字符。';
            return true;
        }
    },
    restContent: function() {
        var $content = document.getElementById('template_content');
        $content.value = document.getElementById('template_default_content').innerHTML;
        template_msg.checkContent($content.value);
    },
    formSubmit: function() {
        var template_content = document.getElementById('template_content'),
            form = document.getElementById('form-template_msg-edit');
        if (template_content.value.length > 120) {
            com.xtip('对不起.模版内容超过120字符,将无法发送.请减少内容后重新提交', {
                type: 2
            });
        } else {

            var data = $.formData(form);
            $.ajax({
                dataType: 'json',
                url: form.action,
                data: data,
                callback: function(res) {
                    if (res.error > 0) {
                        com.xtip(res.msg, {
                            type: 2
                        });
                    } else {
                        com.xhide();
                        main.refresh();
                    }
                }
            });
        }
        return false;
    }
};
template_msg = new template_msg();
