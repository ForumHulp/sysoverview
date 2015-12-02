<?php
/**
*
* @package Services
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\sysoverview\acp;

class sysoverview_module
{
	protected $config;
	protected $request;
	protected $template;
	protected $user;
	protected $cache;
	protected $phpbb_container;
	protected $phpbb_root_path;
	protected $php_ext;
	public $u_action;

	function main($id, $mode)
	{
		global $config, $request, $template, $user, $cache, $phpbb_container, $phpbb_root_path, $phpEx;

		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->cache = $cache;
		$this->container = $phpbb_container;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;

		$this->page_title = $this->user->lang['ACP_SYSOVERVIEW_TITLE'];
		$this->tpl_name = 'acp_sysoverview';
		$action = $this->request->variable('action', '');
		$this->cat = $this->request->variable('tab', 'general');
		$this->search = $this->request->variable('search', '');
		$sk = $this->request->variable('sk', 'service');
		$sd = $this->request->variable('sd', 'a');

		switch ($action)
		{
			case 'details':
				$this->user->add_lang_ext('forumhulp/sysoverview', 'info_acp_sysoverview');
				$this->container->get('forumhulp.helper')->detail('forumhulp/sysoverview');
				$this->tpl_name = 'acp_ext_details';
			break;
			
			case 'search':
				$this->get_services();
				$this->cat = ($this->search) ? $this->searchForService($this->search, $this->services) : $this->cat;
			
			default:

				$this->containerBuilder = $this->getContainerBuilder();
				$this->outputServices($sk, $sd);
		}
	}

	/**
	* {@inheritdoc}
	*/
	protected function outputServices($sk = 'service', $sd = 'a')
	{
		$this->get_services();		
		$total_services = $this->services['total_services'];
		unset( $this->services['total_services']);

		$this->cat_ary = array_keys($this->services);
		$this->cat_ary[] = 'parameters';

		foreach($this->cat_ary as $cat_title)
		{
			$this->template->assign_block_vars('tabs', array(
				'CAT_NAME'		=> $cat_title,
				'SELECTED'		=> ($cat_title == $this->cat) ? ' activetab': '',
				'U_ACTION'		=> $this->u_action . '&amp;tab=' . $cat_title . (($this->search) ? '&amp;search=' . $this->search : '') 
			));
		}
		
		if ($this->cat == 'parameters')
		{
			$this->parameters = $this->containerBuilder->getParameterBag()->all();
			
			if ($sk == 'service')
			{
				($sd == 'a') ? ksort($this->parameters) : krsort($this->parameters);
			} else
			{
				($sd == 'a') ? asort($this->parameters) : arsort($this->parameters);	
			}
			
			$total_services = $total_service = sizeof($this->parameters);
			foreach($this->parameters as $key => $parameter)
			{
				$this->template->assign_block_vars('service', array(
					'SERVICE'		=> $key,
					'SEARCHED'		=> ($this->search && strpos($key, $this->search) !== false) ? ' searchedfor' : '',
					'SCOPE'			=> (is_array($parameter)) ? implode(', ', $parameter) : $parameter
				));
			}
		} else
		{
			$this->services = $this->array_sort($this->services[$this->cat], $sk, (($sd == 'a') ? SORT_ASC : SORT_DESC));
			$total_service = sizeof($this->services);
			foreach($this->services as $key => $service)
			{
				$this->template->assign_block_vars('service', array(
					'SERVICE'		=> $service['service'],
					'SEARCHED'		=> ($this->search && strpos($service['service'], $this->search) !== false) ? ' searchedfor' : '',
					'SCOPE'			=> $service['scope'],
					'CLASS'			=> $service['class'],
					'TAG'			=> $service['tag'],
					'DEPENDENCY'	=> $service['dependency']
				));
			}
		}

		$this->template->assign_vars(array(
			'U_ACTION'				=> $this->u_action . '&amp;tab=' . $this->cat . (($this->search) ? '&amp;search=' . $this->search : ''),
			'U_SORT'				=> $sd,
			'U_NAME'				=> $sk,
			'SEARCHED'				=> $this->search,
			'SERVICES'				=> ($this->cat == 'parameters') ? false : true,
			'IN_ROUTING'			=> ('routing' == $this->cat) ? true: false,
			'ACP_SYSOVERVIEW_EXPLAIN'	=> $this->user->lang('ACP_SYSOVERVIEW_EXPLAIN', $this->user->lang[($this->cat == 'parameters') ? 'ACP_PARAMETER' : 'ACP_SYSOVERVIEW'], $total_services, $total_service)
			));
	}

	/**
	* {@inheritdoc}
	*/
	protected function get_services()
	{
		if (($this->services = $this->cache->get('_service_cats')) === false)
		{
			$serviceIds = $this->containerBuilder->getServiceIds();
			asort($serviceIds);
			$i = 0;
			foreach ($serviceIds as $key => $serviceId)
			{
				$cat = (strpos($serviceId, '.')) ? substr($serviceId, 0, strpos($serviceId, '.')) : 'general';
				$cat = $this->catnames($cat);
				$cat = (!in_array($cat, $this->catnames(true))) ? 'extension' : $cat;

				$indef = false;
				$definition = $this->resolveServiceDefinition($serviceId);

				if ($definition instanceof \Symfony\Component\DependencyInjection\Definition)
				{
					$indef = true;
					$arg = array();
					$tag = (array_keys($definition->getTags()));
					foreach($definition->getArguments() as $key2 => $argument)
					{					
						if (is_object($argument))
						{
							foreach((array) $argument as $id => $value)
							{
								$id = utf8_clean_string($id);
								if (strpos($id, 'referenceid') !== false)
								{
									$arg[] = $value;
								}
							}
							(is_object($argument)) ? null : $arg[] = $argument;
						}
					}
				}
				
				$this->services[$cat][$key] = array(
					'service'		=> $serviceId,
					'scope'			=> ($indef) ? $definition->getScope() : '',
					'class'			=> ($indef) ? $definition->getClass() : '',
					'tag'			=> ($indef) ? ((sizeof($tag)) ? $tag[0] : '') : '',
					'dependency'	=> (($indef && sizeof($arg)) ? 'Dependencies:' . "\n" . implode("\n", $arg) : '')
				);
				$i++;
			}
			
			$this->route =  $this->container->get('router');
			$this->routes = $this->route->get_routes();
			foreach($this->routes as $key => $route)
			{
				$this->services['routing'][++$i] = array(
					'service'		=> $key,
					'scope'			=> $route->getSchemes() ? implode('|', $route->getSchemes()) : $this->user->lang('ANY_MAJ'),
					'class'			=> $route->getPath(),
					'tag'			=> $route->getMethods() ? implode('|', $route->getMethods()) : $this->user->lang('ANY_MAJ'),
					'dependency'	=> '' !== $route->getHost() ? $route->getHost() : $this->user->lang('ANY_MAJ')
				);
			}

			ksort($this->services);
			$this->services['total_services'] = $i;		
			unset($serviceIds, $definition, $this->routes);
			$this->cache->put('_service_cats', $this->services, 1800);
		}
	}

	/**
	* {@inheritdoc}
	*/
	protected function searchForService($id, $array)
	{
		foreach ($array as $key => $val)
		{
			foreach($val as $key2 => $service)
			{
				if (strpos($service['service'], $id) !== false)
				{
					return $key;
				}
			}
		}
		
	}

	/**
	* {@inheritdoc}
	*/
	protected function array_sort($array, $on, $order = SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (sizeof($array) > 0)
		{
			foreach ($array as $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v as $k2 => $v2)
					{
						if ($k2 == $on)
						{
							$sortable_array[$k] = $v2;
						}
					}
				} else
				{
					$sortable_array[$k] = $v;
				}
			}

			switch ($order)
			{
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v)
			{
				$new_array[$k] = $array[$k];
			}
		}
		unset($array, $sortable_array);
		return $new_array;
	}

	/**
	* Loads the ContainerBuilder from the cache.
	* @return \Symfony\Component\DependencyInjection\ContainerBuilder
	* @throws \LogicException
	*/
	protected function getContainerBuilder()
	{
		$phpbb_config_php_file = new \phpbb\config_php_file($this->phpbb_root_path, $this->php_ext);
		if (version_compare($this->config['version'], '3.2.*', '<'))
		{ 
			$container_builder = new \phpbb\di\container_builder($phpbb_config_php_file, $this->phpbb_root_path, $this->php_ext);
			$container_builder->set_compile_container(true);
			$container_builder->set_dump_container(false);
			$container = $container_builder->get_container();
		} else
		{
			$this->request->enable_super_globals();
			$container_builder = new \phpbb\di\container_builder($this->phpbb_root_path, $this->php_ext);
			$container_builder->without_cache();
			$container = $container_builder->with_config($phpbb_config_php_file)->get_container();
			$this->request->disable_super_globals();
		}
		return $container;
	}

	/**
	* Given an array of service IDs, this returns the array of corresponding
	* Definition and Alias objects that those ids represent.
	*
	* @param string $serviceId The service id to resolve
	*
	* @return Definition|Alias
	*/
	protected function resolveServiceDefinition($serviceId)
	{
		if ($this->containerBuilder->hasDefinition($serviceId))
		{
			return $this->containerBuilder->getDefinition($serviceId);
		}

		// Some service IDs don't have a Definition, they're simply an Alias
		if ($this->containerBuilder->hasAlias($serviceId))
		{
			return $this->containerBuilder->getAlias($serviceId);
		}

		// the service has been injected in some special way, just return the service
		return $this->containerBuilder->get($serviceId);
	}

	/**
	* {@inheritdoc}
	*/
	protected function catnames($cat = '')
	{
		$cat_ary = array(
			'search' => array(
					  0 => '',
					  1 => 'attachment',
					  2 => 'avatar',
					  3 => 'feed',
					  4 => 'mimetype',
					  5 => 'router',
					  6 => 'message',
					  7 => 'text_formatter',
					  8 => 'text_reparser',
					  9 => 'ext', 
					 10 => 'config',
					 11 => 'console',
					 12 => 'cron',
					 13 => 'dbal',
					 14 => 'cache',
					 15 => 'acl',
					 16 => 'auth',
					 17 => 'migrator',
					 18 => 'language',
					 19 => 'core',
					 20 => 'captcha',
					 21 => 'template',
					 22 => 'groupposition',
					 23 => 'passwords',
					 24 => 'profilefields',
					 25 => 'notification'),
			'replace' => array(
					  0 => 'general',
					  1 => 'files',
					  2 => 'files',
					  3 => 'content',
					  4 => 'files',
					  5 => 'routing',
					  6 => 'content',
					  7 => 'content',
					  8 => 'content',
					  9 => 'extension',
					 10 => 'controller',
					 11 => 'controller',
					 12 => 'controller',
					 13 => 'controller',
					 14 => 'controller',
					 15 => 'controller',
					 16 => 'controller',
					 17 => 'module',
					 18 => 'module',
					 19 => 'module',
					 20 => 'module',
					 21 => 'module',
					 22 => 'user',
					 23 => 'user',
					 24 => 'user',
					 25 => 'user'
				)	
		);
		
		return ($cat === true) ? array_unique(array_merge($cat_ary['search'], $cat_ary['replace'])) : str_replace($cat_ary['search'], $cat_ary['replace'], (string) $cat);
	}
}
