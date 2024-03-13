(function () {
    var classStageModalJQuery = 'stage_modal_jquery';
    var classModalJQuery = 'modal_jquery';
    var uid = 1;

    $.fn.setCenterPosAbsBlockModal = function ()
    {
        var $this = $(this);
        centerPosAbs($this);
        $(window).resize(function () {
            centerPosAbs($this);
        });

        function centerPosAbs($this) {
            var offsetElemTop = 20;
            var scrollTop = $(document).scrollTop();
            var elemWidth = $this.width();
            var windowWidth = $(window).width();
            $this.css({
                top: ($this.height() > $(window).height()
                    ? scrollTop + offsetElemTop
                    : scrollTop + (($(window).height()-$this.height())/2)),
                left: ((windowWidth-elemWidth)/2)
            });
        }
    };

    function getUID() {
        return ++uid;
    }

    $.createModal = function (options) {
        var uid = getUID();

        (function () {
            var functions = {
                modalClose: function () {
                    $('.' + classModalJQuery+'[data-uid="'+uid+'"]').remove();
                    $('.' + classStageModalJQuery+'[data-uid="'+uid+'"]').remove();
                }
            };

            var defaults = {
                content: '',
                title: '',
                buttons: {},
                onLoad: function (functions) {

                }
            };
            defaults = $.extend(defaults, options);

            var $stageModalJQuery = $('<div></div>').addClass(classStageModalJQuery);
            $stageModalJQuery.attr('data-uid', uid);

            var $form = $('<div></div>').addClass(classModalJQuery);
            $form.attr('data-uid', uid);

            var $formHeader = '<a href="#" data-uid="'+uid+'" class="modal_close">' +
                '<i class="icon-remove fa fa-remove"></i>' +
                '</a>';
            if (defaults.title) {
                $formHeader = $('<div class="modal_header">' +
                    (defaults.title ? '<h3>'+defaults.title+'</h3>' : '') +
                    '<a href="#" class="modal_close" data-uid="'+uid+'">' +
                    '<i class="icon-remove fa fa-remove"></i>' +
                    '</a>' +
                    '</div>');
            }

            var $formContent = $('<div class="modal_content"></div>');
            $formContent.html(defaults.content);

            var $formFooter = '';

            var eventListeners = [];
            for (var name  in defaults.buttons) {
                if (!$formFooter) {
                    $formFooter = $('<div class="modal_footer text-right"></div>');
                }

                var date = new Date();
                var id = 'button' + date.getTime();
                $formFooter.append('<button type="button" id="'+id+'" class="btn btn-default">'+name+'</button>');
                eventListeners.push(function () {
                    $('#'+id).click(defaults.buttons[name]);
                });
            }

            $form.append($formHeader).append($formContent).append($formFooter);

            $('body').append($stageModalJQuery).append($form);
            $('.'+classModalJQuery).setCenterPosAbsBlockModal();

            $('.modal_close[data-uid="'+uid+'"], .'+classStageModalJQuery+'[data-uid="'+uid+'"]').on('click', function (e) {
                e.preventDefault();
                functions.modalClose();
            });

            $.each(eventListeners, function (index, func) {
                func();
            });
            defaults.onLoad(functions);
        })(uid);
    };
})();