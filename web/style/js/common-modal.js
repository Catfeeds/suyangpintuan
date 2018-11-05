$(document).on('tap', '.set-target', function() {
    var $this = $(this),
        $target = $($this.data('target'));
    if ($target.length) {
        if ('INPUT' == $target[0].tagName) {
            $target.val($this.data('value'));
        } else {
            $target.html($this.data('value'));
        }
    }
}).on('tap', '.li-radio-check-triger', function() {
    var $this = $(this),
    $parent = $(this).parent();
    $('.address-frist').removeClass('add-address');
    $('.address-frist').addClass('no-address');
    $('.no-address').removeClass('active'); 
    $this.addClass('active');
    $parent.closest('.modal').hide();
    $($this.data('target')).val($this.data('id'));
    $($parent.data('target')).html($this.html());
    if (!$('.modal-checkout:visible').length) {
        $('html').css('overflow', '');
    }        
    /*$parent.siblings('.li-radio-check').removeClass('on');
    $parent.addClass('on');
    $parent.find('input:radio').prop('checked', true);
    $parent.closest('.modal').hide();
    $($parent.data('target')).html($this.find('section:first').html());
    if (!$('.modal-checkout:visible').length) {
        $('html').css('overflow', '');
    }*/
}).on('tap', '.li-radio', function() {
    var $this = $(this);
    $this.siblings('.li-radio').removeClass('on');
    $this.addClass('on');
    $this.find('input:radio').prop('checked', true);
}).on('tap', '.li-checkbox', function() {
    var $this = $(this),
        status = !$this.hasClass('on');
    $this.toggleClass('on', status);
    $this.find('input:checkbox').prop('checked', status);

}).on('tap', '.toggle-modal', function() {
    var $this = $(this),
        target = $this.data('target'),
        $target = $(target),
        title = $this.data('title'),
        url = $this.data('url');

    $target.find('.modal-title').html(title);
    if (url) {
        //layer.load();
        $target.find('.modal-body').load(url, function() {
            $('html').css('overflow', 'hidden');
            $target.show();
            //layer.closeAll('loading');
        });
    } else {
        $('html').css('overflow', 'hidden');
        $target.show();
    }
}).on('tap', '.modal-close', function() {
    $(this).closest('.modal-checkout').hide();
    if (!$('.modal-checkout:visible').length) {
        $('html').css('overflow', '');
    }
})
