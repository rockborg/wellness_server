<?php
function CopyGetToPost($getarray = array())
{
	foreach ($getarray as $key => $value) {
		$_POST[$key] = $value;
	}
}
?>