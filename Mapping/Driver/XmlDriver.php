<?php

/*
 * This file is part of the Doctrine MongoDBBundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CosmoW\DoctrineRiakBundle\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver as BaseXmlDriver;

/**
 * XmlDriver that additionally looks for mapping information in a global file.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Kris Wallsmith <kris@symfony.com>
 */
class XmlDriver extends BaseXmlDriver
{
    const DEFAULT_FILE_EXTENSION = '.mongodb.xml';

    /**
     * {@inheritDoc}
     */
    public function __construct($prefixes, $fileExtension = self::DEFAULT_FILE_EXTENSION)
    {
        $locator = new SymfonyFileLocator((array) $prefixes, $fileExtension);
        parent::__construct($locator, $fileExtension);
    }
}
