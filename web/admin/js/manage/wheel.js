$.loadJs('/style/js/jquery-1.11.2.min.js', function() {
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        $(document).on('click', '.set-target', function() {
            var $this = $(this),
                $target = $($this.data('target'));
            if ('INPUT' == $target[0].tagName) {
                $target.val($this.data('value'));
            } else {
                $target.html($this.data('value'));
            }
        }).on('click', '.selection-search', function() {
            var $this = $(this),
                $content = $this.closest('.selection-content'),
                $form = $this.closest('form'),
                url = $form.attr('action'),
                param = $form.serialize();

            $.get(url, param, function(res) {
                if (res) {
                    $content.replaceWith(res);
                }
            });
        }).on('click', '.selection-reset', function() {
            var $this = $(this),
                $content = $this.closest('.selection-content'),
                $form = $this.closest('form'),
                url = $form.attr('action'),
                param = {
                    target: $('[name="target"]').val()
                };

            $.get(url, param, function(res) {
                if (res) {
                    $content.replaceWith(res);
                }
            });
        }).on('click', '.selection-content .pager a', function() {
            event.preventDefault();
            var $this = $(this),
                $content = $this.closest('.selection-content'),
                $form = $this.closest('form'),
                url = $form.attr('action'),
                param = $form.serialize(),
                page = $this.data('page');

            if (page){
                url =url +'/' + page;
            }

            $.get(url, param, function(res) {
                if (res) {
                    $content.replaceWith(res);
                }
            });
        }).on('click', '.clear-selection', function() {
            var $this = $(this),$li=$this.closest('li');
            $this.addClass('hidden');
            $li.find('.prize-result').addClass('hidden');
            $li.find('.input-result').val('');
        }).on('change', '[name="post[good_id_selected]"]', function() {
            var $this = $(this),
                $target = $($this.data('target')),
                $li = $target.closest('li');

            $target.val($this.val());
            $li.find('.good-img').attr('src', $this.data('img'));
            $li.find('.good-name').html($this.data('name'));
            $li.find('.good-stock').html($this.data('stock'));
            $li.find('.good-price').html($this.data('price'));
            $li.find('.good-result').removeClass('hidden');
            $li.find('.clear-selection').removeClass('hidden');

        }).on('change', '[name="post[coupon_id_selected]"]', function() {
            var $this = $(this),
                $target = $($this.data('target')),
                $li = $target.closest('li'),
                $tr = $this.closest('tr');

            $target.val($this.val());

            $li.find('.coupon-title').html($tr.find('.coupon-title').html());
            $li.find('.coupon-amount').html($tr.find('.coupon-amount').html());
            $li.find('.coupon-time').html($tr.find('.coupon-time').html());
            $li.find('.coupon-stock').html($tr.find('.coupon-stock').html());

            $li.find('.coupon-result').removeClass('hidden');
            $li.find('.clear-selection').removeClass('hidden');

        }).on('click', '.toggle-active', function(){
            $.post('/manage/wheel/toggleActive', {id:$(this).data('id')},function(){
                main.refresh();
            })
        })
    });


});
