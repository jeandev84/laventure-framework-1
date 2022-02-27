<?php
namespace Laventure\Component\Form\Contract;

/**
 * @FormChildrenTypeInterface
*/
interface FormChildrenTypeInterface
{
     public function getName();
     public function getParent();
     public function getOptions();
     public function renderView();
}