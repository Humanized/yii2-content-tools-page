<?php

namespace humanized\contenttoolspage\components\editor;

use yii\web\View;
use bizley\contenttools\assets\ContentToolsAsset;
use bizley\contenttools\ContentTools AS ContentToolsVendor;

class ContentTools extends ContentToolsVendor
{

    public function initEditor()
    {
        ContentToolsAsset::register($this->getView());
        $this->getView()->registerJs(//";window.addEventListener('load',function(){" .
                "var editor;" .
                "editor=ContentTools.EditorApp.get();" .
                "editor.init('*[" . static::dataAttribute($this->dataInit) . "]','" . static::dataAttribute($this->dataName) . "');" .
                $this->initSaveEngine()
                //."});"
                , View::POS_END);
    }

}
