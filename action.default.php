<?php
if (!isset($gCms)) exit;

if(empty($params['eventid']))
{
	$this->smarty->assign('message', $this->Lang('event_does_not_exist'));
	echo $this->ProcessTemplate('message.tpl');
	return false;
}

$eventid = $params['eventid'];

$db = $gCms->GetDb();
$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
$Res = $db->Execute($sql, Array($eventid));
if($Res !== false)
{
	if($row = $Res->FetchRow())
	{
		$members = '';
		$this->smarty->assign('teamname', $this->Lang('teamname'));
		$this->smarty->assign('phone', $this->Lang('phone'));
		$this->smarty->assign('mail', $this->Lang('mail'));
		$this->smarty->assign('password', $this->Lang('password'));
		$this->smarty->assign('message', $params['message']);
		$this->smarty->assign('teamnameinput', $this->CreateInputText($id, 'teamname', '', 20, 128));
		$this->smarty->assign('phoneinput', $this->CreateInputText($id, 'phone', '', 20, 128));
		$this->smarty->assign('mailinput', $this->CreateInputText($id, 'mail', '', 20, 128));
		$this->smarty->assign('passwordinput', $this->CreateInputPassword($id, 'password', '', 20, 32));
		$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('register')));
		$this->smarty->assign('eventid', $this->CreateInputHidden($id, 'eventid', $params['eventid']));
		$this->smarty->assign('startform', $this->CreateFormStart($id, 'register', $returnid));
		$this->smarty->assign('endform', $this->CreateFormEnd());

		echo $this->ProcessTemplate('form.tpl');
	}
	else
	{
		$this->smarty->assign('message', $this->Lang('event_does_not_exist'));
		echo $this->ProcessTemplate('message.tpl');
	}
}
?>

