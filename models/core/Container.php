<?php

namespace humanized\contenttoolspage\models\core;

use yii\db\ActiveRecord;
use Yii;
use humanized\contenttoolspage\models\containers\ContentContainer;
use humanized\contenttoolspage\models\containers\StaticContainer;
/**
 * This is the model class for table "content_container".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $page_id
 * @property integer $type_id
 * @property string $language_id
 * @property integer $is_published
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentPage $page
 */
class Container extends \yii\db\ActiveRecord
{

    public $attr = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'container';
    }

    /**
     * 
     * @return type
     */
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
     * 
     * @param type $insert
     * @param type $changedAttributes
     * @return type
     */
    public function afterSave($insert, $changedAttributes)
    {
        //Touch timestamps
        $this->touch('updated_at');
        $this->page->touch('updated_at');
        if (!isset($this->attr['id'])) {
            $this->attr['id'] = $this->id;
        }

        $class = "\\humanized\\contenttoolspage\\models\\containers\\" . \yii\helpers\Inflector::camelize($this->type->name) . "Container";
        echo "\n" . $class . "\n";
        $child = ($insert ? new $class() : $class::findOne($this->id));
        $child->setAttributes($this->attr);
        if (!$child->save()) {
            var_dump($child->errors);
            $this->delete();
            return false;
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'is_published', 'uid'], 'required'],
            [['uid'], 'string', 'max' => 30],
            [['page_id', 'is_published', 'position', 'created_at', 'updated_at'], 'integer'],
            [['page_id'], 'exist', 'skipOnError' => false, 'targetClass' => ContentPage::className(), 'targetAttribute' => ['page_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => false, 'targetClass' => ContainerType::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['uid'], 'unique', 'targetAttribute' => ['uid']],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'page_id' => Yii::t('app', 'Page ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'is_published' => Yii::t('app', 'Is Published'),
            'position' => Yii::t('app', 'Position'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(ContentPage::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ContainerType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentContainer()
    {
        return $this->hasOne(ContentContainer::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaticContainer()
    {
        return $this->hasOne(StaticContainer::className(), ['id' => 'id']);
    }

}
