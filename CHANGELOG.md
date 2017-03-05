## 0.5.0 - unreleased

ADDED:

- `Worm::queryTruncate()`

CHANGED:

REMOVED:

FIXED:

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
