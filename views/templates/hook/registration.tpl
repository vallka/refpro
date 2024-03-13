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

<!-- MODULE RefPro -->
<script type="text/javascript">
var refpro_msg = "{l s='Please read the Terms of Service and confirm your agreement.' mod='refpro'}";
{if $is17}
	{literal}
	document.addEventListener('DOMContentLoaded', function(){ 
{/literal}
{else}
{literal}
$(function() {
{/literal}
{/if}
{literal}
		$.fancybox.defaults = {
			tpl: ''
		};
		$("#ref_agree").each(function(e){
			var href = $(this).attr("href");
			if(href.indexOf('?') != -1){
				href = href + "&content_only=1";
			} else {
				href = href + "?content_only=1";
			}
			$(this).attr("href", href);
		});
		$('a.iframe').fancybox();
		$("#account-creation_form").submit(function(){
			checkRef();
		});

//		var check_ref = false;
//		var span_wrapp = $('<span class="wrapp_submit_button"></span');
//		span_wrapp.append($('#submitAccount').clone(true));
//		$('#submitAccount').replaceWith(span_wrapp);
//		$('.wrapp_submit_button').hover(function () {
//			if (check_ref)
//				return false;
//			check_ref = true;
//			if (checkRef(true))
//				$('#submitAccount').removeAttr('disabled');
//			else
//				$('#submitAccount').attr('disabled', 'true');
//		}, function () {
//			check_ref = false;
//		});

		function checkRef(returnResult)
		{
			if($("input[name='goref']").attr("checked") && !($("input[name='ref_agree']").attr("checked") || $("#ref_agree_alt")[0])){
				alert(refpro_msg);
				return false;
			}

			if (typeof returnResult != 'undefined')
				return true;
		}
	});

	{/literal}
</script>
<style>
    {literal}
    div.checker
    {
        float: left;
    }
    #center_column.grid_5 .ref_cb { margin-left: 20px; }
    {/literal}
</style>
<fieldset class="account_creation">
	<h3>{l s='Affiliate program' mod='refpro'}</h3>
	{if isset($smarty.post.ref_url) && $smarty.post.ref_url}
	<p>
		<label for="ref_url" id="ref_url">{l s='You are invited:' mod='refpro'}</label>
		<span class="ref_url">{$ref_name|escape:'htmlall':'UTF-8'}</span>
		<input type="hidden" name="ref_url" value="{$smarty.post.ref_url|escape:'htmlall':'UTF-8'}" />
	</p>
	{/if}
	{if $is17}
    <div class="col-md-12">
          <span class="custom-checkbox">
            <input type="checkbox"  value="on" name="goref"  >
            <span><i class="material-icons rtl-no-flip checkbox-checked">&#xE5CA;</i></span>
            <label for="goref">{l s='Participate in the affiliate program' mod='refpro'}</label >
          </span>
    </div>
	{else}
	<p>
		<input type="checkbox" value="on" name="goref" class="ref_cb"  />
		<label for="goref" class="ref_lbl">{l s='Participate in the affiliate program' mod='refpro'}</label>
	</p>
	{/if}
	{if isset($smarty.post.rp_rules) && $smarty.post.rp_rules}
	{if $is17}
	    <div class="col-md-12">
	          <span class="custom-checkbox">
	            <input type="checkbox"  name="ref_agree"  >
	            <span><i class="material-icons rtl-no-flip checkbox-checked">&#xE5CA;</i></span>
	            <label for="ref_agree">{l s='I have read and agree to the' mod='refpro'} <a href="{$smarty.post.rp_rules|escape:'htmlall':'UTF-8'}" id="ref_agree" class="iframe">"{l s='Terms of Service' mod='refpro'}"</a>.</label >
	          </span>
	    </div>
	{else}
		<p>
			<input type="checkbox" name="ref_agree" class="ref_cb" />
			<label for="ref_agree" class="ref_lbl">
				{l s='I have read and agree to the' mod='refpro'} <a href="{$smarty.post.rp_rules|escape:'htmlall':'UTF-8'}" id="ref_agree" class="iframe">"{l s='Terms of Service' mod='refpro'}"</a>.</label>
		</p>
	{/if}
	{else}
		<input type="hidden" name="ref_agree" value="checked" id="ref_agree_alt" />
	{/if}
</fieldset>
<!-- EOF RefPro -->