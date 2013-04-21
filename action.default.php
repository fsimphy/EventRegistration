<?php
if (!isset($gCms)) exit;

if(empty($params['eventid']))
{
	$this->smarty->assign('message', $this->Lang('event_does_not_exist'));
	echo $this->ProcessTemplate('message.tpl');
	return false;
}

$this->smarty->assign('teamname', $this->Lang('teamname'));
$this->smarty->assign('mail', $this->Lang('mail'));
for($i=1;$i<=10;$i++)
{
	$this->smarty->assign('member'.$i, $this->Lang('member')." $i");
	$this->smarty->assign('member'.$i.'input', $this->CreateInputText($id, "member$i", '', 20, 128));
}

$this->smarty->assign('message', $params['message']); 

$this->smarty->assign('password', $this->Lang('password'));
$this->smarty->assign('teamname', $this->Lang('teamname'));
$this->smarty->assign('passwordinput', $this->CreateInputPassword($id, 'password', '', 20, 32));
$this->smarty->assign('teamnameinput', $this->CreateInputText($id, 'teamname', '', 20, 128));
$this->smarty->assign('mailinput', $this->CreateInputText($id, 'mail', '', 20, 128));
$this->smarty->assign('volleyballsubmit', $this->CreateInputSubmit($id, 'submit', $this->Lang('register')));
$this->smarty->assign('eventid', $this->CreateInputHidden($id, 'eventid', $params['eventid']));
$this->smarty->assign('startform', $this->CreateFormStart($id, 'register', $returnid));
$this->smarty->assign('endform', $this->CreateFormEnd());

echo $this->ProcessTemplate('form.tpl');
?>

