$.loadJs('/style/js/jquery-1.11.2.min.js', function() {
    jQuery.noConflict();

    $.loadJs('/admin/js/jquery.pep.js', function() {

        jQuery(document).ready(function($) {

            var setMoveable = function(target) {
                $(target).pep({
                    shouldEase: false,
                    useCSSTranslation: false,
                    constrainTo: 'parent',
                    // debug: true,
                    drag: function(ev, obj) {

                        var $target = obj.$el,
                            left = $target.css('left'),
                            top = $target.css('top'),
                            $select = $('#src-' + $target.attr('id'));

                        $select.find('.field-left').val(left);
                        $select.find('.field-top').val(top);
                    }
                });
            };


            (function() {
                $('#design-area').css({
                    'width': (parseInt($('.design-width').val()) + 50) + 'mm',
                    'height': (parseInt($('.design-height').val()) + 50) + 'mm'
                });
            })();

            setMoveable('.field-moveable');

            $(document).on('change', '.field-check', function() {
                var $this = $(this),
                    tpl = '',
                    $fieldArea = $this.closest('.field-area'),
                    target = $fieldArea.data('id'),
                    $target = $('#' + target);

                if ($this.prop('checked')) {
                    var val = $fieldArea.find('.field-title').val();
                    if (val) {
                        if (!$target.length) {
                            tpl = '<label class="field-moveable" id="' + target + '">' + val + '</label>';
                            $('#design-area').append(tpl);
                            setMoveable('#' + target);
                        } else {
                            $target.show();
                        }
                    }
                } else {
                    $target.hide();
                }

            }).on('keyup', '.field-title', function() {
                var $this = $(this),
                    $fieldArea = $this.closest('.field-area'),
                    target = $fieldArea.data('id'),
                    $target = $('#' + target),
                    $fieldStatus = $('#src-' + target).find('.field-status');
                val = $this.val();

                if ($fieldArea.find('.field-check').prop('checked')) {
                    if (val) {
                        if ($target.length) {
                            $target.show();
                        } else {
                            tpl = '<label class="field-moveable" id="' + target + '">' + val + '</label>';
                            $('#design-area').append(tpl);
                            setMoveable('#' + target);
                        }
                    } else {
                        $target.hide();
                    }
                }

                $target.html(val);

            }).on('click', '.add-field', function() {
                var $this = $(this),
                    $table = $('.set-field table'),
                    nextKey = $this.data('next-key'),
                    tpl = '<tr class="field-area field-ext" id="src-field-' + nextKey + '" data-id="field-' + nextKey + '">\
                        <td>\
                            <label class="field">\
                                <input type="hidden" name="post[field][' + nextKey + '][id]" value="">\
                                <input type="hidden" name="post[field][' + nextKey + '][name]" value="自定字段">\
                                <input type="hidden" name="post[field][' + nextKey + '][left]" value="0" class="field-left">\
                                <input type="hidden" name="post[field][' + nextKey + '][top]" value="0" class="field-top">\
                                <input type="hidden" name="post[field][' + nextKey + '][ref]" value="0">\
                                <input type="hidden" name="post[field][' + nextKey + '][default]" value="0">\
                                <input type="hidden" name="post[field][' + nextKey + '][status]" value="0">\
                                <input class="field-check" type="checkbox" name="post[field][' + nextKey + '][status]" value="1">\
                                自定字段</label>\
                        </td>\
                        <td>内容: <input class="form-i w200 field-title" type="text" name="post[field][' + nextKey + '][value]" value=""></td>\
                        <td><button class="uiBtn remove-field" style="padding:2px 5px;" type="button">删除本条</button></td></tr>';

                $table.append(tpl);

                $this.data('next-key', nextKey + 1);
            }).on('click', '.remove-field', function() {
                var $fieldArea = $(this).closest('.field-area'),
                    target = $fieldArea.data('id');
                $('#' + target).remove();
                $fieldArea.remove();

            }).on('change', '.design-width', function() {
                var val = parseInt($(this).val());
                if (val > 0) {
                    $('#design-area').css('width', (val + 50) + 'mm');
                } else {
                    com.xtip('纸张宽度须大于0', {
                        type: 2
                    });
                }
            }).on('change', '.design-height', function() {
                var val = parseInt($(this).val());
                if (val > 0) {
                    $('#design-area').css('height', (val + 50) + 'mm');
                } else {
                    com.xtip('纸张高度须大于0', {
                        type: 2
                    });
                }
            }).on('click', '.submit', function() {
                var $form = $('#form-express-print'),
                    params = $form.serialize();
                $.ajax({
                    url: $form.attr('action'),
                    type: 'post',
                    cache: false,
                    dataType: 'json',
                    data: params,
                    beforeSend: function() {},
                    success: function(res) {

                        if (res.error > 0) {

                        } else {
                            if (res.data && res.data.old_image) {
                                $('#config-image').val(res.data.old_image);
                            }
                        }
                        com.xtip(res.msg, {
                            type: 0
                        });
                    },
                    error: function() {
                        com.xtip('系统繁忙，请稍后重试', {
                            type: 2
                        });
                    },
                    complete: function() {}
                });
                return false;
            }).on('click', '.express-print-public', function() {
                var $this = $(this),
                    val = $this.val(),
                    expressId = $('#express_id').val();

                    if(confirm('当前未保存的设置将丢失. 确定切换到公用模版吗?')){
                        window.location = '/manage/#!express/printEdit/?id=' + expressId + '&publicTpl=' + val;
                        $('.e2-com-xhide').click();
                    }

            })
        });

    });
});
