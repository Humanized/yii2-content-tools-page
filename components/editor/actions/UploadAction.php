<?php

/**
 * @author Paweł Bizley Brzozowski
 * @version 1.0
 * @license Apache 2.0
 * https://github.com/bizley-code/yii2-content-tools
 * http://www.yiiframework.com/extension/yii2-content-tools
 * 
 * ContentTools was created by Anthony Blackshaw
 * http://getcontenttools.com/
 * https://github.com/GetmeUK/ContentTools
 */

namespace humanized\contenttoolspage\components\editor\actions;

use humanized\contenttoolspage\components\editor\models\ImageForm;
use Yii;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Example action prepared for the yii2-content-tools.
 * This action handles validation of the uploaded image and saving it.
 * Validation is done using the ImageForm.
 * Action returns the size and url of uploaded image.
 */
class UploadAction extends \bizley\contenttools\actions\UploadAction
{

    public function run()
    {
        if (Yii::$app->request->isPost) {
            $model = new ImageForm();
            $model->image = UploadedFile::getInstanceByName('image');
            if ($model->validate()) {
                if ($model->upload()) {
                    list($width, $height) = getimagesize($model->url);
                    return Json::encode([
                                'size' => [$width, $height],
                                'url' => Yii::$app->request->baseUrl . '/' . $model->url
                    ]);
                }
            } else {
                $errors = [];
                $modelErrors = $model->getErrors();
                foreach ($modelErrors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $fieldError) {
                        $errors[] = $fieldError;
                    }
                }
                if (empty($errors)) {
                    $errors = ['Unknown file upload validation error!'];
                }
                return Json::encode(['errors' => $errors]);
            }
        }
    }

}
