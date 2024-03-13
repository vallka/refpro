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

<div class="form_load_image modal_1" style="display: none;">
<div class="modal_header"><h3>{l s='Upload banner' mod='refpro'}</h3><a href="#" class="modal_close form_load_modal_close" data-uid="2"><i class="icon-remove fa fa-remove"></i></a></div>
<div class="modal_content">
<form id="form_load_image" action="{$smarty.server.REQUEST_URI|escape:'quotes':'UTF-8'}" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Image' mod='refpro'}</label>
		<div class="col-lg-9">
	        <input form="form_load_image" name="banner_img" type="file"/><br>
		</div>	
	</div>

	<div class="form-group">
			<label class="control-label col-lg-3">{l s='Current banner' mod='refpro'}:</label>
            <img src="{$module_img_dir|escape:'quotes':'UTF-8'}{Referral::getSettings('banner_img')|escape:'quotes':'UTF-8'}" alt="{l s='Current banner' mod='refpro'}" title="{l s='Current banner' mod='refpro'}">
    </div>
	
        <input name="upload_banner" form="form_load_image" class="bigButton btn btn-default" value="{l s='Upload banner' mod='refpro'}" type="submit"/>
</form>
    </div>
</div>

<div class="form_load_image2 modal_1" style="display: none;">
<div class="modal_header"><h3>{l s='Upload banner' mod='refpro'}</h3><a href="#" class="modal_close form_load_modal_close" data-uid="2"><i class="icon-remove fa fa-remove"></i></a></div>
<div class="modal_content">
<form id="form_load_image2" action="{$smarty.server.REQUEST_URI|escape:'quotes':'UTF-8'}" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Image' mod='refpro'}</label>
		<div class="col-lg-9">
	        
	        <input form="form_load_image2" name="banner_img" type="file"/><br>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='URL' mod='refpro'}</label>
		<div class="col-lg-9">
			<input type="text" name="banner_url" id="banner_url" class="">
	<p class="help-block">{l s='Enter a page URL (default home page if left blank)' mod='refpro'}</p>
		</div>
	</div>
        <input name="upload_banner2" form="form_load_image2" class="bigButton btn btn-default" value="{l s='Upload banner' mod='refpro'}" type="submit"/>
</form>
    </div>
</div>
<script type="text/javascript">
    var phrases = [];
	phrases['sure'] = "{l s='Are you sure?' mod='refpro'}";
    phrases['done'] = "{l s='Done!' mod='refpro'}";
    phrases['error'] = "{l s='Error!' mod='refpro'}";
    phrases['set_zero'] = "{l s='Clear' mod='refpro'}";
    phrases['discard'] = "{l s='Cancel' mod='refpro'}";
    phrases['withdraw'] = "{l s='Withdrawal' mod='refpro'}";
    phrases['mail_warning'] = "{l s='Attention! Mailing will be done for all participants of the Affiliate program.' mod='refpro'}";
    phrases['send'] = "{l s='Send' mod='refpro'}";
    phrases['mail_sent'] = "{l s='Mail sent!' mod='refpro'}";
    phrases['mail_wrong_data'] = "{l s='Text is absent!' mod='refpro'}";
    phrases['subject'] = "{l s='Subject:' mod='refpro'}";
    phrases['text'] = "{l s='Text:' mod='refpro'}";
    phrases['mailing'] = "{l s='Mailing lists' mod='refpro'}";
    phrases['add_bonus_alert'] = "{l s='This status is already set for bonus adding!!!' mod='refpro'}";
    phrases['remove_bonus_alert'] = "{l s='This status is already set for bonus removing!!!' mod='refpro'}";
    phrases['new_id'] = "{l s='Please enter sponsor id' mod='refpro'}";
    var module_dir = "{$smarty.const._MODULE_DIR_|escape:'quotes':'UTF-8'}";
    var modal_close = "{l s='Close' mod='refpro'}";
</script>
{if !$ip_installed}
<div class="alert alert-warning warn">
	{l s='You can extend module functionality by' mod='refpro'} <a href="{$internalpurse_link|escape:'quotes':'UTF-8'}" target=new>"{l s='Internal purse module' mod='refpro'}"</a>. {l s='Affiliate rewards may be either withdrawn or spent for shopping at your store.' mod='refpro'}
