<?php

/*
 * This file is part of the Doctrine MongoDBBundle
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project, Benjamin Eberlei <kontakt@beberlei.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CosmoW\DoctrineRiakBundle\DependencyInjection\Compiler;

use Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class for Symfony bundles to configure mappings for model classes not in the
 * automapped folder.
 *
 * NOTE: alias is only supported by Symfony 2.6+ and will be ignored with older versions.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class DoctrineRiakMappingsPass extends RegisterMappingsPass
{
    /**
     * You should not directly instantiate this class but use one of the
     * factory methods.
     *
     * @param Definition|Reference $driver            the driver to use
     * @param array                $namespaces        list of namespaces this driver should handle
     * @param string[]             $managerParameters list of parameters that could tell the manager name to use
     * @param bool                 $enabledParameter  if specified, the compiler pass only
     *                                                executes if this parameter exists in the service container.
     * @param string[]             $aliasMap          Map of alias to namespace.
     */
    public function __construct($driver, array $namespaces, array $managerParameters, $enabledParameter = false, array $aliasMap = array())
    {
        $managerParameters[] = 'doctrine_riak.odm.default_document_manager';
        parent::__construct(
            $driver,
            $namespaces,
            $managerParameters,
            'doctrine_riak.odm.%s_metadata_driver',
            $enabledParameter,
            'doctrine_riak.odm.%s_configuration',
            'addDocumentNamespace',
            $aliasMap

        );

    }

    /**
     * @param array    $mappings          Hashmap of directory path to namespace
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param string   $enabledParameter  Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @param string[] $aliasMap          Map of alias to namespace.
     */
    public static function createXmlMappingDriver(array $mappings, array $managerParameters, $enabledParameter = false, array $aliasMap = array())
    {
        $arguments = array($mappings, '.riak.xml');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ODM\Riak\Mapping\Driver\XmlDriver', array($locator));

        return new DoctrineRiakMappingsPass($driver, $mappings, $managerParameters, $enabledParameter, $aliasMap);
    }

    /**
     * @param array    $mappings          Hashmap of directory path to namespace
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param string   $enabledParameter  Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @param string[] $aliasMap          Map of alias to namespace.
     */
    public static function createYamlMappingDriver(array $mappings, array $managerParameters, $enabledParameter = false, array $aliasMap = array())
    {
        $arguments = array($mappings, '.riak.yml');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ODM\Riak\Mapping\Driver\YamlDriver', array($locator));

        return new DoctrineRiakMappingsPass($driver, $mappings, $managerParameters, $enabledParameter, $aliasMap);
    }

    /**
     * @param array    $mappings          Hashmap of directory path to namespace
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param string   $enabledParameter  Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @param string[] $aliasMap          Map of alias to namespace.
     */
    public static function createPhpMappingDriver(array $mappings, array $managerParameters = array(), $enabledParameter = false, array $aliasMap = array())
    {
        $arguments = array($mappings, '.php');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\Common\Persistence\Mapping\Driver\PHPDriver', array($locator));

        return new DoctrineRiakMappingsPass($driver, $mappings, $managerParameters, $enabledParameter, $aliasMap);
    }

    /**
     * @param array    $namespaces        List of namespaces that are handled with annotation mapping
     * @param array    $directories       List of directories to look for annotation mapping files
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param string   $enabledParameter  Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @param string[] $aliasMap          Map of alias to namespace.
     */
    public static function createAnnotationMappingDriver(array $namespaces, array $directories, array $managerParameters, $enabledParameter = false, array $aliasMap = array())
    {
        $arguments = array(new Reference('doctrine_riak.odm.metadata.annotation_reader'), $directories);
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ODM\Riak\Mapping\Driver\AnnotationDriver', array($locator));

        return new DoctrineRiakMappingsPass($driver, $namespaces, $managerParameters, $enabledParameter, $aliasMap);
    }

    /**
     * @param array    $namespaces        List of namespaces that are handled with static php mapping
     * @param array    $directories       List of directories to look for static php mapping files
     * @param string[] $managerParameters List of parameters that could which object manager name
     *                                    your bundle uses. This compiler pass will automatically
     *                                    append the parameter name for the default entity manager
     *                                    to this list.
     * @param string   $enabledParameter  Service container parameter that must be present to
     *                                    enable the mapping. Set to false to not do any check,
     *                                    optional.
     * @param string[] $aliasMap          Map of alias to namespace.
     */
    public static function createStaticPhpMappingDriver(array $namespaces, array $directories, array $managerParameters = array(), $enabledParameter = false, array $aliasMap = array())
    {
        $driver = new Definition('Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver', array($directories));

        return new DoctrineRiakMappingsPass($driver, $namespaces, $managerParameters, $enabledParameter, $aliasMap);
    }
}
