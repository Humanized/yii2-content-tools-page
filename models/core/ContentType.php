<?php

namespace humanized\contenttoolspage\models\core;

use humanized\lookup\models\LookupTable;

/**
 * This is the model class for lookup table "content_type".
 *
 * @property integer $id
 * @property string $name
 */
class ContentType extends LookupTable
{

    const TYPE_DEFAULT = 'default';

    //Model read/write permissions in Lookup table (default to TRUE), 
    //to be set at runtime or through class extension
    /*
      public $createPermission = TRUE;
      public $readPermission = TRUE;
      public $updatePermission = TRUE;
      public $deletePermission = TRUE;
     * 
     */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_type';
    }

}
