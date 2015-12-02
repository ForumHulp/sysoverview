<?php
/**
*
* @package System Overview
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_SYSOVERVIEW_TITLE'		=> 'System Overview',
	'ACP_SYSOVERVIEW_EXPLAIN'	=> '',
	'ACP_SYSOVERVIEW_NAME'		=> 'Servicename',
	'ACP_PARAMETER_NAME'		=> 'Parametername',
	'ACP_PARAMETER_VALUE'		=> 'Value',
	'ACP_ROUTE_NAME'			=> 'Route',
	'ACP_ROUTE_METHOD'			=> 'Method',
	'ACP_ROUTE_PATH'			=> 'Path',
	'ANY_MAJ'					=> 'Any',
	'ACP_SYSOVERVIEW_SCOPE'		=> 'Scope',
	'ACP_SYSOVERVIEW_TAG'		=> 'Tag',
	'ACP_SYSOVERVIEW_PUBLIC'	=> 'Public',
	'ACP_SYSOVERVIEW_CLASS'		=> 'Classname',
	'ACP_SYSOVERVIEW'			=> 'services',
	'ACP_PARAMETER'				=> 'parameters',

	'ACP_SYSOVERVIEW_EXPLAIN'	=> 'A categorized overview of %1$s in your board with their dependencies.<br />There are  %2$s %1$s in this board and  %3$s in this category',
	'FH_HELPER_NOTICE'			=> 'Forumhulp helper application does not exist!<br />Download <a href="">forumhulp/helper</a> and copy the helper folder to your forumhulp extension folder.',
	'SYSOVERVIEW_NOTICE'		=> '<div class="phpinfo"><p class="entry">This extension resides in %1$s » %2$s » %3$s.</p></div>',
));

// Description of Upload extension
$lang = array_merge($lang, array(
	'DESCRIPTION_PAGE'		=> 'Description',
	'DESCRIPTION_NOTICE'	=> 'Extension note',
	'ext_details' => array(
		'details' => array(
			'DESCRIPTION_1'		=> 'System overview',
			'DESCRIPTION_2'		=> 'Dependencies per service',
			'DESCRIPTION_3'		=> 'Sortable',
			'DESCRIPTION_4'		=> 'Searchable',
		),
		'note' => array(
			'NOTICE_1'			=> 'phpBB 3.2 ready.',
		)
	)
));
