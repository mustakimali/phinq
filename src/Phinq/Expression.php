<?php

	namespace Phinq;
	
	class Expression {

		private $parameters;
		private $body;

		public function __construct(array $parameters, $body) {
			$this->parameters = $parameters;
			$this->body = $body;
		}

		public function getParameters() {
			return $this->parameters;
		}

		public function getBody() {
			return $this->body;
		}

		/**
		 * @return string
		 */
		public function toLambda() {
			return create_function('$' . implode(', $', $this->parameters), 'return ' . $this->body . ';');
		}

	}

?>