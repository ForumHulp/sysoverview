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
	'ACP_SYSOVERVIEW_TITLE'		=> 'Systeem Overzicht',
	'ACP_SYSOVERVIEW_NAME'		=> 'Servicenaam',
	'ACP_PARAMETER_NAME'		=> 'Parameternaam',
	'ACP_PARAMETER_VALUE'		=> 'Waarde',
	'ACP_SYSOVERVIEW_SCOPE'		=> 'Scope',
	'ACP_SYSOVERVIEW_TAG'		=> 'Tag',
	'ANY_MAJ'					=> 'Alle',
	'ACP_SYSOVERVIEW_PUBLIC'	=> 'Publiek',
	'ACP_SYSOVERVIEW_CLASS'		=> 'Classnaam',
	'ACP_SYSOVERVIEW'			=> 'services',
	'ACP_PARAMETER'				=> 'parameters',

	'ACP_SYSOVERVIEW_EXPLAIN'	=> 'Een gecatorizeerd overzicht van services in je website met hun afhankelijkheden.<br />Er zijn %1$s services in deze website en %2$s in deze categorie.',
	'FH_HELPER_NOTICE'			=> 'Forumhulp helper applicatie bestaat niet!<br />Download <a href="">forumhulp/helper</a> and copieer de helper map naar de forumhulp extensie map.',
	'SYSOVERVIEW_NOTICE'		=> '<div class="phpinfo"><p class="entry">Deze extensie bevindt zich in %1$s » %2$s » %3$s.</p></div>',
));

// Description of Upload extension
$lang = array_merge($lang, array(
	'DESCRIPTION_PAGE'		=> 'Description',
	'DESCRIPTION_NOTICE'	=> 'Extension note',
	'ext_details' => array(
		'details' => array(
			'DESCRIPTION_1'		=> 'Service overzicht',
			'DESCRIPTION_2'		=> 'afhankelijkheden per service',
			'DESCRIPTION_3'		=> 'Sorteerbaar',
			'DESCRIPTION_4'		=> 'Doorzoekbaar',
		),
		'note' => array(
			'NOTICE_1'			=> 'phpBB 3.2 ready.',
		)
	)
));
