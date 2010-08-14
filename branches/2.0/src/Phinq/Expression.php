<?php

	namespace Phinq;
	
	class Expression {

		private $parameters;
		private $body;
		private $closureVariables;

		public function __construct(array $parameters, $body, array $closureVariables = array()) {
			$this->parameters = $parameters;
			$this->body = trim($body);
			$this->closureVariables = $closureVariables;
		}

		public function getParameters() {
			return $this->parameters;
		}

		public function getBody() {
			return $this->body;
		}

		public function getClosureVariables() {
			return $this->closureVariables;
		}

		/**
		 * @return string|Closure
		 */
		public function toLambda() {
			$__phinq_body = $this->getValidBodyStatement();
			$__phinq_params = implode(', ', $this->parameters);
			if (empty($this->closureVariables)) {
				return create_function($__phinq_params, $__phinq_body);
			}

			return $this->createClosure($__phinq_params, $__phinq_body);
		}

		private function createClosure($__phinq_params, $__phinq_body) {
			foreach ($this->closureVariables as $__phinq_var => &$__phinq_value) {
				$__phinq_var = str_replace(array('&', '$'), '', $__phinq_var);
				$$__phinq_var =& $__phinq_value;
			}

			$__phinq_useVars = implode(', ', array_keys($this->closureVariables));
			return eval("return function($__phinq_params) use ($__phinq_useVars) { $__phinq_body };");
		}

		protected function getValidBodyStatement() {
			if (strpos('{', $this->body) === 0) {
				//assumed to be valid PHP code
				return trim($this->body, '{}');
			}

			//otherwise it's a return statement
			return 'return ' . $this->body . ';';

		}

	}

?>