<?php

	namespace Phinq;

	use Closure;

	class AllExpression extends LambdaDrivenExpression {

		/**
		 * This constructor just makes sure that $predicate is required
		 * rather than optional
		 */
		public function __construct(Closure $predicate) {
			parent::__construct($predicate);
		}

		public function evaluate(array $collection) {
			$predicate = $this->getLambdaExpression();
			return array_reduce($collection, function($current, $next) use ($predicate) { return $current && $predicate($next); }, true);
		}

	}

?>