<?php

	namespace Phinq;

	use Closure;

	class AggregateExpression extends LambdaDrivenExpression {

		private $seed;

		public function __construct(Closure $accumulator, $seed = null) {
			parent::__construct($accumulator);
			$this->seed = $seed;
		}

		public function evaluate(array $collection) {
			return array_reduce($collection, $this->getLambdaExpression(), $this->seed);
		}

	}

?>