/**
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
 * @author    PrestaShop SA    <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

function TabContainer(tab_container_selector)
{
    var _this = this;
    this.tab = $(tab_container_selector);
    this.init = function () {
        _this.tab.find('ul.tabs > li').on('click', function () {
            _this.tab.find('.tabs > li').removeClass('active');
            $(this).addClass('active');
            _this.tab.find('.tabs_content div.tab').hide();
            _this.tab.find('[id="'+$(this).data('tab')+'"]').show();
        });
        _this.tab.find('.tabs_content div.tab').hide();
        _this.tab.find('.tabs_content > div:first').show();
        _this.tab.find('ul > li:first').addClass('active');
	if (window.location.hash != "") {
        	_this.set(window.location.hash.substring(1));
            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 0);
	}
    };
	this.set = function(id)
    {
        _this.tab.find('.tabs > li').removeClass('active');
        $('[data-tab="'+id+'"]').addClass('active');
        _this.tab.find('.tabs_content div.tab').hide();
        _this.tab.find('[id="'+id+'"]').show();
    };
}

$(function () {
    window.tab_container = new TabContainer('.tab_container');
    window.tab_container.init();
});