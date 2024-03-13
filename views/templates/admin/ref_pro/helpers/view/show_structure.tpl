<div class="panel">

    {if version_compare(_PS_VERSION_, '1.6', '>=')}
        <div class="panel-heading">{l s='Affiliate structure' mod='refpro'}</div>
    {else}
        <h2>{l s='Affiliate structure' mod='refpro'}</h2>
    {/if}

    <div>

        <div>
            {if $sponsorId}
                <a href="{$link->getAdminLink('AdminRefPro')}&show_structure&id_customer={$sponsorId}">{$structure[$sponsorId]['name']}</a> ({l s='sponsor' mod='refpro'}, ID {$sponsorId})
            {else}
               ({l s='no sponsor' mod='refpro'})
            {/if}
        </div>

        {function name=refProStructure}
            <ul {if $top}class="refpro-tree"{/if}>
                {foreach $items as $itemId => $item}
                    <li>

                        {if !$item['affiliates']}
                            <span class="refpro-tree-label {if $top}refpro-tree-current-node{/if}">
                        {else}
                            <input type="checkbox" checked="checked" id="refpro-tree-node-{$itemId}">
                            <label class="refpro-tree-label {if $top}refpro-tree-current-node{/if}" for="refpro-tree-node-{$itemId}">
                        {/if}

                                {if (!isset($item['is_sponsor']) || $item['is_sponsor']) || isset($item.val_is_sponsor) && $item.val_is_sponsor}
                                <a href="{$link->getAdminLink('AdminRefPro')}&show_structure&id_customer={$itemId}" class="{if !$item['active']}refpro-customer-inactive{/if}">
                                {/if}
                                    {$item['name']}
                                {if (!isset($item['is_sponsor']) || $item['is_sponsor']) || isset($item.val_is_sponsor) && $item.val_is_sponsor}
                                </a> 
                                {/if}
								(ID {$itemId})

                        {if $item['affiliates']}
                            </label>
                        {else}
                            </span>
                        {/if}

                        {call name=refProStructure items=$item['affiliates'] top=false}

                    </li>
                {/foreach}
            </ul>
        {/function}

        {call name=refProStructure items=$structure[$sponsorId]['affiliates'] top=true}

    </div>

</div>

