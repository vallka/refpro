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

<h2>{l s='Settings of the rewards percents:' mod='refpro'}</h2>
<p>{l s='Set the percentage of partner bonuses for partners from different customer groups. If unchecked, the customers from the current group can not participate in the affiliate program.' mod='refpro'}</p>
<table class="table rates">
    <thead>
        <th>{l s='Group' mod='refpro'}</th>
		{if Referral::getSettings('zero_level_rewards')}
            <th>{l s='Zero level reward, %' mod='refpro'}</th>
        {/if}																							   
		{if $levels > 0}				
        <th>{l s='1-st level reward, %' mod='refpro'}</th>
		{/if}	 
        {if $levels > 1}
            <th>{l s='2-nd level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 2}
            <th>{l s='3-rd level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 3}
            <th>{l s='4-th level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 4}
            <th>{l s='5-th level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 5}
            <th>{l s='6-th level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 6}
            <th>{l s='7-th level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 7}
            <th>{l s='8-th level reward, %' mod='refpro'}</th>
        {/if}
        {if $levels > 8}
            <th>{l s='9-th level reward, %' mod='refpro'}</th>
        {/if}
        <th><input type="checkbox" id="checkall1"/> {l s='Is available' mod='refpro'}</th>
    </thead>
    <tbody>
    {foreach from=$groups key=key item=value}
        {assign var='group_id' value=$value.id_group}
        <tr>
            <td>{$value.name|escape:'quotes':'UTF-8'}</td>
			{if Referral::getSettings('zero_level_rewards')}
                <td><input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_0"
                       value="{Referral::getBonusRate($group_id, '0')|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {else}
                <input type="hidden" name="percent_{$group_id|escape:'quotes':'UTF-8'}_0"
                       value="{Referral::getBonusRate($group_id, '0')|escape:'quotes':'UTF-8'}"/>
            {/if}
            {if $levels > 0}				
                <td>
				    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_1"
                       value="{Referral::getBonusRate($group_id, 1)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
			{/if}
            {if $levels > 1}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_2"
                           value="{Referral::getBonusRate($group_id, 2)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}

            {if $levels > 2}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_3"
                           value="{Referral::getBonusRate($group_id, 3)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}

            {if $levels > 3}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_4"
                           value="{Referral::getBonusRate($group_id, 4)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}

            {if $levels > 4}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_5"
                           value="{Referral::getBonusRate($group_id, 5)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}
            {if $levels > 5}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_6"
                           value="{Referral::getBonusRate($group_id, 6)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}
            {if $levels > 6}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_7"
                           value="{Referral::getBonusRate($group_id, 7)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}
            {if $levels > 7}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_8"
                           value="{Referral::getBonusRate($group_id, 8)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}
            {if $levels > 8}
                <td>
                    <input type="text" name="percent_{$group_id|escape:'quotes':'UTF-8'}_9"
                           value="{Referral::getBonusRate($group_id, 9)|escape:'quotes':'UTF-8'}" title="{l s='A positive integer!' mod='refpro'}" /></td>
            {/if}

            <td>
                <input type="checkbox" value='{$group_id|escape:'quotes':'UTF-8'}' name='available_ids[]' class="group_ids" title="{l s='Set this option if affiliate program available for this group!' mod='refpro'}" {if in_array($group_id, $aAvailableGroups)}checked="checked"{/if} {if in_array($group_id, array(1,2))}disabled="disabled"{/if}>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h2>{l s='Availability of affiliate program for products categories:' mod='refpro'}</h2>
<p>{l s='Management of reward factor of categories and their availability for the affiliate program.If unchecked, the affiliate bonus for products from this category is not charged.With help of reward factor it is possible to increase or decrease a reward for category products over the standard percentage.' mod='refpro'}</p>
<div class="catTreeControls">
    <div>
        <span class="ct_button" id="collapseAll">{l s='Collapse all' mod='refpro'}</span>
    </div>
    <div>
        <span class="ct_button" id="expandAll">{l s='Expand all' mod='refpro'}</span>
    </div>
    <div class="factor_control">
        {l s='Factor' mod='refpro'}
    </div>
    <div class="checkall2_control">
        <input type="checkbox" id="checkall2"/> {l s='Is available' mod='refpro'}
    </div>
</div>
<div id="treeJsJsox">
{foreach from=$aCategories item=category}
<div class="jpl-{$category.level_depth} category_tree_js_parent" data-cat_id="{$category.id_category}" data-id_parent="{$category.id_parent}" data-level_depth="{$category.level_depth}">
    <div class="category_tree_js">
    <span class="expander">-</span>
    <input type="checkbox" class="categoryCheckbox"
                       value="1"
                        {if isset($aResult[$category['id_category']]) && $aResult[$category['id_category']]['availible'] == 1}
                            checked
                        {/if}
                       name="category_availible_{$category.id_category|escape:'quotes':'UTF-8'}"
                       title="{l s='Set this option if the affiliate program available for the products from this category!' mod='refpro'}"
                       data-id-category="{$category.id_category|escape:'quotes':'UTF-8'}" />
    {$category.name|escape:'quotes':'UTF-8'}
    <input type="text" class="category_percent"
                       value='{if isset($aResult[$category.id_category])}{$aResult[$category.id_category|floatval].per|escape:'quotes':'UTF-8'}{else}1{/if}' name='category_percent_{$category.id_category|escape:'quotes':'UTF-8'}' title="{l s='A positive real from 0 to 100! Separator is a point!' mod='refpro'}">
</div>
</div>
{/foreach} 
</div>

<input type="hidden" name="action" value="save_settings" />
<input style="margin-top:20px;" type="submit" value="{l s='Save' mod='refpro'}" class="bigButton btn btn-default" />
<input type="hidden" name="rate_1" value="{Referral::getSettings('rate_1')|escape:'quotes':'UTF-8'}" />
<input type="hidden" name="rate_2" value="{Referral::getSettings('rate_2')|escape:'quotes':'UTF-8'}" />