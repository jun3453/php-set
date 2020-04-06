<?php

namespace jun3453\phpset;

/**
 * Class Set
 * @package jun3453\phpset
 */
class Set implements \IteratorAggregate
{
	private $data = [];

	public function getIterator()
	{
		return new \ArrayIterator(array_keys($this->data));
	}

	/**
	 * Set constructor.
	 * @param array $xs
	 */
	private function __construct(array $xs)
	{
		foreach ($xs as $x) {
			$this->data[$x] = true;
		}
	}

	/**
	 * @param array $xs
	 * @return Set
	 */
	public static function from(array $xs): Set
	{
		return new self($xs);
	}

	/**
	 * @return Set
	 */
	public static function empty(): Set
	{
		return new self([]);
	}

	/**
	 * @return int
	 */
	public function len(): int
	{
		return count($this->data);
	}

	/**
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return $this->len() == 0;
	}

	/**
	 * @return bool
	 */
	public function nonEmpty(): bool
	{
		return $this->len() > 0;
	}

	/**
	 * @param $item
	 * @return bool
	 */
	public function in($item): bool
	{
		return array_key_exists($item, $this->data);
	}

	/**
	 * @param $item
	 * @return bool
	 */
	public function notIn($item): bool
	{
		return !$this->in($item);
	}

	/**
	 * ex: this [2, 3, 4] <= that [2, 3, 4, 5] = true
	 *
	 * @param Set $that
	 * @return bool
	 */
	public function isSubset(Set $that): bool
	{
		$subset = $this->filter(function ($item) use ($that) {
			return $that->notIn($item);
		});
		return $subset->len() === 0;
	}

	/**
	 * ex: this [2, 3, 4, 5] >= that [2, 3, 4] = true
	 *
	 * @param Set $that
	 * @return bool
	 */
	public function isSuperset(Set $that): bool
	{
		$subset = $that->filter(function ($item) {
			return $this->notIn($item);
		});
		return $subset->len() === 0;
	}

	/**
	 * ex: this [1, 2, 3, 4] | that [2, 3, 4, 5] = [1, 2, 3, 4, 5]
	 *
	 * @param Set $that
	 * @return Set
	 */
	public function union(Set $that): Set
	{
		return $that->foldLeft($this, function (Set $acc, $item) {
			return $acc->add($item);
		});
	}

	/**
	 * ex: this [1, 2, 3, 4] & that [2, 3, 4, 5] = [2, 3, 4]
	 *
	 * @param Set $that
	 * @return Set
	 */
	public function intersection(Set $that): Set
	{
		return $this->filter(function ($item) use ($that) {
			return $that->in($item);
		});
	}

	/**
	 * ex: this [1, 2, 3, 4] - that [2, 3, 4, 5] = [1]
	 *
	 * @param Set $that
	 * @return Set
	 */
	public function diff(Set $that): Set
	{
		return $this->filter(function ($item) use ($that) {
			return $that->notIn($item);
		});
	}

	/**
	 * ex: this [1, 2, 3, 4] ^ that [2, 3, 4, 5] = [1, 5]
	 *
	 * @param Set $that
	 * @return Set
	 */
	public function symmetricDiff(Set $that): Set
	{
		$thisDiff = $this->filter(function ($item) use ($that) {
			return $that->notIn($item);
		});

		$thatDiff = $that->filter(function ($item) {
			return $this->notIn($item);
		});

		return $thisDiff->union($thatDiff);
	}

	/**
	 * @return Set
	 */
	public function copy(): Set
	{
		return self::from(array_keys($this->data));
	}

	/**
	 * 追加
	 *
	 * @param $item
	 * @return Set
	 */
	public function add($item): Set
	{
		$copy = $this->copy();
		$copy->data[$item] = true;
		return $copy;
	}

	/**
	 * @param $item
	 * @return Set
	 */
	public function remove($item): Set
	{
		$copy = $this->copy();
		unset($copy->data[$item]);
		return $copy;
	}

	/**
	 * @return Set
	 */
	public function clear(): Set
	{
		$this->data = [];
		return $this;
	}

	/**
	 * @param callable $predicate
	 * @return Set
	 */
	public function filter(callable $predicate): Set
	{
		return self::from(array_filter(array_keys($this->data), $predicate));
	}

	/**
	 * @param callable $predicate
	 * @return Set
	 */
	public function map(callable $predicate): Set
	{
		return self::from(array_map($predicate, array_keys($this->data)));
	}

	/**
	 * @param $zero
	 * @param callable $fn
	 * @return mixed
	 */
	public function foldLeft($zero, callable $fn)
	{
		$result = $zero;
		foreach (array_keys($this->data) as $key) {
			$result = $fn($result, $key);
		}
		return $result;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return array_keys($this->data);
	}
}
