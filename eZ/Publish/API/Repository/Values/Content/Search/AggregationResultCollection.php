<?php

declare(strict_types=1);

namespace eZ\Publish\API\Repository\Values\Content\Search;

use Closure;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use IteratorAggregate;
use Countable;

interface AggregationResultCollection extends Countable, IteratorAggregate
{
    /**
     * This method returns the aggregation result for the given aggregation name.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\OutOfBoundsException
     */
    public function get(string $name): AggregationResult;

    /**
     * This method returns true if the aggregation result for the given aggregation name exists.
     */
    public function has(string $name): bool;

    /**
     * Return first element of collection.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\OutOfBoundsException
     */
    public function first(): FieldDefinition;

    /**
     * Return last element of collection.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\OutOfBoundsException
     */
    public function last(): FieldDefinition;

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return bool TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     */
    public function filter(Closure $predicate): self;

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     */
    public function map(Closure $predicate): array;

    /**
     * Tests whether the given predicate holds for all elements of this collection.
     */
    public function all(Closure $predicate): bool;

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     */
    public function any(Closure $predicate): bool;

    /**
     * Partitions this collection in two collections according to a predicate.
     *
     * Result is an array with two elements. The first element contains the collection
     * of elements where the predicate returned TRUE, the second element
     * contains the collection of elements where the predicate returned FALSE.
     *
     * @return \eZ\Publish\API\Repository\Values\ContentType\FieldDefinitionCollection[]
     */
    public function partition(Closure $predicate): array;

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return \eZ\Publish\API\Repository\Values\ContentType\FieldDefinition[]
     */
    public function toArray(): array;
}
