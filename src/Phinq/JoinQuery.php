<?php

	namespace Phinq;

	class JoinQuery extends JoinableQuery {

		public function execute(array $collection) {
			$innerKeySelector = $this->getInnerKeySelector();
			$outerKeySelector = $this->getOuterKeySelector();
			$resultSelector   = $this->getResultSelector();
			$comparer         = $this->getComparer();
			$outerCollection  = $this->getCollection();
			$outerCount       = count($outerCollection);
			$newCollection    = array();

			array_walk(
				$collection,
				function($value, $key) use ($innerKeySelector, $outerKeySelector, $resultSelector, $comparer, $outerCount, $outerCollection, &$newCollection) {
					$innerKey = $innerKeySelector($value);
					for ($i = 0; $i < $outerCount; $i++) {
						if ($comparer->equals($innerKey, $outerKeySelector($outerCollection[$i])) === 0) {
							$newCollection[] = $resultSelector($value, $outerCollection[$i]);
						}
					}
				}
			);

			return $newCollection;
		}
	}
	
?>