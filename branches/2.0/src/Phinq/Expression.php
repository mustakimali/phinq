<?php

	namespace Phinq;
	
	interface Expression {
		/**
		 * @param array $collection
		 * @return mixed
		 */
		function evaluate(array $collection);
	}

?>