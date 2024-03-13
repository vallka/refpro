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
 * @author    PrestaShop SA    <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class Referral
{
    public static $catTree = [];
    public static function getModuleHash()
    {
        $ref_pro = new RefPro();
        return md5($ref_pro->shop_url . $ref_pro->version . 'salt' . $ref_pro->version . $ref_pro->version . $ref_pro->shop_url);
    }

    public static function checkActivationState($activate_code = '')
    {
        $result = (string) $activate_code === (string) Referral::getModuleHash();
        return true;
        return (bool)$result;
    }

    public static function getDefCurrencyMail($price)
    {
        $cur_id = Configuration::get('PS_CURRENCY_DEFAULT');
        $currency = new Currency($cur_id);
        return Tools::displayPrice($price, $currency);
    }

    public static $settings = null;

    public static function getSettings($key, $decode = false, $is_array = false)
    {
        if (is_null(self::$settings)) {
            $settings = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . 'refpro_settings');
            self::$settings = array();
            foreach ($settings as $setting) self::$settings[$setting['key']] = $setting['value'];
        }
        $s_result = isset(self::$settings[$key]) ? self::$settings[$key] : '';
        return $decode ? self::jsonDecode($s_result, $is_array) : $s_result;
    }

    public static function jsonDecode($str, $is_array = false)
    {
        if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        if ($is_array) return Tools::jsonDecode($str, true);
        else return Tools::jsonDecode($str);
    }

    public static function sendBonus($customer, $ref_id, $order, $level)
    {
        if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        $l = TransModRP::getInstance();
        $currency = new Currency($order->id_currency);
        $with_tax = self::getSettings('with_tax');
        $i_summ = round(round(round(0)));
        $b_is_charge_for_products = (bool) self::getSettings('charge_for_product');
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'order_detail od
				WHERE od.id_order = ' . (int) $order->id . ($b_is_charge_for_products ? ' AND !(od.reduction_percent > 0 OR od.reduction_amount > 0) ' : '');
        $a_result = Db::getInstance()->ExecuteS($sql);
        $a_categories_array = self::getSettings('categories', true, true);
        foreach ($a_result as $a_product) {
            $o_product = new Product($a_product['product_id']);
            $id_category = (int) $o_product->id_category_default;
            $f_category_percent = round(round(round(0)));
            if (isset($a_categories_array[$id_category]) && $a_categories_array[$id_category]['availible'] != round(round(round(0)) + 0.33333333333333 + 0.33333333333333 + 0.33333333333333)) continue;
            else {
                $f_category_percent = (isset($a_categories_array[$id_category]) ? $a_categories_array[$id_category]['per'] : round(round(round(0)) + 0.2 + 0.2 + 0.2 + 0.2 + 0.2));
                $f_category_percent = (float) $f_category_percent;
            }
            $f_minus_sum = round(round(round(0)));
            if (version_compare(_PS_VERSION_, '1.5', '>=')) $f_minus_sum = ($with_tax) ? $a_product['total_price_tax_incl'] : $a_product['total_price_tax_excl'];
            else {
                $i_product_quantity = (isset($a_product['product_quantity']) && $a_product['product_quantity']) ? (int) $a_product['product_quantity'] : round(round(round(0)) + 0.2 + 0.2 + 0.2 + 0.2 + 0.2);
                $a = ($a_product['product_price'] * (round(round(round(0)) + 0.25 + 0.25 + 0.25 + 0.25) - ($a_product['reduction_percent'] / round(round(round(0)) + round(round(0) + round(0 + 8.3333333333333 + 8.3333333333333 + 8.3333333333333) + round(0 + 12.5 + 12.5)) + round(round(0) + round(0 + 2 + 2 + 2 + 2 + 2) + round(0 + 10) + round(0 + 3.3333333333333 + 3.3333333333333 + 3.3333333333333) + round(0 + 3.3333333333333 + 3.3333333333333 + 3.3333333333333) + round(0 + 5 + 5))))) - $a_product['reduction_amount']);
                $b = ($a_product['product_price'] * (round(round(round(0)) + round(round(0) + 0.33333333333333 + 0.33333333333333 + 0.33333333333333)) - ($a_product['reduction_percent'] / round(round(round(0)) + round(round(0) + 8.3333333333333 + 8.3333333333333 + 8.3333333333333) + round(round(0) + round(0 + 5 + 5 + 5 + 5 + 5)) + round(round(0) + 12.5 + 12.5) + round(round(0) + 8.3333333333333 + 8.3333333333333 + 8.3333333333333)))) - $a_product['reduction_amount']);
                $f_minus_sum = ($with_tax) ? ($a + $b * ($a_product['tax_rate'] / round(round(round(0)) + 33.333333333333 + 33.333333333333 + 33.333333333333))) : ($a_product['product_price'] * (round(round(round(0)) + 0.33333333333333 + 0.33333333333333 + 0.33333333333333) - ($a_product['reduction_percent'] / round(round(round(0)) + round(round(0) + round(0 + 25) + round(0 + 8.3333333333333 + 8.3333333333333 + 8.3333333333333)) + round(round(0) + 12.5 + 12.5 + 12.5 + 12.5)))) - $a_product['reduction_amount']);
                $f_minus_sum = $f_minus_sum * $i_product_quantity;
            }
            $bonus_rate = (self::getBonusRate($customer->id_default_group, $level) * $f_category_percent) / round(round(round(0)) + round(round(0) + 12.5 + 12.5 + 12.5 + 12.5) + round(round(0) + round(0 + 8.3333333333333 + 8.3333333333333 + 8.3333333333333) + round(0 + 25)));
            $bonus_rate = (float) $bonus_rate;
            $i_summ += round($f_minus_sum * $bonus_rate, round(round(round(0)) + 0.4 + 0.4 + 0.4 + 0.4 + 0.4));
        }
        $bonus = $i_summ / $currency->conversion_rate;
        if ($bonus && $bonus > round(round(round(0))) && $customer->id) {
            Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET money = money + $bonus, total = total + $bonus
						WHERE is_sponsor = 1 AND id = '{$customer->id}'");

            Hook::exec(
                'actionBalance',
                array(
                    'module_name' => 'refpro',
                    'id_customer' => $customer->id,
                    'value' => $bonus,
                    'message' => $l->l('Referral reward', __FILE__) . ' #' . $order->id,
                    'status' => 1,
                    'transaction_type' => 'REFPRO1'
                )
            );

            $template_vars = array();
            $template_vars['{sum}'] = self::getDefCurrencyMail(round($bonus, round(round(round(0)) + 0.4 + 0.4 + 0.4 + 0.4 + 0.4)));
            $template_vars['{level}'] = $level;
            if (Referral::getSettings('email_notification')) MailModRP::sendMail('bonus', $customer->email, $customer->firstname . ' ' . $customer->lastname, $l->l('You received rewards', __FILE__), $template_vars, $order->id_lang);
            Db::getInstance()->insert('refpro_bonus', array('id_customer' => (int) $customer->id, 'id_ref' => $ref_id, 'level' => $level, 'sum' => $bonus, 'id_order' => (int) $order->id));
        }
    }

    const LIMIT_CUSTOMER_BONUSES = 20;
    /**
     * @param $id_customer
     * @return array
     */
    public static function getBonusesByCustomer($id_customer, $page = 1, $return_total = 0)
    {
        $query = new DbQueryCore();
        $query->from('refpro_bonus', 'rb');
        $query->leftJoin(
            'customer',
            'c',
            'c.id_customer = rb.id_customer'
        );
        $query->where(
            //            'rb.id_ref = '.(int)$id_customer.' OR '.
            ' rb.id_customer = ' . (int) $id_customer
        );

        if ($return_total) {
            $query->select('COUNT(rb.id_customer)');
            return Db::getInstance()->getValue($query->build());
        }

        $query->limit(self::LIMIT_CUSTOMER_BONUSES, ($page - 1) * self::LIMIT_CUSTOMER_BONUSES);

        $query->select('c.*, rb.*');
        $result = Db::getInstance()->executeS($query->build());
        return (is_array($result) ? $result : array());
    }

    /**
     * @param $id_order
     * @return array
     */
    public static function getBonusesByOrder($id_order, $page = 1, $return_total = 0)
    {
        $query = new DbQueryCore();
        $query->from('refpro_bonus', 'rb');
        $query->leftJoin(
            'customer',
            'c',
            'c.id_customer = rb.id_customer'
        );
        $query->where(
            'rb.id_order = ' . (int) $id_order
        );

        if ($return_total) {
            $query->select('COUNT(rb.id_customer)');
            return Db::getInstance()->getValue($query->build());
        }

        $query->limit(self::LIMIT_CUSTOMER_BONUSES, ($page - 1) * self::LIMIT_CUSTOMER_BONUSES);

        $query->select('c.*, rb.*');
        $result = Db::getInstance()->executeS($query->build());
        return (is_array($result) ? $result : array());
    }

    public static function getBonusRate($group_id, $level)
    {
        $level = (string) $level;
        if (!self::checkActivationState(self::getSettings('activation_code'))) return round(round(round(0)));
        $group_rates = self::getSettings('group_rates', true);
        if (!empty($group_rates->{$group_id}) && !empty($group_rates->{$group_id}->{$level - round(round(round(0)) + 0.5 + 0.5)})) {
            return $group_rates->{$group_id}->{$level - round(round(round(0)) + round(round(0) + 0.2 + 0.2 + 0.2 + 0.2 + 0.2))};
        }
        return round(round(round(0)));
    }

    public static function getBonusRateOld($group_id, $level)
    {
        if (!self::checkActivationState(self::getSettings('activation_code'))) return round(round(round(0)));
        $group_rates = self::getSettings('group_rates', true);
        if (!empty($group_rates->{$group_id}) && !empty($group_rates->{$group_id}[$level - round(round(round(0)) + 0.5 + 0.5)])) {
            return $group_rates->{$group_id}[$level - round(round(round(0)) + round(round(0) + 0.2 + 0.2 + 0.2 + 0.2 + 0.2))];
        }
        return round(round(round(0)));
    }

    public static function getCategories()
    {
        $id_land = Context::getContext()->language->id;
        $result = Db::getInstance()->ExecuteS('
		SELECT DISTINCT c.*, cl.*
		FROM `' . _DB_PREFIX_ . 'category` c
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = ' . (int) $id_land . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cg.`id_category` = c.`id_category`)
		WHERE 1
		AND (c.`active` = 1 OR c.`id_category`= 1)
        ORDER BY c.`level_depth` ASC');

        $result = array_combine(array_column($result, 'id_category'), array_values($result));

        return $result;
    }

    public static function findNputCatInSelfArray($result, &$catArray)
    {
        $id_category = $catArray['id_category'];
        $id_parent = $catArray['id_parent'];
        foreach (self::$catTree as $key => $value) {
            if (
                isset(self::$catTree[$key]['id_category']) &&
                self::$catTree[$key]['id_category'] == $id_parent
            ) {
                return;
            }
        }
        if (isset(self::$catTree['id_category'])) {
        }
    }
    public static function setProfit($ref_array, $level)
    {
        // var_dump($ref_array);
        if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        $count_ref_array = count($ref_array);
        for ($i = round(round(round(0))); $i < $count_ref_array; $i++) {
            $profit = Db::getInstance()->ExecuteS('SELECT SUM(sum) FROM ' . _DB_PREFIX_ . "refpro_bonus
			WHERE id_ref = {$ref_array[$i]['id_customer']} AND `level` = '$level'");
            $ref_array[$i]['profit'] = $profit[round(round(round(0)))]['SUM(sum)'];
        }
        return $ref_array;
    }

    public static function saveUserSettings($id, $key, $value)
    {
        if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `$key` = '$value' WHERE `id` = '$id'");
    }

    public static function updateSponsor($id, $sponsor)
    {
        if ($id == $sponsor)
            return false;
        return Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `sponsor` = " . (int) $sponsor . " WHERE `id` = " . (int) $id);
    }

    public static function deleteSponsor($id)
    {
        return Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `is_sponsor` = 0, `active` = 1 WHERE `id` = " . (int) $id);
    }

    public static function setActive($id)
    {
        $query = 'SELECT `active` FROM `' . _DB_PREFIX_ . "refpro_customer` WHERE `id` = " . (int) $id;
        $active = !(bool) Db::getInstance()->GetValue($query);

        return Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `active` = " . (int) $active . " WHERE `id` = " . (int) $id);
    }

    public static function getAllRefCustomers()
    {
        //if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        return Db::getInstance()->ExecuteS('SELECT t1.firstname, t1.lastname, t1.email, t1.id_customer
		FROM ' . _DB_PREFIX_ . 'customer AS t1, ' . _DB_PREFIX_ . "refpro_customer AS t2 WHERE t2.is_sponsor = '1' AND t2.id = t1.id_customer");
    }

    public static function getReferrals($id)
    {
        if (!self::checkActivationState(self::getSettings('activation_code'))) return false;
        return Db::getInstance()->ExecuteS('SELECT t1.firstname, t1.lastname, t1.email, t1.id_customer, t2.is_sponsor
		FROM ' . _DB_PREFIX_ . 'customer AS t1, ' . _DB_PREFIX_ . "refpro_customer AS t2
		WHERE t2.sponsor = '$id' AND t2.id = t1.id_customer ORDER BY t1.date_add DESC");
    }

    public static function ajaxWrap($content)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        $output = "<div id='ajaxWrap'>" . $content . '</div>';
        return $output;
    }

    public static function getSponsor($sid)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        //PrestaShopLogger::addLog("refpro:getSponsor-a:".$sid);  

        $sponsor_r = Db::getInstance()->getRow('SELECT sponsor FROM ' . _DB_PREFIX_ . "refpro_customer WHERE id = '$sid'");
        //PrestaShopLogger::addLog("refpro:getSponsor-b:".$sponsor_r['sponsor']);  
        if ($sponsor_r) {
            $customer = new Customer($sponsor_r['sponsor']);
            //PrestaShopLogger::addLog("refpro:getSponsor-c:".var_export($customer,1));  
            return $customer;

        }
        else return false;
    }

    public static function setSettings($key, $value, $encode = false)
    {
        $value = $encode ? Tools::jsonEncode($value) : $value;
        if (!self::isSettings($key)) {
            self::createSetting($key);
        }
        if ($key == 'ps') $value = str_replace('\\', '\\\\', $value);

        Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_settings SET `value` = '$value' WHERE `key` = '$key'");
    }

    public static function isSettings($key)
    {
        $row = Db::getInstance()->getRow('SELECT `key` FROM ' . _DB_PREFIX_ . 'refpro_settings
        WHERE `key` = "' . pSQL($key) . '"');
        return (is_array($row) && count($row) ? true : false);
    }

    public static function createSetting($key, $value = '')
    {
        Db::getInstance()->insert('refpro_settings', array(
            array(
                'key' => $key,
                'value' => $value
            )
        ));
    }

    public static function formatMoney($raw)
    {
        return Tools::displayPrice(Tools::convertPrice($raw));
    }

    public static function addCustomer($customer_id, $sponsor_id = '', $is_sponsor = 1)
    {
        $query_str = 'UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `is_sponsor` = 1 WHERE `id` = '$customer_id'";
        $query_str2 = 'INSERT IGNORE INTO ' . _DB_PREFIX_ . "refpro_customer (id, sponsor, is_sponsor, active) VALUES ('$customer_id', '$sponsor_id', '$is_sponsor', " . (int) (!Referral::getSettings('validation_of_new_affiliates')) . ")";
        Db::getInstance()->Execute($query_str);
        Db::getInstance()->Execute($query_str2);
    }

    public static function setAffiliateGroup($customer_id)
    {
        $id_group = (int) Referral::getSettings('customer_group_new_affiliates');

        if ($id_group) {
            $customer = new Customer($customer_id);
            if (Validate::isLoadedObject($customer)) {
                $groups = $customer->getGroups();
                $ids = array();
                foreach ($groups as $group) {
                    $ids[] = (int) $group['id_group'];
                }

                if (!in_array($id_group, $ids)) {
                    $customer->addGroups(array(
                        $id_group
                    ));
                    $customer->id_default_group = $id_group;
                    $customer->save();
                    Context::getContext()->customer = $customer;
                }
            }
        }
    }

    public static function getUserData($id)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        return Db::getInstance()->GetRow('SELECT * FROM ' . _DB_PREFIX_ . "refpro_customer WHERE id = '$id'");
    }

    public static function getPayments($user_id, $p = null)
    {
        $limit = round(round(round(0)) + round(round(0) + 2.5 + 2.5 + 2.5 + 2.5) + round(round(0) + round(0 + 3.3333333333333 + 3.3333333333333 + 3.3333333333333)) + round(round(0) + 3.3333333333333 + 3.3333333333333 + 3.3333333333333) + round(round(0) + round(0 + 5) + round(0 + 1.25 + 1.25 + 1.25 + 1.25)) + round(round(0) + round(0 + 0.4 + 0.4 + 0.4 + 0.4 + 0.4) + round(0 + 2) + round(0 + 0.5 + 0.5 + 0.5 + 0.5) + round(0 + 1 + 1) + round(0 + 0.5 + 0.5 + 0.5 + 0.5)));
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        $payments = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . "refpro_payments`
				WHERE `user_id` = '$user_id' ORDER BY `ts` DESC " . (!is_null($p) ? 'LIMIT ' . (($p - round(round(round(0)) + 0.5 + 0.5)) * $limit) . ', 50' : ''));
        $count_payments = count($payments);
        for ($i = round(round(round(0))); $i < $count_payments; $i++) {
            $payments[$i]['ts'] = date('<b>d.m.y</b> - H:i', strtotime($payments[$i]['ts']));
        }
        return $payments;
    }

    public static function isSponsor($id)
    {
        $query = 'SELECT `id` FROM `' . _DB_PREFIX_ . "refpro_customer` WHERE `id` = '$id' AND `is_sponsor` = '1'";
        return Db::getInstance()->GetValue($query);
    }

    public static function isInRefProgr($id)
    {
        $query = 'SELECT `sponsor` FROM `' . _DB_PREFIX_ . "refpro_customer` WHERE `id` = '$id'";
        return Db::getInstance()->GetValue($query);
    }

    public static function isFunctionalityAvailibleForGroup()
    {
        $cookie = &$GLOBALS['cookie'];
        $customer = new Customer($cookie->id_customer);
        $a_availible_groups = Referral::getSettings('available_groups', true);
        return in_array($customer->id_default_group, $a_availible_groups);
    }

    public static function getCartRuleByCustomer($id_customer)
    {
        return (int) Db::getInstance()->getValue(
            'SELECT `id_cart_rule`
            FROM ' . _DB_PREFIX_ . 'refpro_customer
            WHERE `id` = ' . (int) $id_customer
        );
    }

    public static function getCustomerByCartRule($id_cart_rule)
    {
        return (int) Db::getInstance()->getValue(
            'SELECT `id`
            FROM ' . _DB_PREFIX_ . 'refpro_customer
            WHERE `id_cart_rule` = ' . (int) $id_cart_rule
        );
    }

    public static function updateCartRule(Customer $customer, $user_data)
    {
        $id_cart_rule = self::getCartRuleByCustomer($customer->id);
        $cart_rule = new CartRule($id_cart_rule);
        if (Validate::isLoadedObject($cart_rule)) {
            if ($user_data['has_voucher'] == self::VOUCHER_TYPE_PERCENT) {
                $cart_rule->reduction_percent = (float) $user_data['voucher_percent'];
                $cart_rule->reduction_amount = 0;
                $cart_rule->free_shipping = 0;
            }

            if ($user_data['has_voucher'] == self::VOUCHER_TYPE_AMOUNT) {
                $cart_rule->reduction_amount = (float) $user_data['voucher_amount'];
                $cart_rule->reduction_percent = 0;
                $cart_rule->free_shipping = 0;
            }

            if ($user_data['has_voucher'] == self::VOUCHER_TYPE_FREE_SHIPPING) {
                $cart_rule->reduction_amount = 0;
                $cart_rule->reduction_percent = 0;
                $cart_rule->free_shipping = true;
            }
            $cart_rule->save();
        }
    }

    public static function setCartRuleByCustomer($id_customer, $id_cart_rule)
    {
        Db::getInstance()->update(
            'refpro_customer',
            array(
                'id_cart_rule' => $id_cart_rule
            ),
            '`id` = ' . (int) $id_customer
        );
    }

    const VOUCHER_TYPE_PERCENT = 'percent';
    const VOUCHER_TYPE_AMOUNT = 'amount';
    const VOUCHER_TYPE_FREE_SHIPPING = 'free_shipping';

    /**
     * @param Customer $customer
     * @return bool|CartRule
     */
    public static function createNewCartRule(Customer $customer, $type, $user_data)
    {
        $cart_rule = new CartRule();

        foreach (Language::getLanguages(false) as $l) {
            $cart_rule->name[$l['id_lang']] = $customer->email;
        }
        $cart_rule->date_from = date('Y-m-d');
        $cart_rule->date_to = date('2099-12-31');
        $cart_rule->quantity = 99999999;
        $cart_rule->quantity_per_user = 1;
        $cart_rule->code = self::getCode();
        $cart_rule->reduction_tax = true;

        if ($type == self::VOUCHER_TYPE_PERCENT) {
            $cart_rule->reduction_percent = (float) $user_data['voucher_percent'];
        }

        if ($type == self::VOUCHER_TYPE_AMOUNT) {
            $cart_rule->reduction_amount = (float) $user_data['voucher_amount'];
        }
        if ($type == self::VOUCHER_TYPE_FREE_SHIPPING) {
            $cart_rule->free_shipping = true;
        }

        $cart_rule->cart_rule_restriction = 1;
        $cart_rule->date_add = date('d-m-Y');
        $cart_rule->date_upd = date('d-m-Y');

        return ($cart_rule->save() ? $cart_rule : false);
    }

    public static function getCode()
    {
        $code = '';
        $chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
        for ($i = 1; $i <= 8; ++$i) {
            $char = floor(self::rand() * Tools::strlen($chars));
            $code .= (string) $chars{
                (int) $char};
        }
        return $code;
    }

    public static function rand()
    {
        return (float) rand() / (float) getrandmax();
    }

    public static function getCountPayedOrderCustomer($id_customer)
    {
        $order = (int) Db::getInstance()->getValue(
            'SELECT COUNT(o.id_order) FROM ' . _DB_PREFIX_ . 'orders o
            WHERE o.`id_customer` = ' . (int) $id_customer . '
             AND o.`valid` = 1'
        );
        return $order;
    }

    public static function checkNeedCompletedOrders()
    {
        $need_completed_orders = (int) Referral::getSettings('need_completed_orders');
        return ($need_completed_orders == 0
            || ($need_completed_orders > 0
                && Referral::getCountPayedOrderCustomer(Context::getContext()->customer->id) >= $need_completed_orders));
    }
}
