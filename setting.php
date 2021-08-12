<?php

$con = mysqli_connect("localhost","root","","tekvir");

function cmd ($command){
	return shell_exec("PATH=%PATH%;C:\\Program Files (x86)\\VMware\\VMware VIX\\Workstation-15.0.0\\32bit&&".$command);
}
set_time_limit(60);
?>