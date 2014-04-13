<?php
#-------------------------------------------------------------------------
# Module: Volleyballanmeldung
# Version: 0.1
#-------------------------------------------------------------------------
if (!isset($gCms)) exit;

$db = $gCms->GetDb();

$taboptarray = array('mysql' => 'ENGINE=MyISAM');

$dict = NewDataDictionary($db);

$flds = '
	id I KEY NOTNULL AUTOINCREMENT,
	eventname C(128)
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix().'module_eventregistration_events', $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$db->CreateSequence(cms_db_prefix().'module_eventregistration_events_seq');

$this->CreatePermission('Use EventRegistration', $this->Lang('permission'));

$taboptarray = array(
	'mysql' => 'ENGINE=MyISAM',
	'constraints' => 'FOREIGN KEY (event_id) REFERENCES '.cms_db_prefix().'module_eventregistration_events (id)'
);

$flds = '
	id I KEY NOTNULL AUTOINCREMENT,
	teamname C(128),
	phone C(32),
	mail C(128),
	password C(32),
	event_id I NOTNULL
';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix().'module_eventregistration_teams', $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$db->CreateSequence(cms_db_prefix().'module_eventregistration_teams_seq');

$this->SetPreference('from', 'root@localhost');
$this->SetPreference('fromuser', 'CMS Administrator');

$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));

?>

