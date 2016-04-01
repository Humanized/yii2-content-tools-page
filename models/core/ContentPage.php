<?php

namespace humanized\contenttoolspage\models\core;

use Yii;
use yii\db\ActiveRecord;
use common\models\sector\SectorPage;
use humanized\contenttoolspage\models\core\Container;

/**
 * This is the model class for table "content_page".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $parent_id
 * @property integer $type_id
 * @property string $title
 * @property integer $is_published
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentContainer[] $containers
 * @property ContentType $type
 * @property SectorPage[] $sectorPages
 */
class ContentPage extends ActiveRecord
{

    public $containerCount = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'type_id', 'title', 'is_published'], 'required'],
            [['type_id', 'is_published', 'created_at', 'updated_at'], 'integer'],
            [['uid'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 100],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContentType::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContentPage::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['uid'], 'unique', 'targetAttribute' => ['uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'title' => Yii::t('app', 'Title'),
            'is_published' => Yii::t('app', 'Is Published'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        //Remote Settings Empty --> Master Mode 
        if ($this->isNewRecord && !isset($this->uid)) {
            $this->uid = uniqid();
        }
        return true;
    }

    public function behaviors()
    {
        return [

            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainers()
    {
        return $this->hasMany(Container::className(), ['page_id' => 'id'])->orderBy('container.position');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ContentType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ContentPage::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(ContentPage::className(), ['parent_id' => 'id']);
    }

    public function isParent()
    {
        return !empty($this->children);
    }


    public function afterSave($insert, $changedAttributes)
    {
        $contentType = ContainerType::getIdByName(ContainerType::CONTENT_CONTAINER);
        //Create single container after insert
        if ($insert) {
            for ($i = 1; $i <= $this->containerCount; $i++) {
                $container = new Container([
                    'page_id' => $this->id,
                    'position' => $i,
                    'is_published' => 0,
                    'type_id' => $contentType,
                    'attr' => ['data' => '<b>' . $this->title . ' Editable Content ' . '<b>']
                ]);
                if (!$container->save()) {
                    \yii\helpers\VarDumper::dump($container->errors);
                }
            }
        }
        $this->touch('updated_at');
        return parent::afterSave($insert, $changedAttributes);
    }

}
