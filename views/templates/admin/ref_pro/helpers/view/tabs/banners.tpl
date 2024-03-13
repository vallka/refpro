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

<div class="row">
    <div class="col-lg-12 tools_row">
<div class="panel"><h3 class="refpro_heading"><i class="icon-list-ul"></i> {l s='Banners list' mod='refpro'}
	<span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn showFormLoadImage2" href="#">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new' mod='refpro'}" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
	<div id="slidesContent">
		<div id="slides">
			{foreach from=$banners item=banner key=k}
				<div id="slides_{$k|escape:'htmlall':'UTF-8'}" class="panel">
					<div class="row">
						<div class="col-md-6">
							<img src="{$banner.b|escape:'htmlall':'UTF-8'}" alt="" class="img-thumbnail" />
						</div>
						<div class="col-md-6">
							<h4 class="pull-left">
								{if $banner.url}{$banner.url|escape:'htmlall':'UTF-8'}{else}{l s='Main page' mod='refpro'}{/if}
							</h4>
							<div class="btn-group-action pull-right">
								<a class="btn btn-default"
									href="{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}&delete_banner={$k|escape:'htmlall':'UTF-8'}">
									<i class="icon-trash"></i>
									{l s='Delete' mod='refpro'}
								</a>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
    </div>
</div>
