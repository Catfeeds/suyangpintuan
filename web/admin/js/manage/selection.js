$.loadJs('/style/js/jquery-1.11.2.min.js', function() {
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        $(document).on('click', '.selection-complete', function() {
            var $this = $(this), source=$this.data('source'),target = $this.data('target');
            $(target).val($(source).val());

        }).on('click', '.selection-search', function() {
            var $this = $(this),
                $content = $this.closest('.selection-content'),
                $form = $this.closest('form'),
                param = $form.serialize();

            $.get($form.attr('action'), param, function(res) {
                if (res) {
                    $content.replaceWith(res);
                }

            });

        }).on('click', '.com-page a', function() {
            var $this = $(this),
                $content = $this.closest('.selection-content'),
                $form = $this.closest('form'),
                param = $form.serialize();
            $.get($form.attr('action')+'/'+$(this).data('page'), param, function(res) {
                if (res) {
                    $content.replaceWith(res);
                }
            });
        });
    });


});
