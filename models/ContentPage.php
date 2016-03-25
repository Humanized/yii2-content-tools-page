<?php

namespace humanized\contenttoolspage\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\sector\SectorPage;

/**
 * This is the model class for table "content_page".
 *
 * @property integer $id
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
            [['type_id', 'title', 'is_published'], 'required'],
            [['type_id', 'is_published', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContentType::className(), 'targetAttribute' => ['type_id' => 'id']],
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
        return $this->hasMany(ContentContainer::className(), ['page_id' => 'id'])->orderBy('content_container.position');
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
    public function getSectorPages()
    {
        return $this->hasMany(SectorPage::className(), ['content_page_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        //Create single container after insert
        if ($insert) {

            for ($i = 1; $i <= $this->containerCount; $i++) {

                $container = new ContentContainer([
                    'page_id' => $this->id,
                    'language_id' => 'en',
                    'position' => $i,
                    'is_published' => 0,
                    'data' => "<b>$this->title page $i</b>"
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
