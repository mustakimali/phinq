<?php

	namespace Phinq;

	use Closure;

	class ToDictionaryExpression extends LambdaDrivenExpression {

		public function __construct(Closure $keySelector) {
			parent::__construct($keySelector);
		}

		public function evaluate(array $collection) {
			$keySelector = $this->getLambdaExpression();
			$dictionary = new Dictionary();
			for ($i = 0, $count = count($collection); $i < $count; $i++) {
				$dictionary[$keySelector($collection[$i])] = $collection[$i];
			}

			return $dictionary;
		}

	}

?>