<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PDF{
	
	public function __construct()	{			    		
		require_once __DIR__.'/tcpdf/tcpdf.php';
	}	
	
}
?>