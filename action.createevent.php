<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(empty($params['eventname']) || empty($params['maxmembersperteam']) || empty($params['minmembersperteam']))
	$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('not_enough_params')));
$eventname = $params['eventname'];
$maxmembersperteam = $params['maxmembersperteam'];
if($maxmembersperteam < 1) $maxmembersperteam = 1;
$minmembersperteam = $params['minmembersperteam'];
if($minmembersperteam < 1) $minmembersperteam = 1;

$db = $gCms->GetDb();
$taboptarray = array('mysql' => 'ENGINE=MyISAM');

$sql1 = 'INSERT INTO '.cms_db_prefix().'module_eventregistration (eventname, maxmembersperteam, minmembersperteam) VALUES (\''.$eventname.'\', \''.$maxmembersperteam.'\', \''.$minmembersperteam.'\')';

$dict = NewDataDictionary($db);

$flds = '
	id I KEY NOTNULL AUTOINCREMENT PRIMARY,
	teamname C(128),
	mail C(128),';
for($i=1;$i<=$maxmembersperteam;$i++)
{
	$flds .= " member$i C(128),";
}
$flds .= ' password C(32)';

$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(' ', '', mysql_real_escape_string($eventname)));
$message = $flds.$table;
$sql2 = $dict->CreateTableSQL($table, $flds, $taboptarray);
$Res1 = $db->Execute($sql1);
$Res2 = $dict->ExecuteSQLArray($sql2);
$db->CreateSequence($table.'_seq');
if($Res1 !== false && $Res2 !== false)
{
	$message = $this->Lang('creation_successful');
}
else
{
	//$message = $this->Lang('error_database');
}

$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$message));
?>
