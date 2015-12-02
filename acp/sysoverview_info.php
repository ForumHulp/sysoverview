<?php
/**
*
* @package System Overview
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\sysoverview\acp;

class sysoverview_info
{
	function module()
	{
		return array(
			'filename'		=> 'forumhulp\sysoverview\acp\sysoverview_module',
			'title'			=> 'ACP_SYSOVERVIEW_TITLE',
			'version'		=> '1.0.0',
			'modes'			=> array(
				'system'	=> array('title' => 'ACP_SYSOVERVIEW_TITLE', 'auth' => 'ext_forumhulp/sysoverview', 'cat' => array('ACP_CAT_SYSTEM')
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
