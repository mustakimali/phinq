<?php

	namespace Phinq\Tests;

	use Phinq\Phinq;
	use Phinq\Expression;
	use stdClass;

	class MiscTests extends \PHPUnit_Framework_TestCase {

		public function testAggregate() {
			$factorial = Phinq::create(array(1, 2, 3, 4, 5))->aggregate('($current, $next) => $current * $next', 1);
			self::assertEquals(120, $factorial);
		}

		public function testAggregateWithEmptyCollection() {
			$factorial = Phinq::create(array())->aggregate('($current, $next) => $current * $next', 1);
			self::assertEquals(1, $factorial);
		}

		public function testExcept() {
			$collection1 = array(1, 2, 3, 4, 5, 3, 1);
			$collection2 = array(3, 4, 5, 6, 7);

			$diffedCollection = Phinq::create($collection1)->except($collection2)->toArray();

			self::assertSame(array(1, 2, 1), $diffedCollection);
		}

		public function testExceptWithComparer() {
			$obj1 = new Sphinqter('foo');
			$obj2 = new Sphinqter('bar');
			$obj3 = new Sphinqter('baz');
			$obj4 = new Sphinqter('bat');
			$collection1 = array($obj1, $obj2, $obj2, $obj1);
			$collection2 = array($obj1, $obj3, $obj4);

			$diffedCollection = Phinq::create($collection1)->except($collection2, new IdComparer())->toArray();
			self::assertSame(array($obj2, $obj2), $diffedCollection);
		}

		public function testSelectMany() {
			$collection = array(1, 2, 3);

			$newCollection = Phinq::create($collection)->selectMany('$value => array($value, $value + 3)')->toArray();
			self::assertSame(array(1, 4, 2, 5, 3, 6), $newCollection);
		}

		public function testSelectManyShouldNotRecursivelyFlatten() {
			$collection = array(1, 2, 3);

			$newCollection = Phinq::create($collection)->selectMany('$value => array(array($value))')->toArray();
			self::assertSame(array(array(1), array(2), array(3)), $newCollection);
		}

		public function testSequenceEqual() {
			self::assertTrue(Phinq::create(array(1, 2, 3, 4, 5, 6))->sequenceEqual(array(1, 2, 3, 4, 5, 6)));
			self::assertTrue(Phinq::create(array())->sequenceEqual(array()));
			self::assertTrue(Phinq::create(array('foo', 'bar'))->sequenceEqual(array('foo', 'bar')));
			self::assertFalse(Phinq::create(array('bar', 'foo'))->sequenceEqual(array('foo', 'bar')));
			self::assertFalse(Phinq::create(array(1, 2, 3))->sequenceEqual(array(3, 2, 1)));
			self::assertFalse(Phinq::create(array(1, 2, 3))->sequenceEqual(array(1, 2)));
		}

		public function testSequenceEqualWithComparer() {
			$equal = Phinq::create(array(new Sphinqter('foo'), new Sphinqter('bar')))
				->sequenceEqual(array(new Sphinqter('foo'), new Sphinqter('bar')), new IdComparer());
			
			self::assertTrue($equal);
		}

		public function testSimpleJoin() {
			$collection = array(1, 2, 3);
			$collectionToJoinOn = array(2, 4, 6, 2);

			$joinedCollection = Phinq::create($collection)
				->join(
					$collectionToJoinOn,
					'$value => $value',
					'$value => $value',
					'($innerValue, $outerValue) => $innerValue'
				)->toArray();

			self::assertSame(array(2, 2), $joinedCollection);
		}

		public function testJoinWithComparer() {
			$collection = array(new Sphinqter('foo', 1), new Sphinqter('bar', 2));
			$collectionToJoinOn = array(new Sphinqter('foo', 3), new Sphinqter('foo', 4));

			$joinedCollection = Phinq::create($collection)
				->join(
					$collectionToJoinOn,
					'$value => $value',
					'$value => $value',
					'($innerValue, $outerValue) => "first: $innerValue->foo, second: $outerValue->foo"',
					new IdComparer()
				)->toArray();

			self::assertSame(array('first: 1, second: 3', 'first: 1, second: 4'), $joinedCollection);
		}

		public function testGroupJoin() {
			$foo = new Sphinqter('foo', 1);
			$bar = new Sphinqter('bar', 2);
			$collection = array($foo, $bar);
			$fooJoin1 = new Sphinqter('foo', 3);
			$fooJoin2 = new Sphinqter('foo', 4);
			$collectionToJoinOn = array($fooJoin1, $fooJoin2);

			$joinedCollection = Phinq::create($collection)
				->groupJoin(
					$collectionToJoinOn,
					'$value => $value',
					'$value => $value',
					'($key, $matches) => array("key" => $key, "matches" => $matches)',
					new IdComparer()
				)->toArray();

			self::assertEquals(2, count($joinedCollection));

			$value = $joinedCollection[0];
			self::assertSame($foo, $value['key']);
			self::assertSame(array($fooJoin1, $fooJoin2), $value['matches']);

			$value = $joinedCollection[1];
			self::assertSame($bar, $value['key']);
			self::assertSame(array(), $value['matches']);
		}

		public function testCastToString() {
			self::assertSame(array('1', '2', '3'), Phinq::create(range(1, 3))->cast('string')->toArray());
		}

		public function testCastToInt() {
			self::assertSame(range(1, 3), Phinq::create(array('1', '2', '3'))->cast('int')->toArray());
			self::assertSame(range(1, 3), Phinq::create(array('1', '2', '3'))->cast('integer')->toArray());
		}

		public function testCastToFloat() {
			self::assertSame(array(1.1, 2.2, 3.3), Phinq::create(array('1.1', '2.2', '3.3'))->cast('float')->toArray());
			self::assertSame(array(1.1, 2.2, 3.3), Phinq::create(array('1.1', '2.2', '3.3'))->cast('real')->toArray());
			self::assertSame(array(1.1, 2.2, 3.3), Phinq::create(array('1.1', '2.2', '3.3'))->cast('double')->toArray());
		}

		public function testCastToBool() {
			self::assertSame(array(true, false), Phinq::create(array(1, 0))->cast('bool')->toArray());
			self::assertSame(array(true, false), Phinq::create(array(1, 0))->cast('boolean')->toArray());
		}

		public function testCastToArray() {
			self::assertSame(array(array(1), array(2)), Phinq::create(array(1, 2))->cast('array')->toArray());
		}

		public function testCastToObject() {
			$expected = new stdClass();
			$expected->scalar = 'bar';
			self::assertEquals(array($expected), Phinq::create(array('bar'))->cast('object')->toArray());
		}

		public function testCastToNull() {
			self::assertSame(array_fill(0, 6, null), Phinq::create(array('foo', 0, new stdClass(), 1.3, xml_parser_create(), array()))->cast('null')->toArray());
			self::assertSame(array_fill(0, 6, null), Phinq::create(array('foo', 0, new stdClass(), 1.3, xml_parser_create(), array()))->cast('unset')->toArray());
		}

		public function testCastToBinary() {
			self::assertSame(array('foo'), Phinq::create(array('foo'))->cast('binary')->toArray());
		}

		public function testCastWithInvalidType() {
			$this->setExpectedException('InvalidArgumentException');
			Phinq::create(array('foo'))->cast('foo')->toArray();
		}

		public function testOfType() {
			self::assertSame(array(), Phinq::create(array('foo'))->ofType('string')->toArray());

			$sphinqter1 = new Sphinqter();
			$sphinqter2 = new Sphinqter();
			$collection = array($sphinqter1, $sphinqter2, 'foo');

			self::assertSame(array($sphinqter1, $sphinqter2), Phinq::create($collection)->ofType('Phinq\Tests\Sphinqter')->toArray());
		}

		public function testDefaultIfEmpty() {
			self::assertSame(array('foo'), Phinq::create(array('foo'))->defaultIfEmpty()->toArray());
			self::assertSame(array(null), Phinq::create(array())->defaultIfEmpty()->toArray());
			self::assertSame(array('foo'), Phinq::create(array())->defaultIfEmpty('foo')->toArray());
		}

		public function testZip() {
			self::assertSame(array('foobar'), Phinq::create(array('foo'))->zip(array('bar'), '($a, $b) => $a . $b')->toArray());
		}

		public function testZipWithDifferentSizeArrays() {
			self::assertSame(array('foobar'), Phinq::create(array('foo', 'bar'))->zip(array('bar'), '($a, $b) => $a . $b')->toArray());
			self::assertSame(array('foobar'), Phinq::create(array('foo'))->zip(array('bar', 'foo'), '($a, $b) => $a . $b')->toArray());
		}

		public function testWalk() {
			$temp = array();

			$phinq = Phinq::create(array('foo', 'bar'))
				->walk(new Expression(array('$value'), '$temp[] = $value', array('&$temp' => &$temp)));

			self::assertSame(array('foo', 'bar'), $phinq->toArray());
			self::assertSame(array('foo', 'bar'), $temp);
		}

	}

?>