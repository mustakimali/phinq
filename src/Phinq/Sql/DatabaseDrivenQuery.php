<?php

	namespace Phinq\Sql;

	use Phinq\Query;
	use Phinq\Expression;

	abstract class DatabaseDrivenQuery implements DatabaseDriven, Query {

		private $expression;
		private $driverType;

		public function __construct(Expression $expression, $driverType) {
			$this->expression = $expression;
			$this->driverType = $driverType;
		}

		public function getDriverType() {
			return $this->driverType;
		}

		public function getExpression() {
			return $this->expression;
		}

	}

?>