<?php

	namespace Phinq;

	use Closure;

	abstract class OrderedQuery extends LambdaDrivenQuery {

		private $descending;

		public function __construct(Expression $expression, $descending = false) {
			parent::__construct($expression);
			$this->descending = (bool)$descending;
		}

		public final function isDescending() {
			return $this->descending;
		}

		public final function execute(array $collection) {
			usort($collection, $this->getSortingCallback());
			return $collection;
		}

		/**
		 * @return Closure
		 */
		public abstract function getSortingCallback();

	}
	
?>