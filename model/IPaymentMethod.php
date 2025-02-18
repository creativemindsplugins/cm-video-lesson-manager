<?php

namespace com\cminds\videolesson\model;

interface IPaymentMethod {
	
	function isPayed();
	function getCosts();
	function setCosts($costs);
	
	static function isAvailable();
	
}