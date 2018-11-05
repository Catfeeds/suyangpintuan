var score = function() {};
score.prototype = {
    formSubmit: function() {
        var form = document.getElementById('form-score'),
            data = $.formData(form);

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
                    com.xtip(res.msg, {
                        type: 1
                    });
                }
            }
        });

    },
    signSubmit: function() {
        var form = document.getElementById('form-score-sign'),
            data = $.formData(form),
            signExtends = form.querySelectorAll('.sign-extend'),
            tmpTarget, tmpAmount, tmpInputs;

        for (var i = 0; i < signExtends.length; i++) {

            tmpInputs = signExtends[i].querySelectorAll('input');

            tmpTarget = tmpInputs[0].value;
            if (tmpTarget <= 0 || parseInt(tmpTarget) != tmpTarget) {
                com.xtip('对不起,连续签到天数需是正整数 ', {
                    type: 2
                });
                tmpInputs[0].focus();
                return false;
            }

            tmpAmount = tmpInputs[1].value;
            if (tmpAmount === 0 || parseInt(tmpAmount) != tmpAmount) {
                com.xtip('对不起,奖励积分需是非0整数 ', {
                    type: 2
                });
                tmpInputs[1].focus();

                return false;
            }
        }

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
                    com.xtip(res.msg, {
                        type: 1
                    });
                }
            }
        });
    },
    signExtendAdd: function() {

        var form = document.getElementById('form-score-sign'),
            btn = form.querySelector('.tr-btn'),
            nodes = form.querySelectorAll('.extend'),
            node = document.createElement('tr'),
            count = nodes ? nodes.length : 0,
            nextCount = count + 1;

        if (nextCount > 10) {
            com.xtip('对不起,最多增加10条扩展规则', {
                type: 2
            });
            return false;
        }

        node.className = 'extend';
        node.innerHTML = '<td>扩展规则:</td>\
        <td><label> 连续签到 </label><input type = "input" class = "form-i w40" name = "post[extend][' + count + '][target]" value = "' + nextCount * 2 + '" />天: \
        <label> 奖励 </label><input type = "input" class = "form-i w40" name = "post[extend][' + count + '][amount]" value = "' + nextCount * 2 + '" />积分\
        <button style="margin-left: 20px;" class="uiBtn e2-score-extendRemove">删除本条</button></td>';

        btn.parentNode.insertBefore(node, btn);
    },
    rechargeSubmit: function() {
        var form = document.getElementById('form-score-recharge'),
            data = $.formData(form),
            trExtends = form.querySelectorAll('.extend'),
            tmpTarget, tmpAmount, tmpInputs;

        for (var i = 0; i < trExtends.length; i++) {

            tmpInputs = trExtends[i].querySelectorAll('input');

            tmpTarget = tmpInputs[0].value;
            if (tmpTarget <= 0 || isNaN(tmpTarget)) {
                com.xtip('对不起,金额需是正数 ', {
                    type: 2
                });
                tmpInputs[0].focus();
                return false;
            }

            tmpAmount = tmpInputs[1].value;
            if (tmpAmount === 0 || parseInt(tmpAmount) != tmpAmount) {
                com.xtip('对不起,奖励积分需是非0整数 ', {
                    type: 2
                });
                tmpInputs[1].focus();

                return false;
            }
        }

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
                    com.xtip(res.msg, {
                        type: 1
                    });
                }
            }
        });
    },
    rechargeExtendAdd: function() {

        var form = document.getElementById('form-score-recharge'),
            btn = form.querySelector('.tr-btn'),
            nodes = form.querySelectorAll('.extend'),
            node = document.createElement('tr'),
            count = nodes ? nodes.length : 0,
            nextCount = count + 1;

        if (nextCount > 10) {
            com.xtip('对不起,最多增加10条扩展规则', {
                type: 2
            });
            return false;
        }

        node.className = 'extend';
        node.innerHTML = '<td>扩展规则:</td>\
        <td><label> 满 </label><input type = "input" class = "form-i w40" name = "post[extend][' + count + '][target]" value = "' + nextCount * 50 + '" />元: \
        <label> 奖励 </label><input type = "input" class = "form-i w40" name = "post[extend][' + count + '][amount]" value = "' + nextCount * 50 + '" />积分\
        <button style="margin-left: 20px;" class="uiBtn e2-score-extendRemove">删除本条</button></td>';

        btn.parentNode.insertBefore(node, btn);
    },

    extendRemove: function() {
        this._self.parentNode.parentNode.remove();
    }

};
score = new score();
