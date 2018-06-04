<?php


class AdminExampleController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'example_data';
		$this->className = 'ExampleData';
		$this->lang = true;
		$this->deleted = false;
		$this->colorOnBackground = false;
		parent::__construct();
	}

	public function renderList()
	{




		$this->fields_list = array(
			'id_example_data' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 25
			),
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 'auto',
			),
		);

		
		$lists = parent::renderList();

		parent::initToolbar();

		return $lists;
	}


	public function renderForm()
	{
		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Example')
				
			),
			'input' => array(
				array(
					'type' => 'text',
					'lang' => true,
					'label' => $this->l('Name:'),
					'name' => 'name',
					'size' => 40
				)
				
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button'
			)
		);

		if (!($obj = $this->loadObject(true)))
			return;

		

		return parent::renderForm();
	}

	
}
