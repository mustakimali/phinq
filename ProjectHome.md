A faithful port of .NET's LINQ library to PHP.

This library makes heavy use of [anonymous functions](http://php.net/manual/en/functions.anonymous.php), a feature introduced in PHP 5.3. As such, it requires PHP 5.3.0 or greater.

LINQ methods implemented:

  * `Aggregate()`
  * `All()`
  * `Any()`
  * `Average()`
  * `Cast()`
  * `Concat()`
  * `Contains()`
  * `Count()`
  * `DefaultIfEmpty()`
  * `Distinct()`
  * `ElementAt()`
  * `ElementAtOrDefault()`
  * `Except()`
  * `First()`
  * `FirstOrDefault()`
  * `GroupBy()`
  * `GroupJoin()`
  * `Intersect()`
  * `Join()`
  * `Last()`
  * `LastOrDefault()`
  * `Max()`
  * `Min()`
  * `OfType()`
  * `OrderBy()`
  * `Reverse()`
  * `Select()`
  * `SelectMany()`
  * `SequenceEqual()`
  * `Single()`
  * `SingleOrDefault()`
  * `Skip()`
  * `SkipWhile()`
  * `Sum()`
  * `Take()`
  * `TakeWhile()`
  * `ThenBy()`
  * `ToArray()`
  * `ToDictionary()`
  * `Union()`
  * `Where()`
  * `Zip()`

Extra methods:

  * `walk()`

LINQ methods to be implemented:

None.

LINQ methods not implemented:

  * `AsEnumerable()`
  * `AsParallel()`
  * `AsQueryable()`
  * `LongCount()`
  * `OrderByDescending()` - implemented as an optional argument to `orderBy()`
  * `ThenByDescending()` - implemented as an optional argument to `thenBy()`
  * `ToList()`
  * `ToLookup()`