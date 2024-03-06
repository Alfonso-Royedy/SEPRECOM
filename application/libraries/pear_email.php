<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PEAR_EMail {
	
	public function __construct()	{			    		
		require_once __DIR__.'/pear_email/Mail.php';
	}	
	
}
?>