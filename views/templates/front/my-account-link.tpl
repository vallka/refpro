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

<!-- MODULE Refpro -->
<li><a href="{$link->getModuleLink('refpro', 'myaccount')|escape:'quotes':'UTF-8'}"
	title="{l s='Affiliate program' mod='refpro'}"><img
	src="{$module_template_dir|escape:'htmlall':'UTF-8'}views/img/refpro.gif"
	alt="{l s='Affiliate program' mod='refpro'}" class="icon" /></a><a
	href="{$link->getModuleLink('refpro', 'myaccount')|escape:'quotes':'UTF-8'}"
	title="{l s='Affiliate program' mod='refpro'}">{l s='Affiliate program' mod='refpro'}</a></li>
{if !isset($hide) || $hide !='1'}
<li>
	<a href="#" class="reflinks">{l s='Get an affiliate link' mod='refpro'}</a>
</li>
{/if}
