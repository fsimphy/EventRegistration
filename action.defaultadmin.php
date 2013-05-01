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

echo $this->StartTabHeaders();
	echo $this->SetTabHeader('overview',$this->Lang('tab_title_overview'), ('overview' == $tab)?true:false);
	echo $this->SetTabHeader('settings', $this->Lang('tab_title_settings'), ('settings' == $tab)?true:false);
echo $this->EndTabHeaders();

echo $this->StartTabContent();
	echo $this->StartTab('overview');
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

		$this->smarty->assign('text', $text);

		echo $this->ProcessTemplate('adminpanel.tpl');
	echo $this->EndTab();
	echo $this->StartTab('settings');
		$text .= $this->CreateFormStart($id, 'setadminprefs', $returnid);
		$text .= $this->Lang('fromuser').$this->CreateInputText( $id, 'input_fromuser', $this->GetPreference('fromuser'), 50, 80));
		$text .= $this->Lang('from').$this->CreateInputText( $id, 'input_from', $this->GetPreference('from'), 80, 80));
		$text .= $this->CreateInputSubmit($id, 'submit', $this->Lang('save'));
		$text .= $this->CreateFormEnd());
	echo $this->EndTab();
echo $this->EndTabContent();


?>

