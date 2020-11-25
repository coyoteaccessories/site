<?php

namespace Zemez\SampleDataInstaller\Model\Import;

/**
 * Interface ImportInterface
 *
 * @package Zemez\SampleDataInstaller\Model\Import
 */
interface ImportInterface
{
    /**
     * @return array
     */
    public function import();
}