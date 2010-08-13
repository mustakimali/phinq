<?php

	namespace Phinq;

	abstract class JoinableQuery extends ComparableQuery {

		private $collectionToJoinOn;
		private $innerKeySelector;
		private $outerKeySelector;
		private $resultSelector;

		public function __construct(array $collectionToJoinOn, Expression $innerKeySelector, Expression $outerKeySelector, Expression $resultSelector, EqualityComparer $comparer = null) {
			parent::__construct($comparer);
			$this->collectionToJoinOn = $collectionToJoinOn;
			$this->innerKeySelector = $innerKeySelector->toLambda();
			$this->outerKeySelector = $outerKeySelector->toLambda();
			$this->resultSelector = $resultSelector->toLambda();
		}

		protected function getCollection() {
			return $this->collectionToJoinOn;
		}

		protected function getInnerKeySelector() {
			return $this->innerKeySelector;
		}

		protected function getOuterKeySelector() {
			return $this->outerKeySelector;
		}

		protected function getResultSelector() {
			return $this->resultSelector;
		}
		
	}

	?>