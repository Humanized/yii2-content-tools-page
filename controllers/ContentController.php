<?php

namespace humanized\contenttoolspage\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use humanized\contenttoolspage\models\core\ContentPage;
use humanized\contenttoolspage\models\core\ContentType;
use humanized\contenttoolspage\models\containers\ContentContainer;
use humanized\contenttoolspage\models\containers\StaticContainer;
use humanized\contenttoolspage\components\editor\actions\UploadAction;
use humanized\contenttoolspage\components\editor\actions\InsertAction;
use humanized\contenttoolspage\components\editor\actions\RotateAction;
use yii\web\Response;
use yii\filters\VerbFilter;

class ContentController extends Controller
{

    public $context = NULL;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-container' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'content-tools-image-upload',
                            'content-tools-image-insert',
                            'content-tools-image-rotate',
                            'update', 'toggle-publish-page', 'toggle-publish-container', 'create-container', 'delete-container'
                        ],
                        'allow' => true,
                    //'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'content-tools-image-upload' => UploadAction::className(),
            'content-tools-image-insert' => InsertAction::className(),
            'content-tools-image-rotate' => RotateAction::className(),
        ];
    }

    /**
     * 
     * @return array[]
     */
    public function actionUpdate()
    {
        $i = 0;
        foreach ($_POST as $key => $content) {
            $model = ContentContainer::findOne($key);
            $model->data = $content;
            $model->save();
            $i++;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [];
    }

    public function actionTogglePublishPage($id, $sector)
    {
        $model = ContentPage::findOne($id);
        $model->is_published = !$model->is_published;
        $model->save(false);
        return $this->redirect(['/' . $this->id, 'sector' => $sector, 'caller' => $model->type->name]);
    }

    public function actionTogglePublishContainer($id, $sector)
    {
        \Yii::$app->session->setFlash('success', $this->id);
        $model = ContentContainer::findOne($id);
        $model->is_published = !$model->is_published;
        $model->save(false);
        return $this->redirect(['/' . $this->id, 'sector' => $sector, 'caller' => $model->page->type->name]);
    }

    public function actionCreateContainer($id, $sector, $caller, $type)
    {
        $container = new Container([
            'page_id' => $id,
            'position' => 99,
            'is_published' => 0,
            'type_id' => $type,
        ]);

        $container->save();
        return $this->redirect(['/' . $this->id, 'sector' => $sector, 'caller' => $caller]);
    }

    public function actionDeleteContainer($id, $sector)
    {
        $model = ContentContainer::findOne($id);
        $caller = $model->page->type->name;
        $model->delete();
        return $this->redirect(['/' . $this->id, 'sector' => $sector, 'caller' => $caller]);
    }

    public function findContainer($id)
    {
        
    }

}
