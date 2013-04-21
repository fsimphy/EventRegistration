<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(empty($params['eventid']))
{
	$this->Redirect($id, 'defaultadmin', '', Array("module_message"=>$this->Lang('event_does_not_exist')));
}
else if(empty($params['teamid']))
{
	$this->Redirect($id, 'defaultadmin', '', Array("module_message"=>$this->Lang('team_does_not_exist')))
}
else
{
	$db =& $this->GetDb();
	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration WHERE id=?';
	$Res = $db->Execute($sql, Array($params['eventid']));
	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventname = $row['eventname'];
			$eventid = $row['id'];
			$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(" ", "", mysql_real_escape_string($eventname)));
			$sql = 'SELECT * FROM '.$table.' WHERE id=?';
			$Res = $db->Execute($sql, Array($params['teamid']));
			if($Res !== false)
			{
				if($Res->FetchRow())
				{
					$sql = 'DELETE FROM '.$table.' WHERE id=?';
					$db->Execute($sql, Array($params['teamid']));
					$this->Redirect($id, 'viewevent', '', Array('module_message'=>$this->Lang('teamdeleted'), 'eventid'=>$eventid));
				}
				else
					$this->Redirect($id, 'viewevent', '', Array('module_message'=>$this->Lang('team_does_not_exist'), 'eventid'=>$eventid));
			}
		}
		else
		{
			$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('event_does_not_exist')));
		}
	}
}
?>
