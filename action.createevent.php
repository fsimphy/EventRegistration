<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(empty($params['eventname']))
	$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$this->Lang('event_does_not_exist')));
$eventname = $params['eventname'];

$db = $gCms->GetDb();
$taboptarray = array('mysql' => 'ENGINE=MyISAM');

$sql1 = 'INSERT INTO '.cms_db_prefix().'module_eventregistration (eventname) VALUES (\''.$params['eventname'].'\')';

$dict = NewDataDictionary($db);

$flds = '
	id I KEY NOTNULL AUTOINCREMENT PRIMARY,
	teamname C(128),
	mail C(128),
	member1 C(128),
	member2 C(128),
	member3 C(128),
	member4 C(128),
	member5 C(128),
	member6 C(128),
	member7 C(128),
	member8 C(128),
	member9 C(128),
	member10 C(128),
	password C(32)
    ';

$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(' ', '', mysql_real_escape_string($eventname)));

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
	$message = $this->Lang('error_database');
}

$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$message));
?>
