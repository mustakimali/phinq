<?php

	namespace Phinq;
	
	class MaxExpression extends SortedExpression {

		protected function isDescending() {
			return true;
		}
	}

?>