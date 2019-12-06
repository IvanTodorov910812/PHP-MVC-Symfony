<?php
/**
 * Created by PhpStorm.
 * User: ivant
 * Date: 14/03/2019
 * Time: 22:01
 */

namespace AppBundle\Service\Aws;


interface UploadInterface
{
    /**
     * @param string $localPath
     * @param string $fileName
     * @param string $contentType
     * @return string
     */
    public function upload(string $localPath, string $fileName, string $contentType): string;

    /**
     * @param string $fileName
     */
    public function delete(string $fileName): void;

    /**
     * @return string
     */
    public function generateUniqueFileName(): string;
}