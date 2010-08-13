<?php

	namespace Phinq;

	use Closure;

	abstract class SortedExpression implements Expression, LambdaDriven {

		private $lambda;

		public function __construct(Closure $lambda = null) {
			$this->lambda = $lambda ?: function($value) { return $value; };
		}

		public final function evaluate(array $collection) {
			usort($collection, Util::getDefaultSortCallback($this->getLambdaExpression(), $this->isDescending()));
			return @$collection[0];
		}

		public function getLambdaExpression() {
			return $this->lambda;
		}

		/**
		 * @return bool
		 */
		protected abstract function isDescending();
	}

?>