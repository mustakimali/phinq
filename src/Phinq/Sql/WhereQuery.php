<?php

	namespace Phinq\Sql;

	use Phinq\Query;
	
	class WhereQuery extends DatabaseDrivenQuery {

		public function execute(array $collection) {
			$collection = array(
				'data' => array(
					'id' => 'int',
					'username' => 'string',
					'password' => 'binary',
					'active' => 'bool',
					'created' => 'timestamp',
					'expires' => 'datetime'
				),
				'query' => array(
					'select' => array(),
					'table' => 'users',
					'where' => array(),
					'groupby' => array(),
					'orderby' => array(),
					'limit' => '',
					'offset' => '',
					'join' => array()
				)
			);

			$collection['query']['where'][] = new WhereClause();

			return $collection;
		}

		
		
	}

?>