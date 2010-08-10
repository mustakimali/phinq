<?php

	namespace Phinq;

	use ReflectionException, InvalidArgumentException, ReflectionClass;
	
	class LambdaDrivenQueryFactory implements QueryFactory {

		private static $reflectionCache = array();

		public function getQuery($queryType, array $expressions) {
			return self::getInstance('Phinq\\' . $queryType . 'Query', $expressions);
		}

		public function getExpression($expressionType, array $expressions) {
			return self::getInstance('Phinq\\' . $expressionType . 'Expression', $expressions);
		}

		private static function getInstance($className, array $args) {
			if (!isset(self::$reflectionCache[$className])) {
				self::$reflectionCache[$className] = new ReflectionClass($className);
			}

			return self::$reflectionCache[$className]->newInstanceArgs($args);
		}

	}

?>