</div>
{/if}
{if $smarty.const._PS_VERSION_ < 1.6}
<fieldset id="refTab" class="custom_responsive">
    <legend>{l s='Referral programm' mod='refpro'}</legend>
{else}
<div id="refTab" class="panel">
    <div class="panel-heading">{l s='Referral programm' mod='refpro'}</div>
{/if}

    {if !Referral::checkActivationState(Referral::getSettings('activation_code'))}
    {if !empty($activation_attempt)}
        <p class="error alert alert-danger">{l s='You entered a wrong activation code!' mod='refpro'}</p>
    {/if}
    {if !empty($email_sent)}
        <p class="conf alert alert-success">{l s='Your application is accepted. The activation code will be sent to the specified Email soon.' mod='refpro'}</p>
    {/if}

    {if !empty($email_error)}
        <p class="error alert alert-danger">{l s='The Email is invalid!' mod='refpro'}</p>
    {/if}
    <div id="tabs" class="tab_container">
        <ul class="tabs">
            {if empty($email_sent)}
                <li data-tab="tabs-1">{l s='Order an activation code' mod='refpro'}</li>
            {/if}
            <li data-tab="tabs-2">{l s='Input the code' mod='refpro'}</li>
        </ul>
        <div class="tabs_content">
            {if empty($email_sent)}
                <div class="tab" id="tabs-1">
                    <p>{l s='To order an activation code input the Email that You used for buying the module in the field below and click the button "Order".' mod='refpro'}</p>
                    <p>
                    <form method="POST">
                        <input type="text" name="email" value="{if isset($smarty.get.email)}{$smarty.get.email|escape:'quotes':'UTF-8'}{/if}" />
                        <input type="hidden" name="action" value="activation_request" />
                        <br>
                        <input type="submit" class="btn btn-default" value=" {l s='Order' mod='refpro'} " />
                    </form>
                    </p>
                    <div>
                        <div>
                            {l s='For reference and in case of problems use the contacts:' mod='refpro'}
                        </div>
                        {include file="./contacts.tpl"}
                    </div>
                </div>
            {/if}
            <div class="tab" id="tabs-2">
                <p>{l s='To activate the module, please enter the received activation code in the field below and click "Activate":' mod='refpro'}</p>
                <p>
                <form method="POST">
                    <input type="text" name="activation_code" value="" />
                    <input type="hidden" name="action" value="activate" />
                    <br>
                    <input type="submit" class="btn btn-default" value=" {l s='Activate' mod='refpro'}" />
                </form>
                </p>
            </div>
        </div>
    </div>
    {else}
    <div id="tabs" class="tab_container">
        <ul class="tabs">
            <li data-tab="general_information">{l s='General information' mod='refpro'}</li>
            <li data-tab="settings">{l s='Settings' mod='refpro'}</li>
            <li data-tab="rewards">{l s='Rewards' mod='refpro'}</li>
            <li data-tab="customers">{l s='Affiliate list' mod='refpro'}</li>
			{if !$ip_installed && $total_his>0}<li data-tab="history">{l s='Payouts history' mod='refpro'}</li>{/if}
            <li data-tab="banners">{l s='Banners' mod='refpro'}</li>
        </ul>
        <div class="tabs_content">
            <div class="tab" id="general_information">
                {include file="./tabs/general_information.tpl"}
            </div>
            <form method="post" id="setForm">
                <div class="tab" id="settings">
                    {include file="./tabs/settings.tpl"}
                </div>
                <div class="tab" id="customers">
                    {include file="./tabs/customers.tpl"}
                </div>
				<div class="tab" id="history">
                    {include file="./tabs/history.tpl"}
                </div>
                <div class="tab" id="rewards">
                    {include file="./tabs/rewards.tpl"}
                </div>
                <div class="tab" id="banners">
                    {include file="./tabs/banners.tpl"}
                </div>
            </form>
        </div>
    </div>
    <div id="ref_footer">
        <a href="{$link_resource|escape:'quotes':'UTF-8'}" class="logo" target="_blank">
            <img src="{$module_img_dir|escape:'quotes':'UTF-8'}logo_admin.png" alt="{l s='The module is developed by order of UnderKey.Ru' mod='refpro'}" title="{l s='The module is developed by order of UnderKey.Ru' mod='refpro'}" />
        </a>
        <div class="in">
            <div class="row">
                <div class="label">{l s='Module:' mod='refpro'}</div>
                <div class="def">{l s='The affiliate program module RefPro' mod='refpro'}</div>
            </div>
            <div class="row">
                <div class="label">{l s='Version:' mod='refpro'}</div>
                <div class="def">{$version|escape:'quotes':'UTF-8'}</div>
            </div>
            <div class="row">
                <div class="label">{l s='Developers:' mod='refpro'}</div>
                <div class="def">DaRiuS</div>
            </div>
            <div class="row">
                <div class="label">{l s='Documentation:' mod='refpro'}</div>
                <div class="def"><a href="../modules/refpro/readme.pdf" target="_blank" style="vertical-align: top;">Readme.pdf</a><a href="../modules/refpro/readme.pdf" target="_blank"><img src="{$module_img_dir|escape:'quotes':'UTF-8'}pdf.png"/></a><a href="https://youtu.be/n3SouJVEhgk" target="_blank"><img src="{$module_img_dir|escape:'quotes':'UTF-8'}youtube.png"/></a>
                </div>
            </div>
        </div>
        {/if}
    </div>

