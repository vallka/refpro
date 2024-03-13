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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if is_array($customers) && count($customers)}
<h2>{l s='Active affiliates list:' mod='refpro'}</h2>
    <table class="table GIRefTableSortable" id="refTable">
        <thead>
            <th class="cb"><input type="checkbox" name="thcb" id="thcb" /></th>
            <th class="name">{l s='Partner Name' mod='refpro'}</th>
            <th class="name">{l s='Email' mod='refpro'}</th>
            {if !$ip_installed}<th class="money">{l s='Account balance' mod='refpro'}</th>{/if}
            <th class="total">{l s='Total earned' mod='refpro'}</th>
            {if !$ip_installed}<th class="actions">{l s='Actions' mod='refpro'}</th>{/if}
        </thead>
        <tbody>
        {foreach from=$customers item=customer}
            <tr {if $customer['order']}class='hasOrder' title='{l s='Withdrawal ordered' mod='refpro'}'{/if} >
                <td class="cb"><input type="checkbox" name="cust_{$customer['id']|escape:'quotes':'UTF-8'}" /></td>
                <td class="name">{$customer['firstname']|escape:'quotes':'UTF-8'} {$customer['lastname']|escape:'quotes':'UTF-8'}</td>
                <td class="name">{$customer['email']|escape:'quotes':'UTF-8'}</td>
                {if !$ip_installed}<td class="money">{Referral::formatMoney($customer['money'])|escape:'quotes':'UTF-8'}</td>{/if}
                <td class="total">{Referral::formatMoney($customer['total'])|escape:'quotes':'UTF-8'}</td>
		{if !$ip_installed}
                <td class="name">
                    {if $customer['money']}
                        <a href="#" class="transfer">{l s='Clear the balance' mod='refpro'}</a>
                        <div class="goZeroData">
                            <p><b>{l s='Partner details:' mod='refpro'}</b></p>
                            {assign var=wallets value=false}
                            {for $i=1 to 5}
                                {assign var=key value="ps_`$i`"}
                                {if $customer[$key] && $p_systems->$key}
                                    {assign var=wallets value=true}
                                    <p>{$p_systems->$key|escape:'quotes':'UTF-8'}: {$customer[$key]|escape:'quotes':'UTF-8'}</p>
                                {/if}
                            {/for}
                            {if !$wallets}
                                <p>{l s='The partner has not filled details!' mod='refpro'}</p>
                            {/if}
                        </div>
                    {/if}

                </td>
		{/if}
            </tr>
        {/foreach}

        </tbody>
    </table>
    <table>
        <tr>
            <td colspan="6">{l s='The total number of the participants in the affiliate program:' mod='refpro'} <strong>{$total_refs|escape:'quotes':'UTF-8'}</strong></td>
        </tr>
    </table>
    {if !$ip_installed}<p><span style="background-color: #DDDDDD;">{l s='Grey' mod='refpro'}</span> {l s='is for the partners who ordered the withdrawal.' mod='refpro'}</p>{/if}
    <div class='customDiv'>
        <p class="tip">{l s='Mark the partners whom You need to send a letter. In order to make the mailing to all participants of the affiliate program, leave all checkboxes unchecked.' mod='refpro'}</p>
        <input type="button" id="mailerButton" value="{l s='Send newsletter' mod='refpro'}" class="bigButton btn btn-default" style="margin-left:0;"/>
    </div>
{else}
    {if $total_refs == 0}
        <p>{l s='Registered partners in the affiliate program are absent.' mod='refpro'}</p>
    {else}
        <p>{l s='Registered partners in the affiliate program not shown any activity.' mod='refpro'}</p>
        <p>{l s='The total number of the participants in the affiliate program:' mod='refpro'}<strong>{$total_refs|escape:'quotes':'UTF-8'}</strong></p>
        <div>
            <input type="button" id="mailerButton" value="{l s='Send newsletter' mod='refpro'}" class="bigButton btn btn-default" style="margin-left:0;"/>
        </div>
    {/if}
{/if}