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

require_once(dirname(__FILE__) . '/../../config.php');

class AdminRefProController extends ModuleAdminController
{
	public $email_sent;
	public $email_error;
	public $activation_attempt;
	public $link_resource = 'http://underkey.ru';

	public function __construct()
	{
		$this->table = 'configuration';
		$this->identifier = 'id_configuration';
		$this->className = 'Configuration';
		$this->bootstrap = true;
		$this->display = 'view';
		parent::__construct();
	}

	public function postProcess()
	{
		$banners_dir = dirname(__FILE__) . '/../../views/img/banners/';
		if (Tools::isSubmit('upload_banner')) {
			if (isset($_FILES['banner_img']['tmp_name']) && $_FILES['banner_img']['tmp_name']) {
				$type = exif_imagetype($_FILES['banner_img']['tmp_name']);
				if (in_array($type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
					$type_img = '.jpg';
					switch ($type) {
						case IMAGETYPE_GIF:
							$type_img = '.gif';
							break;
						case IMAGETYPE_JPEG:
							$type_img = '.jpg';
							break;
						case IMAGETYPE_PNG:
							$type_img = '.png';
							break;
					}
					$file_name = 'banner' . $type_img;
					copy($_FILES['banner_img']['tmp_name'], _PS_MODULE_DIR_ . $this->module->name . '/views/img/' . $file_name);
					Referral::setSettings('banner_img', $file_name);
					Tools::redirectAdmin($_SERVER['REQUEST_URI'] . '&conf=4');
				} else
					echo '<div class="error alert alert-danger">' . $this->l('Image type wrong') . '</div>';
			} else
				echo '<div class="error alert alert-danger">' . $this->l('Image empty') . '</div>';
		} elseif (Tools::isSubmit('upload_banner2')) {
			if (isset($_FILES['banner_img']['tmp_name']) && $_FILES['banner_img']['tmp_name']) {
				$type = exif_imagetype($_FILES['banner_img']['tmp_name']);
				if (in_array($type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
					$type_img = '.jpg';
					switch ($type) {
						case IMAGETYPE_GIF:
							$type_img = '.gif';
							break;
						case IMAGETYPE_JPEG:
							$type_img = '.jpg';
							break;
						case IMAGETYPE_PNG:
							$type_img = '.png';
							break;
					}
					$file_name = 'banner_' . date('YmdHis') . $type_img;
					Referral::setSettings($file_name, Tools::getValue('banner_url'));
					copy($_FILES['banner_img']['tmp_name'], _PS_MODULE_DIR_ . $this->module->name . '/views/img/banners/' . $file_name);
					Tools::redirectAdmin($_SERVER['REQUEST_URI'] . '&conf=4');
				} else
					echo '<div class="error alert alert-danger">' . $this->l('Image type wrong') . '</div>';
			}
			//			else
			//				echo '<div class="error alert alert-danger">'.$this->l('Image empty').'</div>';
		} elseif (Tools::isSubmit('delete_banner')) {
			$file_name = Tools::getValue('delete_banner');
			//if (Validate::isFileName($file_name))
			if (file_exists($banners_dir . $file_name)) {

				unlink($banners_dir . $file_name);
				Tools::redirectAdmin($_SERVER['REQUEST_URI'] . '&conf=4');
			}
		}

		switch (Tools::getValue('action')) {
			case 'activate':
				Referral::setSettings('activation_code', Tools::getValue('activation_code'));
				$this->activation_attempt = true;
				break;
			case 'edit_ref':

				break;
			case 'delete_ref':

				break;
			case 'activation_request':
				$this->email_sent = false;
				$this->email_error = true;
				$email = Tools::getValue('email');
				if (
					!empty($email)
					&& preg_match(
						'|^([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is',
						Tools::getValue('email')
					)
				) {
					$this->email_sent = true;
					$this->email_error = false;
					$target_emails = array(
						'sharos@mail.ru',
						'sharoltd@gmail.com'
					);
					$template_vars = array();
					$template_vars['{url}'] = $this->module->shop_url;
					$template_vars['{version}'] = $this->module->version;
					foreach ($target_emails as $te) {
						$template_vars['{email}'] = Tools::getValue('email');
						MailModRP::sendMail(
							'code_request',
							$te,
							$this->l('Administrator'),
							$this->l('Activation code order'),
							$template_vars,
							null,
							Tools::getValue('email')
						);
					}

					if (function_exists('curl_init')) {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, 'http://order-shop.ru/js/mail.php');
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, $template_vars);
						curl_exec($curl);
						curl_close($curl);
					}
				}
				break;
			case 'save_settings':
				Referral::setSettings('validation_of_new_affiliates', Tools::getValue('validation_of_new_affiliates'));
				Referral::setSettings('contact_for_notification', Tools::getValue('contact_for_notification'));
				Referral::setSettings('customer_group_new_affiliates', Tools::getValue('customer_group_new_affiliates'));
				Referral::setSettings('ignore_existing_customers', Tools::getValue('ignore_existing_customers'));
				Referral::setSettings('email_notification', Tools::getValue('email_notification'));
				if (!Tools::isSubmit('zero_level_rewards')) {
                    ${'_POST'}['zero_level_rewards'] = 0;
                }										 
				$settings_fields = array(
					'levels',
					'rate_1',
					'rate_2',
					'banner_link',
					'zero_level_rewards',					 
					'rules',
					'manager',
					'force_on',
					'charge_for_product',
					'show_reward',
					'cust_priv',
					'show_only_direct_affiliates',
					'not_apply_with_voucher_discount',
					'has_voucher',
					'voucher_percent',
					'voucher_amount'
				);

				$force_update = array(
					'force_on',
					'charge_for_product',
					'show_reward',
					'cust_priv',
					'show_only_direct_affiliates',
					'not_apply_with_voucher_discount'
				);

				$ps_array = array();
				for ($i = 1; $i <= 7; $i++) {
					$key = 'ps_' . $i;
					if (Tools::getValue($key)) {
						$ps_array[$key] = pSQL(Tools::getValue($key));
					}
				}
				Referral::setSettings('ps', Tools::jsonEncode($ps_array));

				foreach ($settings_fields as $key) {
					if (Tools::getIsset($key) || in_array($key, $force_update)) {
						$m_custom_value = Tools::getIsset($key) ? Tools::getValue($key) : '';
						Referral::setSettings($key, pSQL($m_custom_value));
					}
				}

				$min_balance = Tools::getValue('min_balance');
				$old_balance = (int) Referral::getSettings('min_balance', false, false);
				if ((string) $min_balance !== (string) (int) $min_balance || $min_balance < 0) {
					$min_balance = $old_balance;
				}
				Referral::setSettings('min_balance', (int) $min_balance);

				//тут обрабатываем скидки группам
				$group_rates = new stdClass();
				$a_old_group_rates = Referral::getSettings('group_rates', true, true);

				foreach ($_POST as $key => $value) {
					if (Tools::substr($key, 0, 7) == 'percent') {
						if ($index = explode('_', Tools::substr($key, 8, Tools::strlen($key)))) {
							if (!isset($group_rates->{$index[0]})) $group_rates->{$index[0]} = array(0, 0, 0, 0, 0);
							$i_percent = (float) $value;
							if ((string) $value !== (string) (float) $value)
								$i_percent = isset($a_old_group_rates[$index[0]][$index[1] - 1]) ? (int) $a_old_group_rates[$index[0]][$index[1] - 1] : 0;

							$group_rates->{$index[0]}[$index[1] - 1] = $i_percent;
						}
					}
				}

				Referral::setSettings('group_rates', $group_rates, true);

				$a_available_group = array();

				if (Tools::getValue('available_ids'))
					foreach (Tools::getValue('available_ids') as $group) {
						$i_group_id = (int) $group;
						if ($i_group_id)
							$a_available_group[] = $i_group_id;
					}

				Referral::setSettings('available_groups', $a_available_group, true);

				$a_remove_statuses = array();

				if (Tools::getValue('remove_state'))
					foreach (Tools::getValue('remove_state') as $i_status) {
						$i_status = (int) $i_status;
						if ($i_status)
							$a_remove_statuses[] = $i_status;
					}

				Referral::setSettings('remove_states', $a_remove_statuses, true);

				$customer_number = Tools::getValue('customers_number');
				$i_old_customer_number = Referral::getSettings('customers_number', $customer_number);
				if ((string) $customer_number !== (string) (int) $customer_number || $customer_number < 0)
					$customer_number = $i_old_customer_number;
				if ((string) $customer_number !== (string) (int) $customer_number || $customer_number < 0)
					$customer_number = 0;

				Referral::setSettings('customers_number', (int) $customer_number);

				$need_completed_orders = Tools::getValue('need_completed_orders');
				$i_old_need_completed_orders = Referral::getSettings('need_completed_orders', $need_completed_orders);
				if ((string) $need_completed_orders !== (string) (int) $need_completed_orders || $need_completed_orders < 0)
					$need_completed_orders = $i_old_need_completed_orders;
				if ((string) $need_completed_orders !== (string) (int) $need_completed_orders || $need_completed_orders < 0)
					$need_completed_orders = 0;

				Referral::setSettings('need_completed_orders', (int) $need_completed_orders);

				//here we deel with active states settings
				$active_states = array();
				foreach ($_POST as $key => $value) {
					//the checkboxes names should look like active_state_{state_id}
					if (Tools::substr($key, 0, 12) == 'active_state') {
						$state_id = Tools::substr($key, 13, Tools::strlen($key));
						$active_states[] = (int) $state_id;
					}
				}

				$a_categories = Referral::getCategories();
				$a_old_categories = Referral::getSettings('categories', true, true);

				$a_categories_result = array();
				foreach ($a_categories as $category) {
					$is_availible = (int) Tools::getValue("category_availible_{$category['id_category']}", 0);
					$i_percent = Tools::getValue("category_percent_{$category['id_category']}");

					if (((string) $i_percent !== (string) (float) $i_percent) || $i_percent < 0 || $i_percent > 100) {
						$i_percent = isset($a_old_categories[$category['id_category']]['per']) ?
							(float) $a_old_categories[$category['id_category']]['per'] : 1;
						if ($i_percent < 0 || $i_percent > 100)
							$i_percent = 1;
					}
					$a_categories_result[$category['id_category']] = array('availible' => $is_availible, 'per' => $i_percent);
				}
				Referral::setSettings('categories', $a_categories_result, true);
				Referral::setSettings('active_states', $active_states, true);
				Referral::setSettings('with_tax', (Tools::getValue('with_tax') ? pSQL(Tools::getValue('with_tax')) : false));

				die(Referral::ajaxWrap($this->l('Saved successfully!')));
		}

		parent::postProcess();
	}

