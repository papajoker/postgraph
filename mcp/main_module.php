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
 * postGraph MCP module.
 */
class main_module
{
	var $u_action;

	function main($id, $mode)
    {
        global $phpbb_container, $db;
        $user = $phpbb_container->get('user');
        $template = $phpbb_container->get('template');
        $request = $phpbb_container->get('request');

        $user->add_lang_ext('papajoke/postgraph', 'mcp_postgraph_front');
        $this->tpl_name = 'mcp_postgraph_body';
        $this->page_title = $user->lang('MCP_POSTGRAPH_TITLE');
        add_form_key('papajoke/postgraph');

        $condition="from_unixtime(post_time) > DATE_SUB(CURDATE(), interval 30 DAY)";
        $title = "30 ".$user->lang('LAST_DAYS');
        $days = date('t'); 
        
        if ($request->is_set_post('submit')) {
            if (!check_form_key('papajoke/postgraph')) {
                trigger_error('FORM_INVALID');
            }
        }

        $ladate = request_var('ladate', date("Y-m-d"));
        
        if ($ladate !='')
        {
            $dt = new \DateTime($ladate);
            $year = (int)$dt->format('Y'); //substr($ladate,0,4);
            $month = (int)$dt->format('n'); //substr($ladate,5,2);
            $days= $dt->format('t');
            $condition="YEAR(from_unixtime(post_time))=".$year." AND MONTH(from_unixtime(post_time))=".$month;
            $title = ": ".$dt->format('F')." ".$year;
            $template->assign_vars(array(
                'LADATE'               => $dt->format('Y-m-d'),
            ));
        }

        $template->assign_vars(array(
            'TOP_TITLE'               => $title,
        ));

        $sql="SELECT DAY(from_unixtime(post_time)) as day, 
                COUNT(post_id) as nb, 
                (SELECT COUNT(post_id) FROM ".POSTS_TABLE." WHERE ".$condition.") as total,
                FORMAT(100*COUNT(post_id)/(SELECT COUNT(post_id) FROM ".POSTS_TABLE." WHERE ".$condition."),1) as pourcentage
            FROM ".POSTS_TABLE."
            WHERE 
                ".$condition."
            GROUP BY day
            ORDER BY day ASC
            ";

        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) 
        {
            $template->assign_block_vars('posts', array(
                'DAY' => $row['day'],
                'NB' => $row['nb'],
                'TOTAL' => $row['total'],
                'POURCENT' => $row['pourcentage'],
            ));
        }
        $db->sql_freeresult($result);


        $sql="SELECT DAY(from_unixtime(post_time)) as day, 
            COUNT(DISTINCT(poster_id)) as nb, 
            (SELECT COUNT(DISTINCT(poster_id)) FROM ".POSTS_TABLE." WHERE ".$condition.") as total,
            FORMAT(100*COUNT(DISTINCT(poster_id))/(SELECT COUNT(DISTINCT(poster_id)) FROM ".POSTS_TABLE." WHERE ".$condition."),1) as pourcentage
            FROM ".POSTS_TABLE."
            WHERE 
                ".$condition."
            GROUP BY day
            ORDER BY day ASC
            ";

        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) 
        {
            $template->assign_block_vars('posters', array(
                'DAY' => $row['day'],
                'NB' => $row['nb'],
                'TOTAL' => $row['total'],
                'POURCENT' => $row['pourcentage'],
            ));
        }
        $db->sql_freeresult($result);  

        $sql="SELECT DAY(from_unixtime(user_regdate)) as day, 
        COUNT(DISTINCT(user_id)) as nb
        FROM ".USERS_TABLE."
        WHERE 
            ".str_replace('post_time','user_regdate',$condition)."
        GROUP BY day
        ORDER BY day ASC
        ";

        $result = $db->sql_query($sql);
        $array= $this->setArray($days);
        while ($row = $db->sql_fetchrow($result)) 
        {
            $array[$row['day']]=array(
                'DAY' => $row['day'],
                'NB' => $row['nb'],
            );
        }
        //$template->assign_block_vars('nusers', $array);
        foreach($array as $item){
            $template->assign_block_vars('nusers', $item);
        }
        $db->sql_freeresult($result);  
        //var_dump($array);

        $template->assign_var('U_POST_ACTION', $this->u_action);
    }

    private function setArray($days){
        $return = array();
        for ($i=1; $i<=$days; $i++){
            $return[$i]= array('DAY'=>$i,'NB'=>0);
        }
        //unset($array[0]);
        return $return;
    }
}
