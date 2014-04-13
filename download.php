<?php
require_once(dirname(__FILE__)."/../../include.php");

global $gCms;
if(is_null($gCms))
	$gCms = cmsms();
$v =& $gCms->modules['EventRegistration']['object'];
if(is_null($dm))
	$er = &CMSModule::GetModuleInstance('EventRegistration');

if(!$er->CheckPermission('Use EventRegistration') || empty($_GET['eventid']))
{
	exit;
}

$eventid= mysql_real_escape_string($_GET['eventid']);

$db = $gCms->GetDb();
$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
$Res = $db->Execute($sql, Array($eventid));
if($Res !== false)
{
	if($row = $Res->FetchRow())
	{
		$eventname = $row['eventname'];
		$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_teams WHERE event_id=?';
		$Res = $db->Execute($sql, Array($eventid));
		if($Res !== false)
		{
			header('Content-type: application/txt');
			header('Content-Disposition: attachment; filename="'.$eventname.'.csv"');
			echo $er->Lang('teamname').";";
			echo $er->Lang('phone').";";
			echo $er->Lang('mail')."\r\n\r\n";
			while($row = $Res->FetchRow())
			{
				echo $row['teamname'].";";
				echo $row['phone'].";";
				echo $row['mail']."\r\n";
			}
		}
	}
}




?>

