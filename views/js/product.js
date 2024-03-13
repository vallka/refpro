$(function () {
    var ajax = null;

    function initRefproWidgetOnProduct() {
        if (!$('.refpro_reward').length) {
            return;
        }

        if (ajax) {
            ajax.abort();
            ajax = null;
        }

        ajax = $.ajax({
            url: document.location.href,
            type: 'POST',
            dataType: 'json',
            data: {
                updateRefproReward: true,
                id_pa: getCombination()
            },
            success: function (json) {
                $('.refpro_reward').html(json.reward);
            }
        });
    }

    function getCombination() {
        var id = null;
        if ($('#product-details').length) {
            var data = $('#product-details').data('product');
            id = data.id_product_attribute;
        }
        if ($('#idCombination').length) {
            id = $('#idCombination').val();
        }
        return parseInt(id);
    }

    initRefproWidgetOnProduct();

    if (typeof prestashop != 'undefined') {
        prestashop.on('updatedProduct', function (e) {
            //e.id_product_attribute
            initRefproWidgetOnProduct();
        });
    }
    window.oldFindCombinationRefpro = window.findCombination;

    window.findCombination = function(firstTime)
    {
        oldFindCombinationRefpro(firstTime);
        initRefproWidgetOnProduct();
    };
});