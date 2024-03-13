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

{if $total_refs>0}
<table class="table allCustomersRefTableSortable" id="refTable">
        <tfoot style="display: table-header-group;">
            <th class="name"></th>
			<th class="name"></th>
            <th class="name"></th>
			<th class="name"></th>
			<th class="name"></th>
			<th class="name"></th>
			<th class="name"></th>
        </tfoot>
        <thead>
            <th class="name">ID</th>
			<th class="name select-filter">{l s='Partner Name' mod='refpro'}</th>
            <th class="name select-filter">{l s='Email' mod='refpro'}</th>
			<th class="name select-filter">{l s='Sponsor ID' mod='refpro'}</th>
			<th class="name select-filter">{l s='Total earned' mod='refpro'}</th>
			<th class="name select-filter">{l s='Registration' mod='refpro'}</th>
			<th class="name">{l s='Actions' mod='refpro'}</th>
        </thead>
        <tbody>
        {foreach $all_customers item=ref_user name=refs}
            <tr> 
				<td class="name ref_id" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{$ref_user['id']|escape:'quotes':'UTF-8'}</td>
                <td class="name" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{$ref_user['firstname']|escape:'quotes':'UTF-8'} {$ref_user['lastname']|escape:'quotes':'UTF-8'}</td>
                <td class="name" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{$ref_user['email']|escape:'quotes':'UTF-8'}</td>
                <td class="name ref_sponsor" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{$ref_user['sponsor']|escape:'quotes':'UTF-8'}</td>
				<td class="name" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{Referral::formatMoney($ref_user['total'])|escape:'quotes':'UTF-8'}</td>
				<td class="name" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>{$ref_user['td']|escape:'quotes':'UTF-8'}</td>
				<td class="name" {if !$ref_user['active_ref']}style="background-color: #ddd"{/if}>
					<div class="btn-group-action">
						<div class="btn-group">

							<a class="btn tooltip-link ref_action_show_structure" href="{$link->getAdminLink('AdminRefPro')}&show_structure&id_customer={$ref_user['id']}" title="{l s='Show structure' mod='refpro'}">
								{if $is17}<i class="material-icons">people</i>{else}<i class="icon-group"></i>{/if}
							</a>
							<a href="#ref_action_edit" class="btn tooltip-link ref_action_edit" title="{l s='Edit' mod='refpro'}">
								{if $is17}<i class="material-icons">mode_edit</i>{else}<i class="icon-pencil"></i>{/if}
							</a>
							<a class="btn tooltip-link ref_action_disable" href="#ref_action_disable" title="{l s='Block/Unblock' mod='refpro'}">
								{if $is17}<i class="material-icons">{if $ref_user['active_ref']}pause{else}play_arrow{/if}</i>{else}<i class="icon-{if $ref_user['active_ref']}pause{else}play{/if}"></i>{/if}
							</a>
							<a class="btn tooltip-link ref_action_delete" href="#ref_action_delete" title="{l s='Delete' mod='refpro'}">
								{if $is17}<i class="material-icons">delete</i>{else}<i class="icon-trash"></i>{/if}
							</a>
						
						</div>
					</div>
            </tr>
        {/foreach}
		
		</tbody>
</table>
<div class="row">&nbsp;</div>
{if $total_refs>0}
<p><span style="background-color: #DDDDDD;">{l s='Grey' mod='refpro'}</span> {l s='is for the blocked partners' mod='refpro'}</p>
{/if}
{/if}

<div class="row">
    <div class="col-lg-12 tools_row">
        <input type="button" id="involvedEveryoneButton" value="{l s='Make partners from all customers' mod='refpro'}" class="btn btn-default">
        <a href="#everyone_involved" id='everyone_involved' class="ajaxLink" title="{l s='All previously registered customers become partners!' mod='refpro'}" style="display: none;">
            {l s='Make partners from all customers' mod='refpro'}
        </a>
    </div>
</div>