<?php

	namespace Phinq;

	use ReflectionException, InvalidArgumentException, ReflectionClass;
	
	class ReflectedQueryFactory implements QueryFactory {

		private static $reflectionCache = array();

		public function getQuery($queryType, array $expressions = array()) {
			$className = 'Phinq\\' . $queryType . 'Query';
			if (!isset(self::$reflectionCache[$className])) {
				self::$reflectionCache[$className] = new ReflectionClass($className);
			}

			return self::$reflectionCache[$className]->newInstanceArgs($expressions);
		}

	}

?>