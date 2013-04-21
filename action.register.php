<?php

function generateRandStr($length)
{
	$randstr = ""; 
	for($i=0; $i<$length; $i++)
	{ 
		$randnum = mt_rand(0,61); 
		if($randnum < 10)
		{
			$randstr .= chr($randnum+48); 
		}
		else if($randnum < 36)
		{ 
			$randstr .= chr($randnum+55); 
		}
		else
		{
			$randstr .= chr($randnum+61);
		}
	}
	return $randstr;
}

function sendMail($To, $Mailtext, $Subject, $From, $Bcc='')
{
	if(!empty($Bcc))
	$headers = '';
	$headers .= "Bcc: {$Bcc}\r\n";
	$headers .= "From: {$From}\r\n" .
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/plain; charset=UTF-8\r\n" .
	"Content-Transfer-Encoding: 8bit\r\n";
	mail($To, $Subject, $Mailtext, $headers);
}

if(!isset($gCms)) exit;
if(empty($params['eventid']))
{
	$message = $this->Lang('event_does_not_exist');
	$this->smarty->assign('message', $message);
	echo $this->ProcessTemplate('message.tpl');
	return;
}

$counter = 0;
$table='';
$register = true;
$update = false;
$eventid = mysql_real_escape_string($params['eventid']);
for($i=1;$i<=10;$i++)
{
	if(!empty($params["member$i"]))
		$counter++;
}

if(empty($params['teamname']) || empty($params['mail']) || $counter < 5)
{
	$message = $this->Lang('error_not_enough_input');
}
else
{
	$db = $gCms->GetDb();
	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration WHERE id=?';
	$Res = $db->Execute($sql, Array($eventid));
	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventname = $row['eventname'];
			$table = cms_db_prefix().'module_eventregistration_'.strtolower(str_replace(' ', '', mysql_real_escape_string($eventname)));
			$Res = $db->Execute('SELECT teamname, password FROM '.$table.' ORDER BY id');
			if($Res !== false)
			{
				while($row = $Res->FetchRow())
				{
					if(strcmp($row['teamname'], $params['teamname']) == 0)
					{
						if(!empty($params['password']) && strcmp($row['password'], $params['password']) == 0)
						{
							$update = true;
						}
						else
							$message = $this->Lang('error_teamname_already_used');
						$register = false;
					}
				}
			}
			else
				$message = $this->Lang('error_database');

			if(empty($params['password']))
				$password = generateRandStr(10);
			else
			{
				$password = $params['password'];
			}
					
			if($register)
			{
				$command= 'INSERT INTO '.$table.' (teamname, mail, member1, member2, member3, member4, member5, member6, member7, member8, member9, member10, password) VALUES(\''.$params['teamname'].'\', \''.$params['mail'].'\', \''.$params['member1'].'\', \''.$params['member2'].'\', \''.$params['member3'].'\', \''.$params['member4'].'\', \''.$params['member5'].'\', \''.$params['member6'].'\', \''.$params['member7'].'\', \''.$params['member8'].'\', \''.$params['member9'].'\', \''.$params['member10'].'\', \''.$password.'\')';
				$Res = $db->Execute($command);
				if($Res !== false)
				{
					$message = $this->Lang('registration_successful');
					//TODO: remove hardcoded "From"
					sendMail($params['mail'], $this->Lang('mail_text_register', $eventname, $params['teamname'], $password), $this->Lang('mail_subject_register', $eventname), 'Fachschaft Mathematik/Physik Universität Regensburg<physik.fachschaft@physik.uni-regensburg.de>');
				}
				else
				{
					$message = $this->Lang('error_database');
				}
			}
			else if($update)
			{
				$command = 'UPDATE '.$table.' SET mail=\''.$params['mail'].'\', member1=\''.$params['member1'].'\', member2=\''.$params['member2'].'\', member3=\''.$params['member3'].'\', member4=\''.$params['member4'].'\', member5=\''.$params['member5'].'\', member6=\''.$params['member6'].'\', member7=\''.$params['member7'].'\', member8=\''.$params['member8'].'\', member9=\''.$params['member9'].'\', member10=\''.$params['member10'].'\' WHERE password=\''.$password.'\' AND teamname=\''.$params['teamname'].'\'';
				$Res = $db->Execute($command);
				if($Res !== false)
				{
					$message = $this->Lang('update_successful');
					//TODO: remove hardcoded "From"
					sendMail($params['mail'], $this->Lang('mail_text_update', $eventname, $params['teamname'], $password), $this->Lang('mail_subject_update', $eventname), 'Fachschaft Mathematik/Physik Universität Regensburg<physik.fachschaft@physik.uni-regensburg.de>');

				}
			}
		}
		else
		{
			$message = $this->Lang('event_does_not_exist');
		}
	}
}
$this->smarty->assign('message', $message);
echo $this->ProcessTemplate('message.tpl');

?>
