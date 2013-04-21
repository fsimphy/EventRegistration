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

	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration WHERE id=?';
	$Res = $db->Execute($sql, Array($params['eventid']));
	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventname = $row['eventname'];
			$eventid = $row['id'];
			$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(" ", "", mysql_real_escape_string($eventname)));

			$sqlarray = $dict->DropTableSQL($table);
			$dict->ExecuteSQLArray($sqlarray);
			$db->DropSequence($table.'_seq');
			$sql = 'DELETE FROM '.cms_db_prefix().'module_eventregistration WHERE id=?';
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
