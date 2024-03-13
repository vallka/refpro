{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    Goryachev Dmitry    <dariusakafest@gmail.com>
* @copyright 2007-2017 Goryachev Dmitry
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{if $has_voucher}
    <p>{l s='or' mod='refpro'}</p>
    <p>{l s='You can use the following voucher code to refer customers:' mod='refpro'}</p>
    <div>
        <div class="voucher_cart_rule">
            <div class="wrapp_voucher_cart_rule">
                <div class="voucher_code">{$voucher_cart_rule->code|escape:'quotes':'UTF-8'}</div>
                <div class="voucher_description">
                    {l s='Discount' mod='refpro'}
                    {if $voucher_cart_rule->free_shipping}
                        "{l s='Free shipping' mod='refpro'}"
                    {elseif $voucher_cart_rule->reduction_percent > 0}
                        {$voucher_cart_rule->reduction_percent|floatval}%
                    {else}
                        {convertPriceWithCurrency price=$voucher_cart_rule->reduction_amount currency=$currency_default}
                    {/if} - {l s='send it to the customer' mod='refpro'}</div>
            </div>
        </div>
    </div><br>
{/if}