## 0.3.0 - unreleased

ADDED:

- Ability to persist entities via `Worm::save()` and `Worm::delete()`
- Ability to persist related entities via `Worm::saveRelatedEntity()` and `Worm::saveRelatedEntities()`
- Ability to define if a relationship has `ON DELETE CASCADE` constraint
- `IdentityMap::createObjectFromId` method
- `SelectQueryBuilder::fetchColumn()` and `SelectQueryBuilder::fetchCount()` methods
- `Worm::transaction()` method
- `Worm::queryInsert()`, `Worm::queryUpdate()` and `Worm::queryDelete()` methods
- `SelectQueryBuilder::withAllTransitiveRelationships()` method

CHANGED:

- Renamed `Worm::queryModel()` to `Worm::query()`
- Updated minimum Larva version requirement to 0.4
- Adapted to changes in Larva 0.4

REMOVED:

FIXED:

- `WHERE` conditions won't be erased when using `SelectQueryBuilder::fetchById()`

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
