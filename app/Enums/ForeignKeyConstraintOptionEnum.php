<?php

namespace App\Enums;

enum ForeignKeyConstraintOptionEnum: string
{
    /** apply no action with foreign key constraints */
    case NO_ACTION = 'NO ACTION';
    /** apply cascade deletion/updating with foreign key constraints */
    case CASCADE = 'CASCADE';
    /** set relational field to null during foreign key operations */
    case SET_NULL = 'SET NULL';
    /** restrict deleting parent row until child row is deleted/updated */
    case RESTRICT = 'RESTRICT';
}
