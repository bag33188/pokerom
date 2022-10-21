SELECT DISTINCT TABLE_NAME,
                INDEX_NAME,
                COLUMN_NAME,
                INDEX_TYPE,
                NON_UNIQUE,
                NULLABLE,
                INDEX_SCHEMA,
                SEQ_IN_INDEX,
                CARDINALITY,
                INDEX_COMMENT,
                IGNORED,
                COLLATION
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'pokerom_db'
ORDER BY TABLE_NAME DESC;
