<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 14/03/2019
 * Time: 21:51
 */

namespace AppBundle\Service\FormError;


use Symfony\Component\Form\FormInterface;

interface FormErrorServiceInterface
{
    /**
     * @param FormInterface $form
     * @return FormInterface
     */
    public function checkErrors(FormInterface $form);

}