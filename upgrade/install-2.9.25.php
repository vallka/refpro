<?php

/**
 * 2007-2019 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2012-2019 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

function upgrade_module_2_9_25($module)
{
    $groups = Group::getGroups(
        Context::getContext()->language->id
    );

    $group_rates = new stdClass();
    foreach ($groups as $group) {
        foreach (range(0, 9) as $level) {
            if ($level != 0) {
                $percent = Referral::getBonusRateOld($group['id_group'], $level);
            } else {
                $percent = 0;
            }
            $group_rates->{$group['id_group']}[$level - 1] = $percent;
        }
    }

    Referral::setSettings('group_rates', $group_rates, true);
    return true;
}
