<?php

	namespace Phinq;

	final class ExpressionType {

		 const Aggregate = 'Aggregate';
		 const All = 'All';
		 const Any = 'Any';
		 const Average = 'Average';
		 const Contains = 'Contains';
		 const Count = 'Count';
		 const DefaultIfEmpty = 'DefaultIfEmpty';
		 const ElementAt = 'ElementAt';
		 const ElementAtOrDefault = 'ElementAtOrDefault';
		 const First = 'First';
		 const FirstOrDefault = 'FirstOrDefault';
		 const Last = 'Last';
		 const LastOrDefault = 'LastOrDefault';
		 const Max = 'Max';
		 const Min = 'Min';
		 const SequenceEqual = 'SequenceEqual';
		 const Single = 'Single';
		 const SingleOrDefault = 'SingleOrDefault';
		 const Sum = 'Sum';
		 const ToArray = 'ToArray';
		 const ToDictionary = 'ToDictionary';

		//@codeCoverageIgnoreStart
		private function __construct() {}
		//@codeCoverageIgnoreEnd
	}

?>