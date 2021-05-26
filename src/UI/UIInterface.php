<?php

namespace srag\CommentsUI\UI;

use srag\CommentsUI\Ctrl\CtrlInterface;
use srag\DIC\Plugin\Pluginable;

/**
 * Interface UIInterface
 *
 * @package srag\CommentsUI\UI
 */
interface UIInterface extends Pluginable
{

    const LANG_MODULE_COMMENTSUI = "commentsui";


    /**
     * @return string
     */
    public function render() : string;


    /**
     * @param CtrlInterface $ctrl_class
     *
     * @return self
     */
    public function withCtrlClass(CtrlInterface $ctrl_class) : self;


    /**
     * @param string $id
     *
     * @return self
     */
    public function withId(string $id) : self;
}
