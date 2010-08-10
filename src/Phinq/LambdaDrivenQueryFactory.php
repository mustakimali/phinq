<?php

	namespace Phinq;

	use ReflectionException, InvalidArgumentException, ReflectionClass;
	
	class LambdaDrivenQueryFactory implements QueryFactory {

		private static $reflectionCache = array();

		public function getQuery($queryType, array $expressions) {
			$className = 'Phinq\\' . $queryType . 'Query';
			
			try {
				$class = self::getClass($className);
			} catch (ReflectionException $e) {
				throw new InvalidArgumentException('Unknown query type: ' . $queryType, $e->getCode(), $e);
			}

			return $class->newInstanceArgs($expressions);
		}

		/**
		 * @param string $className
		 * @return ReflectionClass
		 */
		private static function getClass($className) {
			if (!isset(self::$reflectionCache[$className])) {
				self::$reflectionCache[$className] = new ReflectionClass($className);
			}

			return self::$reflectionCache[$className];
		}

	}

?>