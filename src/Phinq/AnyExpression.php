<?php

	namespace Phinq;

	use Closure;
	
	class AnyExpression extends LambdaDrivenExpression {

		public function evaluate(array $collection) {
			$predicate = $this->getLambdaExpression();
			if ($predicate === null && !empty($collection)) {
				return true;
			}

			foreach ($collection as $value) {
				if ($predicate($value)) {
					return true;
				}
			}

			return false;
		}

	}

?>