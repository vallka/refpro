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
* @author    Goryachev Dmitry    <dariusakafest@gmail.com>
* @copyright 2007-2017 Goryachev Dmitry
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="pagination clearfix">
    {if $start!=$stop}
        <ul class="pagination">
            {if $p != 1}
                {assign var='p_previous' value=$p-1}
                <li class="pagination_previous">
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$p_previous|intval}#{$hash}">
                        <i class="icon-chevron-left"></i>
                    </a>
                </li>
            {else}
                <li class="disabled pagination_previous">
						<span>
							<i class="icon-chevron-left"></i>
						</span>
                </li>
            {/if}
            {if $start==3}
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}=1#{$hash}">
                        <span>1</span>
                    </a>
                </li>
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}=2#{$hash}">
                        <span>2</span>
                    </a>
                </li>
            {/if}
            {if $start==2}
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}=1#{$hash}">
                        <span>1</span>
                    </a>
                </li>
            {/if}
            {if $start>3}
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}=1#{$hash}">
                        <span>1</span>
                    </a>
                </li>
                <li class="truncate">
						<span>
							<span>...</span>
						</span>
                </li>
            {/if}
            {section name=pagination start=$start loop=$stop+1 step=1}
                {if $p == $smarty.section.pagination.index}
                    <li class="active current">
							<span>
								<span>{$p|escape:'html':'UTF-8'}</span>
							</span>
                    </li>
                {else}
                    <li>
                        <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$smarty.section.pagination.index|intval}#{$hash}">
                            <span>{$smarty.section.pagination.index|escape:'html':'UTF-8'}</span>
                        </a>
                    </li>
                {/if}
            {/section}
            {if $pages_nb>$stop+2}
                <li class="truncate">
						<span>
							<span>...</span>
						</span>
                </li>
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$pages_nb|intval}#{$hash}">
                        <span>{$pages_nb|intval}</span>
                    </a>
                </li>
            {/if}
            {if $pages_nb==$stop+1}
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$pages_nb|intval}#{$hash}">
                        <span>{$pages_nb|intval}</span>
                    </a>
                </li>
            {/if}
            {if $pages_nb==$stop+2}
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={($pages_nb - 1)|intval}#{$hash}">
                        <span>{$pages_nb-1|intval}</span>
                    </a>
                </li>
                <li>
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$pages_nb|intval}#{$hash}">
                        <span>{$pages_nb|intval}</span>
                    </a>
                </li>
            {/if}
            {if $pages_nb > 1 AND $p != $pages_nb}
                {assign var='p_next' value=$p+1}
                <li class="pagination_next">
                    <a href="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'htmlall':'UTF-8'}&amp;{$param}={$p_next|intval}#{$hash}">
                        <i class="icon-chevron-right"></i>
                    </a>
                </li>
            {else}
                <li class="disabled pagination_next">
						<span>
							<i class="icon-chevron-right"></i>
						</span>
                </li>
            {/if}
        </ul>
    {/if}
</div>
