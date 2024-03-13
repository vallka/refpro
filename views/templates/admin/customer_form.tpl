<div class="from-group clearfix has_voucher">
    <label class="control-label col-lg-3">{l s='To use invite-code/voucher to attract customers' mod='refpro'}</label>
    <div class="col-lg-9">
        <select name="has_voucher">
            <option {if !$user_data['has_voucher']}selected{/if} value="0">--{l s='None' mod='refpro'}--</option>
            <option {if $user_data['has_voucher'] == 'percent'}selected{/if} value="percent">%</option>
            <option {if $user_data['has_voucher'] == 'amount'}selected{/if} value="amount">{$get_def_currency|escape:'quotes':'UTF-8'}</option>
            <option {if $user_data['has_voucher'] == 'free_shipping'}selected{/if} value="free_shipping">{l s='Free shipping' mod='refpro'}</option>
        </select><br>
    </div>
</div>
<div class="form-group clearfix voucher_percent">
    <label class="control-label col-lg-3">{l s='Voucher percent' mod='refpro'}</label>
    <div class="col-lg-3">
        <input class="form-control" type="text" name="voucher_percent" value="{$user_data['voucher_percent']|escape:'quotes':'UTF-8'}" title="{l s='A positive real number! Separator is a point!' mod='refpro'}">
    </div>
</div>
<div class="form-group clearfix voucher_amount">
    <label class="control-label col-lg-3">{l s='Voucher amount' mod='refpro'}</label>
    <div class="col-lg-3">
        <input class="form-control" type="text" name="voucher_amount" value="{$user_data['voucher_amount']|escape:'quotes':'UTF-8'}" title="{l s='A positive real number! Separator is a point!' mod='refpro'}">
    </div>
</div>
<script>
    $('[name="has_voucher"]').on('change', function () {
        $('.voucher_percent, .voucher_amount').hide();
        if ($(this).val() == 'percent') {
            $('.voucher_percent').show();
        } else if ($(this).val() == 'amount') {
            $('.voucher_amount').show();
        }
    }).trigger('change');
</script>