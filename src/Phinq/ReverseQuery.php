<?php

	namespace Phinq;

	class ReverseQuery implements Query {

		/**
		 * Just a dummy constructor so that we can instantiate it via
		 * reflection without doing something annoying
		 *
		 * @ignore
		 */
		public function __construct() {}

		public function execute(array $collection) {
			return array_reverse($collection);
		}
	}
	
?>