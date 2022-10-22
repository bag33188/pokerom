<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum ForeignKeyConstraintOptionEnum: string
{
    use EnumMethods;

    /** apply no action with foreign key constraints */
    case NO_ACTION = 'NO ACTION';
    /** apply cascade deletion/updating with foreign key constraints */
    case CASCADE = 'CASCADE';
    /** set relational field to null during foreign key operations */
    case SET_NULL = 'SET NULL';
    /** restrict deleting parent row until child row is deleted/updated */
    case RESTRICT = 'RESTRICT';
}
