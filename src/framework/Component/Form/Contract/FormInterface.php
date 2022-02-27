<?php
namespace Laventure\Component\Form\Contract;


/**
 * @FormInterface
*/
interface FormInterface
{

     /**
      * Open form tag
      *
      * @param array $options
      * @return mixed
     */
     public function open(array $options);




     /**
      * Add form items
      *
      * @param string $name
      * @param string|null $type
      * @param array $options
      * @return mixed
     */
     public function add(string $name, string $type, array $options = []);




     /**
      * Close form tag
      *
      * @return mixed
     */
     public function close();






     /**
      * Render form
      *
      * @return mixed
     */
     public function renderView();
}