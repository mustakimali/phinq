<?php

	namespace Phinq;
	
	class Expression {

		private $parameters;
		private $body;
		private $closureVariables;

		public function __construct(array $parameters, $body, array $closureVariables = array()) {
			$this->parameters = $parameters;
			$this->body = $body;
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
			if (empty($this->closureVariables)) {
				return create_function('$' . implode(', $', $this->parameters), 'return ' . $this->body . ';');
			}

			foreach ($this->closureVariables as $__phinq_var => &$__phinq_value) {
				$__phinq_var = str_replace(array('&', '$'), '', $__phinq_var);
				$$__phinq_var =& $__phinq_value;
			}

			$code = 'return function(' . '$' . implode(', $', $this->parameters) . ') use (' . implode(', ', array_keys($this->closureVariables)) . ') { return ' . $this->body . '; };';
			return eval($code);
		}

	}

?>