<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

$text = '';

if(empty($params['eventid']))
	$this->Redirect($id, "defaultadmin", '', Array("module_message"=>$this->Lang('event_does_not_exist')));
else
{
	$db = $gCms->GetDb();
	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
	$Res = $db->Execute($sql, Array($params['eventid']));

	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventname = $row['eventname'];
			$eventid = $row['id'];
			$maxteams = $row['maxteams'];
			$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_teams WHERE event_id=?';
			$Res = $db->Execute($sql, Array($eventid));
			if($Res !== false)
			{
				$text .= '<h2>'.$eventname.'</h2>';
				if($maxteams > 0)
					$text .= '<p>'.$this->Lang('max_teams').': '.$maxteams.'</p>';
				$text .= '<p>'.$this->Lang('registered_teams').': '.$Res->RecordCount().'</p>';
				$text .= '<p>'.$this->CreateLink($id, 'deleteevent', '', $this->Lang('deleteevent'), Array('eventid'=>$eventid), $this->Lang('really_delete')).'</p>';
				$text .= '<p><a href="'.$gCms->config['root_url'].'/modules/EventRegistration/download.php?eventid='.$eventid.'">'.$this->Lang('csv').'</a></p>';
				while($row = $Res->FetchRow())
				{
					$text .= '<h3>'.$row['teamname'].'</h3>';
					$text .= '<p>'.$this->Lang('mail').': '.$row['mail'].'<br/>'.$this->Lang('phone').': '.$row['phone'].'</p>';
					$text .= $this->CreateLink($id, 'deleteteam', '', $this->Lang('deleteteam'), Array('eventid'=>$eventid,'teamid'=>$row['id']), $this->Lang('really_delete')).'<hr/>';
				}
				$text .= $this->CreateLink($id, 'defaultadmin', $returnid, '« '.$this->Lang('back_to_eventlist'), Array('active_tab' => 'overview'));
			}
			else
				$this->Redirect($id, "defaultadmin", '', Array("module_message"=>$this->Lang('error_database')));
		}
		else
			$this->Redirect($id, "defaultadmin", '', Array("module_message"=>$this->Lang('event_does_not_exist')));
	}
	else
		$this->Redirect($id, "defaultadmin", '', Array("module_message"=>$this->Lang('error_database')));
	$this->smarty->assign('text', $text);
	echo $this->ProcessTemplate('adminpanel.tpl');
}
?>

