<?php

	namespace Phinq\Tests\Sql;

	use Phinq\Sql\SqlGenerator;
	use Phinq\Expression;

	class SqlGeneratorTest extends \PHPUnit_Framework_TestCase {

		public function testGenerateSqlWithMultiStatementBody() {
			$this->setExpectedException('Phinq\ParserException', 'Invalid expression body: must be one statement');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '{ $foo = 1; return $foo; }'));
		}

		public function testEquality() {
			$generator = new SqlGenerator();
			self::assertEquals('1 = 1', $generator->generateSql(new Expression(array('$foo'), '1 == 1')));
			self::assertEquals('1 = 1', $generator->generateSql(new Expression(array('$foo'), '1 === 1')));
			self::assertEquals('1 != 1', $generator->generateSql(new Expression(array('$foo'), '1 != 1')));
			self::assertEquals('1 != 1', $generator->generateSql(new Expression(array('$foo'), '1 !== 1')));
		}

		public function testNullEquality() {
			$generator = new SqlGenerator();
			self::assertEquals('1 IS NULL', $generator->generateSql(new Expression(array('$foo'), '1 == null')));
			self::assertEquals('1 IS NULL', $generator->generateSql(new Expression(array('$foo'), '1 === null')));
			self::assertEquals('1 IS NOT NULL', $generator->generateSql(new Expression(array('$foo'), '1 != null')));
			self::assertEquals('1 IS NOT NULL', $generator->generateSql(new Expression(array('$foo'), '1 !== null')));
		}

		public function testTrueFalseAndNull() {
			$generator = new SqlGenerator();
			self::assertEquals('1', $generator->generateSql(new Expression(array('$foo'), 'true')));
			self::assertEquals('0', $generator->generateSql(new Expression(array('$foo'), 'false')));
			self::assertEquals('NULL', $generator->generateSql(new Expression(array('$foo'), 'null')));
		}

		public function testUnaryNot() {
			$generator = new SqlGenerator();
			self::assertEquals('(1) = 0', $generator->generateSql(new Expression(array('$foo'), '!(1)')));
			self::assertEquals('(1 + 1) = 0', $generator->generateSql(new Expression(array('$foo'), '!(1 + 1)')));
			self::assertEquals('(1 + (1)) = 0', $generator->generateSql(new Expression(array('$foo'), '!(1 + (1))')));
		}

		public function testUnaryNotMustBeEnclosedInParentheses() {
			$this->setExpectedException('Phinq\ParserException', 'Phinq requires the unary boolean operator ("!") to be followed by an expression encased in parentheses (e.g. "!($foo)")');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '!1'));
		}

		public function testUnaryNotWithoutClosingParenthesis() {
			$this->setExpectedException('Phinq\ParserException', 'Closing parenthesis never found');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '!(1'));
		}

		public function testInequality() {
			$generator = new SqlGenerator();
			self::assertEquals('1 >= 1', $generator->generateSql(new Expression(array('$foo'), '1 >= 1')));
			self::assertEquals('1 <= 1', $generator->generateSql(new Expression(array('$foo'), '1 <= 1')));
			self::assertEquals('1 < 1', $generator->generateSql(new Expression(array('$foo'), '1 < 1')));
			self::assertEquals('1 > 1', $generator->generateSql(new Expression(array('$foo'), '1 > 1')));
		}

		public function testBooleanLogic() {
			$generator = new SqlGenerator();
			self::assertEquals('1 AND 1', $generator->generateSql(new Expression(array('$foo'), '1 && 1')));
			self::assertEquals('1 OR 1', $generator->generateSql(new Expression(array('$foo'), '1 || 1')));
		}

		public function testConvertDoubleQuotedString() {
			$generator = new SqlGenerator();
			self::assertEquals('\'foo\' = \'foo\'', $generator->generateSql(new Expression(array('$foo'), '"foo" == \'foo\'')));
			self::assertEquals('\'fo\\\'o\' = \'fo\\\'o\'', $generator->generateSql(new Expression(array('$foo'), '"fo\\\'o" == \'fo\\\'o\'')));
		}

		public function testAddition() {
			$generator = new SqlGenerator();
			self::assertEquals('1 + 1', $generator->generateSql(new Expression(array('$foo'), '1 + 1')));
		}

		public function testSubtraction() {
			$generator = new SqlGenerator();
			self::assertEquals('1 - 1', $generator->generateSql(new Expression(array('$foo'), '1 - 1')));
		}

		public function testDivision() {
			$generator = new SqlGenerator();
			self::assertEquals('1 / 1', $generator->generateSql(new Expression(array('$foo'), '1 / 1')));
		}

		public function testMultiplication() {
			$generator = new SqlGenerator();
			self::assertEquals('1 * 1', $generator->generateSql(new Expression(array('$foo'), '1 * 1')));
		}

		public function testBitwiseAnd() {
			$generator = new SqlGenerator();
			self::assertEquals('1 & 1', $generator->generateSql(new Expression(array('$foo'), '1 & 1')));
		}

		public function testBitwiseOr() {
			$generator = new SqlGenerator();
			self::assertEquals('1 | 1', $generator->generateSql(new Expression(array('$foo'), '1 | 1')));
		}

		public function testBitwiseXor() {
			$generator = new SqlGenerator();
			self::assertEquals('1 ^ 1', $generator->generateSql(new Expression(array('$foo'), '1 ^ 1')));
		}

		public function testBitwiseComplement() {
			$generator = new SqlGenerator();
			self::assertEquals('~1', $generator->generateSql(new Expression(array('$foo'), '~1')));
		}

		public function testModulus() {
			$generator = new SqlGenerator();
			self::assertEquals('1 % 1', $generator->generateSql(new Expression(array('$foo'), '1 % 1')));
		}

		public function testNestedScope() {
			$generator = new SqlGenerator();
			self::assertEquals('1 + (1 * 1)', $generator->generateSql(new Expression(array('$foo'), '1 + (1 * 1)')));
		}

		public function testReplaceString() {
			$generator = new SqlGenerator();
			self::assertEquals('REPLACE(\'foo\', \'bar\', \'foobar\')', $generator->generateSql(new Expression(array('$foo'), 'str_replace(\'foo\', \'bar\', \'foobar\')')));
		}

		public function testCompareStrings() {
			$generator = new SqlGenerator();
			self::assertEquals('STRCMP(\'foo\', \'bar\') = 0', $generator->generateSql(new Expression(array('$foo'), 'strcmp(\'foo\', \'bar\') === 0')));
		}

		public function testRepeatString() {
			$generator = new SqlGenerator();
			self::assertEquals('REPEAT(\'foo\', 7)', $generator->generateSql(new Expression(array('$foo'), 'str_repeat(\'foo\', 7)')));
		}

		public function testStringReverse() {
			$generator = new SqlGenerator();
			self::assertEquals('REVERSE(\'foo\')', $generator->generateSql(new Expression(array('$foo'), 'strrev(\'foo\')')));
		}

		public function testStringLength() {
			$generator = new SqlGenerator();
			self::assertEquals('LENGTH(\'foo\')', $generator->generateSql(new Expression(array('$foo'), 'strlen(\'foo\')')));
		}

		public function testToLowerCase() {
			$generator = new SqlGenerator();
			self::assertEquals('LOWER(\'foo\')', $generator->generateSql(new Expression(array('$foo'), 'strtolower(\'foo\')')));
		}

		public function testToUpperCase() {
			$generator = new SqlGenerator();
			self::assertEquals('UPPER(\'foo\')', $generator->generateSql(new Expression(array('$foo'), 'strtoupper(\'foo\')')));
		}

		public function testUnicodePoint() {
			$generator = new SqlGenerator();
			self::assertEquals('ORD(\'f\')', $generator->generateSql(new Expression(array('$foo'), 'ord(\'f\')')));
		}

		public function testSoundex() {
			$generator = new SqlGenerator();
			self::assertEquals('SOUNDEX(\'foo\')', $generator->generateSql(new Expression(array('$foo'), 'soundex(\'foo\')')));
		}

		public function testSubstring() {
			$generator = new SqlGenerator();
			self::assertEquals('SUBSTRING(\'foo\' FROM 1 FOR 7)', $generator->generateSql(new Expression(array('$foo'), 'substr(\'foo\', 0, 7)')));
			self::assertEquals('SUBSTRING(\'foo\' FROM 2)', $generator->generateSql(new Expression(array('$foo'), 'substr(\'foo\', 1)')));
		}

		public function testTrim() {
			$generator = new SqlGenerator();
			self::assertEquals('TRIM(BOTH FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'trim(\'foo\')')));
			self::assertEquals('TRIM(BOTH \'f\' FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'trim(\'foo\', \'f\')')));
			
			self::assertEquals('TRIM(BOTH FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'trim( \'foo\' )')));
		}

		public function testTrimLeft() {
			$generator = new SqlGenerator();
			self::assertEquals('TRIM(LEADING FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'ltrim(\'foo\')')));
			self::assertEquals('TRIM(LEADING \'f\' FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'ltrim(\'foo\', \'f\')')));
		}

		public function testTrimRight() {
			$generator = new SqlGenerator();
			self::assertEquals('TRIM(TRAILING FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'rtrim(\'foo\')')));
			self::assertEquals('TRIM(TRAILING \'f\' FROM \'foo\')', $generator->generateSql(new Expression(array('$foo'), 'rtrim(\'foo\', \'f\')')));
		}

		public function testTrimMultipleCharacters() {
			$this->setExpectedException('Exception', 'Trimming multiple characters is not supported');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), 'trim(\'foo\', \'foo\')'));
		}

		public function testTrimWithoutArguments() {
			$this->setExpectedException('Phinq\ParserException', 'trim() must have exactly one or two arguments');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), 'trim()'));
		}

		public function testPregMatch() {
			$generator = new SqlGenerator();
			self::assertEquals('\'foo\' REGEXP BINARY \'foo\'', $generator->generateSql(new Expression(array('$foo'), 'preg_match("/foo/", "foo")')));

			//backslash tests
			self::assertEquals("'foo' REGEXP BINARY 'fo\\'o'", $generator->generateSql(new Expression(array('$foo'), 'preg_match("/fo\'o/", "foo")')));
			self::assertEquals("'foo' REGEXP BINARY 'fo\\\\o'", $generator->generateSql(new Expression(array('$foo'), 'preg_match("/fo\\\\o/", "foo")')));
			self::assertEquals("'foo' REGEXP BINARY 'foo'", $generator->generateSql(new Expression(array('$foo'), 'preg_match("/fo\\o/", "foo")')));
			self::assertEquals("'foo' REGEXP BINARY 'fo\\o'", $generator->generateSql(new Expression(array('$foo'), 'preg_match(\'/fo\\o/\', "foo")')));
		}

		public function testCaseInsensitivePregMatch() {
			$generator = new SqlGenerator();
			self::assertEquals('\'foo\' REGEXP \'foo\'', $generator->generateSql(new Expression(array('$foo'), 'preg_match("/foo/i", "foo")')));
		}

		public function testParseVariable() {
			$generator = new SqlGenerator();
			self::assertEquals('bar', $generator->generateSql(new Expression(array('$foo'), '$foo->bar')));
			self::assertEquals('bar', $generator->generateSql(new Expression(array('$foo'), '$foo -> bar')));
		}

		public function testParseVariableWithUndefinedVariable() {
			$this->setExpectedException('Phinq\ParserException', 'Undefined variable "$bar"');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '$bar->bar'));
		}

		public function testParseVariableWithNoField() {
			$this->setExpectedException('Phinq\ParserException', 'Parser does not know what to do with the variable "$foo"');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '$foo->'));
		}

		public function testParseVariableWithNoObjectOperator() {
			$this->setExpectedException('Phinq\ParserException', 'Unexpected token of type "T_STRING"');
			$generator = new SqlGenerator();
			$generator->generateSql(new Expression(array('$foo'), '$foo foo'));
		}

	}

?>