{if $smarty.const._PS_VERSION_ < 1.6}
</fieldset>
{else}
</div>
{/if}
<script>
    var _DATATABLE_SEARCH_ = "{$_DATATABLE_SEARCH_}";
    var _DATATABLE_sEmptyTable_ = "{$_DATATABLE_sEmptyTable_}";
    var _DATATABLE_sInfo_ = "{$_DATATABLE_sInfo_}";
    var _DATATABLE_sInfoEmpty_ = "{$_DATATABLE_sInfoEmpty_}";
    var _DATATABLE_sInfoFiltered_ = "{$_DATATABLE_sInfo_}";
    var _DATATABLE_sLengthMenu_ = "{$_DATATABLE_sLengthMenu_}";
    var _DATATABLE_sZeroRecords_ = "{$_DATATABLE_sZeroRecords_}";
    var _DATATABLE_ALL_ = "{$_DATATABLE_ALL_}";
</script>
<script src="{$_DATATABLE_JS_URI_}"></script>
<script>
var historyTable = $('.historyRefTableSortable').DataTable(
    {
    "pagingType": "full_numbers",
    "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, _DATATABLE_ALL_]],
    "columnDefs": [{
        "targets": 0,
        "orderable": false
    }  ],
    "order": [[ 4, "desc" ]]
});

var $GITable = $('.GIRefTableSortable'),
GITablethLength = $GITable.find('th').length;

if(GITablethLength > 5){
        $GITable.DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, _DATATABLE_ALL_]],
        "columnDefs": [ {
            "targets": 0,
            "orderable": false
        },{
            "targets": 5,
            "orderable": false
        }],
        "order": [[3, "desc" ]]
    });
}else{
    $GITable.DataTable(
        {
        "pagingType": "full_numbers",
        "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, _DATATABLE_ALL_]],
        "columnDefs": [ {
            "targets": 0,
            "orderable": false
        }],
        "order": [[3, "desc" ]]
    });
}


$('.GIRefTableSortable').on('page.dt order.dt search.dt', function(){
    setTimeout(()=>{
        transferBinder();
    }, 0)
});

var allCustomersTable = $('.allCustomersRefTableSortable').DataTable(
    {
    "pagingType": "full_numbers",
    "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, _DATATABLE_ALL_]],
    "columnDefs": [ {
        "targets": 6,
        "orderable": false
    }]
});

//addControls(historyTable);
//addControls(allCustomersTable);

function addControls(table){
    table.columns( '.select-filter' ).every( function () {
        var that = this;
        var select = $('<select />')
            .appendTo(
                this.footer()
            )
            .on( 'change', function () {
                that
                    .search( $(this).val() )
                    .draw();
            } );
    
        select.append( $('<option value="">Фильтр пуст</option>') );
        this
            .cache( 'search' )
            .sort()
            .unique()
            .each( function ( d ) {
                select.append( $('<option value="'+d+'">'+d+'</option>') );
            } );
    } );
}
</script>