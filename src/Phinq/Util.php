<?php

	namespace Phinq;

	use Traversable, InvalidArgumentException, Closure;

	final class Util {

		//@codeCoverageIgnoreStart
		private function __construct() {}
		//@codeCoverageIgnoreEnd

		public static function compare($a, $b) {
			if (is_int($a) || is_float($a)) {
				return (is_int($b) || is_float($b)) && $a == $b ? 0 : ($a < $b ? -1 : 1);
			} else if (is_object($a)) {
				return is_object($b) && $a === $b ? 0 : 1; //can't cast object to int
			} else {
				return gettype($a) === gettype($b) && $a === $b ? 0 : ($a < $b ? -1 : 1);
			}
		}

		public static function convertToNumericallyIndexedArray($collection) {
			if (is_array($collection)) {
				return array_values($collection);
			} else if ($collection instanceof Phinq) {
				return $collection->toArray();
			} else if ($collection instanceof Traversable) {
				$array = array();
				foreach ($collection as $value) {
					$array[] = $value;
				}
				return $array;
			}

			throw new InvalidArgumentException('Unable to convert value to an array');
		}

		public static function getDefaultSortCallback(Closure $lambda, $descending) {
			$direction = $descending ? -1 : 1;

			return function($a, $b) use ($lambda, $direction) {
				$resultA = $lambda($a);
				$resultB = $lambda($b);

				if ($resultA == $resultB) {
					return 0;
				}

				return $resultA < $resultB ? 1 * -$direction : $direction;
			};
		}

	}

?>