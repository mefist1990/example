<?php

if (!defined('_PS_VERSION_'))
	exit;


if (!defined('_MYSQL_ENGINE_'))
	define('_MYSQL_ENGINE_', 'MyISAM');


require_once(_PS_MODULE_DIR_.'example/models/ExampleData.php');

class Example extends Module
{
	private $errors = null;

	public function __construct()
	{

		$this->author = 'PrestaWeb.ru';

		$this->name = 'example';

		$this->tab = 'others';

		$this->version = '0.1.0';


		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
		$this->bootstrap = true;

		$this->need_instance = 0;

		$this->dependencies = array();

		$this->limited_countries = array();

		parent::__construct();

		$this->displayName = $this->l('Тестовый модуль');

		$this->description = $this->l('Сделать модуль с админ-контроллером, моделью и контролером на фронт-енде');

		$this->confirmUninstall = $this->l('Вы действительно хотите удалить этот модуль??');

		if ($this->active && Configuration::get('EXAMPLE_CONF') == '')
			$this->warning = $this->l('Вы должны настроить свой модуль');

		$this->errors = array();
	}

	public function install()
	{
		// Добавляю новые таблицы
		$sql = array();


$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'example_data` (
	`id_example_data` int(10) NOT NULL AUTO_INCREMENT,
	`lorem` varchar(50) NOT NULL,
	PRIMARY KEY (`id_example_data`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'example_data_lang` (
	`id_example_data` int(10) NOT NULL AUTO_INCREMENT,
	`id_lang` int(10) NOT NULL,
	`name` varchar(64) NOT NULL,
	UNIQUE KEY `example_data_lang_index` (`id_example_data`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
		
		foreach ($sql as $s)
			if (!Db::getInstance()->execute($s))
				return false;

		// Добавляю табы в админку
		$parent_tab = new Tab();
		// Определение языка для перевода
		$parent_tab->name[$this->context->language->id] = $this->l('1 lvl TAB');
		$parent_tab->class_name = 'AdminMainExample';
		$parent_tab->id_parent = 0; // Главная вкладка
		$parent_tab->module = $this->name;
		$parent_tab->add();


		$tab = new Tab();
		// Need a foreach for the language
		$tab->name[$this->context->language->id] = $this->l('2  lvl TAB');
		$tab->class_name = 'AdminExample';
		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		$tab->add();

		//Init
		Configuration::updateValue('EXAMPLE_CONF', '');

		return parent::install() && $this->registerHook('actionObjectExampleDataAddAfter') && $this->registerHook('footer');
	}

	public function uninstall()
	{
		// Удаляю таблицы
		$sql = array();
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'example_data`;';
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'example_data_lang`;';
		foreach ($sql as $s)
			if (!Db::getInstance()->execute($s))
				return false;

		Configuration::deleteByName('EXAMPLE_CONF');

		// Удаляю табы
		$moduleTabs = Tab::getCollectionFromModule($this->name);
		if (!empty($moduleTabs)) {
			foreach ($moduleTabs as $moduleTab) {
				$moduleTab->delete();
			}
		}

		// Удаляю модуль
		if (!parent::uninstall())
			return false;

		return true;
	}


	public function hookActionObjectExampleDataAddAfter($params)
	{
		
		$params = $params;

		return true;
	}

	 public function hookFooter()
  {

        $sql_example = 'SELECT name FROM '._DB_PREFIX_.' example_data_lang';
      	$res_example = Db::getInstance()->executeS($sql_example);
  		$this->context->smarty->assign('res_example', $res_example);
  		return $this->display(__FILE__, 'example.tpl');
  }
}
