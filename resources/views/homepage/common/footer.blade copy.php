<?php
/*$menu_footer = getMenus('menu-footer'); */
?>
<script src="{{asset('library/toastr/toastr.min.js')}}"></script>
<link href="{{asset('library/toastr/toastr.min.css')}}" rel="stylesheet">
<link href="{{asset('library/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('library/sweetalert/sweetalert.min.js')}}"></script>
@include('components.alert-success')
<script src="{{asset('frontend/js/hc-offcanvas-nav.js')}}"></script>
<script>
    function myFunction(x) {
        x.classList.toggle("change")
    }(function($) {
        var $main_nav = $('#main-nav');
        var $toggle = $('.toggle');
        var defaultData = {
            maxWidth: !1,
            customToggle: $toggle,
            levelTitles: !0,
            pushContent: '#container'
        };
        $main_nav.find('li.add').children('a').on('click', function() {
            var $this = $(this);
            var $li = $this.parent();
            var items = eval('(' + $this.attr('data-add') + ')');
            $li.before('<li class="new"><a>' + items[0] + '</a></li>');
            items.shift();
            if (!items.length) {
                $li.remove()
            } else {
                $this.attr('data-add', JSON.stringify(items))
            }
            Nav.update(!0)
        });
        var Nav = $main_nav.hcOffcanvasNav(defaultData);
        const update = (settings) => {
            if (Nav.isOpen()) {
                Nav.on('close.once', function() {
                    Nav.update(settings);
                    Nav.open()
                });
                Nav.close()
            } else {
                Nav.update(settings)
            }
        };
        $('.actions').find('a').on('click', function(e) {
            e.preventDefault();
            var $this = $(this).addClass('active');
            var $siblings = $this.parent().siblings().children('a').removeClass('active');
            var settings = eval('(' + $this.data('demo') + ')');
            update(settings)
        });
        $('.actions').find('input').on('change', function() {
            var $this = $(this);
            var settings = eval('(' + $this.data('demo') + ')');
            if ($this.is(':checked')) {
                update(settings)
            } else {
                var removeData = {};
                $.each(settings, function(index, value) {
                    removeData[index] = !1
                });
                update(removeData)
            }
        })
    })(jQuery)
    // new WOW().init();
</script>