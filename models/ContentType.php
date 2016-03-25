<?php

namespace humanized\contenttoolspage\models;

use common\models\base\Lookup;

/**
 * This is the model class for lookup table "content_type".
 *
 * @property integer $id
 * @property string $name
 */
class ContentType extends Lookup
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_type';
    }

}
