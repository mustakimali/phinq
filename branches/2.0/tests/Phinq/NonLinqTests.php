<?php

	namespace Phinq\Tests;

	use Phinq\Phinq;
	use ArrayIterator, IteratorAggregate;
	
	class NonLinqTests extends \PHPUnit_Framework_TestCase {

		public function tearDown() {
			Phinq::setDefaultQueryFactory(); //resets the default query factory
		}

		public function testUseNonDefaultQueryFactory() {
			$queryFactory = $this->getMock('Phinq\QueryFactory');
			$expression = $this->getMock('Phinq\Expression');
			$expression->expects($this->any())->method('evaluate');

			$queryFactory->expects($this->once())->method('getExpression')->will($this->returnValue($expression));

			Phinq::setDefaultQueryFactory($queryFactory);

			Phinq::create(array())->any();
		}

		public function testCreateFromPhinq() {
			$phinq = Phinq::create(array('foo', 'bar'));
			$collection = Phinq::create($phinq)->toArray();

			self::assertSame(array('foo', 'bar'), $collection);
		}

		public function testCreateFromIterator() {
			$iterator = new ArrayIterator(array('foo', 'bar'));
			$collection = Phinq::create($iterator)->toArray();

			self::assertSame(array('foo', 'bar'), $collection);
		}

		public function testCreateFromIteratorAggregate() {
			$iteratorAggregate = new IteratorAggregateImplementation(array('foo', 'bar'));
			$collection = Phinq::create($iteratorAggregate)->toArray();

			self::assertSame(array('foo', 'bar'), $collection);
		}

		public function testCreateWithSomethingNotConvertibleToArray() {
			$this->setExpectedException('InvalidArgumentException');
			Phinq::create('foo');
		}
		
	}

	class IteratorAggregateImplementation implements IteratorAggregate {

		private $data;

		public function __construct(array $data) {
			$this->data = $data;
		}

		public function getIterator() {
			return new ArrayIterator($this->data);
		}
	}

?>