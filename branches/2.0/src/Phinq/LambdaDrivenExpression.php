<?php

	namespace Phinq;

	use Closure;

	abstract class LambdaDrivenExpression implements Expression, LambdaDriven {

		private $lambda;

		public function __construct(Closure $lambda = null) {
			$this->lambda = $lambda;
		}

		public function getLambdaExpression() {
			return $this->lambda;
		}
	}

?>