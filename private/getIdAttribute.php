<?php

/**
 * Custom accessor for the model's id.
 *
 * @param mixed|null $value
 * @return mixed
 */
function getIdAttribute(mixed $value = null): mixed
{
    # // If we don't have a value for 'id', we will use the Mongo '_id' value.
    # // This allows us to work with models in a more sql-like way.
    # if (! $value && array_key_exists('_id', $this->attributes)) {
    #     $value = $this->attributes['_id'];
    # }
    #
    # // Convert ObjectID to string.
    # if ($value instanceof ObjectID) {
    #     return (string) $value;
    # } elseif ($value instanceof Binary) {
    #     return (string) $value->getData();
    # }

    return $value;
}

# typeof $value => \MongoDB\BSON\ObjectId|string|null
