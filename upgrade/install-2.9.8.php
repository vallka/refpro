<?php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2012-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) exit;

function upgrade_module_2_9_8()
{
    $list_fields = Db::getInstance()->executeS(
        'SHOW FIELDS FROM `'._DB_PREFIX_.'refpro_customer`'
    );

    if (is_array($list_fields)) {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('has_voucher', $list_fields)) {
            Db::getInstance()->execute(
                'ALTER TABLE  `'._DB_PREFIX_.'refpro_customer`
                ADD  `has_voucher` varchar(50) DEFAULT "0"'
            );
        }
        if (!in_array('voucher_percent', $list_fields)) {
            Db::getInstance()->execute(
                'ALTER TABLE  `'._DB_PREFIX_.'refpro_customer`
                ADD  `voucher_percent` float DEFAULT NULL'
            );
        }
        if (!in_array('voucher_amount', $list_fields)) {
            Db::getInstance()->execute(
                'ALTER TABLE  `'._DB_PREFIX_.'refpro_customer`
                ADD  `voucher_amount` float DEFAULT NULL'
            );
        }
    }

    return true;
}
