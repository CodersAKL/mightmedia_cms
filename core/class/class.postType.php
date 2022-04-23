<?php

class PostType {

	public $params = [];

	public $posTypes = [];


	public function __construct($args = [])
	{
		// $this–>args = $args;
		$this->defaultArgs = [
			'name'  		=>	'custom',
			'label'  		=>	'Custom',
			'label_list'  	=>	'Custom list',
			'label_create'	=>	'Custom create',
			'icon'  		=>	'extension',
			'fields'    	=>	[
				'title'     => true,
				'editor'    => true,
				'excerpt'	=> false,
				'media'     => [
					'attachments'   => false,
					'photo'         => false,
					'gallery'       => false,
				],
				// add defaults params
			],
		];
	}

	public function setArgs(array $params = [])
	{
		$this->params = array_merge($this->defaultArgs, $params);
		$this->posTypes[$this->defaultArgs['name']] = array_merge($this->defaultArgs, $params);
	}

	public function getArgs()
	{
		return $this->params;
	}

	public function getPostType($name = [])
	{
		return ! empty($this->posTypes) ? $this->posTypes[$name] : $this->posTypes;
	}

	public function createPostType()
	{
		

		// create db stuff
		// $this->createPostQuery($params);

		// register data
		// addAction('adminCustomData', [$this, 'adminCustomData']);
		// register admin pages
		addAction('adminRoutes', [$this, 'adminCustomRoutes']);

		// register admin menus
		addAction('adminMenu', [$this, 'adminCustomMenu']);

		// register site pages ?

	}

	private function createPostQuery()
	{
		// make array query and create table
		$newQuery = [
			'name'	=>	$this->params['name'],
			'columns'	=>	[
				'id'	=>	[
					'type'		=>	'bigint(20)',
					'null'		=>	false,
					'default'	=>	null,
					'increment'	=>	true,
					'collation'	=>	'utf8mb4_general_ci',
				],
			],
			'engine'	=>	'InnoDB',
			'charset'	=>	'utf8mb4',
			'primary'	=>	'id'
		];

		foreach ($this->params['fields'] as $paramKey => $param) {
			// skip if param turned off
			if(! $param) {
				continue;
			}

			$newQuery[$paramKey] = [];
			// individually check
			switch ($paramKey) {
				case 'title':
					$newQuery[$paramKey] += [
						'type'		=>	'varchar(255)',
					];

					break;

				case 'excerpt':
					$newQuery[$paramKey] += [
						'type'		=>	'text',
					];

					break;

				case 'content':
					$newQuery[$paramKey] += [
						'type'		=>	'longtext',
					];

					break;

				case 'media': // TODO: foreign key to media relations table 
					$newQuery[$paramKey] += [
						'type'		=>	'bigint(20)',
					];

					break;
				
				default:
					$newQuery[$paramKey] += [
						'type'		=>	'varchar(255)',
					];
					break;
			}

			// defaul settings
			$newQuery[$paramKey] = [
				'null'		=>	true,
				'default'	=>	null,
				'increment'	=>	false,
				'collation'	=>	'utf8mb4_general_ci',
			];
		}
	}

	public function adminCustomData()
	{
		// return $this->params;
	}

	public function adminCustomRoutes()
	{
		$viewData = [
			'postType' => $this->params
		];
		
		get('/' . ADMIN_DIR . '/type/$type', ADMIN_ROOT . 'sys/custom/page.php', false, $viewData);
		get('/' . ADMIN_DIR . '/type/$type/$page', ADMIN_ROOT . 'sys/custom/page.php', false, $viewData);
	}


	public function adminCustomMenu($data)
	{

		$mainUrl = '/' . ADMIN_DIR . '/type/' . $this->params['name'];

		$data[$this->params['name']] = [
			'url' 	=> $mainUrl,
			'title' => $this->params['label'],
			'icon' 	=> $this->params['icon'],
			'sub'	=> [
				[
					'url' 	=> $mainUrl . '/list',
					'title' => $this->params['label_list'],
				],
				[
					'url' 	=> $mainUrl . '/create',
					'title' => $this->params['label_create'],
				]
			]
		];

		return $data;
	}

}