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

namespace CosmoW\DoctrineRiakBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CreateProxyDirectoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('doctrine_riak.odm.proxy_dir')) {
            return;
        }
        // Don't do anything if auto_generate_proxy_classes is false
        if (!$container->getParameter('doctrine_riak.odm.auto_generate_proxy_classes')) {
            return;
        }
        // Create document proxy directory
        $proxyCacheDir = $container->getParameter('doctrine_riak.odm.proxy_dir');
        if (!is_dir($proxyCacheDir)) {
            if (false === @mkdir($proxyCacheDir, 0775, true)) {
                exit(sprintf('Unable to create the Doctrine Proxy directory (%s)', dirname($proxyCacheDir)));
            }
        } elseif (!is_writable($proxyCacheDir)) {
            exit(sprintf('Unable to write in the Doctrine Proxy directory (%s)', $proxyCacheDir));
        }
    }

}
