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
{if !$ip_installed}
<h2>{l s='Payment systems:' mod='refpro'}</h2><p>{l s='Enter in the fields below the name of payment systems that will be used for withdrawal by partners.' mod='refpro'}</p>
<p>{l s='Important! Do not change the order of the payment system after running the affiliate program unless absolutely necessary. Partners details will be connected with a specific ID.' mod='refpro'}</p>
<table class="settings table">
    <thead>
        <th class="cb">{l s='ID' mod='refpro'}</th>
        <th>{l s='Name' mod='refpro'}</th>
    </thead>
    <tbody>
    {for $i=1 to 5}
        {assign var=key value="ps_`$i`"}
        {if !empty($p_systems->$key)}
            {assign var=value value=$p_systems->$key}
        {else}
            {assign var=value value=''}
        {/if}
        <tr>
            <td class="cb">{$i|escape:'quotes':'UTF-8'}</td>
            <td><input type="text" name="{$key|escape:'quotes':'UTF-8'}" value="{$value|escape:'quotes':'UTF-8'}" /></td>
        </tr>
    {/for}
    </tbody>
</table>
{/if}
<h2>{l s='Active order statuses:' mod='refpro'}</h2><p>{l s='Select the order statuses under which partner bonuses are added or removed.' mod='refpro'}</p>
<table class="table">
    <thead>
        <th>
            {l s='Add bonus' mod='refpro'}
        </th>
        <th>
            {l s='Remove bonus' mod='refpro'}
        </th>
        <th>
            {l s='Status name' mod='refpro'}
        </th>
    </thead>
    <tbody>
    {foreach from=$availeable_states item='as'}
        {assign var=is_active value=in_array($as['id_order_state'], $active_states)}
        {assign var=is_active_remove value=in_array($as['id_order_state'], $remove_states)}
        <tr>
            <td style="width: 20px!important; text-align: center;"><input class="clicbleChecking" value="{$as['id_order_state']|escape:'quotes':'UTF-8'}" type="checkbox" {if $is_active}checked="checked"{/if} name="active_state_{$as['id_order_state']|escape:'quotes':'UTF-8'}" /></td>
            <td style="width: 20px!important; text-align: center;"><input type="checkbox" value="{$as['id_order_state']|escape:'quotes':'UTF-8'}" id="removing_bonus_{$as['id_order_state']|escape:'quotes':'UTF-8'}" name="remove_state[]" {if $is_active_remove}checked="checked"{/if} /></td>
            <td>
                {$as['name']|escape:'quotes':'UTF-8'}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h2>{l s='Other settings:' mod='refpro'}</h2>
