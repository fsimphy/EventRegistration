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
    id I KEY NOTNULL AUTOINCREMENT PRIMARY,
    eventname C(128),
    maxmembersperteam UNSIGNED TINYINT,
    minmembersperteam UNSIGNED TINYINT
    ';

$sqlarray = $dict->CreateTableSQL(cms_db_prefix().'module_eventregistration', $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$db->CreateSequence(cms_db_prefix().'module_eventregistration_seq');

$this->CreatePermission('Use EventRegistration', $this->Lang('permission'));

$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));

?>