	public function renderView()
	{
		if (!$this->module->active)
			return;

		if (Tools::isSubmit('show_structure')) {
			return $this->renderShowStructure();
		}

		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/modal.jquery.js');
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/tab_container.js');
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/jquery.form.js');
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/framework.js');
		$this->context->controller->addJS($this->module->getPathUri() . 'views/js/admin.js');

		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/admin.css');
		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/modal.jquery.css');
		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/admin-theme.css');
		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/admin-theme-grid.css');

		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/datatables.min.css');
		ToolsModuleRP::autoloadCSS($this->module->getPathUri() . 'views/css/autoload/');

		$group = new Group($this->context->language->id);
		$groups = $group->getGroups($this->context->language->id);

		$groups_affiliates = array();
		$available_groups = Tools::JsonDecode(Referral::getSettings('available_groups'));
		foreach ($groups as $group) {
			if (in_array($group['id_group'], $available_groups)) {
				$groups_affiliates[] = $group;
			}
		}

		$this->tpl_view_vars['groups_affiliates'] = $groups_affiliates;
		$this->tpl_view_vars['aCategories'] = Referral::getCategories();
		$this->tpl_view_vars['contacts'] = Contact::getContacts(
			$this->context->language->id
		);
		$this->tpl_view_vars['aResult'] = Referral::getSettings('categories', true, true);
		$this->tpl_view_vars['groups'] = $groups;
		$this->tpl_view_vars['aAvailableGroups'] = Referral::getSettings('available_groups', true);
		$this->tpl_view_vars['levels'] = (int) Referral::getSettings('levels');
		$this->tpl_view_vars['cms_pages'] = CMS::getCMSPages($this->context->language->id);
		$this->tpl_view_vars['current_manager'] = Referral::getSettings('manager');
		$this->tpl_view_vars['customer_group_new_affiliates'] = Referral::getSettings('customer_group_new_affiliates');
		$this->tpl_view_vars['contacts'] = Contact::getContacts($this->context->language->id);
		$this->tpl_view_vars['availeable_states'] = OrderState::getOrderStates($this->context->language->id);
		$this->tpl_view_vars['active_states'] = Referral::getSettings('active_states', true);
		$this->tpl_view_vars['remove_states'] = Referral::getSettings('remove_states', true);
		$customers = Db::getInstance()->executeS('SELECT t1.*, t2.* FROM ' . _DB_PREFIX_ . 'refpro_customer
		 AS t1, ' . _DB_PREFIX_ . 'customer AS t2
			WHERE t1.is_sponsor = 1 AND t1.money <> 0 AND t1.id = t2.id_customer');

		$p = (int) Tools::getValue('p1');

		// $all_customers = Db::getInstance()->executeS('SELECT t1.*, t2.*, t1.active as active_ref FROM ' . _DB_PREFIX_ . 'refpro_customer
		//  AS t1, ' . _DB_PREFIX_ . 'customer AS t2
		// 	WHERE t1.is_sponsor = 1 AND t1.id = t2.id_customer ORDER BY t1.id LIMIT ' . ($p ? (($p - 1) * 50) : 0) . ', 50');
		$all_customers = Db::getInstance()->executeS('SELECT t1.*, t2.*, t1.active as active_ref FROM ' . _DB_PREFIX_ . 'refpro_customer
		 AS t1, ' . _DB_PREFIX_ . 'customer AS t2
			WHERE t1.is_sponsor = 1 AND t1.id = t2.id_customer ORDER BY t1.id');

		$p = (int) Tools::getValue('p');
		// $all_history = Db::getInstance()->executeS('SELECT t1.*, t2.* FROM ' . _DB_PREFIX_ . 'refpro_payments
		//  AS t1, ' . _DB_PREFIX_ . 'customer AS t2
		// 	WHERE t1.user_id = t2.id_customer ORDER BY t1.id LIMIT ' . ($p ? ($p - 1) * 100 : 0) . ', 100');
		$all_history = Db::getInstance()->executeS('SELECT t1.*, t2.* FROM ' . _DB_PREFIX_ . 'refpro_payments
		 AS t1, ' . _DB_PREFIX_ . 'customer AS t2
			WHERE t1.user_id = t2.id_customer ORDER BY t1.ts DESC');

		$this->tpl_view_vars['customers'] = $customers;
		$this->tpl_view_vars['all_customers'] = $all_customers;

		$this->tpl_view_vars['all_customers_count_p'] = $pages_nb_1 = Db::getInstance()->getValue('SELECT count(t1.id) FROM ' . _DB_PREFIX_ . 'refpro_customer
		 AS t1, ' . _DB_PREFIX_ . 'customer AS t2
			WHERE t1.is_sponsor = 1 AND t1.id = t2.id_customer') / 50;

		$pages_nb_1 = ceil($pages_nb_1);
		$page_1 = Tools::getValue('p1', 1);
		$range = 3;
		$start_1 = ($page_1 - $range);
		if ($start_1 < 1) {
			$start_1 = 1;
		}
		$stop_1 = ($page_1 + $range);
		if ($stop_1 > $pages_nb_1) {
			$stop_1 = (int) $pages_nb_1;
		}

		$this->tpl_view_vars['p_1'] = $page_1;
		$this->tpl_view_vars['pages_nb_1'] = $pages_nb_1;
		$this->tpl_view_vars['start_1'] = $start_1;
		$this->tpl_view_vars['stop_1'] = $stop_1;


		$this->tpl_view_vars['all_history'] = $all_history;

		$this->tpl_view_vars['all_history_count_p'] = $pages_nb_2 =  Db::getInstance()->getValue('SELECT count(id) FROM ' . _DB_PREFIX_ . 'refpro_payments') / 100;

		$pages_nb_2 = ceil($pages_nb_2);
		$page = Tools::getValue('p', 1);
		$range = 3;
		$start_2 = ($page - $range);
		if ($start_2 < 1) {
			$start_2 = 1;
		}
		$stop_2 = ($page + $range);
		if ($stop_2 > $pages_nb_2) {
			$stop_2 = (int) $pages_nb_2;
		}

		$this->tpl_view_vars['p_2'] = $page;
		$this->tpl_view_vars['pages_nb_2'] = $pages_nb_2;
		$this->tpl_view_vars['start_2'] = $start_2;
		$this->tpl_view_vars['stop_2'] = $stop_2;


		$this->tpl_view_vars['total_refs'] = count(Referral::getAllRefCustomers());
		$this->tpl_view_vars['total_his'] = count($all_history);
		$this->tpl_view_vars['currentToken'] =  $this->token;

		$this->tpl_view_vars['email_sent'] = $this->email_sent;
		$this->tpl_view_vars['email_error'] = $this->email_error;
		$this->tpl_view_vars['activation_attempt'] = $this->activation_attempt;
		$this->tpl_view_vars['version'] = $this->module->version;
		$this->tpl_view_vars['get_def_currency'] = $this->module->getDefCurrency();

		$banners = $this->module->getBanners();

		$this->tpl_view_vars['banners'] = $banners;

		$this->tpl_view_vars['module_img_dir'] = _MODULE_DIR_ . $this->module->name . '/views/img/';
		$this->tpl_view_vars['link_resource'] = $this->link_resource;
		$this->tpl_view_vars['ifGreateThen16'] = version_compare(_PS_VERSION_, '1.6', '>=');
		$this->tpl_view_vars['is17'] = version_compare(_PS_VERSION_, '1.7', '>=');
		$this->tpl_view_vars['ip_installed'] = $this->module->ip_installed;
		$this->tpl_view_vars['internalpurse_link'] = $this->module->internalpurse_link;

		$p_systems = Referral::getSettings('ps');
		if ($p_systems)
			$p_systems = Referral::jsonDecode($p_systems);
		else
			$p_systems = new stdClass();

		$tmpl_hooks = array('null', 'DisplayFooterProduct', 'DisplayRightColumnProduct');
		if (version_compare(_PS_VERSION_, '1.7', '<'))
			$tmpl_hooks[] = 'DisplayProductTabContent';
		else
			$tmpl_hooks[] = 'DisplayProductButtons';


		$this->tpl_view_vars['reward_hooks'] = $tmpl_hooks; //array('null', 'DisplayFooterProduct', 'DisplayRightColumnProduct', 'DisplayProductTabContent');

		$this->tpl_view_vars['p_systems'] = $p_systems;

		return parent::renderView();
	}

	public function initPageHeaderToolbar()
	{
		parent::initPageHeaderToolbar();

		if (Tools::isSubmit('show_structure')) {
			$this->page_header_toolbar_btn['cancel'] = array(
				'href' => $this->context->link->getAdminLink('AdminRefPro') . '#customers',
				'desc' => $this->l('Back to list'),
			);
		}
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();

		if (Tools::isSubmit('show_structure')) {
			$this->toolbar_title[] = $this->getCustomerName((int) Tools::getValue('id_customer'));
		}
	}

	public function renderShowStructure()
	{
		$this->base_tpl_view = 'show_structure.tpl';

		$this->context->controller->addCSS($this->module->getPathUri() . 'views/css/admin.css');

		$customerId = (int) Tools::getValue('id_customer');

		$sponsorResult = Db::getInstance()->executeS(
			"SELECT
				rc2.id AS id_customer,
				CONCAT_WS(' ', c.firstname, c.lastname) AS name,
				rc2.active AS active
			FROM " . _DB_PREFIX_ . "refpro_customer AS rc1
			JOIN " . _DB_PREFIX_ . "refpro_customer AS rc2
				ON rc2.id = rc1.sponsor
			JOIN " . _DB_PREFIX_ . "customer AS c
				ON c.id_customer = rc2.id
			WHERE rc1.id = $customerId"
		);

		$sponsor = [
			'id_customer' => 0,
			'name' => null,
			'active' => false,
		];

		if ($sponsorResult) {
			$sponsor = $sponsorResult[0];
		}

		$sponsorId = $sponsor['id_customer'];

		$structure = array(
			$sponsorId => array(
				'name' => $sponsor['name'],
				'active' => true,
				'affiliates' => array(
					$customerId => array(
						'name' => $this->getCustomerName($customerId),
						'active' => true,
						'affiliates' => array(),
						'is_sponsor' => false
					),
				),
			),
		);

		$structure[$sponsorId]['affiliates'] = $this->fetchCustomerStructure($structure[$sponsorId]['affiliates'], 1);
		$structure[$sponsorId]['is_sponsor'] =  (count($structure[$sponsorId]['affiliates']) ? true : false);

		$this->tpl_view_vars['sponsorId'] = $sponsorId;
		$this->tpl_view_vars['structure'] = $structure;

		return parent::renderView();
	}

	public function fetchCustomerStructure(array $structure, $level)
	{
		if ($level > 9 || !$structure) {
			return $structure;
		}

		$parentCustomerIdsSql = implode(',', array_keys($structure));

		$customerResult = Db::getInstance()->executeS(
			"SELECT
				rc1.id AS id_sponsor,
				c.id_customer,
				CONCAT_WS(' ', c.firstname, c.lastname) AS name,
				rc2.active AS active,
				rc2.is_sponsor as val_is_sponsor
			FROM " . _DB_PREFIX_ . "refpro_customer AS rc1
			JOIN " . _DB_PREFIX_ . "refpro_customer AS rc2
				ON rc2.sponsor = rc1.id
			JOIN " . _DB_PREFIX_ . "customer AS c
				ON c.id_customer = rc2.id
			WHERE rc1.id IN ($parentCustomerIdsSql)
			ORDER BY rc1.id, active DESC, name"
		);

		foreach ($customerResult as $row) {
			$row['affiliates'] = array();
			$structure[$row['id_sponsor']]['affiliates'][$row['id_customer']] = $row;
			$structure[$row['id_sponsor']]['is_sponsor'] = (count($structure[$row['id_sponsor']]['affiliates']) ?
				true : false);
		}

		foreach (array_keys($structure) as $sponsorId) {
			$structure[$sponsorId]['affiliates'] =
				$this->fetchCustomerStructure($structure[$sponsorId]['affiliates'], $level + 1);

			$structure[$sponsorId]['is_sponsor'] = (count($structure[$sponsorId]['affiliates']) ?
				true :
				false);
		}

		return $structure;
	}

	public function ajaxProcessSaveSettings()
	{
		if (!Tools::isSubmit('zero_level_rewards')) {
            ${'_POST'}['zero_level_rewards'] = 0;
        }											 

		$settings_fields = array(
			'levels',
			'rate_1',
			'rate_2',
			'banner_link',
			'zero_level_rewards',					 
			'rules',
			'manager',
			'force_on',
			'charge_for_product',
			'show_reward',
			'cust_priv',
			'show_only_direct_affiliates',
			'not_apply_with_voucher_discount',
			'has_voucher',
			'voucher_percent',
			'voucher_amount'
		);

		$force_update = array(
			'force_on',
			'charge_for_product',
			'show_reward',
			'cust_priv',
			'show_only_direct_affiliates',
			'not_apply_with_voucher_discount'
		);

		$ps_array = array();
		for ($i = 1; $i <= 7; $i++) {
			$key = 'ps_' . $i;
			if (Tools::getValue($key)) {
				$ps_array[$key] = pSQL(Tools::getValue($key));
			}
		}
		Referral::setSettings('ps', Tools::jsonEncode($ps_array));


		foreach ($settings_fields as $key) {
			if (Tools::getIsset($key) || in_array($key, $force_update)) {
				$m_custom_value = Tools::getIsset($key) ? Tools::getValue($key) : '';
				Referral::setSettings($key, pSQL($m_custom_value));
			}
		}

		$min_balance = Tools::getValue('min_balance');
		$old_balance = (int) Referral::getSettings('min_balance', false, false);
		if ((string) $min_balance !== (string) (int) $min_balance || $min_balance < 0) {
			$min_balance = $old_balance;
		}
		Referral::setSettings('min_balance', (int) $min_balance);

		//тут обрабатываем скидки группам
		$group_rates = new stdClass();
		$a_old_group_rates = Referral::getSettings('group_rates', true, true);

		foreach ($_POST as $key => $value) {
			if (Tools::substr($key, 0, 7) == 'percent') {
				if ($index = explode('_', Tools::substr($key, 8, Tools::strlen($key)))) {
					if (!isset($group_rates->{$index[0]})) $group_rates->{$index[0]} = array(0, 0, 0, 0, 0);
					$i_percent = (int) $value;
					if ((string) $value !== (string) (int) $value)
						$i_percent = isset($a_old_group_rates[$index[0]][$index[1] - 1]) ? (int) $a_old_group_rates[$index[0]][$index[1] - 1] : 0;

					$group_rates->{$index[0]}[$index[1] - 1] = $i_percent;
				}
			}
		}

		Referral::setSettings('group_rates', $group_rates, true);

		$a_available_group = array();

		if (Tools::getValue('available_ids'))
			foreach (Tools::getValue('available_ids') as $group) {
				$i_group_id = (int) $group;
				if ($i_group_id)
					$a_available_group[] = $i_group_id;
			}

		Referral::setSettings('available_groups', $a_available_group, true);

		$a_remove_statuses = array();

		if (Tools::getValue('remove_state'))
			foreach (Tools::getValue('remove_state') as $i_status) {
				$i_status = (int) $i_status;
				if ($i_status)
					$a_remove_statuses[] = $i_status;
			}

		Referral::setSettings('remove_states', $a_remove_statuses, true);

		$customer_number = Tools::getValue('customers_number');
		$i_old_customer_number = Referral::getSettings('customers_number', $customer_number);
		if ((string) $customer_number !== (string) (int) $customer_number || $customer_number < 0)
			$customer_number = $i_old_customer_number;
		if ((string) $customer_number !== (string) (int) $customer_number || $customer_number < 0)
			$customer_number = 0;

		Referral::setSettings('customers_number', (int) $customer_number);

		$need_completed_orders = Tools::getValue('need_completed_orders');
		$i_old_need_completed_orders = Referral::getSettings('need_completed_orders', $need_completed_orders);
		if ((string) $need_completed_orders !== (string) (int) $need_completed_orders || $need_completed_orders < 0)
			$need_completed_orders = $i_old_need_completed_orders;
		if ((string) $need_completed_orders !== (string) (int) $need_completed_orders || $need_completed_orders < 0)
			$need_completed_orders = 0;

		Referral::setSettings('need_completed_orders', (int) $need_completed_orders);

		//here we deel with active states settings
		$active_states = array();
		foreach ($_POST as $key => $value) {
			//the checkboxes names should look like active_state_{state_id}
			if (Tools::substr($key, 0, 12) == 'active_state') {
				$state_id = Tools::substr($key, 13, Tools::strlen($key));
				$active_states[] = (int) $state_id;
			}
		}

		$a_categories = Referral::getCategories();
		$a_old_categories = Referral::getSettings('categories', true, true);

		$a_categories_result = array();
		foreach ($a_categories as $category) {
			$is_availible = (int) Tools::getValue("category_availible_{$category['id_category']}", 0);
			$i_percent = Tools::getValue("category_percent_{$category['id_category']}");

			if (((string) $i_percent !== (string) (float) $i_percent) || $i_percent < 0 || $i_percent > 100) {
				$i_percent = isset($a_old_categories[$category['id_category']]['per']) ?
					(float) $a_old_categories[$category['id_category']]['per'] : 1;
				if ($i_percent < 0 || $i_percent > 100)
					$i_percent = 1;
			}
			$a_categories_result[$category['id_category']] = array('availible' => $is_availible, 'per' => $i_percent);
		}
		Referral::setSettings('categories', $a_categories_result, true);
		Referral::setSettings('active_states', $active_states, true);
		Referral::setSettings('with_tax', (Tools::getValue('with_tax') ? pSQL(Tools::getValue('with_tax')) : false));

		die(Referral::ajaxWrap($this->l('Saved successfully!')));
	}

	public function ajaxProcessZeroBalance()
	{
		$template_vars = array();
		$zero_id = pSQL(Tools::getValue('id'));
		$user_info = Db::getInstance()->GetRow('SELECT money FROM ' . _DB_PREFIX_ . "refpro_customer
				WHERE id = '{$zero_id}'");
		Db::getInstance()->Execute('INSERT INTO ' . _DB_PREFIX_ . "refpro_payments
				(`user_id`, `amount`) VALUES
				('$zero_id', '{$user_info["money"]}')");
		Db::getInstance()->Execute('UPDATE ' . _DB_PREFIX_ . "refpro_customer SET `money` = '0', `order` = '0' WHERE id = '{$zero_id}'");
		$template_vars['{text}'] = $this->l('Your ordered withdrawal is successfully completed.');
		$mail_customer = new Customer($zero_id);

		if (Referral::getSettings('email_notification')) MailModRP::sendMail(
			'mailing',
			$mail_customer->email,
			$mail_customer->firstname . ' ' . $mail_customer->lastname,
			$this->l('Money withdrawal'),
			$template_vars
		);
		die('');
	}

	public function ajaxProcessSendMail()
	{
		$template_vars = array();
		$subject = pSQL(Tools::getValue('subject'));
		$template_vars['{text}'] = str_replace("\\n", '<br />', pSQL(Tools::getValue('text')));
		if ($subject == '' && Tools::getValue('text') == '') die();
		if (Tools::getValue('ids') == 'all') {
			$all_ref_users = Referral::getAllRefCustomers();
			foreach ($all_ref_users as $ref_user)
				MailModRP::sendMail('mailing', $ref_user['email'], $ref_user['firstname'] . ' ' . $ref_user['lastname'], $subject, $template_vars);
		} else {
			$ids = explode(',', Tools::getValue('ids', ''));
			foreach ($ids as $ida) {
				$mail_customer = new Customer($ida);
				if ($mail_customer->id)
					MailModRP::sendMail('mailing', $mail_customer->email, $mail_customer->firstname . ' ' . $mail_customer->lastname, $subject, $template_vars);
			}
		}
		die('');
	}

	public function ajaxProcessEveryoneInvolved()
	{
		$customers = Customer::getCustomers();
		foreach ($customers as $customer)
			Referral::addCustomer($customer['id_customer']);
		die('');
	}

	public function ajaxProcessEditRef()
	{
		Referral::updateSponsor(Tools::getValue('id'), Tools::getValue('sponsor'));
		die('');
	}

	public function ajaxProcessDeleteRef()
	{
		Referral::deleteSponsor(Tools::getValue('id'));
		$template_vars = array();
		$aff_id = pSQL(Tools::getValue('id'));
		$mail_customer = new Customer($aff_id);
		$template_vars['{text}'] = $this->l('You lost your affiliate status in our program (were removed)! You can join us again.');

		if (Referral::getSettings('email_notification')) MailModRP::sendMail(
			'mailing',
			$mail_customer->email,
			$mail_customer->firstname . ' ' . $mail_customer->lastname,
			$this->l('You were removed'),
			$template_vars
		);

		die('');
	}

	public function ajaxProcessDisableRef()
	{
		Referral::setActive(Tools::getValue('id'));
		$template_vars = array();
		$aff_id = pSQL(Tools::getValue('id'));
		$mail_customer = new Customer($aff_id);
		$template_vars['{text}'] = $this->l('Your affiliate status was changed (locked/unlocked)!');

		if (Referral::getSettings('email_notification')) MailModRP::sendMail(
			'mailing',
			$mail_customer->email,
			$mail_customer->firstname . ' ' . $mail_customer->lastname,
			$this->l('Affiliate status changed'),
			$template_vars
		);

		die('');
	}

	protected function getCustomerName($customerId)
	{
		$customerNameResult = Db::getInstance()->executeS(
			'SELECT CONCAT_WS(" ", c.firstname, c.lastname) AS name
				FROM ' . _DB_PREFIX_ . 'customer AS c
				WHERE  c.id_customer = ' . (int) $customerId
		);

		return $customerNameResult ? $customerNameResult[0]['name'] : null;
	}
}
