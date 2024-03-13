( function($) {
    $(document).ready(function() {
        $('[name="customer[has_voucher]"]').on('change', function () {

            $('.voucher_percent').closest('div[class^="form-group"]').hide();
            $('.voucher_amount').closest('div[class^="form-group"]').hide();
            if ($(this).val() == 'percent') {
                $('.voucher_percent').closest('div[class^="form-group"]').show();
            } else if ($(this).val() == 'amount') {
                $('.voucher_amount').closest('div[class^="form-group"]').show();
            }
        }).trigger('change');
    });
} ) ( jQuery );