<?php

namespace jun3453\phpset;

use PHPUnit\Framework\TestCase;

/**
 * Class SetTest
 * @package jun3453\phpset
 */
class SetTest extends TestCase
{
	public function testBasic()
	{
		$set = Set::from([1, 1, 2, 2, 3, 3]);

		$this->assertEquals($set->toArray(), [1, 2, 3]);
		$this->assertEquals($set->len(), 3);
		$this->assertEquals($set->isEmpty(), false);
		$this->assertEquals($set->nonEmpty(), true);
		$this->assertEquals($set->in(1), true);
		$this->assertEquals($set->in(4), false);
		$this->assertEquals($set->notIn(1), false);
		$this->assertEquals($set->notIn(4), true);
	}

	public function testMap()
	{
		$set = Set::from([1, 2, 3]);

		$result = $set->map(function (int $s) {
			return $s * 2;
		})->toArray();

		$this->assertEquals($result, [2, 4, 6]);
	}

	public function testFilter()
	{
		$set = Set::from([1, 2, 3]);

		$result = $set->filter(function (int $s) {
			return $s === 2;
		})->toArray();

		$this->assertEquals($result, [2]);
	}

	public function testFoldLeft()
	{
		$set = Set::from([1, 2, 3]);

		$result = $set->foldLeft(0, function ($acc, $item) {
			return $acc + $item;
		});

		$this->assertEquals($result, 6);
	}

	public function testUnion()
	{
		$set = Set::from([1, 2, 3, 4]);
		$that = Set::from([2, 3, 4, 5]);

		$result = $set->union($that)->toArray();

		$this->assertEquals($result, [1, 2, 3, 4, 5]);
	}

	public function testIntersection()
	{
		$set = Set::from([1, 2, 3, 4]);
		$that = Set::from([2, 3, 4, 5]);

		$result = $set->intersection($that)->toArray();

		$this->assertEquals($result, [2, 3, 4]);
	}

	public function testDiff()
	{
		$set = Set::from([1, 2, 3, 4]);
		$that = Set::from([2, 3, 4, 5]);

		$result = $set->diff($that)->toArray();

		$this->assertEquals($result, [1]);
	}

	public function testSymmetricDiff()
	{
		$set = Set::from([1, 2, 3, 4]);
		$that = Set::from([2, 3, 4, 5]);

		$result = $set->symmetricDiff($that)->toArray();

		$this->assertEquals($result, [1, 5]);
	}

	public function testIsSubset()
	{
		$set = Set::from([2, 3, 4]);
		$that = Set::from([2, 3, 4, 5]);

		$this->assertTrue($set->isSubset($that));
		$this->assertFalse($that->isSubset($set));
	}

	public function testIsSuperset()
	{
		$set = Set::from([2, 3, 4, 5]);
		$that = Set::from([2, 3, 4]);

		$this->assertTrue($set->isSuperset($that));
		$this->assertFalse($that->isSuperset($set));
	}
}
