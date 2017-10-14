<?php
/**
 *
 * postGraph PCM. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, papajoke
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace papajoke\postgraph\mcp;

/**
 * postGraph PCM MCP module info.
 */
class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\papajoke\postgraph\mcp\main_module',
			'title'		=> 'MCP_POSTGRAPH_TITLE',
			'modes'		=> array(
				'front'	=> array(
					'title'	=> 'MCP_POSTGRAPH',
					'auth'	=> 'ext_papajoke/postgraph',
					'cat'	=> array('MCP_MAIN')
				),
			),
		);
	}
}
