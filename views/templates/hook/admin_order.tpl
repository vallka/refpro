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

{if $ps_15}
<br>
<br>
<fieldset>
    <legend>{l s='RefPro: Affiliate rewards' mod='refpro'}</legend>
{else}
<div class="panel card">
    <div class="panel-heading card-header">{l s='RefPro: Affiliate rewards' mod='refpro'}</div>
{/if}
    <div class="form-group clearfix">
        <div class="col-lg-12">
            <table class="table" {if $ps_15}style="width: 100%" {/if}>
                <thead>
                <tr>
                    <th>{l s='Email' mod='refpro'}</th>
                    <th>{l s='Level' mod='refpro'}</th>
                    <th>{l s='Sum' mod='refpro'}</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$bonuses item=row}
                    <tr>
                        <td>
                            {$row.email}
                        </td>
                        <td>{$row.level}{l s='-st level reward' mod='refpro'}</td>
                        <td>{displayPrice price=$row.sum}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $pages > 1}
        <div class="row">
            <div class="col-lg-12">
                <a data-total-pages="{$pages}" data-page="1" data-event-load-more href="#">
                    {l s='Show more' mod='refpro'}
                </a>
            </div>
        </div>
    {/if}
{if $ps_15}
</fieldset>
{else}
</div>
{/if}
<script>
    $('body').delegate('[data-event-load-more]', 'click', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var total_pages = $(this).data('total-pages');

        var self = $(this);
        $.ajax({
            url: document.location.href,
            type: 'POST',
            dataType: 'html',
            data: {
                ajax: true,
                action: 'load_more_bonuses_order',
                next_page: page + 1
            },
            success: function (html) {
                self.closest('.card').find('table tbody').append(html);
                self.data('page', page + 1);
                if (page + 1 >= total_pages) {
                    self.parent().parent().hide();
                }
            }
        });
    });
</script>