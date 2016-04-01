<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace humanized\contenttoolspage\components;

class StaticHelper
{

    const VIEW = 'view';
    const VIEWPARAMS = 'viewParams';

    /**
     * Expects values with following keys:
     * id: the name of the static content container
     * view: the view file to be rendered
     * viewParams: additional view file params
     * 
     * @var array 
     */
    public $register = [
    ];

    public static function read($id, $entry)
    {
        $register = ((new StaticHelper())->register);

        if (isset($register[$id])) {
            $record = $register[$id];
            return $record[$entry];
        }
        return NULL;
    }


}
