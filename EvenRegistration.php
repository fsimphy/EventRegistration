<?php
#-------------------------------------------------------------------------
# Module: EventRegistration
# Version: 0.1
#-------------------------------------------------------------------------

class EventRegistration extends CMSModule{

    function GetName()
    {
        return 'EventRegistration';
    }

    function GetFriendlyName()
    {
        return $this->Lang('friendlyname');
    }

    function SetParameters()
    {
        $this->RegisterModulePlugin();
        $this->RestrictUnknownParams();
		$this->CreateParameter('teamname');
        $this->SetParameterType('teamname', CLEAN_STRING);
 		$this->CreateParameter('mail');
        $this->SetParameterType('mail', CLEAN_STRING);
 		$this->CreateParameter('password');
        $this->SetParameterType('password', CLEAN_STRING);
  		$this->CreateParameter('submit');
        $this->SetParameterType('submit', CLEAN_STRING);
		$this->CreateParameter('message');
		$this->SetParameterType('message', CLEAN_STRING);
		for($i=1;$i<=10;$i++)
		{	
			$this->CreateParameter("member$i");
			$this->SetParameterType("member$i", CLEAN_STRING);
 		}
        $this->CreateParameter('eventname');
        $this->SetParameterType('eventname', CLEAN_STRING);
        $this->CreateParameter('eventid');
        $this->SetParameterType('eventid', CLEAN_INT);
    }

    function GetVersion()
    {
        return '0.1';
    }

    function GetHelp()
    {
        return $this->Lang('help');
    }

    function GetAuthor()
    {
        return 'Johannes Loher';
    }

    function GetAuthorEmail()
    {
        return 'johannes.loher@fg4f.de';
    }

    function GetChangeLog()    {
        return $this->Lang('changelog');
    }

    function IsPluginModule()
    {
        return true;
    }

    function HasAdmin()
    {
        return true;
    }

    function GetAdminSection()
    {
        return 'extensions';
    }

    function GetAdminDescription()
    {
        return $this->Lang('admindescription');
    }

    function VisibleToAdminUser()
    {
        return true;
        $this->CheckPermisson('Use EventRegistration');
    }

    function MinimumCMSVersion()
    {
        return '1.9';
    }

    function MaximumCMSVersion()
    {
        return '1.10.9';
    }

    function InstallPostMessage()
    {
        return $this->Lang('postinstall');
    }

    function UninstallPostMessage()
    {
        return $this->Lang('postuninstall');
    }

    function UninstallPreMessage()
    {
        return $this->Lang('really_uninstall');
    }
}

?>
