<?php

	namespace Phinq;
	
	final class QueryType {

		// const Aggregate = 'Aggregate';
		// const All = 'All';
		// const Any = 'Any';
		// const Average = 'Average';
		const Cast = 'Cast';
		const Concat = 'Concat';
		// const Contains = 'Contains';
		// const Count = 'Count';
		// const DefaultIfEmpty = 'DefaultIfEmpty';
		const Distinct = 'Distinct';
		// const ElementAt = 'ElementAt';
		// const ElementAtOrDefault = 'ElementAtOrDefault';
		const Except = 'Except';
		// const First = 'First';
		// const FirstOrDefault = 'FirstOrDefault';
		const GroupBy = 'GroupBy';
		const GroupJoin = 'GroupJoin';
		const Intersect = 'Intersect';
		const Join = 'Join';
		// const Last = 'Last';
		// const LastOrDefault = 'LastOrDefault';
		// const Max = 'Max';
		// const Min = 'Min';
		const OfType = 'OfType';
		const OrderBy = 'OrderBy';
		const Reverse = 'Reverse';
		const Select = 'Select';
		const SelectMany = 'SelectMany';
		// const SequenceEqual = 'SequenceEqual';
		// const Single = 'Single';
		// const SingleOrDefault = 'SingleOrDefault';
		const Skip = 'Skip';
		const SkipWhile = 'SkipWhile';
		// const Sum = 'Sum';
		const Take = 'Take';
		const TakeWhile = 'TakeWhile';
		const ThenBy = 'ThenBy';
		// const ToArray = 'ToArray';
		// const ToDictionary = 'ToDictionary';
		const Union = 'Union';
		const Where = 'Where';
		const Zip = 'Zip';

		const Walk = 'Walk';

		//@codeCoverageIgnoreStart
		private function __construct() {}
		//@codeCoverageIgnoreEnd
	}

?>