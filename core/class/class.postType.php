<?php

/**
 * PostType
 */
class PostType {
	
	/**
	 * params
	 *
	 * @var array
	 */
	public $params = [];
	
	/**
	 * posTypes
	 *
	 * @var array
	 */
	public $posTypes = [];

	
	/**
	 * __construct
	 *
	 * @param  array $args
	 * @return void
	 */
	public function __construct(array $args = [])
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
	
	/**
	 * setArgs
	 *
	 * @param  array $params
	 * @return void
	 */
	public function setArgs(array $params = [])
	{
		$this->params = array_merge($this->defaultArgs, $params);
		$this->posTypes[$this->defaultArgs['name']] = array_merge($this->defaultArgs, $params);
	}
	
	/**
	 * getArgs
	 *
	 * @return void
	 */
	public function getArgs()
	{
		return $this->params;
	}
	
	/**
	 * getPostType
	 *
	 * @param  array $name
	 * @return void
	 */
	public function getPostType(array $name = [])
	{
		return ! empty($this->posTypes) ? $this->posTypes[$name] : $this->posTypes;
	}
	
	/**
	 * createPostType
	 *
	 * @return void
	 */
	public function createPostType()
	{
		

		// create db stuff
		// $this->createPostQuery($params);

		// register data
		// addAction('adminCustomData', [$this, 'adminCustomData']);
		// register admin pages
		addAction('adminRoutes', [$this, 'adminCustomRoutes'], 10);

		// register admin menus
		addAction('adminMenu', [$this, 'adminCustomMenu'], 10);

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
	
	/**
	 * adminCustomRoutes
	 *
	 * @return void
	 */
	public function adminCustomRoutes()
	{
		$viewData = [
			'postType' => $this->params
		];
		
		get('/' . ADMIN_DIR . '/type/$type', ADMIN_ROOT . 'sys/custom/page.php', false, $viewData);
		get('/' . ADMIN_DIR . '/type/$type/$page', ADMIN_ROOT . 'sys/custom/page.php', false, $viewData);
	}

	
	/**
	 * adminCustomMenu
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function adminCustomMenu($data)
	{

		$mainUrl = '/' . ADMIN_DIR . '/type/' . $this->params['name'];

		if(! empty($data[$this->params['name']])) {
			throw new \Exception($this->params['name'] . ' Already exists!');

			return $data;
		}

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