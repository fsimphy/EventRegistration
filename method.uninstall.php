<?php
#-------------------------------------------------------------------------
# Module: EventRegistration
# Version: 0.1
#-------------------------------------------------------------------------
if (!isset($gCms)) exit;

$db = $gCms->GetDb();

$taboptarray = array('mysql' => 'ENGINE=MyISAM');
$dict = NewDataDictionary($db);
$Res = $db->Execute('SELECT eventname FROM '.cms_db_prefix().'module_eventregistration ORDER BY id');

if($Res !== false)
{
	while($row = $Res->FetchRow())
	{
		$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(' ', '', mysql_real_escape_string($row['eventname'])));
		$sqlarray = $dict->DropTableSQL($table);
		$dict->ExecuteSQLArray($sqlarray);
		$db->DropSequence($table."_seq" );
	}
}

$sqlarray = $dict->DropTableSQL(cms_db_prefix().'module_eventregistration');
$dict->ExecuteSQLArray($sqlarray);

$db->DropSequence(cms_db_prefix().'module_eventregistration_seq' );

$this->RemovePermission('Use EventRegistration');

$this->RemovePreference('fromuser');
$this->RemovePreference('from');

$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>
