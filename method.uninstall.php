<?php
#-------------------------------------------------------------------------
# Module: EventRegistration
# Version: 0.1
#-------------------------------------------------------------------------
if (!isset($gCms)) exit;

$db = $gCms->GetDb();

$taboptarray = array('mysql' => 'ENGINE=MyISAM');
$dict = NewDataDictionary($db);

$sqlarray = $dict->DropTableSQL(cms_db_prefix().'module_eventregistration_events');
$dict->ExecuteSQLArray($sqlarray);

$db->DropSequence(cms_db_prefix().'module_eventregistration_events_seq' );

$sqlarray = $dict->DropTableSQL(cms_db_prefix().'module_eventregistration_teams');
$dict->ExecuteSQLArray($sqlarray);

$db->DropSequence(cms_db_prefix().'module_eventregistration_teams_seq' );

$this->RemovePermission('Use EventRegistration');

$this->RemovePreference('fromuser');
$this->RemovePreference('from');

$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>
