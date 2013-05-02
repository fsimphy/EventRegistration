<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if(isset($params['input_from']))
{
	$this->SetPreference('from',$params['input_from']);
}

if(isset($params['input_fromuser']))
{
	$this->SetPreference('fromuser',$params['input_fromuser']);
}

$message = $this->Lang('preferences_updated_sucessfully');
$this->Redirect($id, 'defaultadmin', '', Array('module_message'=>$message, 'active_tab' => 'settings'));
?>
