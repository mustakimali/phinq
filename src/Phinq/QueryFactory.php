<?php

	namespace Phinq;
	
	interface QueryFactory {

		/**
		 * @param string $queryType One of the QueryType constants
		 * @param array $expressions
		 * @return Query
		 */
		function getQuery($queryType, array $expressions = array());
	}

?>