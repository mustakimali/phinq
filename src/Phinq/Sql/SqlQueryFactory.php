<?php

	namespace Phinq\Sql;

	use PDO, ReflectionClass;
	use Phinq\ReflectedQueryFactory;
	
	class SqlQueryFactory extends ReflectedQueryFactory {

		private $pdo;
		private $driverType;

		public function __construct(PDO $pdo, $driverType) {
			$this->pdo = $pdo;
			$this->driverType = $driverType;
		}

		protected function createQueryInstance(ReflectionClass $class, array $args) {
			array_unshift($args, $this->driverType);
			return $class->newInstanceArgs($args);
		}

		protected function getNamespace() {
			return __NAMESPACE__;
		}
		
	}

?>