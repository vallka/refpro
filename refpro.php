<?php
//define('VK_MYIP', '90.255.128.26');

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

require_once(dirname(__FILE__) . '/config.php');

if (!defined('_PS_VERSION_')) exit;

class RefPro extends Module
{
    public $ip_installed;

    public function __construct()
    {
        $this->name = 'refpro';
        $this->tab = 'advertising_marketing';
        $this->version = '2.9.26';
        $this->author = 'DaRiuS';
        $this->module_key = 'a4f22646b86b6d10c9063efc6355ed47';
        $this->internalpurse_link = 'https://addons.prestashop.com/product.php?id_product=25035';
        $this->shop_url = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $this->controllers = array('myaccount');
        parent::__construct();
        $this->confirmUninstall = $this->l('Attention! Will eliminate all the partners saved. Are you sure you want uninstall?');
        $this->displayName = $this->l('Extended Affiliate Program');
        $this->description = $this->l('A multi-level affiliate program with differentiation percent for groups and categories');
        $this->ip_installed = Module::getInstanceByName('userbalance')
            && Module::isInstalled('userbalance');

        if (Tools::version_compare(_PS_VERSION_, '1.7.6.0', '>=')) {
            if (!Hook::isModuleRegisteredOnHook(
                $this,
                'actionCustomerFormBuilderModifier',
                Context::getContext()->shop->id
            )) {
                Hook::registerHook($this, 'actionCustomerFormBuilderModifier');
            }
            if (!Hook::isModuleRegisteredOnHook(
                $this,
                'actionAfterCreateCustomerFormHandler',
                Context::getContext()->shop->id
            )) {
                Hook::registerHook($this, 'actionAfterCreateCustomerFormHandler');
            }
            if (!Hook::isModuleRegisteredOnHook(
                $this,
                'actionAfterUpdateCustomerFormHandler',
                Context::getContext()->shop->id
            )) {
                Hook::registerHook($this, 'actionAfterUpdateCustomerFormHandler');
            }
        }
    }


    public function install()
    {
        $name_langs = array(
            'en' => 'Affiliate Program',
            'ru' => 'Партнерская программа',
            'pl' => 'Program partnerski',
            'br' => 'Programa de afiliados',
            'pt' => 'Programa de afiliados',
            'it' => 'Programma affiliazione',
            'fr' => 'Programme d\'affiliation',
            'es' => 'Programa de Afiliados',
            'lv' => 'Partneru programma',
            'ro' => 'Program afiliere',
            'de' => 'Refferalprogramm'
        );
        ToolsModuleRP::createTab(
            $this->name,
            'AdminRefPro',
            (version_compare(_PS_VERSION_, '1.5', '>=') && version_compare(_PS_VERSION_, '1.5.1', '<')
                || version_compare(_PS_VERSION_, '1.7', '>=')) ? 'AdminParentCustomer' : 'AdminCustomers',
            $name_langs
        );

        if (
            !parent::install()
            || !$this->installDB()
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayCustomerAccountForm')
            || !$this->registerHook('createAccountForm')
            || !$this->registerHook('actionCustomerAccountAdd')
            || !$this->registerHook('customerAccount')
            || !$this->registerHook('MyAccountBlock')
            || !$this->registerHook('updateOrderStatus')
            || !$this->registerHook('displayRightColumnProduct')
            || !$this->registerHook('displayProductTabContent')
            || !$this->registerHook('displayProductTab')
            || !$this->registerHook('displayAdminForm')
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('actionAdminAddVoucherBefore')
            || !$this->registerHook('actionAdminBefore')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayAdminCustomers')
            || !$this->registerHook('displayAdminOrder')
            || !$this->registerHook('displayMyAccountBlockfooter')
        )
            return false;
        if (version_compare(_PS_VERSION_, '1.7', '>=') && !$this->registerHook('displayBeforeBodyClosingTag'))
            return false;
        /*		if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                    $page_name = 'module-'.$this->name.'-'.$controller;
                    $meta_data = Meta::getMetaByPage($page_name, $this->context->language->id);
                    $meta = new Meta($meta_data['id_meta']);
                    foreach (Language::getLanguages() as $l)
                        $meta->name[$l['id_lang']] = (isset($name_langs[$l['iso_code']]) ? $name_langs[$l['iso_code']] : $name_langs['en']);
                    $meta->save();
                }*/
        return true;
    }

