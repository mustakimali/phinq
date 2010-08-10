<?php

	namespace Phinq;

	final class QueryType {

		const Cast = 'Cast';
		const Concat = 'Concat';
		const DefaultIfEmpty = 'DefaultIfEmpty';
		const Distinct = 'Distinct';
		const Except = 'Except';
		const GroupBy = 'GroupBy';
		const GroupJoin = 'GroupJoin';
		const Intersect = 'Intersect';
		const Join = 'Join';
		const OfType = 'OfType';
		const OrderBy = 'OrderBy';
		const Reverse = 'Reverse';
		const Select = 'Select';
		const SelectMany = 'SelectMany';
		const Skip = 'Skip';
		const SkipWhile = 'SkipWhile';
		const Take = 'Take';
		const TakeWhile = 'TakeWhile';
		const ThenBy = 'ThenBy';
		const Union = 'Union';
		const Walk = 'Walk';
		const Where = 'Where';
		const Zip = 'Zip';

		//@codeCoverageIgnoreStart
		private function __construct() {}
		//@codeCoverageIgnoreEnd
	}

?>