<table class="table settings2">
    <tbody>
	<tr>
        <td>{l s='Enable zero level rewards (for own purchases)' mod='refpro'}</td>
        <td>
            <input type='checkbox' name='zero_level_rewards' style="width: 3%;" value='1' {if Referral::getSettings('zero_level_rewards') == 1}checked='checked'{/if} title="{l s='Set this option ON if you want to charge rewards to affiliates for their own purchases (cashback)' mod='refpro'}">
        </td>
    </tr>
    <tr>
        <td>{l s='Affiliate program levels' mod='refpro'}</td>
        <td>
            <select name="levels">
				{if Referral::getSettings('zero_level_rewards') == 1}<option value="0" {if $levels == 0}selected='selected'{/if} >0</option>{/if}																   
                <option value="1" {if $levels == 1}selected='selected'{/if} >1</option>
                <option value="2" {if $levels == 2}selected='selected'{/if} >2</option>
                <option value="3" {if $levels == 3}selected='selected'{/if} >3</option>
                <option value="4" {if $levels == 4}selected='selected'{/if} >4</option>
                <option value="5" {if $levels == 5}selected='selected'{/if} >5</option>
                <option value="6" {if $levels == 6}selected='selected'{/if} >6</option>
                <option value="7" {if $levels == 7}selected='selected'{/if} >7</option>
                <option value="8" {if $levels == 8}selected='selected'{/if} >8</option>
                <option value="9" {if $levels == 9}selected='selected'{/if} >9</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>{l s='Banner link' mod='refpro'}</td>
        <td>
            <div class="row">
                <div class="col-lg-9">
                    <input type="text" name="banner_link" value="{Referral::getSettings('banner_link')|escape:'quotes':'UTF-8'}" title="{l s='Here you can set a link for the banner in the partner section of customer account. Empty this field to hide the banner.' mod='refpro'}" />
                </div>
                <div class="col-lg-3">
                    <input type="button" class="showFormLoadImage btn btn-default" value="{l s='Load image' mod='refpro'}">
                </div>
            </div>
        </td>
    </tr>
{*{if !$ip_installed}*}
    <tr>
        <td>{l s='Contact for notification' mod='refpro'}</td>
        <td>
            <select name="manager">
                {foreach from=$contacts item=contact}
                    <option value="{$contact['id_contact']|escape:'quotes':'UTF-8'}" {if $contact['id_contact'] == $current_manager}selected='selected'{/if}>{$contact['name']|escape:'quotes':'UTF-8'}</option>
                {/foreach}
            </select>

        </td>
    </tr>
{*{/if}*}
    <tr>
        <td>{l s='CMS page with Terms of Service' mod='refpro'}</td>
        <td>
            <select name="rules">
                <option value="0">--{l s='None' mod='refpro'}--</option>
                {foreach from=$cms_pages item=cms}
                    <option {if Referral::getSettings('rules') == $cms['id_cms']}selected="selected"{/if} value="{$cms['id_cms']|escape:'quotes':'UTF-8'}">{$cms['meta_title']|escape:'quotes':'UTF-8'}</option>
                {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td>{l s='Сustomer group for new affiliates' mod='refpro'}</td>
        <td>
            <select name="customer_group_new_affiliates">
                <option value="">{l s='By default' mod='refpro'}</option>
                {foreach from=$groups_affiliates item=group}
                    <option value="{$group['id_group']|escape:'quotes':'UTF-8'}" {if $group['id_group'] == $customer_group_new_affiliates}selected='selected'{/if}>{$group['name']|escape:'quotes':'UTF-8'}</option>
                {/foreach}
            </select>

        </td>
    </tr>
{*    <tr>*}
{*        <td>{l s='Contact for notifications' mod='refpro'}</td>*}
{*        <td>*}
{*            <select name="contact_for_notification">*}
{*                <option value="0">--{l s='None' mod='refpro'}--</option>*}
{*                {foreach from=$contacts item=contact}*}
{*                    <option {if Referral::getSettings('contact_for_notification') == $contact['id_contact']}selected="selected"{/if} value="{$contact['id_contact']|escape:'quotes':'UTF-8'}">{$contact['name']|escape:'quotes':'UTF-8'}</option>*}
{*                {/foreach}*}
{*            </select>*}
{*        </td>*}
{*    </tr>*}

    <tr>
        <td>{l s='Validation of new affiliates' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="validation_of_new_affiliates" value="1" title="{l s='Set this option ON if you want to individually allow participation in the affiliate program to everyone who joined!' mod='refpro'}"
                {if Referral::getSettings('validation_of_new_affiliates')}
                   checked='checked'
            {/if}
        </td>
    </tr>
	<tr>
        <td>{l s='Send basic email-notifications to affiliates' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="email_notification" title="{l s='Set this option OFF to ignore sending email-notifications to affiliates (status validation, customer inviting, reward charging, etc).' mod='refpro'}"
                {if Referral::getSettings('email_notification')}
                   checked='checked'
                {/if}
            />
        </td>
    </tr>

{if !$ip_installed}
    <tr>
        <td>{l s='The minimum limit for withdrawal' mod='refpro'} ({$get_def_currency|escape:'quotes':'UTF-8'})</td>
        <td><input type="text" name="min_balance" value="{Referral::getSettings('min_balance')|escape:'quotes':'UTF-8'}" title="{l s='A positive integer, for example: 100' mod='refpro'}" /></td>
    </tr>
{/if}
    {if Configuration::get('PS_TAX')}
        <tr>
            <td>{l s='Percent calculation method' mod='refpro'}</td>
            <td>
                <select name="with_tax">
                    <option value="0" {if Referral::getSettings('with_tax')}selected="selected"{/if} >
                        {l s='Do not include taxes' mod='refpro'}
                    </option>
                    <option value="1" {if Referral::getSettings('with_tax')}selected="selected"{/if} >
                        {l s='Include taxes' mod='refpro'}
                    </option>
                </select>
            </td>
        </tr>
    {/if}
	<tr>
        <td>{l s='Affiliate program is available only to customers who have N completed orders in the store (0 - no limits)' mod='refpro'}</td>
        <td>
            <input type="text" name='need_completed_orders' value="{Referral::getSettings('need_completed_orders')|intval}" title="{l s='Number of the completed orders after which the affiliate program is available for this customer. Positive integer or zero if no limits.' mod='refpro'}">
        </td>
    </tr>
    <tr>
        <td>{l s='Rewards apply only to the first N orders of each customer (0-unlimited)' mod='refpro'}</td>
        <td>
            <input type="text" name='customers_number' value="{Referral::getSettings('customers_number')|intval}" title="{l s='Number of the first customer orders for which the partner get a bonus. Positive integer or zero for an unlimited number.' mod='refpro'}">
        </td>
    </tr>
    <tr>
        <td>{l s='Do not apply a reward for products with specific price' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="charge_for_product" title="{l s='Set this option ON if you do not want charge bonus for products with specific price!' mod='refpro'}"
            {if Referral::getSettings('charge_for_product')}
               checked='checked'
            {/if}
        </td>
    </tr>
    <tr>
        <td>{l s='Do not apply a reward for orders with voucher discount' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="not_apply_with_voucher_discount" value="1" title="{l s='Set this option ON if you do not want charge bonus for orders with voucher discount!' mod='refpro'}"
                {if Referral::getSettings('not_apply_with_voucher_discount')}
                   checked='checked'
            {/if}
        </td>
    </tr>
	<tr>
        <td>{l s='To provide customers privacy' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="cust_priv" title="{l s='Set this option ON if you do not want to show affiliate details in the partner account.' mod='refpro'}"
            {if Referral::getSettings('cust_priv')}
               checked='checked'
            {/if}
        </td>
    </tr>
    <tr>
        <td>{l s='Show only direct affiliates in the partner account' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="show_only_direct_affiliates" title="{l s='Set this option ON to hide the 2nd and higher level affiliates in the partner account.' mod='refpro'}"
                {if Referral::getSettings('show_only_direct_affiliates')}
                   checked='checked'
                {/if}
            />
        </td>
    </tr>
    <tr>
        <td>{l s='To ignore inviting of existing customers' mod='refpro'}</td>
        <td>
            <input type="checkbox" name="ignore_existing_customers" title="{l s='Set this option ON to ignore inviting to affiliate program of existing customers when using invite-codes/vouchers.' mod='refpro'}"
                {if Referral::getSettings('ignore_existing_customers')}
                   checked='checked'
                {/if}
            />
        </td>
    </tr>
    <tr>
        <td>{l s='To show an affiliate reward in hook on the product page' mod='refpro'}</td>
        <td>
		<select name="show_reward">
{foreach from=$reward_hooks item='reward_hook'}
			<option value="{$reward_hook|escape:'htmlall':'UTF-8'}"{if Referral::getSettings('show_reward')==$reward_hook} selected="selected"{/if}>{if $reward_hook=='null'}--{l s='None' mod='refpro'}--{else}{$reward_hook|escape:'htmlall':'UTF-8'}{/if}</option>
{/foreach}
		</select>
        </td>
    </tr>
    <tr>
        <td>{l s='To display the affiliate program block in the account creating form' mod='refpro'}</td>
        <td>
            <select name="force_on">
			    <option {if Referral::getSettings('force_on') == 'display_block'}selected{/if} value="display_block" title="{l s='The customer consent to participate in the affiliate program is asked when registering!' mod='refpro'}">{l s='Display the block' mod='refpro'}</option>
                <option {if Referral::getSettings('force_on') == 'force_on'}selected{/if} value="force_on" title="{l s='The customer consent to participate in the affiliate program is not asked when registering!' mod='refpro'}">{l s='Hide block and register as partner' mod='refpro'}</option>
                <option {if Referral::getSettings('force_on') == 'hide_block'}selected{/if} value="hide_block" title="{l s='A customer can not become a partner during registration!' mod='refpro'}">{l s='Hide block and do not register as partner' mod='refpro'}</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>{l s='To use invite-code/voucher to attract customers' mod='refpro'}</td>
        <td>
            <select name="has_voucher">
                <option {if !Referral::getSettings('has_voucher')}selected{/if} value="0">--{l s='None' mod='refpro'}--</option>
                <option {if Referral::getSettings('has_voucher') == 'percent'}selected{/if} value="percent">%</option>
                <option {if Referral::getSettings('has_voucher') == 'amount'}selected{/if} value="amount">{$get_def_currency|escape:'quotes':'UTF-8'}</option>
                <option {if Referral::getSettings('has_voucher') == 'free_shipping'}selected{/if} value="free_shipping">{l s='Free shipping' mod='refpro'}</option>
            </select>
        </td>
    </tr>
    <tr class="voucher_percent">
        <td>
            {l s='Voucher percent' mod='refpro'} (%)
        </td>
        <td>
            <input type="text" name="voucher_percent" value="{Referral::getSettings('voucher_percent')|escape:'quotes':'UTF-8'}" title="{l s='A positive real number! Separator is a point!' mod='refpro'}">
        </td>
    </tr>
    <tr class="voucher_amount">
        <td>
            {l s='Voucher amount' mod='refpro'} ({$get_def_currency|escape:'quotes':'UTF-8'})
        </td>
        <td>
            <input type="text" name="voucher_amount" value="{Referral::getSettings('voucher_amount')|escape:'quotes':'UTF-8'}" title="{l s='A positive real number! Separator is a point!' mod='refpro'}">
        </td>
    </tr>
    </tbody>
</table>
<input type="hidden" name="action" value="save_settings" />
<input type="submit" value="{l s='Save' mod='refpro'}" class="bigButton btn btn-default" />
<input type="hidden" name="rate_1" value="{Referral::getSettings('rate_1')|escape:'quotes':'UTF-8'}" />
<input type="hidden" name="rate_2" value="{Referral::getSettings('rate_2')|escape:'quotes':'UTF-8'}" />