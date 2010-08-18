<?php

	namespace Phinq;

	use ReflectionClass;
	
	class ReflectedQueryFactory implements QueryFactory {

		private static $reflectionCache = array();

		public function getQuery($queryType, array $expressions = array()) {
			$className = sprintf('%s\%sQuery', $this->getNamespace(), $queryType);
			if (!isset(self::$reflectionCache[$className])) {
				self::$reflectionCache[$className] = new ReflectionClass($className);
			}

			return $this->createQueryInstance(self::$reflectionCache[$className], $expressions);
		}

		protected function createQueryInstance(ReflectionClass $class, array $args) {
			return $class->newInstanceArgs($args);
		}

		protected function getNamespace() {
			return __NAMESPACE__;
		}

	}

?>