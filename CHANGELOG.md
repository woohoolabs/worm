## 0.7.0 - unreleased

ADDED:

CHANGED:

- Increased minimum PHP version requirement to 7.4 as property type declarations were added
- Updated Larva to 0.7
- Updated dev dependencies
- Improved static analysis

REMOVED:

FIXED:

## 0.6.0 - 2018-12-21

ADDED:

- Support for building relationships

CHANGED:

- Updated Larva to 0.6
- Require PHPUnit 7.0 minimally to run tests
- Apply the Woohoo Labs. Coding Standard

FIXED:

- Cloning query builders

## 0.5.0 - 2017-09-12

ADDED:

- Support for composite primary keys
- `Worm::queryTruncate()`

CHANGED:

- Increased minimum PHP version requirement to 7.1
- Updated minimum Larva version requirement to 0.5
- `ModelInterface::getPrimaryKey(): string` was changed to `ModelInterface::getPrimaryKeys(): array`
- Optimized retrieval of "belongs-to" relationships

FIXED:

- Identities are added to the Identity Map in the correct order when fetching relationships of multiple levels
- Handle null foreign keys properly

## 0.4.1 - 2017-03-05

FIXED:

- `InsertQueryBuilder::multipleFields()` caused exception

## 0.4.0 - 2017-03-05

ADDED:

- Ability to persist entities via `Worm::save()` and `Worm::delete()`
- Ability to persist related entities via `Worm::saveRelatedEntity()` and `Worm::saveRelatedEntities()`
- Ability to define if a relationship has `ON DELETE CASCADE` constraint
- `IdentityMap::createObjectFromId()` method
- `Worm::transaction()` method
- `Worm::queryInsert()`, `Worm::queryUpdate()` and `Worm::queryDelete()` methods
- `SelectQueryBuilder::fetchColumn()` and `SelectQueryBuilder::fetchCount()` methods
- `SelectQueryBuilder::withAllTransitiveRelationships()` method
- `getSql()` and `getParams()` methods to query builders

CHANGED:

- Renamed `Worm::queryModel()` to `Worm::query()`
- A newly added `ConditionBuilder` class is used by query builders instead of its Larva counterpart
- Updated minimum Larva version requirement to 0.4
- Adapted interfaces to changes in Larva 0.4

FIXED:

- `WHERE` conditions won't be erased when using `SelectQueryBuilder::fetchById()`
- Fetching relationships of an empty list of entities won't raise syntax error
- Retrieval of belongs-to relationships

## 0.2.0 - 2017-02-17

ADDED:

- Identity Map
- Support for retrieving multiple levels of relationships in the same query
- Various methods to `SelectQueryBuilder` to reflect changes in Larva v0.3

CHANGED:

- Updated minimum Larva version requirement to 0.3
- Optimized retrieval of relationships

REMOVED:

- `ModelInterface::isAutoIncremented()` method

FIXED:

- Removed unnecessary joins when loading relationships
- Many-to-Many relationships can reference fields other than primary keys

## 0.1.0 - 2017-02-03

- Initial release
