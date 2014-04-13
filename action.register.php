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

$register = true;
$update = false;

if(empty($params['teamname']) || empty($params['mail']) || empty($params['phone']))
{
	$message = $this->Lang('error_not_enough_input');
}
else
{
	$eventid = mysql_real_escape_string($params['eventid']);
	$teamname = mysql_real_escape_string($params['teamname'];
	$password = mysql_real_escape_string($params['password']);
	$mail = mysql_real_escape_string($params['mail']);
	$phone = mysql_real_escape_string($params['phone']);

	$db = $gCms->GetDb();
	$sql = 'SELECT * FROM '.cms_db_prefix().'module_eventregistration_events WHERE id=?';
	$Res = $db->Execute($sql, Array($eventid));
	if($Res !== false)
	{
		if($row = $Res->FetchRow())
		{
			$eventname = $row[eventname;]
			$sql = 'SELECT teamname, password FROM '.cms_db_prefix().'module_eventregistration_teams WHERE event_id=? ODER by id';
			$Res = $db->Execute($sql, Array($eventid));
			if($Res !== false)
			{
				while($row = $Res->FetchRow())
				{
					if(strcmp($row['teamname'], $teamname) == 0)
					{
						if(!empty($password) && strcmp($row['password'], $password) == 0)
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

			if(empty($password))
				$password = generateRandStr(10);
					
			if($register)
			{
				$command= 'INSERT INTO '.$table.' (teamname, phone, mail, password, event_id) VALUES(\''.$teamname.'\', \''.$phone.'\', \''.$mail.'\', \''.$password.'\', \''.$eventid.'\')';
				$Res = $db->Execute($command);
				if($Res !== false)
				{
					$message = $this->Lang('registration_successful');
					sendMail($mail, $this->Lang('mail_text_register', $eventname, $teamname, $password), $this->Lang('mail_subject_register', $eventname), $this->GetPreference('fromuser').'<'.$this->GetPreference('from').'>');
				}
				else
				{
					$message = $this->Lang('error_database');
				}
			}
			else if($update)
			{
				$command = 'UPDATE '.$table.' SET mail=\''.$mail.'\', phone=\''.$phone.'\' WHERE password=\''.$password.'\' AND teamname=\''.$teamname.'\'';
				$Res = $db->Execute($command);
				if($Res !== false)
				{
					$message = $this->Lang('update_successful');
					sendMail($mail, $this->Lang('mail_text_update', $eventname, $teamname, $password), $this->Lang('mail_subject_update', $eventname), $this->GetPreference('fromuser').'<'.$this->GetPreference('from').'>');

				}
				else
				{
					$message = $this->Lang('error_database');
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
