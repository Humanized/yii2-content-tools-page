<?php

namespace humanized\contenttoolspage\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "content_container".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $language_id
 * @property integer $is_published
 * @property integer $position
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ContentPage $page
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

    public function beforeSave($insert)
    {
        $this->data = preg_replace('/[\n]+/', '', $this->data);
        $this->data = preg_replace('/[\r]+/', '', $this->data);
        $this->data = preg_replace('/[\t]+/', '', $this->data);
        $this->data = preg_replace('/  /', '', $this->data);
        $this->data = preg_replace('/<p> <\/p>/', '', $this->data);
        $this->data = preg_replace('/<p><\/p>/', '', $this->data);
        $this->data = preg_replace('/<p><br><\/p>/', '', $this->data);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->touch('updated_at');
        $this->page->touch('updated_at');
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'is_published'], 'required'],
            [['page_id', 'is_published', 'position', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string'],
            [['language_id'], 'string', 'max' => 2],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContentPage::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
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

}
