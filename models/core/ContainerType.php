<?php

namespace humanized\contenttoolspage\models\core;

use humanized\lookup\models\LookupTable;

/**
 * This is the model class for lookup table "container_type".
 *
 * @property integer $id
 * @property string $name
 */
class ContainerType extends LookupTable
{

    const STATIC_CONTAINER = 'static';
    const CONTENT_CONTAINER = 'content';

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
        return 'container_type';
    }

}
