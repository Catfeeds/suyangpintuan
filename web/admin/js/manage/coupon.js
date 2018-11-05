// 必须确定main作用域存在 否则绑定到其他对象上
main.coupon = {
    initGoodscatSelection: function() {}
};
$.loadJs('/style/js/jquery-1.11.2.min.js', function() {
    jQuery.noConflict();
    $.loadJs('/admin/js/zTree_v3/js/jquery.ztree.all.min.js', function() {

        jQuery(document).ready(function($) {

            var setting = {
                check: {
                    enable: true,
                    // autoCheckTrigger: true
                },
                view: {
                    dblClickExpand: false
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                callback: {
                    beforeClick: function(treeId, treeNode) {
                        $.fn.zTree.getZTreeObj(treeId).checkNode(treeNode, !treeNode.checked, true, true);
                        return false;
                    },
                    onCheck: function(e, treeId, treeNode) {
                        var nodes = $.fn.zTree.getZTreeObj(treeId).getCheckedNodes(true),
                            arr = [],
                            val = '',
                            target = $(e.target).data('target');
                        if (nodes.length) {
                            for (var i = 0, l = nodes.length; i < l; i++) {
                                arr.push(nodes[i].id);
                            }
                            val = arr.join(',');
                        }

                        $(target).val(val);
                        checkTarget(target);
                    }
                }
            };

            var checkTarget = function(target) {
                var $target = $(target),
                    length, tips = '',
                    val = $target.val();

                $target.next('.tree-tips').remove();

                if (val) {
                    length = val.split(',').length;
                    tips = '<span class="tree-tips"><strong class="c-red">已选择' + length + '条分类</strong><button class="clear-goodscat uiBtn" type="button" data-target="#' + $target.attr('id') + '">清空</button></span>';
                    $target.after(tips);
                }
            };

            // 重置全局方法
            main.coupon.initGoodscatSelection = function() {

                $('.goodscat-selection').each(function(index, el) {
                    var $this = $(this),
                        target = $this.data('target'),
                        data = { target: $(target).val() };

                    checkTarget(target);
                    $.getJSON('/home/goodscat', data, function(res) {
                        if (res.error === 0) {
                            $.fn.zTree.init($this, setting, res.data.list);
                        } else {
                            $this.html('暂无分类, 请按需前往商品管理设置商品分配');
                        }
                    });
                });
            };
            main.coupon.initGoodscatSelection();

            $(document).on('click', '.clear-goodscat', function() {
                var target = $(this).data('target'),
                    $target = $(target);
                $.fn.zTree.getZTreeObj($target.data('tree-id')).checkAllNodes(false);
                $target.val('');
                checkTarget(target);
            });




        });


    });
});
