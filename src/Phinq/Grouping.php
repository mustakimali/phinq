<?php

	namespace Phinq;

	class Grouping extends Phinq {

		private $key;

		public function __construct(array $collection, $key, QueryFactory $queryFactory) {
			parent::__construct($collection, $queryFactory);
			$this->key = $key;
		}

		public final function getKey() {
			return $this->key;
		}

	}
	
?>