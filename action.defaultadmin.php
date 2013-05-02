<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

if (!empty($params['active_tab'])) {
    $tab = $params['active_tab'];
} else {
  $tab = '';
}


$text = '';

$text .= $this->StartTabHeaders();
	$text .= $this->SetTabHeader('overview',$this->Lang('tab_title_overview'), ('overview' == $tab)?true:false);
	$text .= $this->SetTabHeader('settings', $this->Lang('tab_title_settings'), ('settings' == $tab)?true:false);
$text .= $this->EndTabHeaders();

$text .= $this->StartTabContent();
	$text .= $this->StartTab('overview', $params);
		$text .= $this->CreateFormStart($id, 'createevent');
		$text .= $this->Lang('eventname').':'.$this->CreateInputText($id, 'eventname', '', 20, 128);
		$text .= $this->Lang('maxmembersperteam').':'.$this->CreateInputText($id, 'maxmembersperteam', '10');
		$text .= $this->Lang('minmembersperteam').':'.$this->CreateInputText($id, 'minmembersperteam', '1');
		$text .= $this->CreateInputSubmit($id, 'submit', $this->Lang('createevent'));
		$text .= $this->CreateFormEnd();

		$text .= '<h3>'.$this->Lang('events').'</h3>';
		$text .= '<ul>';
		$db = $gCms->GetDb();
		$Res = $db->Execute('SELECT * FROM '.cms_db_prefix().'module_eventregistration ORDER BY id');
		if($Res !== false)
		{
			while($row = $Res->FetchRow())
			{
				$text .= '<li>'.$this->CreateLink($id, 'viewevent', '', $row['eventname'].' ('.$row['id'].')', Array('eventid'=>$row['id'])).'</li>';
			}
		}
		$text .= '</ul>';


	$text .= $this->EndTab();
	$text .= $this->StartTab('settings', $params);
		$text .= $this->CreateFormStart($id, 'setadminprefs', $returnid);
		$text .= $this->Lang('fromuser').':'.$this->CreateInputText( $id, 'input_fromuser', $this->GetPreference('fromuser'), 50, 128);
		$text .= $this->Lang('from').':'.$this->CreateInputText( $id, 'input_from', $this->GetPreference('from'), 50, 128);
		$text .= $this->CreateInputSubmit($id, 'submit', $this->Lang('save'));
		$text .= $this->CreateFormEnd();
	$text .= $this->EndTab();
$text .= $this->EndTabContent();

$this->smarty->assign('text', $text);
echo  $this->ProcessTemplate('adminpanel.tpl');
?>

