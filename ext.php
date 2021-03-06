<?php
/**
*
* @package System Overview
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\sysoverview;

class ext extends \phpbb\extension\base
{
	public function is_enableable()
	{
		if (!class_exists('forumhulp\helper\helper'))
		{
			$this->container->get('user')->add_lang_ext('forumhulp/sysoverview', 'info_acp_sysoverview');
			trigger_error($this->container->get('user')->lang['FH_HELPER_NOTICE'], E_USER_WARNING);
		}

		if (!$this->container->get('ext.manager')->is_enabled('forumhulp/helper'))
		{
			$this->container->get('ext.manager')->enable('forumhulp/helper');
		}

		return class_exists('forumhulp\helper\helper');
	}

	/**
	 * @param mixed $old_state State returned by previous call of this method
	 * @return mixed Returns false after last step, otherwise temporary state
	 * @access public
	 */
	public function enable_step($old_state)
	{
		if (empty($old_state))
		{
			$this->container->get('user')->add_lang_ext('forumhulp/sysoverview', 'info_acp_sysoverview');
			$this->container->get('template')->assign_var('L_EXTENSION_ENABLE_SUCCESS', $this->container->get('user')->lang['EXTENSION_ENABLE_SUCCESS'] .
				(isset($this->container->get('user')->lang['SYSOVERVIEW_NOTICE']) ?
				sprintf($this->container->get('user')->lang['SYSOVERVIEW_NOTICE'],
						$this->container->get('user')->lang['ACP_CAT_SYSTEM'],
						$this->container->get('user')->lang['ACP_GENERAL_TASKS'],
						$this->container->get('user')->lang['ACP_SYSOVERVIEW_TITLE']) : ''));
		}
		// Run parent enable step method
		return parent::enable_step($old_state);
	}
}
