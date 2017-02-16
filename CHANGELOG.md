## 0.2.0 - unreleased

ADDED:

- Identity Map
- Support for retrieving multiple levels of relationships in the same query
- Various methods to `SelectQueryBuilder` to reflect changes in Larva v0.3

CHANGED:

- Updated minimum Larva version requirement to v0.3.0
- Optimized retrieval of relationships

REMOVED:

- `ModelInterface::isAutoIncremented()` method

FIXED:

- Removed unnecessary joins when loading relationships
- Many-to-Many relationships can reference fields other than primary keys

## 0.1.0 - 2017-02-03

- Initial release
