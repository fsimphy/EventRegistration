<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(empty($params['eventname']))
	$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('not_enough_params')));
$eventname = $params['eventname'];

$db = $gCms->GetDb();

$sql = 'INSERT INTO '.cms_db_prefix().'module_eventregistration_events (eventname) VALUES (\''.$eventname.'\')';
$Res = $db->Execute($sql1);

if($Res !== false)
{
	$message = $this->Lang('creation_successful');
}
else
{
	$message = $this->Lang('error_database');
}

$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$message));
?>
