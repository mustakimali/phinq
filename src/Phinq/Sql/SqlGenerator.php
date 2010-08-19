<?php

	namespace Phinq\Sql;

	use Exception;
	use Phinq\Expression;
	use Phinq\ParserException;

	class SqlGenerator {

		public function generateSql(Expression $expression) {
			if ($expression->bodyHasMultipleStatements()) {
				throw new ParserException('Invalid expression body: must be one statement');
			}

			list($parameter) = $expression->getParameters();
			$tokens = token_get_all('<?php ' . $expression->getBody() . '?>');
			return $this->tokensToSql($tokens, 0, count($tokens), $parameter);
		}

		protected function tokensToSql(array $tokens, $start, $end, $parameter) {
			$query = '';
			for ($i = $start; isset($tokens[$i]) && $i < $end; $i++) {
				$token = $tokens[$i];
				if (is_array($token)) {
					switch ($token[0]) {
						case T_OPEN_TAG:
						case T_CLOSE_TAG:
							break;
						case T_WHITESPACE:
						case T_LNUMBER: //number
						case T_DNUMBER: //number
							$query .= $token[1];
							break;
						case T_CONSTANT_ENCAPSED_STRING: //quoted string
							$string = $token[1];
							if ($string[0] !== '\'') {
								//single quote the double-quoted string
								$string = '\'' . addslashes(stripslashes(substr($string, 1, strlen($string) - 2))) . '\'';
							}

							$query .= $string;
							break;
						case T_VARIABLE:
							$query .= $this->parseVariable($tokens, $i, $token[1], $parameter);
							break;
						case T_STRING:
							switch (strtolower($token[1])) {
								case 'strcmp':
									$query .= $this->compareStrings($tokens, $i);
									break;
								case 'str_replace':
									$query .= $this->replaceString($tokens, $i);
									break;
								case 'str_repeat':
									$query .= $this->repeatString($tokens, $i);
									break;
								case 'strrev':
									$query .= $this->reverseString($tokens, $i);
									break;
								case 'strlen':
									$query .= $this->getStringLength($tokens, $i);
									break;
								case 'strtolower':
									$query .= $this->toLowerCase($tokens, $i);
									break;
								case 'strtoupper':
									$query .= $this->toUpperCase($tokens, $i);
									break;
								case 'ord':
									$query .= $this->getUnicodePoint($tokens, $i);
									break;
								case 'preg_match':
									throw new Exception('Not implemented yet');
								case 'soundex':
									$query .= $this->soundex($tokens, $i);
									break;
								case 'substr':
									$args = $this->getFunctionArguments($tokens, $i);
									$count = count($args);
									if ($count < 2) {
										throw new ParserException('substr() must have exactly two or three arguments');
									}

									$string = $this->tokensToSql($args[0], 0, count($args[0]), $parameter);
									$start = $this->tokensToSql($args[1], 0, count($args[1]), $parameter);
									$length = isset($args[2]) ? $this->tokensToSql($args[2], 0, count($args[2]), $parameter) : null;
									$query .= $this->substring($string, $start, $length);
									break;
								case 'trim':
								case 'ltrim':
								case 'rtrim':
									$args = $this->getFunctionArguments($tokens, $i);
									$direction = $token[1] === 'trim' ? 'both' : ($token[1] === 'ltrim' ? 'left' : 'right');
									$count = count($args);
									if ($count !== 1 && $count !== 2) {
										throw new ParserException('Expected either one or two arguments to function "' . $token[1] . '"');
									}

									$stringToTrim = $this->tokensToSql($args[0], 0, count($args[0]), $parameter);
									$charsToTrim = '';
									if ($count === 2) {
										$charsToTrim = $this->tokensToSql($args[1], 0, count($args[1]), $parameter);
										if ($charsToTrim[0] === '\'' && strlen($charsToTrim) > 3) {
											throw new Exception('Trimming multiple characters is not supported');
										}
									}
									
									$query .= $this->trim($stringToTrim, $charsToTrim, $direction);
									break;
								case 'in_array':
									throw new Exception('Not implemented yet');
								default:
									throw new ParserException('Unsupported function call to "' . $token[1] . '"');
							}
							break;
						case T_IS_EQUAL: //==
						case T_IS_IDENTICAL: //===
							$query .= '=';
							break;
						case T_IS_NOT_EQUAL: //!=
						case T_IS_NOT_IDENTICAL: //!==
							$query .= '!=';
							break;
						case T_IS_GREATER_OR_EQUAL: //>=
						case T_IS_SMALLER_OR_EQUAL: //<=
							$query .= $token[1];
							break;
						case T_BOOLEAN_AND: //&&
							$query .= 'AND';
							break;
						case T_BOOLEAN_OR: //||
							$query .= 'OR';
							break;
						default:
							throw new ParserException(sprintf('Unexpected token of type "%s" in body', token_name($token[0])));
					}
				} else {
					switch ($token) {
						case '+': //addition
						case '-': //subtraction
						case '/': //division
						case '*': //multiplication
						case '&': //bitwise and
						case '|': //bitwise or
						case '(': //scope
						case ')': //scope
						case '^': //bitwise xor
						case '~': //bitwise complement
						case '%': //modulus
						case '>': //greater than
						case '<': //less than
						case ',': //commas are okay
							$query .= $token;
							break;
						case '!':
							//handle unary boolean expression
							throw new Exception('Not supported yet');
						default:
							throw new ParserException(sprintf('Unexpected character "%s" in body', $token));
					}
				}
			}

			return $query;
		}

		private function getFunctionArguments(array $tokens, &$i) {
			$depth = 0;
			$functionStarted = false;
			$args = array();
			$currentArg = array();
			while (isset($tokens[++$i])) {
				$token = $tokens[$i];
				if (!$functionStarted) {
					if ($token === '(') {
						$functionStarted = true;
						continue;
					} else if (is_array($token) && $token[0] === T_WHITESPACE) {
						continue;
					} else {
						throw new ParserException('Unexpected token after function call, expected whitespace or "("');
					}
				}

				if ($token === ')') {
					if ($depth === 0) {
						break;
					} else {
						$depth--;
					}
				} else if ($token === '(') {
					$depth++;
				} else if ($depth === 0 && $token === ',') {
					$args[] = $currentArg;
					$currentArg = array();
				} else if (@$token[0] !== T_WHITESPACE) {
					$currentArg[] = $token;
				}
			}

			if (!empty($currentArg)) {
				$args[] = $currentArg;
			}

			if (!$functionStarted) {
				throw new ParserException('Expected "(" after function call');
			}

			if ($depth !== 0) {
				throw new ParserException('Syntax error: "(" scope never closed during function call');
			}

			return $args;
		}

		protected function parseVariable(array $tokens, &$i, $variableName, $parameter) {
			if ($variableName !== $parameter) {
				throw new ParserException('Undefined variable "' . $variableName . '"');
			}

			$expression = '';
			$expectField = false;
			while (isset($tokens[++$i])) {
				switch ($tokens[$i][0]) {
					case T_WHITESPACE:
						break;
					case T_OBJECT_OPERATOR:
						$expectField = true;
						break;
					case T_STRING:
						if (!$expectField) {
							throw new ParserException('Unexpected token of type "' . token_name($tokens[$i][0]) . '"');
						}

						$expression = $tokens[$i][1];
						break 2;
					default:
						break 2;
				}
			}

			if (empty($expression)) {
				throw new ParserException('Parser does not know what to do with the variable "' . $variableName . '"');
			}

			return $expression;
		}

		protected function replaceString(array $tokens, &$i) {
			return 'REPLACE';
		}

		protected function compareStrings(array $tokens, &$i) {
			return 'STRCMP';
		}

		protected function repeatString(array $tokens, &$i) {
			return 'REPEAT';
		}

		protected function reverseString(array $tokens, &$i) {
			return 'REVERSE';
		}

		protected function getStringLength(array $tokens, &$i) {
			return 'LENGTH';
		}

		protected function toLowerCase(array $tokens, &$i) {
			return 'LOWER';
		}

		protected function getUnicodePoint(array $tokens, &$i) {
			return 'ORD';
		}

		protected function toUpperCase(array $tokens, &$i) {
			return 'UPPER';
		}

		protected function soundex(array $tokens, &$i) {
			return 'SOUNDEX';
		}

		/**
		 * @param string $haystack
		 * @param string $start
		 * @param int|null $length
		 * @return string
		 */
		protected function substring($haystack, $start, $length) {
			if ($length !== null) {
				$length = ' FOR ' . $length;
			}

			if (is_numeric($start)) {
				$start += 1; //string indexes start at one instead of zero
			}
			
			return 'SUBSTRING(' . $haystack . ' FROM ' . $start .  $length . ')';
		}

		/**
		 * @param string $stringToTrim
		 * @param string $charsToTrim If empty, will trim spaces
		 * @param string $direction "left", "right", or "both"
		 * @return string
		 */
		protected function trim($stringToTrim, $charsToTrim, $direction) {
			$leadingOrTrailing = $direction === 'right' ? 'TRAILING' : ($direction === 'left' ? 'LEADING' : 'BOTH');
			if (!empty($charsToTrim)) {
				$charsToTrim = ' ' . $charsToTrim;
			}

			return 'TRIM(' . $leadingOrTrailing . $charsToTrim . ' FROM ' . $stringToTrim . ')';
		}

	}

?>