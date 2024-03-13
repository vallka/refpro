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
{if isset($enable_fancybox) && $enable_fancybox}
<script>
	if (typeof $.fn.fancybox != 'undefined' && typeof $.fancybox.version == 'undefined')
		$.fancybox.defaults = {
			tpl: ''
		};
</script>
{/if}
<script>
    var module_dir = "{$module_dir|escape:'htmlall':'UTF-8'}";
    var FancyboxI18nClose = "";
    var FancyboxI18nNext = "";
    var FancyboxI18nPrev = "";
	var modal_close = "{l s='Close' mod='refpro'}";
	var current_url = "{if $is17}{$urls.current_url|escape:'html':'UTF-8'}{else}{$current_url|escape:'html':'UTF-8'}{/if}";
</script>
<script type="text/javascript">
	{literal}$(function(){{/literal}
		{if isset($is_sponsor) && $is_sponsor}
		{literal}$(".reflinks").each(function(){
			if(!$(this).text()){
				$(this).text("{/literal}{l s='Get an affiliate link' mod='refpro'}{literal}");
				$(this).attr('href', '#');
			}
		});

		$(".reflinks").click(function(e){
			e.preventDefault();
			if(current_url.indexOf('?') != -1){
				var _char = '&';
			} else {
				var _char = '?';
			}
			{/literal}
			var secureUrl = current_url + _char + 'ref={$email|md5}';
			{literal}
			var secureUrlEscaped = encodeURIComponent(secureUrl);
			showDialog(
				'<div class="reflinks_text">' +
					{/literal}
					'<div class="refpro-svg">' +
						'<a href="' + secureUrl + '" target="_blank"><img src="{$qrCodeInlineUrl}&url=' + secureUrlEscaped + '" /></a>' +
						'<div>' +
							'<a href="{$qrCodeDownloadUrl}&url=' + secureUrlEscaped + '">' + "{l s='Download QR code' mod='refpro'}" + '</a>' +
						'</div>' +
					'</div>' +
					{literal}
					current_url + _char + 'ref={/literal}{$email|escape:'htmlall'}<br /><br />{l s='or' mod='refpro'}<br /><br /> ' +
					current_url + _char + 'ref={$id|intval}' + '<br /><br />{l s='or' mod='refpro'}<br /><br /> ' +
					secureUrl + '<br /><hr />' +
					'<b>Social sharing:</b> ' +
					'<a href="http://twitter.com/share?text=' + document.title + ':&url=' + current_url + _char + 'ref={$id|intval}' + '" target=new>Twitter</a> | ' +
					'<a href="http://www.facebook.com/sharer.php?u=' + current_url + _char + 'ref={$id|intval}' + '" target=new>Facebook</a> | ' +
					'<a href="https://plus.google.com/share?url=' + current_url + _char + 'ref={$id|intval}' + '" target=new>Google+</a> | ' +
					'<a href="http://www.linkedin.com/shareArticle?mini=true&url=' + current_url + _char + 'ref={$id|intval}' + '&title=' + document.title + '" target=new>LinkedIn</a> | ' +
					'<a href="http://vkontakte.ru/share.php?url=' + current_url + _char + 'ref={$id|intval}' + '" target=new>VKontakte</a>' +
				'</div>',
				"{l s='Affiliate links to this page' mod='refpro'}"
			);
		});
		{else}
		$(".reflinks").remove();
		{/if}
	});

</script>