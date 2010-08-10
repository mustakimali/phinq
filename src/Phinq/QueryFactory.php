<?php

	namespace Phinq;
	
	interface QueryFactory {

		/**
		 * @param string $queryType One of the QueryType constants
		 * @param array $expressions
		 * @return Query
		 */
		function getQuery($queryType, array $expressions);

		/**
		 * @param string $expressionType One of the ExpressionType constants
		 * @param array $expressions
		 * @return mixed
		 */
		function getExpression($expressionType, array $expressions);
	}

?>