<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Use EventRegistration'))
{
	echo $this->ProcessTemplate('accessdenied.tpl');
	return false;
}

$text = '';

$text .= $this->CreateFormStart($id, 'createevent');
$text .= $this->Lang('eventname').$this->CreateInputText($id, 'eventname', '', 20, 128);
$text .= $this->Lang('maxmembersperteam').':'.$this->CreateInputNumber($id, 'maxmembersperteam', '10');
$text .= $this->Lang('minmembersperteam').':'.$this->CreateInputNumber($id, 'minmembersperteam', '1');
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
?>

