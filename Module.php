<?php

namespace humanized\contenttoolspage;

/**
 * Humanized Content Tools Page Module for Yii2
 * 
 * A simple CMS module allow the creation of user-editable webpages using the in-place content-tools WYSIWYG editor
 * 
 * 
 * @name Yii2 Content Tools Module Class 
 * @version 0.1
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-content-tools-page
 * 
 */
class Module extends \yii\base\Module
{

    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'humanized\contenttoolspage\commands';
        }
    }

}
