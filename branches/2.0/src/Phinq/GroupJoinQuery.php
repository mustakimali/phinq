<?php

	namespace Phinq;

	use Closure;

	class GroupJoinQuery extends JoinableQuery {

		public function execute(array $collection) {
			$innerKeySelector = $this->getInnerKeySelector();
			$outerKeySelector = $this->getOuterKeySelector();
			$comparer         = $this->getComparer();
			$outerCollection  = $this->getCollection();
			$outerCount       = count($outerCollection);
			$dictionary       = new GroupingDictionary();

			array_walk(
				$collection,
				function($value, $key) use ($innerKeySelector, $outerKeySelector, $comparer, $outerCount, $outerCollection, &$dictionary) {
					$innerKey = $innerKeySelector($value);
					for ($i = 0; $i < $outerCount; $i++) {
						if ($comparer->equals($innerKey, $outerKeySelector($outerCollection[$i])) === 0) {
							$dictionary[$value] = $outerCollection[$i];
						}
					}
				}
			);

			//there's probably a more efficient way to do this... but does anybody ever actually use GroupJoin()? I mean, come on!
			$resultSelector = $this->getResultSelector();
			$newCollection = array();
			foreach ($collection as $value) {
				if (isset($dictionary[$value])) {
					$newCollection[] = $resultSelector($value, $dictionary[$value]);
				} else {
					$newCollection[] = $resultSelector($value, array());
				}
			}

			return $newCollection;
		}
	}

?>