    public function installDB()
    {
        $ok = true;
        if ($ok) $ok = Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'refpro_customer` (
				  `id` double NOT NULL,
				  `sponsor` double DEFAULT NULL,
				  `is_sponsor` double DEFAULT NULL,
				  `money` double NOT NULL DEFAULT "0",
				  `total` double NOT NULL DEFAULT "0",
				  `order` tinyint(1) DEFAULT "0",
				  `ps_1` varchar(50) DEFAULT NULL,
				  `ps_2` varchar(50) DEFAULT NULL,
				  `ps_3` varchar(50) DEFAULT NULL,
				  `ps_4` varchar(50) DEFAULT NULL,
				  `ps_5` varchar(50) DEFAULT NULL,
				  `id_cart_rule` int(50) DEFAULT NULL,
				  `has_voucher` varchar(50) DEFAULT "0",
				  `voucher_percent` float DEFAULT NULL,
				  `voucher_amount` float DEFAULT NULL,
				  `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT "1",
				  `td` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			');
        if ($ok) $ok = Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'refpro_bonus` (
				  `id` int(50) NOT NULL AUTO_INCREMENT,
				  `id_customer` int(50) DEFAULT NULL,
				  `id_ref` int(50) DEFAULT NULL,
				  `level` int(1) DEFAULT NULL,
				  `sum` float DEFAULT NULL,
				  `id_order` int(50) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			');
        if ($ok) $ok = Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'refpro_settings` (
				  `key` varchar(50) NOT NULL,
				  `value` text DEFAULT NULL,
				  PRIMARY KEY (`key`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			');
        if ($ok) $ok = Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'refpro_payments` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `amount` double NOT NULL,
				  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			');

        $categories = Referral::getCategories();
        $result = array();
        foreach ($categories as $category)
            $result[$category['id_category']] = array(
                'availible' => round(round(0) + round(0 + 0.2 + 0.2 + 0.2 + 0.2 + 0.2)),
                'per' => round(round(0) + round(0 + 0.5 + 0.5))
            );
        $groups = new Group();
        $groups = $groups->getGroups(round(round(0) + 0.5 + 0.5));
        $default_group = isset($groups[round(round(0))]) ? $groups[round(round(0))] : array();
        if (version_compare(_PS_VERSION_, '1.5', '>='))
            $default_group = isset($groups[round(round(0) + 0.66666666666667 + 0.66666666666667 + 0.66666666666667)]) ? $groups[round(round(0) + round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333) + round(0 + 0.33333333333333 + 0.33333333333333 + 0.33333333333333))] : array();
        $av_group = array();
        $av_group[] = isset($default_group['id_group']) ? $default_group['id_group'] : null;
        if ($ok) $ok = Db::getInstance()->Execute('
			insert  into `' . _DB_PREFIX_ . "refpro_settings`(`key`,`value`) values ('levels','2'),
			('banner_link',NULL),('manager',1),('ps',NULL),
			('banner_img','banner.gif'),
			('rules',NULL),
			('validation_of_new_affiliates',0),
			('contact_for_notification',0),
			('email_notification',1),
			('min_balance',100),
			('ignore_existing_customers',1),
			('group_rates','{}'),
			('activation_code',NULL),
			('has_voucher', 0),
			('voucher_percent', 0),
			('voucher_amount', 0),
			('force_on', 'display_block'),('active_states','[2]'),('with_tax','0'), ('charge_for_product', null),
			('available_groups', '" . pSQL(Tools::jsonEncode($av_group)) . "'), ('remove_states', '[7]'), ('customers_number', 0),
			('categories', '" . pSQL(Tools::jsonEncode($result)) . "'), ('show_reward', 1), ('cust_priv', 1), ('not_apply_with_voucher_discount', 0),
			('show_only_direct_affiliates', 0);
			");
        return $ok;
    }

    public function uninstall()
    {
        ToolsModuleRP::deleteTab('AdminRefPro');
        if (!parent::uninstall() || !$this->uninstallDB())
            return false;
        return true;
    }

    public function uninstallDB()
    {
        $ok = true;
        if ($ok) $ok = Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'refpro_customer`;');
        if ($ok) $ok = Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'refpro_bonus`;');
        if ($ok) $ok = Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'refpro_settings`;');
        if ($ok) $ok = Db::getInstance()->Execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'refpro_payments`;');
        return $ok;
    }

    public function hookDisplayHeader()
    {
        //if (defined('VK_MYIP') and $_SERVER['REMOTE_ADDR']==VK_MYIP) PrestaShopLogger::addLog("refpro:hookDisplayHeader:{$_SERVER['REMOTE_ADDR']}!");

        if (Tools::isSubmit('updateRefproReward')) {
            //if (defined('VK_MYIP') and $_SERVER['REMOTE_ADDR']==VK_MYIP) PrestaShopLogger::addLog("refpro:hookDisplayHeader:updateRefproReward");
            $id_pa = Tools::getValue('id_pa');
            if (!$id_pa) {
                $id_pa = null;
            }

            $id_product = Tools::getValue('id_product');
            $reward = round(round(0));
            $product = new Product($id_product);

            if ($product) {
                $customer = new Customer($this->context->customer->id);
                $categories_array = Referral::getSettings('categories', true, true);
                $id_category = (int) $product->id_category_default;

                if (
                    !isset($categories_array[$id_category])
                    || (isset($categories_array[$id_category])
                        && $categories_array[$id_category]['availible'] != round(round(0) + 0.25 + 0.25 + 0.25 + 0.25))
                )
                    return false;

                $category_percent = (isset($categories_array[$id_category]) ?
                    $categories_array[$id_category]['per'] : round(round(0)));
                $category_percent = (float) $category_percent;

                $bonus_rate = (Referral::getBonusRate($customer->id_default_group, round(round(0) + round(0 + 1))) * $category_percent) / round(round(0) + round(0 + 6.25 + 6.25 + 6.25 + 6.25) + round(0 + 8.3333333333333 + 8.3333333333333 + 8.3333333333333) + round(0 + 12.5 + 12.5) + round(0 + 25));
                $bonus_rate = (float) $bonus_rate;
                if (Referral::getSettings('with_tax'))
                    $f_product_price = (float) Product::getPriceStatic($product->id, true, $id_pa);
                else
                    $f_product_price = (float) Product::getPriceStatic($product->id, false, $id_pa);
                $currency = new Currency($this->context->currency->id);
                $reward = (float) round((($f_product_price * $bonus_rate) / $currency->conversion_rate), round(round(0) + 0.5 + 0.5 + 0.5 + 0.5));
            }

            die(Tools::jsonEncode(array(
                'reward' => Referral::formatMoney($reward)
            )));
        }


        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (!$this->context->cookie->logged && $url_link = Tools::getValue('ref')) {
            if ($url_link) {
                $this->context->cookie->ref = $url_link;
                $this->context->cookie->write();
                //if (defined('VK_MYIP') and $_SERVER['REMOTE_ADDR']==VK_MYIP) PrestaShopLogger::addLog("refpro:hookDisplayHeader:url_link");
            }
        }
        if (
            Tools::isSubmit('addDiscount')
            && $code = Tools::getValue('discount_name')
        ) {
            $id_cart_rule = CartRule::getIdByCode($code);
            $id_customer = Referral::getCustomerByCartRule($id_cart_rule);
            //if (defined('VK_MYIP') and $_SERVER['REMOTE_ADDR']==VK_MYIP) PrestaShopLogger::addLog("refpro:hookDisplayHeader:addDiscount:".$id_customer);
            if ($id_customer) {
                $customer = new Customer($id_customer);
                if (Validate::isLoadedObject($customer)) {
                    $this->context->cookie->ref = $customer->email;
                    $this->context->cookie->write();
                    self::_addCustToRef($this->context->customer->id, $id_customer);
                }
            }
        }

        if (Tools::isSubmit('addingCartRule')) {
            $cart_rules = $this->context->cart->getCartRules();

            if (is_array($cart_rules) && count($cart_rules)) {
                foreach ($cart_rules as $cart_rule) {
                    $id_customer = Referral::getCustomerByCartRule($cart_rule['id_cart_rule']);
                    //if (defined('VK_MYIP') and $_SERVER['REMOTE_ADDR']==VK_MYIP) PrestaShopLogger::addLog("refpro:hookDisplayHeader:addingCartRule:".$id_customer);
                    if ($id_customer) {
                        $customer = new Customer($id_customer);
                        if (Validate::isLoadedObject($customer)) {
                            $this->context->cookie->ref = $customer->email;
                            $this->context->cookie->write();
                            self::_addCustToRef($this->context->customer->id, $id_customer);
                        }
                    }
                }
            }
        }

        if ($this->context->cookie->logged && Referral::isFunctionalityAvailibleForGroup())
            $this->context->smarty->assign(array(
                'email' => $this->context->customer->email,
                'id' => $this->context->customer->id,
                'is_sponsor' => Referral::isSponsor($this->context->customer->id),
                'module_img_dir' => _MODULE_DIR_ . $this->name . '/views/img/'
            ));

        if (
            $this->context->controller instanceof ProductController
            || $this->context->controller instanceof ParentOrderController
            || $this->context->controller instanceof OrderController
        ) {
            $this->context->controller->addJS($this->getPathUri() . 'views/js/product.js');
        }

        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->context->controller->addJS($this->getPathUri() . 'views/js/jquery-migrate-1.2.1.min.js');

        $this->context->controller->addJS($this->getPathUri() . 'views/js/modal.jquery.js');
        $this->context->controller->addJS($this->getPathUri() . 'views/js/framework.js');

        $this->context->controller->addCSS($this->getPathUri() . 'views/css/modal.jquery.css');
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/refpro.css');

        if (in_array(Tools::getValue('controller'), array('authentication', 'myaccount', 'orderopc', 'order'))) {
            $this->context->controller->addCSS($this->getPathUri() . 'views/css/autoload/jquery.fancybox-1.3.4.css');
            $this->context->controller->addJS($this->getPathUri() . 'views/js/jquery.fancybox-1.3.4.js');
            $this->context->smarty->assign(array(
                'enable_fancybox' => true
            ));
        }
        $this->context->smarty->assign(array(
            'is17' => version_compare(_PS_VERSION_, '1.7', '>='),
            'current_url' => $this->getCurrentURL(true, false),
            'qrCodeInlineUrl' => $this->context->link->getModuleLink(
                'refpro',
                'myaccount',
                array('action' => 'download_qr_code', 'ajax' => true),
                true
            ),
            'qrCodeDownloadUrl' => $this->context->link->getModuleLink(
                'refpro',
                'myaccount',
                array('action' => 'download_qr_code', 'ajax' => true, 'download' => true),
                true
            ),
        ));

        if (version_compare(_PS_VERSION_, '1.7', '<'))
            return $this->display(__FILE__, 'module-header.tpl');
    }

    public function getCurrentURL()
    {

        return Tools::getHttpHost(true) . preg_replace('/(?:(\?)|&amp;)p=\d+/', '$1', Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']));
    }

    private function _addCustToRef($customer, $ref)
    {
        if ($customer == $ref)
            return;
        if ($customer == 0)
            return;
        $old_ref = Referral::isInRefProgr($customer);
        $template_vars = array();
        $mail_sponsor = new Customer($ref);
        $template_vars['{text}'] = $this->l('The new customer (sub-affiliate) joined to your downline in the affiliate program.');

        if ($old_ref === false) {
            if (Referral::getSettings('force_on') == 'force_on')
                $is_sponsor = 1;
            else
                $is_sponsor = 0;
            if (!Referral::getSettings('ignore_existing_customers')) {
                Referral::addCustomer($customer, $ref, $is_sponsor);
                if (Referral::getSettings('email_notification')) MailModRP::sendMail(
                    'mailing',
                    $mail_sponsor->email,
                    $mail_sponsor->firstname . ' ' . $mail_sponsor->lastname,
                    $this->l('You invited a new customer'),
                    $template_vars
                );
            }
            return;
        }

        if (!Referral::getSettings('ignore_existing_customers')) {
            Referral::updateSponsor($customer, $ref);
            if (Referral::getSettings('email_notification')) MailModRP::sendMail(
                'mailing',
                $mail_sponsor->email,
                $mail_sponsor->firstname . ' ' . $mail_sponsor->lastname,
                $this->l('You invited a new customer'),
                $template_vars
            );
        }
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->context->smarty->assign(array(
                'is17' => true,
            ));

            return $this->display(__FILE__, 'module-header.tpl');
        }

        return '';
    }

    public function hookCreateAccountForm()
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (Referral::getSettings('force_on') != 'display_block') return false;

        $available_groups = Tools::JsonDecode(Referral::getSettings('available_groups'));

        //        $customer_group_new_affiliates = Referral::getSettings('customer_group_new_affiliates');
        //
        //        if (!$customer_group_new_affiliates) {
        //            if (!in_array(3, $available_groups))
        //                return false;
        //        }

        /*
                if (Validate::isLoadedObject($this->context->customer)) {
                    if (empty(array_intersect($this->context->customer->getGroups(), $available_groups)))
                        return false;
                } else {
                    if (!in_array(1, $available_groups))
                        return false;
                }
        */
        if (version_compare(_PS_VERSION_, '1.7', '>='))
            $this->context->controller->addJS($this->getPathUri() . 'views/js/jquery-migrate-1.2.1.min.js');

        $this->context->controller->addJS($this->getPathUri() . 'views/js/framework.js');
        $this->context->controller->addJS($this->getPathUri() . 'views/js/jquery.fancybox-1.3.4.js');

        $this->context->controller->addCSS($this->getPathUri() . 'views/css/refpro.css');

        if ((float) _PS_VERSION_ == 1.6)
            $this->context->controller->addCSS($this->getPathUri() . 'views/css/refpro16.css');

        $this->context->controller->addCSS($this->getPathUri() . 'views/css/jquery.fancybox-1.3.4.css');

        $cms_page = new CMS(Referral::getSettings('rules'), $this->context->language->id);
        $alias = null;
        if (Validate::isLoadedObject($cms_page)) $alias = $cms_page->link_rewrite;
        $rules_url = ((int) Referral::getSettings('rules') ?
            $this->context->link->getCMSLink(Referral::getSettings('rules'), $alias) : '');
        if ($rules_url) $_POST['rp_rules'] = $rules_url;
        if ($ref = $this->context->cookie->ref) $_POST['ref_url'] = $ref;
        $referal_c = self::_getSponsor($ref);

        if (Validate::isLoadedObject($referal_c))
            $ref_name = $referal_c->firstname . ' ' . (Referral::getSettings('cust_priv') ? Tools::substr($referal_c->lastname, 0, 1) . '.' : $referal_c->lastname);
        else
            $ref_name = $ref;
        $this->context->smarty->assign(array(
            'is16' => version_compare(_PS_VERSION_, '1.6', '>='),
            'is17' => version_compare(_PS_VERSION_, '1.7', '>='),
            'ref_name' => $ref_name
        ));
        
		$user_data = Referral::getUserData($this->context->customer->id);
        if (!$user_data['is_sponsor']) return $this->display(__FILE__, 'registration.tpl');
    }

    private function _getSponsor($link)
    {
        if (!(int) $link && Validate::isEmail($link)) {
            $sponsor = new Customer();
            $sponsor = $sponsor->getByEmail($link);
            return $sponsor;
        } elseif (Validate::isMd5($link)) {
            $result = Db::getInstance()->getValue('
				SELECT id_customer
				FROM `' . _DB_PREFIX_ . 'customer`
				WHERE MD5(`email`) = \'' . pSQL($link) . '\'');
            return new Customer($result);
        } else
            return new Customer($link);
    }

    public function hookActionCustomerAccountAdd($params)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        $new_user = $params['newCustomer'];
        if (!Tools::getValue('ref_url') || !empty($this->context->cookie->ref)) {
            $link = !Tools::getValue('ref_url') ? pSQL(Tools::getValue('ref_url')) : '';
            $link = $link ? $link : $this->context->cookie->ref;
            $sponsor = null;
            if (!(int) $link && Validate::isEmail($link)) {
                $sponsor = new Customer();
                $sponsor = $sponsor->getByEmail($link);
            } elseif (Validate::isMd5($link)) {
                $result = Db::getInstance()->getValue('
					SELECT id_customer
					FROM `' . _DB_PREFIX_ . 'customer`
					WHERE MD5(`email`) = \'' . pSQL($link) . '\'');
                $sponsor = new Customer($result);
            } else
                $sponsor = new Customer($link);
        }
        $sponsor_id = $sponsor ? $sponsor->id : '';
        if ((Referral::getSettings('force_on') == 'display_block' && (Tools::getValue('goref') == 'on' || Tools::getValue('goref')))
            || Referral::getSettings('force_on') == 'force_on'
        )
            $is_sponsor = round(round(0) + round(0 + 1));
        else
            $is_sponsor = round(round(0));
        if (!empty($sponsor_id) || !empty($is_sponsor)) {

            if (
                Tools::getValue('goref') == 'on' || Tools::getValue('goref')
                || $is_sponsor
            ) {
                Referral::setAffiliateGroup($new_user->id);
            }

            if ($is_sponsor) {
                $this->sendMailNotification($new_user->id);
            }
            Referral::addCustomer($new_user->id, $sponsor_id, $is_sponsor);

            if ($sponsor_id > 0) {
                $template_vars = array();
                $mail_sponsor = new Customer($sponsor_id);
                $template_vars['{text}'] = $this->l('The new customer (sub-affiliate) joined to your downline in the affiliate program.');

                if (Referral::getSettings('email_notification')) MailModRP::sendMail(
                    'mailing',
                    $mail_sponsor->email,
                    $mail_sponsor->firstname . ' ' . $mail_sponsor->lastname,
                    $this->l('You invited a new customer'),
                    $template_vars
                );
            }
        }
    }

    public function sendMailNotification($customer_id)
    {
        if (Referral::getSettings('validation_of_new_affiliates')) {
            $contact = new Contact(Referral::getSettings('manager'));
            if (Validate::isLoadedObject($contact)) {
                $customer = new Customer($customer_id);

                $data = array(
                    '{user}' => $customer->firstname . ' ' . $customer->lastname . ' (id #' . $customer->id . ')',
                    '{date}' => date('H:i:s d-m-Y')
                );
                $l = TransModRP::getInstance();

                MailModRP::sendMail(
                    'new_partner',
                    $contact->email,
                    $contact->name[$customer->id_lang],
                    $l->l('New partner', __FILE__),
                    $data,
                    $customer->id_lang
                );
            }
        }
    }

    /**
     * @deprecated
     * @return bool
     */
    public function isFunctionalityAvailibleForGroup()
    {
        return Referral::isFunctionalityAvailibleForGroup();
    }

    public function hookCustomerAccount($params = array(), $side = false)
    {
        unset($params);

        if (!Referral::checkActivationState(Referral::getSettings('activation_code')))
            return false;

        if (!Referral::isFunctionalityAvailibleForGroup())
            return false;

        $user_data = Referral::getUserData($this->context->customer->id);
        $my_acc_check = strpos($_SERVER['REQUEST_URI'], 'myaccount');

        if (!$side || !$user_data['is_sponsor'] || $my_acc_check || !$user_data['active'])
            $this->context->smarty->assign(array('hide' => '1'));

        $this->context->smarty->assign(array('side' => $side));

        if (version_compare(_PS_VERSION_, '1.7', '>='))
            return $this->display(__FILE__, 'my-account-link-17.tpl');
        elseif (version_compare(_PS_VERSION_, '1.6', '>='))
            return $this->display(__FILE__, 'my-account-link-16.tpl');
        elseif ((float) _PS_VERSION_ == 1.5)
            return $this->display(__FILE__, 'my-account-link-15.tpl');

        return $this->display(__FILE__, 'my-account-link.tpl');
    }

    public function hookDisplayFooterProduct($params)
    {
        if (Referral::getSettings('show_reward') == 'DisplayFooterProduct')
            return $this->hookRefproReward($params);
    }

    public function hookDisplayProductButtons($params)
    {
        if (Referral::getSettings('show_reward') == 'DisplayProductButtons')
            return $this->hookRefproReward($params);
    }

    public function hookDisplayRightColumnProduct($params)
    {
        if (Referral::getSettings('show_reward') == 'DisplayRightColumnProduct')
            return $this->hookRefproReward($params);
    }

    public function hookDisplayProductTabContent($params)
    {
        if (Referral::getSettings('show_reward') == 'DisplayProductTabContent')
            return $this->hookRefproReward($params);
    }

    public function hookDisplayProductTab()
    {
        if (Referral::getSettings('show_reward') == 'DisplayProductTabContent')
            return '';
    }

    public function hookRefproReward($params)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (!$this->context->cookie->logged)
            return false;

        if (!Referral::isFunctionalityAvailibleForGroup())
            return false;

        if (!Referral::isSponsor($this->context->customer->id))
            return false;

        $user_data = Referral::getUserData($this->context->customer->id);

        if (!$user_data['active'])
            return false;

        $reward = round(round(0));
        if (!isset($params['product']))
            $product = new Product(Tools::getValue('id_product'));
        else
            $product = $params['product'];
        if (is_array($product))
            $product = (object) $product;
        $reduce_amount = (float) Product::getPriceStatic(
            $product->id,
            false,
            null,
            round(round(0) + round(0 + 1 + 1 + 1) + round(0 + 0.75 + 0.75 + 0.75 + 0.75)),
            null,
            true
        );
        $is_charge_for_products = (bool) Referral::getSettings('charge_for_product');

        if ($reduce_amount > round(round(0)) && $is_charge_for_products) {
            $this->context->smarty->assign(array('fReward' => Referral::formatMoney($reward)));
            return $this->display(__FILE__, 'views/templates/hook/rewards.tpl');
        }

        $customer = new Customer($this->context->customer->id);
        $categories_array = Referral::getSettings('categories', true, true);
        $id_category = (int) $product->id_category_default;

        if (
            !isset($categories_array[$id_category])
            || (isset($categories_array[$id_category])
                && $categories_array[$id_category]['availible'] != round(round(0) + 0.25 + 0.25 + 0.25 + 0.25))
        )
            return false;

        $category_percent = (isset($categories_array[$id_category]) ?
            $categories_array[$id_category]['per'] : round(round(0)));
        $category_percent = (float) $category_percent;

        $bonus_rate = (Referral::getBonusRate($customer->id_default_group, round(round(0) + round(0 + 1))) * $category_percent) / round(round(0) + round(0 + 6.25 + 6.25 + 6.25 + 6.25) + round(0 + 8.3333333333333 + 8.3333333333333 + 8.3333333333333) + round(0 + 12.5 + 12.5) + round(0 + 25));
        $bonus_rate = (float) $bonus_rate;
        if (Referral::getSettings('with_tax'))
            $f_product_price = (float) Product::getPriceStatic($product->id, true);
        else
            $f_product_price = (float) Product::getPriceStatic($product->id, false);
        $currency = new Currency($this->context->currency->id);
        $reward = (float) round((($f_product_price * $bonus_rate) / $currency->conversion_rate), round(round(0) + 0.5 + 0.5 + 0.5 + 0.5));
        $this->context->smarty->assign(array(
            'fReward' => Referral::formatMoney($reward)
        ));
        return $this->display(__FILE__, 'views/templates/hook/rewards.tpl');
    }

    public function hookMyAccountBlock($params)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (!Referral::isFunctionalityAvailibleForGroup()) return false;
        return $this->hookCustomerAccount($params, true);
    }

    public function hookDisplayMyAccountBlock($params)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (!Referral::isFunctionalityAvailibleForGroup()) return false;
        return $this->hookCustomerAccount($params, true);
    }

    public function hookdisplayMyAccountBlockfooter($params)
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        if (!Referral::isFunctionalityAvailibleForGroup()) return false;
        return $this->hookCustomerAccount($params, true);
    }

    public function getBanners($ret_files = false)
    {
        $banner_dir = '/views/img/banners/';
        $files = glob(dirname(__FILE__) . $banner_dir . '*.jpg');
        $files = array_merge($files, glob(dirname(__FILE__) . $banner_dir . '*.png'));
        $files = array_merge($files, glob(dirname(__FILE__) . $banner_dir . '*.gif'));
        if ($ret_files)
            return $files;
        $new_files = array();
        foreach ($files as $file)
            $new_files[basename($file)] = $this->context->link->getMediaLink(_MODULE_DIR_ . $this->name . $banner_dir . basename($file));

        foreach ($new_files as $k => $banner) {
            $url = Referral::getSettings($k);
            $new_files[$k] = array('b' => $banner, 'url' => $url);
        }

        return $new_files;
    }

    public function hookUpdateOrderStatus($params)
    {
        //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-id_order:".$params['id_order']);  

        if (!Referral::checkActivationState(Referral::getSettings('activation_code')))
            return false;

        $new_status = $params['newOrderStatus']->id;
        $active_states = Referral::getSettings('active_states', true);
        $available_groups = Referral::getSettings('available_groups', true);

        //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-a:".$new_status);  

        if (in_array($new_status, $active_states)) {
            //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-b");  
            $not_apply_with_voucher_discount = false;

            $order = new Order($params['id_order']);
            $cart_rules = $order->getCartRules();
            if (
                is_array($cart_rules) && count($cart_rules)
                && Referral::getSettings('not_apply_with_voucher_discount')
            ) {
                $not_apply_with_voucher_discount = true;
            }

            if (!$not_apply_with_voucher_discount) {
                //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-b1");  
                if (Referral::getSettings('zero_level_rewards')) {
                    //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-b2");  
                    $sponsor = new Customer($order->id_customer);
                } else {
                    //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-b3:".$order->id_customer);  
                    $sponsor = Referral::getSponsor($order->id_customer);
                }

                //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-c:".$sponsor->id);  

                $per = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . "refpro_bonus WHERE id_order = '$order->id'");
                $is_count = true;
                $o_customer_order = (int) Referral::getSettings('customers_number');
                if ($o_customer_order > round(round(0))) {
                    $s_sql = "SELECT count(*) as 'count' FROM " . _DB_PREFIX_ . "refpro_bonus rb WHERE
							rb.id_customer = '{$sponsor->id}' AND rb.id_ref = '{$order->id_customer}'";
                    $a_count_order = Db::getInstance()->getRow($s_sql);
                    $i_count_order = isset($a_count_order['count']) ? (int) $a_count_order['count'] : round(round(0));
                    if ($i_count_order >= $o_customer_order) $is_count = false;
                }
                if ($sponsor && !$per && $is_count) {
                    //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-d");  
                    if (Referral::getSettings('zero_level_rewards')) {
						 $i = 0;
					 } else {
						 $i = round(round(0) + 0.5 + 0.5);
				     }
                    $level = (int) Referral::getSettings('levels');
                    if ($level > round(round(0) + 4.5 + 4.5)) $level = round(round(0) + 2.25 + 2.25 + 2.25 + 2.25);
                    while ($i <= $level && $sponsor->id) {
                        //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-e:".$i);  
                        if (in_array($sponsor->id_default_group, $available_groups)) {
                            //PrestaShopLogger::addLog("refpro:hookUpdateOrderStatus-f");  
                            Referral::sendBonus($sponsor, $order->id_customer, $order, $i);
                        }
                        $sponsor = Referral::getSponsor($sponsor->id);
                        $i++;
                    }
                }
            }
        }

        $remove_states = Referral::getSettings('remove_states', true);
        if (in_array($new_status, $remove_states)) {
            $order = new Order($params['id_order']);
            $oper = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . "refpro_bonus WHERE id_order = '$order->id'");
            if (!empty($oper)) {
                foreach ($oper as $per) {
                    if (Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET
					money = money - {$per["sum"]}, total = total - {$per["sum"]} WHERE is_sponsor = 1 AND id = '{$per["id_customer"]}'")) {
                        if ($this->ip_installed) {
                            Hook::exec(
                                'actionBalance',
                                array(
                                    'module_name' => 'refpro',
                                    'id_customer' => $per['id_customer'],
                                    'value' => -$per['sum'],
                                    'message' => $this->l('Referral refund') . ' #' . $per['id_order'],
                                    'status' => 1,
                                    'transaction_type' => 'REFPRO2'
                                )
                            );
                        }
                        Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . "refpro_bonus WHERE id = '{$per['id']}'");
                        $template_vars = array();
                        $template_vars['{summ}'] = Referral::getDefCurrencyMail($per['sum']);
                        $template_vars['{order_id}'] = $per['id_order'];
                        $customer = new Customer($per['id_customer']);

                        if (Referral::getSettings('email_notification')) MailModRP::sendMail(
                            'bonus-refunded',
                            $customer->email,
                            $customer->firstname . ' ' . $customer->lastname,
                            $this->l('Affiliated order was refunded'),
                            $template_vars,
                            $order->id_lang
                        );
                    }
                }
            }
        }
    }

    public function hookActionAdminControllerSetMedia()
    {

        if ($this->context->controller->controller_name != 'AdminCustomers') {
            return '';
        }

        if (Tools::version_compare(_PS_VERSION_, '1.7.6.0', '>=')) {
            $this->context->controller->addJs($this->_path . 'views/js/admin.refpro.js');
        }

        $id_customer = (int) Tools::getValue('id_customer');
        if (!$id_customer) {
            return '';
        }
        $customer = new Customer($id_customer);
        if (!Validate::isLoadedObject($customer)) {
            return '';
        }


        if (Tools::isSubmit('submitAddcustomer')) {
            Db::getInstance()->update(
                'refpro_customer',
                array(
                    'has_voucher' => Tools::getValue('has_voucher'),
                    'voucher_percent' => (float) Tools::getValue('voucher_percent'),
                    'voucher_amount' => (float) Tools::getValue('voucher_amount')
                ),
                '`id` = ' . $customer->id
            );

            Referral::updateCartRule(
                $customer,
                array(
                    'has_voucher' => Tools::getValue('has_voucher'),
                    'voucher_percent' => (float) Tools::getValue('voucher_percent'),
                    'voucher_amount' => (float) Tools::getValue('voucher_amount')
                )
            );
        }
    }

    public function hookDisplayAdminForm()
    {

        if ($this->context->controller->controller_name != 'AdminCustomers') {
            return '';
        }
        $id_customer = (int) Tools::getValue('id_customer');

        if (!$id_customer) {
            return '';
        }

        $user_data = Referral::getUserData($id_customer);

        // Check is customer is affiliate
        if (!$user_data || $user_data['is_sponsor'] != 1) {
            return '';
        }

        return ToolsModuleRP::fetchTemplate('admin/customer_form.tpl', array(
            'user_data' => $user_data,
            'get_def_currency' => $this->getDefCurrency()
        ));
    }

    /**
     * @deprecated
     * @param $key
     * @param $value
     * @param bool $encode
     */
    public function setSettings($key, $value, $encode = false)
    {
        Referral::setSettings($key, $value, $encode);
    }

    /**
     * @deprecated
     * @param $sid
     * @return bool|Customer
     */
    public function getSponsor($sid)
    {
        return Referral::getSponsor($sid);
    }

    /**
     * @deprecated
     * @param $content
     * @return bool|string
     */
    public function ajaxWrap($content)
    {
        return Referral::ajaxWrap($content);
    }

    /**
     * @deprecated
     * @param $str
     * @param bool $is_array
     * @return array|bool
     */
    public function jsonDecode($str, $is_array = false)
    {
        return Referral::jsonDecode($str, $is_array);
    }

    /**
     * @deprecated
     * @param $group_id
     * @param $level
     * @return float
     */
    public function getBonusRate($group_id, $level)
    {
        return Referral::getBonusRate($group_id, $level);
    }

    /**
     * @deprecated
     * @param $customer
     * @param $ref_id
     * @param $order
     * @param $level
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public function sendBonus($customer, $ref_id, $order, $level)
    {
        return Referral::sendBonus($customer, $ref_id, $order, $level);
    }

    /**
     * @deprecated
     * @param $id
     * @return array|bool|false|mysqli_result|null|PDOStatement|resource
     */
    public function getReferrals($id)
    {
        return Referral::getReferrals($id);
    }

    /**
     * @deprecated
     * @return array|bool|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public function getAllRefCustomers()
    {
        return Referral::getAllRefCustomers();
    }

    /**
     * @deprecated
     * @param $key
     * @param bool $decode
     * @param bool $is_array
     */
    public function getSettings($key, $decode = false, $is_array = false)
    {
        Referral::getSettings($key, $decode, $is_array);
    }

    /**
     * @deprecated
     * @param $id
     * @param $key
     * @param $value
     * @return bool
     */
    public function saveUserSettings($id, $key, $value)
    {
        Referral::saveUserSettings($id, $key, $value);
    }

    /**
     * @deprecated
     * @param $price
     * @return string
     */
    public function getDefCurrencyMail($price)
    {
        return Referral::getDefCurrencyMail($price);
    }

    /**
     * @deprecated
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    public function getCategories()
    {
        return Referral::getCategories();
    }

    /**
     * @deprecated
     * @param $ref_array
     * @param $level
     * @return bool
     */
    public function setProfit($ref_array, $level)
    {
        return Referral::setProfit($ref_array, $level);
    }

    /**
     * @return bool
     */
    public function getDefCurrency()
    {
        if (!Referral::checkActivationState(Referral::getSettings('activation_code'))) return false;
        $cur_id = Configuration::get('PS_CURRENCY_DEFAULT');

        $query_str = 'SELECT `iso_code` FROM ' . _DB_PREFIX_ . "currency WHERE id_currency = '$cur_id'";
        $cur_row = Db::getInstance()->getRow($query_str);
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $currency = new Currency($cur_id);
            $sign = $currency->getSign();
        } else {
            $query_str = 'SELECT `sign` FROM ' . _DB_PREFIX_ . "currency WHERE id_currency = '$cur_id'";
            $cur_row = Db::getInstance()->getRow($query_str);
            $sign = $cur_row['sign'];
        }
        $def_currency = 'defCurrency';
        return $this->{$def_currency} = $sign;
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        $this->context->smarty->assign(array(
            '_DATATABLE_JS_URI_' => $this->getPathUri() . 'views/js/datatables.min.js',
            '_DATATABLE_SEARCH_' => $this->l('Search in all fields:'),
            '_DATATABLE_sEmptyTable_' => $this->l('No data'),
            '_DATATABLE_sInfo_' => $this->l('Displayed from _START_ to _END_ out of _TOTAL_ lines'),
            '_DATATABLE_sInfoEmpty_' => $this->l('No results'),
            '_DATATABLE_sLengthMenu_' => $this->l('Display _MENU_ lines at the page'),
            '_DATATABLE_sZeroRecords_' => $this->l('Not found!'),
            '_DATATABLE_ALL_' => $this->l('ALL'),
        ));
        if (Tools::isSubmit('ajax')) {
            switch (Tools::getValue('action')) {
                case 'load_more_bonuses':
                    $next_page = Tools::getValue('next_page');
                    $id_customer = Tools::getValue('id_customer');
                    $bonuses = Referral::getBonusesByCustomer($id_customer, $next_page);
                    $this->context->smarty->assign(array(
                        'bonuses' => $bonuses,
                        'link' => $this->context->link
                    ));
                    die($this->display(__FILE__, 'rows_bonuses.tpl'));
                    break;
                case 'load_more_bonuses_order':
                    $next_page = Tools::getValue('next_page');
                    $id_order = Tools::getValue('id_order');
                    $bonuses = Referral::getBonusesByOrder($id_order, $next_page);
                    $this->context->smarty->assign(array(
                        'bonuses' => $bonuses,
                        'link' => $this->context->link
                    ));
                    die($this->display(__FILE__, 'rows_bonuses_order.tpl'));
                    break;
            }
        }

        if (version_compare(_PS_VERSION_, '1.6.0', '<'))
            $this->hookActionAdminBefore($params);
    }

    public function hookActionAdminBefore($params)
    {
        if (Tools::getValue('action') == 'addVoucher') {
            $this->hookActionAdminAddVoucherBefore($params);
        }
    }

    public function hookActionAdminAddVoucherBefore($params)
    {

        if ($id_cart_rule = Tools::getValue('id_cart_rule')) {
            $id_customer = Referral::getCustomerByCartRule($id_cart_rule);
            if ($id_customer) {
                $customer = new Customer($id_customer);
                if (Validate::isLoadedObject($customer)) {
                    self::_addCustToRef(Tools::getValue('id_customer'), $id_customer);
                }
            }
        }
    }

    public function hookActionCustomerFormBuilderModifier($params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];

        $customerId = $params['id'];

        $user_data = Referral::getUserData($customerId);

        // Check is customer is affiliate
        if (!$user_data || $user_data['is_sponsor'] != 1) {
            return '';
        }

        $formBuilder->add('has_voucher', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
            'label' => $this->getTranslator()->trans('To use invite-code/voucher to attract customers', [], 'Modules.Refpro'),
            'required' => false,
            'choices' => [
                '-- ' . $this->getTranslator()->trans('None', [], 'Modules.Refpro') . ' --' => 0,
                '%' => 'percent',
                $this->getDefCurrency() => 'amount',
                $this->l('Free shipping') => 'free_shipping'
            ],
        ]);
        $params['data']['has_voucher'] = $user_data['has_voucher'];

        $formBuilder->add('voucher_percent', \Symfony\Component\Form\Extension\Core\Type\NumberType::class, [
            'label' => $this->getTranslator()->trans('Voucher percent', [], 'Modules.Refpro'),
            'required' => false,
            'help' => $this->getTranslator()->trans('A positive real number! Separator is a point!', [], 'Modules.Refpro'),
            'label_attr' => array('class' => 'voucher_percent'),
            'attr'       => array('class' => 'voucher_percent'),
        ]);
        $params['data']['voucher_percent'] = $user_data['voucher_percent'];

        $formBuilder->add('voucher_amount', \Symfony\Component\Form\Extension\Core\Type\NumberType::class, [
            'label' => $this->getTranslator()->trans('Voucher amount', [], 'Modules.Refpro'),
            'required' => false,
            'help' => $this->getTranslator()->trans('A positive real number! Separator is a point!', [], 'Modules.Refpro'),
            'label_attr' => array('class' => 'voucher_amount'),
            'attr'       => array('class' => 'voucher_amount'),
        ]);
        $params['data']['voucher_amount'] = $user_data['voucher_amount'];

        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterUpdateCustomerFormHandler(array $params)
    {
        $this->updateCustomerVoucherStatus($params);
    }

    public function hookActionAfterCreateCustomerFormHandler(array $params)
    {
        $this->updateCustomerVoucherStatus($params);
    }

    public function hookDisplayAdminCustomers($params)
    {
        $id_customer = (int) $params['id_customer'];
        $bonuses = Referral::getBonusesByCustomer($id_customer);
        $total = Referral::getBonusesByCustomer($id_customer, 1, true);
        $pages = ceil($total / Referral::LIMIT_CUSTOMER_BONUSES);

        if (!count($bonuses)) {
            return '';
        }

        $this->context->smarty->assign(array(
            'bonuses' => $bonuses,
            'link' => $this->context->link,
            'total' => $total,
            'pages' => $pages,
            'id_customer' => $id_customer,
            'ps_15' => version_compare('1.6.0.0', _PS_VERSION_, '>')
                && version_compare('1.5.0.0', _PS_VERSION_, '<=')
        ));
        return $this->display(__FILE__, 'admin_customers.tpl');
    }

    public function hookDisplayAdminOrder($params)
    {
        $id_order = (int) $params['id_order'];
        $bonuses = Referral::getBonusesByOrder($id_order);
        $total = Referral::getBonusesByOrder($id_order, 1, true);
        $pages = ceil($total / Referral::LIMIT_CUSTOMER_BONUSES);

        if (!count($bonuses)) {
            return '';
        }

        $this->context->smarty->assign(array(
            'bonuses' => $bonuses,
            'link' => $this->context->link,
            'total' => $total,
            'pages' => $pages,
            'ps_15' => version_compare('1.6.0.0', _PS_VERSION_, '>')
                && version_compare('1.5.0.0', _PS_VERSION_, '<=')
        ));
        return $this->display(__FILE__, 'admin_order.tpl');
    }

    private function updateCustomerVoucherStatus(array $params)
    {
        $customerId = $params['id'];
        /** @var array $customerFormData */
        $customerFormData = $params['form_data'];
        $has_voucher = $customerFormData['has_voucher'];
        $voucher_percent = (float) $customerFormData['voucher_percent'];
        $voucher_amount = (float) $customerFormData['voucher_amount'];

        // implement review status saving here
        $customer = new Customer($customerId);
        if (!Validate::isLoadedObject($customer)) {
            return '';
        }

        Db::getInstance()->update(
            'refpro_customer',
            array(
                'has_voucher' => $has_voucher,
                'voucher_percent' => $voucher_percent,
                'voucher_amount' => $voucher_amount
            ),
            '`id` = ' . $customer->id
        );

        Referral::updateCartRule(
            $customer,
            array(
                'has_voucher' => $has_voucher,
                'voucher_percent' => $voucher_percent,
                'voucher_amount' => $voucher_amount
            )
        );
    }
	
	public function getContent()
    {
        Tools::redirectAdmin('index.php?controller=AdminRefPro&token='.Tools::getAdminTokenLite('AdminRefPro'));
    }
}
