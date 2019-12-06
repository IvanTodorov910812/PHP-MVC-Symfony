<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 20/03/2019
 * Time: 16:55
 */

namespace AppBundle\Service\Document;

use AppBundle\Entity\Document;


/**
 * Interface DocumentServiceInterface
 * @package AppBundle\Service\Document
 */
interface DocumentServiceInterface
{
    /**
     * @param Document $document
     */
    public function deleteDocument(Document $document);
}