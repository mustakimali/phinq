<?php

	namespace Phinq;

	use Closure;

	class OrderedPhinq extends Phinq {

		public function __construct($collection, QueryFactory $queryFactory, array $queries) {
			parent::__construct($collection, $queryFactory, $queries);
		}

		/**
		 * Performs a subsequent sort
		 *
		 * @param mixed $expression
		 * @param bool $descending Whether to sort in descending order
		 * @return OrderedPhinq
		 */
		public function thenBy($expression, $descending = false) {
			$this->addToQueue($this->getQueryFactory()->getQuery(QueryType::ThenBy, array($this->getLastQuery(), $expression, $descending)));
			return $this;
		}

	}
	
?>