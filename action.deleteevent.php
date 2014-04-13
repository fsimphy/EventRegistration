<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(empty($params['eventid']))
	$this->Redirect($id, "defaultadmin", '', Array("module_message"=>$this->Lang('event_does_not_exist')));
else
{
	$db =& $this->GetDb();
	$dict = NewDataDictionary($db);

	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
	$Res = $db->Execute($sql, Array($params['eventid']));
	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventid = $row['id'];
			$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_teams WHERE event_id=?';
			$Res = $db->Execute($sql, Array($params['eventid']));
			if($Res !== false)
			{
				while($row = $Res->FetchRow())
				{
					$teamid = $row['id'];
					$sql = 'DELETE FROM '.cms_db_prefix().'module_eventregistration_teams WHERE id=?';
					$db->Execute($sql, Array($teamid));
				}
			}
			$sql = 'DELETE FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
			$db->Execute($sql, Array($eventid));
			$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('eventdeleted')));
		}
		else
		{
			$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('event_does_not_exist')));
		}
	}
}
?>
