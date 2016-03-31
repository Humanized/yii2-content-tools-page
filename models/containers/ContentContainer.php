<?php

namespace humanized\contenttoolspage\models\containers;

use Yii;
use humanized\contenttoolspage\models\core\Container;

/**
 * This is the model class for table "content_container".
 *
 * @property integer $id
 * @property string $language_id
 * @property string $data
 *
 * @property Container $container
 */
class ContentContainer extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_container';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['data'], 'string'],
            [['language_id'], 'string', 'max' => 2],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Container::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContainer()
    {
        return $this->hasOne(Container::className(), ['id' => 'id']);
    }

    /**
     * 
     * @param boolean $insert
     * @return type
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if(!isset($this->data)){
            $this->data = "<b>EDITABLE CONTAINER</b>";
        }
        $this->cleanData();
        return true;
    }

    private function cleanData()
    {
        $this->data = preg_replace('/[\n]+/', '', $this->data);
        $this->data = preg_replace('/[\r]+/', '', $this->data);
        $this->data = preg_replace('/[\t]+/', '', $this->data);
        $this->data = preg_replace('/  /', '', $this->data);
        $this->data = preg_replace('/<p> <\/p>/', '', $this->data);
        $this->data = preg_replace('/<p><\/p>/', '', $this->data);
        $this->data = preg_replace('/<p><br><\/p>/', '', $this->data);
    }

}
