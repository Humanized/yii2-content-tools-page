<?php

namespace humanized\contenttoolspage\controllers;
use humanized\contenttoolspage\models\ContentType;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IndexController extends ContentController
{

    public $public = true;

    public function actionIndex($caller, $sector)
    {
 
        if (!in_array($caller, [ContentType::REGULATION])) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist');
        }
        $content = SectorPage::getContent($sector, $caller);

        return $this->render('index', [
                    'content' => $content,
                    'sector' => $sectorModel,
                    'caller' => $caller,
        ]);
    }

}
