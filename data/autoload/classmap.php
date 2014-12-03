<?php

$rootPath = realpath(__DIR__ . '/../../');

return array(
    'Admin\Module' => false,
    'Annotation' => false,
    'CommonTest\Traits\MockDateTrait' => $rootPath . '/test/../vendor/olcs/OlcsCommon/test/Common/src/Common/Traits'
        . '/MockDateTrait.php',
    'Common\Form\Model\Form\AdminDiscPrinting' => false,
    'Common\Form\Model\Form\name' => false,
    'Common\Service\Common\Form\Element\DynamicMultiCheckboxCommonService' => false,
    'Common\Service\Common\Form\Element\DynamicRadioCommonService' => false,
    'Common\Service\Common\Form\Element\DynamicSelectCommonService' => false,
    'Common\Service\Common\Form\Elements\Custom\OlcsCheckboxCommonService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\ActionButtonService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\CheckboxService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\FeeWaiveNoteService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\NoRenderService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\OperatingCentreCommunityLicencesService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\OperatingCentreTotalVehicleAuthorisationsService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\OperatingCentreTrailerAuthorisationsService' => false,
    'Common\Service\Common\Form\Elements\InputFilters\OperatingCentreVehicleAuthorisationsService' => false,
    'Common\Service\Common\Form\Elements\Types\CompanyNumberCommonService' => false,
    'Common\Service\Common\Form\Elements\Types\HtmlCommonService' => false,
    'Common\Service\Common\Form\Elements\Types\HtmlService' => false,
    'Common\Service\Common\Form\Elements\Types\HtmlTranslatedService' => false,
    'Common\Service\Common\Form\Elements\Types\TableService' => false,
    'Common\Service\Common\Form\FormCommonService' => false,
    'Common\Service\Common\Service\Data\PublicHolidayCommonService' => false,
    'Common\Service\Csrf' => false,
    'Common\Service\Data\Pi' => false,
    'Common\Service\DateSelect' => false,
    'Common\Service\DoctrineModule\Form\Element\ObjectMultiCheckboxDoctrineModuleService' => false,
    'Common\Service\DoctrineModule\Form\Element\ObjectRadioDoctrineModuleService' => false,
    'Common\Service\DoctrineModule\Form\Element\ObjectSelectDoctrineModuleService' => false,
    'Common\Service\DynamicSelect' => false,
    'Common\Service\ExceptionLogger' => false,
    'Common\Service\Hidden' => false,
    'Common\Service\Logger' => false,
    'Common\Service\OlcsCheckbox' => false,
    'Common\Service\Olcs\Form\Element\SlaDateSelectOlcsService' => false,
    'Common\Service\Olcs\Form\Element\SlaDateTimeSelectOlcsService' => false,
    'Common\Service\Olcs\Service\Data\Search\SearchOlcsService' => false,
    'Common\Service\PublicInquiryReason' => false,
    'Common\Service\Radio' => false,
    'Common\Service\SlaDateTimeSelect' => false,
    'Common\Service\SubmissionSections' => false,
    'Common\Service\Submit' => false,
    'Common\Service\Table\Type\application' => false,
    'Common\Service\Text' => false,
    'Common\Service\TextArea' => false,
    'Common\Service\Zend\Form\Element\ButtonService' => false,
    'Common\Service\Zend\Form\Element\CsrfZendService' => false,
    'Common\Service\Zend\Form\Element\SelectService' => false,
    'Common\Service\Zend\Form\FieldsetService' => false,
    'Common\Service\Zend\Form\FieldsetZendService' => false,
    'Common\Service\Zend\Form\FormZendService' => false,
    'Common\Service\Zend\I18n\Translator\TranslatorInterfaceZendService' => false,
    'Common\Service\Zend\ModuleManager\ModuleManagerZendService' => false,
    'Common\Service\Zend\Stdlib\Hydrator\ArraySerializableZendService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\ActionButtonService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\NoRenderService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\OperatingCentreCommunityLicencesService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\OperatingCentreTotalVehicleAuthorisationsService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\OperatingCentreTrailerAuthorisationsService' => false,
    'Common\Service\\Common\Form\Elements\InputFilters\OperatingCentreVehicleAuthorisationsService' => false,
    'Common\Service\\Common\Form\Elements\Types\HtmlService' => false,
    'Common\Service\\Common\Form\Elements\Types\HtmlTranslatedService' => false,
    'Common\Service\\Common\Form\Elements\Types\TableService' => false,
    'Common\Service\\Zend\Form\Element\ButtonService' => false,
    'Common\Service\\Zend\Form\Element\SelectService' => false,
    'Common\Service\commonformelementdynamicselect' => false,
    'Common\Service\commonformelementscustomolcscheckbox' => false,
    'Common\Service\commonformelementsinputfiltersactionbutton' => false,
    'Common\Service\commonformelementsinputfilterscheckbox' => false,
    'Common\Service\commonformelementsinputfiltersfeewaivenote' => false,
    'Common\Service\commonformelementsinputfiltersnorender' => false,
    'Common\Service\commonformelementsinputfiltersoperatingcentrecommunitylicences' => false,
    'Common\Service\commonformelementsinputfiltersoperatingcentretotalvehicleauthorisations' => false,
    'Common\Service\commonformelementsinputfiltersoperatingcentretrailerauthorisations' => false,
    'Common\Service\commonformelementsinputfiltersoperatingcentrevehicleauthorisations' => false,
    'Common\Service\commonformelementstypescompanynumber' => false,
    'Common\Service\commonformelementstypeshtml' => false,
    'Common\Service\commonformelementstypeshtmltranslated' => false,
    'Common\Service\commonformelementstypestable' => false,
    'Common\Service\commonformform' => false,
    'Common\Service\csrf' => false,
    'Common\Service\dateselect' => false,
    'Common\Service\dynamicselect' => false,
    'Common\Service\hidden' => false,
    'Common\Service\olcsformelementsladatetimeselect' => false,
    'Common\Service\radio' => false,
    'Common\Service\submit' => false,
    'Common\Service\text' => false,
    'Common\Service\textarea' => false,
    'Common\Service\zendformelementbutton' => false,
    'Common\Service\zendformelementcsrf' => false,
    'Common\Service\zendformelementselect' => false,
    'Common\Service\zendformfieldset' => false,
    'Common\Service\zendformform' => false,
    'DataHelper' => false,
    'DoctrineModule\Module' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Module.php',
    'DoctrineModule\Options\Cache' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Options'
        . '/Cache.php',
    'DoctrineModule\Options\Driver' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Options'
        . '/Driver.php',
    'DoctrineModule\Options\EventManager' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Options'
        . '/EventManager.php',
    'DoctrineModule\ServiceFactory\AbstractDoctrineServiceFactory' => $rootPath . '/vendor/doctrine/doctrine-module/src'
        . '/DoctrineModule/ServiceFactory/AbstractDoctrineServiceFactory.php',
    'DoctrineModule\Service\AbstractFactory' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule'
        . '/Service/AbstractFactory.php',
    'DoctrineModule\Service\CacheFactory' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Service'
        . '/CacheFactory.php',
    'DoctrineModule\Service\DriverFactory' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule/Service'
        . '/DriverFactory.php',
    'DoctrineModule\Service\EventManagerFactory' => $rootPath . '/vendor/doctrine/doctrine-module/src/DoctrineModule'
        . '/Service/EventManagerFactory.php',
    'DoctrineORMModule\Module' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src/DoctrineORMModule/Module.php',
    'DoctrineORMModule\Options\Configuration' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Options/Configuration.php',
    'DoctrineORMModule\Options\DBALConfiguration' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Options/DBALConfiguration.php',
    'DoctrineORMModule\Options\DBALConnection' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Options/DBALConnection.php',
    'DoctrineORMModule\Options\EntityManager' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Options/EntityManager.php',
    'DoctrineORMModule\Options\EntityResolver' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Options/EntityResolver.php',
    'DoctrineORMModule\Service\ConfigurationFactory' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Service/ConfigurationFactory.php',
    'DoctrineORMModule\Service\DBALConfigurationFactory' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Service/DBALConfigurationFactory.php',
    'DoctrineORMModule\Service\DBALConnectionFactory' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Service/DBALConnectionFactory.php',
    'DoctrineORMModule\Service\EntityManagerFactory' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Service/EntityManagerFactory.php',
    'DoctrineORMModule\Service\EntityResolverFactory' => $rootPath . '/vendor/doctrine/doctrine-orm-module/src'
        . '/DoctrineORMModule/Service/EntityResolverFactory.php',
    'Doctrine\Common\Annotations\AnnotationRegistry' => $rootPath . '/vendor/doctrine/annotations/lib/Doctrine/Common'
        . '/Annotations/AnnotationRegistry.php',
    'Doctrine\Common\Annotations\Annotation\Target' => $rootPath . '/vendor/doctrine/annotations/lib/Doctrine/Common'
        . '/Annotations/Annotation/Target.php',
    'Doctrine\Common\Annotations\DocLexer' => $rootPath . '/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations'
        . '/DocLexer.php',
    'Doctrine\Common\Annotations\DocParser' => $rootPath . '/vendor/doctrine/annotations/lib/Doctrine/Common'
        . '/Annotations/DocParser.php',
    'Doctrine\Common\Cache\ArrayCache' => $rootPath . '/vendor/doctrine/cache/lib/Doctrine/Common/Cache/ArrayCache.php',
    'Doctrine\Common\Cache\Cache' => $rootPath . '/vendor/doctrine/cache/lib/Doctrine/Common/Cache/Cache.php',
    'Doctrine\Common\Cache\CacheProvider' => $rootPath . '/vendor/doctrine/cache/lib/Doctrine/Common/Cache'
        . '/CacheProvider.php',
    'Doctrine\Common\EventManager' => $rootPath . '/vendor/doctrine/common/lib/Doctrine/Common/EventManager.php',
    'Doctrine\Common\Lexer\AbstractLexer' => $rootPath . '/vendor/doctrine/lexer/lib/Doctrine/Common/Lexer'
        . '/AbstractLexer.php',
    'Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory' => $rootPath . '/vendor/doctrine/common/lib'
        . '/Doctrine/Common/Persistence/Mapping/AbstractClassMetadataFactory.php',
    'Doctrine\Common\Persistence\Mapping\ClassMetadataFactory' => $rootPath . '/vendor/doctrine/common/lib/Doctrine'
        . '/Common/Persistence/Mapping/ClassMetadataFactory.php',
    'Doctrine\Common\Persistence\Mapping\Driver\MappingDriver' => $rootPath . '/vendor/doctrine/common/lib/Doctrine'
        . '/Common/Persistence/Mapping/Driver/MappingDriver.php',
    'Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain' => $rootPath . '/vendor/doctrine/common/lib'
        . '/Doctrine/Common/Persistence/Mapping/Driver/MappingDriverChain.php',
    'Doctrine\Common\Persistence\ObjectManager' => $rootPath . '/vendor/doctrine/common/lib/Doctrine/Common/Persistence'
        . '/ObjectManager.php',
    'Doctrine\Common\PropertyChangedListener' => $rootPath . '/vendor/doctrine/common/lib/Doctrine/Common'
        . '/PropertyChangedListener.php',
    'Doctrine\Common\Proxy\AbstractProxyFactory' => $rootPath . '/vendor/doctrine/common/lib/Doctrine/Common/Proxy'
        . '/AbstractProxyFactory.php',
    'Doctrine\Common\Proxy\ProxyGenerator' => $rootPath . '/vendor/doctrine/common/lib/Doctrine/Common/Proxy'
        . '/ProxyGenerator.php',
    'Doctrine\DBAL\Configuration' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Configuration.php',
    'Doctrine\DBAL\Connection' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Connection.php',
    'Doctrine\DBAL\Driver' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver.php',
    'Doctrine\DBAL\DriverManager' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/DriverManager.php',
    'Doctrine\DBAL\Driver\Connection' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/Connection.php',
    'Doctrine\DBAL\Driver\PDOMySql\Driver' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOMySql'
        . '/Driver.php',
    'Doctrine\DBAL\Platforms\AbstractPlatform' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Platforms'
        . '/AbstractPlatform.php',
    'Doctrine\DBAL\Platforms\MySqlPlatform' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Platforms'
        . '/MySqlPlatform.php',
    'Doctrine\DBAL\Query\Expression\ExpressionBuilder' => $rootPath . '/vendor/doctrine/dbal/lib/Doctrine/DBAL/Query'
        . '/Expression/ExpressionBuilder.php',
    'Doctrine\Instantiator\Instantiator' => $rootPath . '/vendor/doctrine/instantiator/src/Doctrine/Instantiator'
        . '/Instantiator.php',
    'Doctrine\Instantiator\InstantiatorInterface' => $rootPath . '/vendor/doctrine/instantiator/src/Doctrine'
        . '/Instantiator/InstantiatorInterface.php',
    'Doctrine\ORM\Configuration' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Configuration.php',
    'Doctrine\ORM\EntityManager' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManager.php',
    'Doctrine\ORM\EntityManagerInterface' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM'
        . '/EntityManagerInterface.php',
    'Doctrine\ORM\Event\ListenersInvoker' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Event'
        . '/ListenersInvoker.php',
    'Doctrine\ORM\Events' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Events.php',
    'Doctrine\ORM\Mapping\ClassMetadataFactory' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping'
        . '/ClassMetadataFactory.php',
    'Doctrine\ORM\Mapping\DefaultEntityListenerResolver' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping'
        . '/DefaultEntityListenerResolver.php',
    'Doctrine\ORM\Mapping\Driver\DriverChain' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver'
        . '/DriverChain.php',
    'Doctrine\ORM\Mapping\EntityListenerResolver' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping'
        . '/EntityListenerResolver.php',
    'Doctrine\ORM\Proxy\ProxyFactory' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Proxy/ProxyFactory.php',
    'Doctrine\ORM\Repository\DefaultRepositoryFactory' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Repository'
        . '/DefaultRepositoryFactory.php',
    'Doctrine\ORM\Repository\RepositoryFactory' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Repository'
        . '/RepositoryFactory.php',
    'Doctrine\ORM\Tools\ResolveTargetEntityListener' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/Tools'
        . '/ResolveTargetEntityListener.php',
    'Doctrine\ORM\UnitOfWork' => $rootPath . '/vendor/doctrine/orm/lib/Doctrine/ORM/UnitOfWork.php',
    'Dvsa\Jackrabbit\Data\Object\File' => $rootPath . '/vendor/dvsa/jackrabbit/src/Data/Object/File.php',
    'Dvsa\Jackrabbit\Event\DocumentListener' => $rootPath . '/vendor/dvsa/jackrabbit/src/Event/DocumentListener.php',
    'Dvsa\Jackrabbit\Module' => $rootPath . '/vendor/dvsa/jackrabbit/src/Module.php',
    'Dvsa\Jackrabbit\Service\AbstractFactory' => $rootPath . '/vendor/dvsa/jackrabbit/src/Service/AbstractFactory.php',
    'Dvsa\Jackrabbit\Service\Client' => $rootPath . '/vendor/dvsa/jackrabbit/src/Service/Client.php',
    'Dvsa\Jackrabbit\Service\ClientFactory' => $rootPath . '/vendor/dvsa/jackrabbit/src/Service/ClientFactory.php',
    'Dvsa\Jackrabbit\Service\DocumentListenerFactory' => $rootPath . '/vendor/dvsa/jackrabbit/src/Service'
        . '/DocumentListenerFactory.php',
    'Helper\Form' => false,
    'JohnKary\PHPUnit\Listener\SpeedTrapListener' => $rootPath . '/vendor/johnkary/phpunit-speedtrap/src/JohnKary'
        . '/PHPUnit/Listener/SpeedTrapListener.php',
    'Mockery' => $rootPath . '/vendor/mockery/mockery/library/Mockery.php',
    'Mockery\Adapter\Phpunit\MockeryTestCase' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Adapter/Phpunit'
        . '/MockeryTestCase.php',
    'Mockery\CompositeExpectation' => $rootPath . '/vendor/mockery/mockery/library/Mockery/CompositeExpectation.php',
    'Mockery\Configuration' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Configuration.php',
    'Mockery\Container' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Container.php',
    'Mockery\CountValidator\CountValidatorAbstract' => $rootPath . '/vendor/mockery/mockery/library/Mockery'
        . '/CountValidator/CountValidatorAbstract.php',
    'Mockery\CountValidator\Exact' => $rootPath . '/vendor/mockery/mockery/library/Mockery/CountValidator/Exact.php',
    'Mockery\Exception' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Exception.php',
    'Mockery\Exception\NoMatchingExpectationException' => $rootPath . '/vendor/mockery/mockery/library/Mockery'
        . '/Exception/NoMatchingExpectationException.php',
    'Mockery\Expectation' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Expectation.php',
    'Mockery\ExpectationDirector' => $rootPath . '/vendor/mockery/mockery/library/Mockery/ExpectationDirector.php',
    'Mockery\ExpectationInterface' => $rootPath . '/vendor/mockery/mockery/library/Mockery/ExpectationInterface.php',
    'Mockery\Generator\CachingGenerator' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/CachingGenerator.php',
    'Mockery\Generator\DefinedTargetClass' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/DefinedTargetClass.php',
    'Mockery\Generator\Generator' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator/Generator.php',
    'Mockery\Generator\Method' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator/Method.php',
    'Mockery\Generator\MockConfiguration' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/MockConfiguration.php',
    'Mockery\Generator\MockConfigurationBuilder' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/MockConfigurationBuilder.php',
    'Mockery\Generator\MockDefinition' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/MockDefinition.php',
    'Mockery\Generator\Parameter' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator/Parameter.php',
    'Mockery\Generator\StringManipulationGenerator' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/StringManipulationGenerator.php',
    'Mockery\Generator\StringManipulation\Pass\CallTypeHintPass' => $rootPath . '/vendor/mockery/mockery/library'
        . '/Mockery/Generator/StringManipulation/Pass/CallTypeHintPass.php',
    'Mockery\Generator\StringManipulation\Pass\ClassNamePass' => $rootPath . '/vendor/mockery/mockery/library/Mockery'
        . '/Generator/StringManipulation/Pass/ClassNamePass.php',
    'Mockery\Generator\StringManipulation\Pass\ClassPass' => $rootPath . '/vendor/mockery/mockery/library/Mockery'
        . '/Generator/StringManipulation/Pass/ClassPass.php',
    'Mockery\Generator\StringManipulation\Pass\InstanceMockPass' => $rootPath . '/vendor/mockery/mockery/library'
        . '/Mockery/Generator/StringManipulation/Pass/InstanceMockPass.php',
    'Mockery\Generator\StringManipulation\Pass\InterfacePass' => $rootPath . '/vendor/mockery/mockery/library/Mockery'
        . '/Generator/StringManipulation/Pass/InterfacePass.php',
    'Mockery\Generator\StringManipulation\Pass\MethodDefinitionPass' => $rootPath . '/vendor/mockery/mockery/library'
        . '/Mockery/Generator/StringManipulation/Pass/MethodDefinitionPass.php',
    'Mockery\Generator\StringManipulation\Pass\Pass' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/StringManipulation/Pass/Pass.php',
    'Mockery\Generator\StringManipulation\Pass\RemoveBuiltinMethodsThatAreFinalPass' => $rootPath . '/vendor/mockery'
        . '/mockery/library/Mockery/Generator/StringManipulation/Pass/RemoveBuiltinMethodsThatAreFinalPass.php',
    'Mockery\Generator\StringManipulation\Pass\RemoveUnserializeForInternalSerializableClassesPass' => $rootPath . ''
        . '/vendor/mockery/mockery/library/Mockery/Generator/StringManipulation/Pass'
        . '/RemoveUnserializeForInternalSerializableClassesPass.php',
    'Mockery\Generator\UndefinedTargetClass' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Generator'
        . '/UndefinedTargetClass.php',
    'Mockery\Loader\EvalLoader' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Loader/EvalLoader.php',
    'Mockery\Loader\Loader' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Loader/Loader.php',
    'Mockery\Matcher\Closure' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Matcher/Closure.php',
    'Mockery\Matcher\MatcherAbstract' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Matcher'
        . '/MatcherAbstract.php',
    'Mockery\Matcher\Type' => $rootPath . '/vendor/mockery/mockery/library/Mockery/Matcher/Type.php',
    'Mockery\MethodCall' => $rootPath . '/vendor/mockery/mockery/library/Mockery/MethodCall.php',
    'Mockery\MockInterface' => $rootPath . '/vendor/mockery/mockery/library/Mockery/MockInterface.php',
    'Mockery\ReceivedMethodCalls' => $rootPath . '/vendor/mockery/mockery/library/Mockery/ReceivedMethodCalls.php',
    'OlcsTest\Controller\ControllerTestAbstract' => $rootPath . '/test/Olcs/src/Controller/ControllerTestAbstract.php',
    'OlcsTest\Controller\Lva\AbstractLvaControllerTestCase' => $rootPath . '/test/Olcs/src/Controller/Lva'
        . '/AbstractLvaControllerTestCase.php',
    'OlcsTest\Controller\Traits\Stub\StubApplicationController' => $rootPath . '/test/Olcs/src/Controller/Traits/Stub'
        . '/StubApplicationController.php',
    'OlcsTest\Data\Object\Search\SearchAbstractTest' => $rootPath . '/test/Olcs/src/Data/Object/Search'
        . '/SearchAbstractTest.php',
    'Olcs\Controller\Lva\Application' => false,
    'Olcs\Form\Model\Form\AdminDiscPrinting' => false,
    'Olcs\Form\Model\Form\Lva\BusinessType' => false,
    'Olcs\Form\Model\Form\Lva\OperatingCentres' => false,
    'Olcs\Form\Model\Form\Lva\TypeOfLicence' => false,
    'Olcs\Form\Model\Form\name' => false,
    'Olcs\Logging\Helper\LogError' => $rootPath . '/vendor/olcs/olcs-logging/src/Helper/LogError.php',
    'Olcs\Logging\Helper\LogException' => $rootPath . '/vendor/olcs/olcs-logging/src/Helper/LogException.php',
    'Olcs\Logging\Listener\LogError' => $rootPath . '/vendor/olcs/olcs-logging/src/Listener/LogError.php',
    'Olcs\Logging\Listener\LogRequest' => $rootPath . '/vendor/olcs/olcs-logging/src/Listener/LogRequest.php',
    'Olcs\Logging\Log\Formatter\Exception' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Formatter/Exception.php',
    'Olcs\Logging\Log\Formatter\Standard' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Formatter/Standard.php',
    'Olcs\Logging\Log\Processor\Microtime' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Processor/Microtime.php',
    'Olcs\Logging\Log\Processor\RemoteIp' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Processor/RemoteIp.php',
    'Olcs\Logging\Log\Processor\SessionId' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Processor/SessionId.php',
    'Olcs\Logging\Log\Processor\UserId' => $rootPath . '/vendor/olcs/olcs-logging/src/Log/Processor/UserId.php',
    'Olcs\Logging\Module' => $rootPath . '/vendor/olcs/olcs-logging/src/Module.php',
    'Olcs\Module' => false,
    'Olcs\Service\Data\foo' => false,
    'Olcs\TestHelpers\ControllerAddEditHelper' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/ControllerAddEditHelper.php',
    'Olcs\TestHelpers\ControllerPluginManagerHelper' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/ControllerPluginManagerHelper.php',
    'Olcs\TestHelpers\ControllerRouteMatchHelper' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/ControllerRouteMatchHelper.php',
    'Olcs\TestHelpers\FormTester\AbstractFormTest' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/FormTester/AbstractFormTest.php',
    'Olcs\TestHelpers\FormTester\Data\Object\Context' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/FormTester/Data/Object/Context.php',
    'Olcs\TestHelpers\FormTester\Data\Object\Stack' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/FormTester/Data/Object/Stack.php',
    'Olcs\TestHelpers\FormTester\Data\Object\Test' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/FormTester/Data/Object/Test.php',
    'Olcs\TestHelpers\FormTester\Data\Object\Value' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/FormTester/Data/Object/Value.php',
    'Olcs\TestHelpers\FormTester\Utils' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers/FormTester'
        . '/Utils.php',
    'Olcs\TestHelpers\Lva\Traits\LvaControllerTestTrait' => $rootPath . '/vendor/olcs/olcs-testhelpers/src/TestHelpers'
        . '/Lva/Traits/LvaControllerTestTrait.php',
    'PHPUnit_Extensions_Database_TestCase' => false,
    'PHPUnit_Extensions_SeleniumTestCase' => false,
    'PHPUnit_Extensions_Story_TestCase' => false,
    'PHP_Invoker' => false,
    'RestHelper' => false,
    'SelfServe\Form\Model\Form\AdminDiscPrinting' => false,
    'SelfServe\Form\Model\Form\name' => false,
    'StdClas' => false,
    'Symfony\Component\Yaml\Yaml' => $rootPath . '/vendor/symfony/yaml/Symfony/Component/Yaml/Yaml.php',
    'ZendDeveloperTools\Options' => $rootPath . '/vendor/zendframework/zend-developer-tools/src/ZendDeveloperTools'
        . '/Options.php',
    'ZendDeveloperTools\Report' => $rootPath . '/vendor/zendframework/zend-developer-tools/src/ZendDeveloperTools'
        . '/Report.php',
    'ZendDeveloperTools\ReportInterface' => $rootPath . '/vendor/zendframework/zend-developer-tools/src'
        . '/ZendDeveloperTools/ReportInterface.php',
    'Zend\Code\Annotation\AnnotationCollection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Annotation/AnnotationCollection.php',
    'Zend\Code\Annotation\AnnotationManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Annotation/AnnotationManager.php',
    'Zend\Code\Annotation\Parser\DoctrineAnnotationParser' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Code/Annotation/Parser/DoctrineAnnotationParser.php',
    'Zend\Code\Annotation\Parser\ParserInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Annotation/Parser/ParserInterface.php',
    'Zend\Code\NameInformation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/NameInformation.php',
    'Zend\Code\Reflection\ClassReflection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Reflection/ClassReflection.php',
    'Zend\Code\Reflection\PropertyReflection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Reflection/PropertyReflection.php',
    'Zend\Code\Reflection\ReflectionInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Reflection/ReflectionInterface.php',
    'Zend\Code\Scanner\AnnotationScanner' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code/Scanner'
        . '/AnnotationScanner.php',
    'Zend\Code\Scanner\CachingFileScanner' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code'
        . '/Scanner/CachingFileScanner.php',
    'Zend\Code\Scanner\FileScanner' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code/Scanner'
        . '/FileScanner.php',
    'Zend\Code\Scanner\ScannerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code/Scanner'
        . '/ScannerInterface.php',
    'Zend\Code\Scanner\TokenArrayScanner' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Code/Scanner'
        . '/TokenArrayScanner.php',
    'Zend\Config\Factory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Config/Factory.php',
    'Zend\Console\Console' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Console/Console.php',
    'Zend\Console\Request' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Console/Request.php',
    'Zend\Console\Response' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Console/Response.php',
    'Zend\Controller\Plugin\Params' => false,
    'Zend\Controller\Plugin\Redirect' => false,
    'Zend\Escaper\Escaper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Escaper/Escaper.php',
    'Zend\EventManager\AbstractListenerAggregate' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/AbstractListenerAggregate.php',
    'Zend\EventManager\Event' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/EventManager/Event.php',
    'Zend\EventManager\EventInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/EventManager'
        . '/EventInterface.php',
    'Zend\EventManager\EventManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/EventManager'
        . '/EventManager.php',
    'Zend\EventManager\EventManagerAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/EventManagerAwareInterface.php',
    'Zend\EventManager\EventManagerAwareTrait' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/EventManagerAwareTrait.php',
    'Zend\EventManager\EventManagerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/EventManagerInterface.php',
    'Zend\EventManager\EventsCapableInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/EventsCapableInterface.php',
    'Zend\EventManager\ListenerAggregateInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/ListenerAggregateInterface.php',
    'Zend\EventManager\ListenerAggregateTrait' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/ListenerAggregateTrait.php',
    'Zend\EventManager\ResponseCollection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/ResponseCollection.php',
    'Zend\EventManager\SharedEventAggregateAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/EventManager/SharedEventAggregateAwareInterface.php',
    'Zend\EventManager\SharedEventManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/SharedEventManager.php',
    'Zend\EventManager\SharedEventManagerAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/EventManager/SharedEventManagerAwareInterface.php',
    'Zend\EventManager\SharedEventManagerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/SharedEventManagerInterface.php',
    'Zend\EventManager\StaticEventManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/EventManager/StaticEventManager.php',
    'Zend\Filter\AbstractFilter' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/AbstractFilter.php',
    'Zend\Filter\Digits' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/Digits.php',
    'Zend\Filter\FilterChain' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/FilterChain.php',
    'Zend\Filter\FilterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/FilterInterface.php',
    'Zend\Filter\FilterPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/FilterPluginManager.php',
    'Zend\Filter\StringTrim' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/StringTrim.php',
    'Zend\Filter\Word\AbstractSeparator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/Word'
        . '/AbstractSeparator.php',
    'Zend\Filter\Word\CamelCaseToDash' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/Word'
        . '/CamelCaseToDash.php',
    'Zend\Filter\Word\CamelCaseToSeparator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/Word/CamelCaseToSeparator.php',
    'Zend\Filter\Word\CamelCaseToUnderscore' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/Word/CamelCaseToUnderscore.php',
    'Zend\Filter\Word\DashToCamelCase' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/Word'
        . '/DashToCamelCase.php',
    'Zend\Filter\Word\SeparatorToCamelCase' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/Word/SeparatorToCamelCase.php',
    'Zend\Filter\Word\SeparatorToSeparator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/Word/SeparatorToSeparator.php',
    'Zend\Filter\Word\UnderscoreToCamelCase' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter'
        . '/Word/UnderscoreToCamelCase.php',
    'Zend\Filter\Word\UnderscoreToDash' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Filter/Word'
        . '/UnderscoreToDash.php',
    'Zend\Form\Annotation\AbstractAnnotationsListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Form/Annotation/AbstractAnnotationsListener.php',
    'Zend\Form\Annotation\AbstractArrayAnnotation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Form/Annotation/AbstractArrayAnnotation.php',
    'Zend\Form\Annotation\AbstractArrayOrStringAnnotation' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Form/Annotation/AbstractArrayOrStringAnnotation.php',
    'Zend\Form\Annotation\AbstractStringAnnotation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Form/Annotation/AbstractStringAnnotation.php',
    'Zend\Form\Annotation\AllowEmpty' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/AllowEmpty.php',
    'Zend\Form\Annotation\AnnotationBuilder' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/Annotation/AnnotationBuilder.php',
    'Zend\Form\Annotation\Attributes' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Attributes.php',
    'Zend\Form\Annotation\ComposedObject' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/Annotation/ComposedObject.php',
    'Zend\Form\Annotation\ElementAnnotationsListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Form/Annotation/ElementAnnotationsListener.php',
    'Zend\Form\Annotation\Filter' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Filter.php',
    'Zend\Form\Annotation\FormAnnotationsListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Form/Annotation/FormAnnotationsListener.php',
    'Zend\Form\Annotation\Hydrator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Hydrator.php',
    'Zend\Form\Annotation\Input' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Input.php',
    'Zend\Form\Annotation\Name' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Name.php',
    'Zend\Form\Annotation\Options' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Options.php',
    'Zend\Form\Annotation\Required' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Required.php',
    'Zend\Form\Annotation\Type' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Type.php',
    'Zend\Form\Annotation\Validator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Annotation'
        . '/Validator.php',
    'Zend\Form\Element' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element.php',
    'Zend\Form\ElementAttributeRemovalInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/ElementAttributeRemovalInterface.php',
    'Zend\Form\ElementInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/ElementInterface.php',
    'Zend\Form\ElementPrepareAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/ElementPrepareAwareInterface.php',
    'Zend\Form\Element\Button' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Button.php',
    'Zend\Form\Element\Checkbox' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Checkbox.php',
    'Zend\Form\Element\Csrf' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element/Csrf.php',
    'Zend\Form\Element\DateSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/DateSelect.php',
    'Zend\Form\Element\DateTimeSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/DateTimeSelect.php',
    'Zend\Form\Element\Hidden' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Hidden.php',
    'Zend\Form\Element\MonthSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/MonthSelect.php',
    'Zend\Form\Element\MultiCheckbox' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/MultiCheckbox.php',
    'Zend\Form\Element\Radio' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element/Radio.php',
    'Zend\Form\Element\Select' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Select.php',
    'Zend\Form\Element\Submit' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Submit.php',
    'Zend\Form\Element\Text' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element/Text.php',
    'Zend\Form\Element\Textarea' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Element'
        . '/Textarea.php',
    'Zend\Form\Factory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Factory.php',
    'Zend\Form\Fieldset' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Fieldset.php',
    'Zend\Form\FieldsetInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/FieldsetInterface.php',
    'Zend\Form\Form' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/Form.php',
    'Zend\Form\FormAbstractServiceFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/FormAbstractServiceFactory.php',
    'Zend\Form\FormElementManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/FormElementManager.php',
    'Zend\Form\FormFactoryAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/FormFactoryAwareInterface.php',
    'Zend\Form\FormInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/FormInterface.php',
    'Zend\Form\LabelAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/LabelAwareInterface.php',
    'Zend\Form\View\HelperConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/HelperConfig.php',
    'Zend\Form\View\Helper\AbstractHelper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/AbstractHelper.php',
    'Zend\Form\View\Helper\Form' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/Form.php',
    'Zend\Form\View\Helper\FormButton' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormButton.php',
    'Zend\Form\View\Helper\FormCheckbox' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormCheckbox.php',
    'Zend\Form\View\Helper\FormCollection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormCollection.php',
    'Zend\Form\View\Helper\FormDateSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormDateSelect.php',
    'Zend\Form\View\Helper\FormElement' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormElement.php',
    'Zend\Form\View\Helper\FormElementErrors' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/View/Helper/FormElementErrors.php',
    'Zend\Form\View\Helper\FormHidden' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormHidden.php',
    'Zend\Form\View\Helper\FormInput' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/FormInput.php',
    'Zend\Form\View\Helper\FormLabel' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/FormLabel.php',
    'Zend\Form\View\Helper\FormMonthSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormMonthSelect.php',
    'Zend\Form\View\Helper\FormMultiCheckbox' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form'
        . '/View/Helper/FormMultiCheckbox.php',
    'Zend\Form\View\Helper\FormRadio' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/FormRadio.php',
    'Zend\Form\View\Helper\FormRow' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/FormRow.php',
    'Zend\Form\View\Helper\FormSelect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormSelect.php',
    'Zend\Form\View\Helper\FormSubmit' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View'
        . '/Helper/FormSubmit.php',
    'Zend\Form\View\Helper\FormText' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Form/View/Helper'
        . '/FormText.php',
    'Zend\Http\AbstractMessage' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/AbstractMessage.php',
    'Zend\Http\Client' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Client.php',
    'Zend\Http\Client\Adapter\AdapterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/Client/Adapter/AdapterInterface.php',
    'Zend\Http\Client\Adapter\Socket' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Client'
        . '/Adapter/Socket.php',
    'Zend\Http\Client\Adapter\StreamInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/Client/Adapter/StreamInterface.php',
    'Zend\Http\HeaderLoader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/HeaderLoader.php',
    'Zend\Http\Header\AbstractAccept' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/AbstractAccept.php',
    'Zend\Http\Header\AbstractDate' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/AbstractDate.php',
    'Zend\Http\Header\AbstractLocation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/AbstractLocation.php',
    'Zend\Http\Header\Accept' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header/Accept.php',
    'Zend\Http\Header\AcceptLanguage' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/AcceptLanguage.php',
    'Zend\Http\Header\Accept\FieldValuePart\AbstractFieldValuePart' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Http/Header/Accept/FieldValuePart/AbstractFieldValuePart.php',
    'Zend\Http\Header\Accept\FieldValuePart\AcceptFieldValuePart' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Http/Header/Accept/FieldValuePart/AcceptFieldValuePart.php',
    'Zend\Http\Header\Accept\FieldValuePart\LanguageFieldValuePart' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Http/Header/Accept/FieldValuePart/LanguageFieldValuePart.php',
    'Zend\Http\Header\CacheControl' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/CacheControl.php',
    'Zend\Http\Header\Connection' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/Connection.php',
    'Zend\Http\Header\ContentEncoding' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/ContentEncoding.php',
    'Zend\Http\Header\ContentLength' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/ContentLength.php',
    'Zend\Http\Header\ContentType' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/ContentType.php',
    'Zend\Http\Header\Cookie' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header/Cookie.php',
    'Zend\Http\Header\Expires' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/Expires.php',
    'Zend\Http\Header\GenericHeader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/GenericHeader.php',
    'Zend\Http\Header\HeaderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/HeaderInterface.php',
    'Zend\Http\Header\Host' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header/Host.php',
    'Zend\Http\Header\Location' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/Location.php',
    'Zend\Http\Header\MultipleHeaderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/Header/MultipleHeaderInterface.php',
    'Zend\Http\Header\SetCookie' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Header'
        . '/SetCookie.php',
    'Zend\Http\Headers' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Headers.php',
    'Zend\Http\PhpEnvironment\RemoteAddress' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/PhpEnvironment/RemoteAddress.php',
    'Zend\Http\PhpEnvironment\Request' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/PhpEnvironment/Request.php',
    'Zend\Http\PhpEnvironment\Response' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http'
        . '/PhpEnvironment/Response.php',
    'Zend\Http\Request' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Request.php',
    'Zend\Http\Response' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Http/Response.php',
    'Zend\I18n\Filter\AbstractLocale' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/Filter'
        . '/AbstractLocale.php',
    'Zend\I18n\Filter\Alnum' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/Filter/Alnum.php',
    'Zend\I18n\Translator\LoaderPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n'
        . '/Translator/LoaderPluginManager.php',
    'Zend\I18n\Translator\Loader\AbstractFileLoader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/I18n/Translator/Loader/AbstractFileLoader.php',
    'Zend\I18n\Translator\Loader\FileLoaderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/I18n/Translator/Loader/FileLoaderInterface.php',
    'Zend\I18n\Translator\Loader\PhpArray' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n'
        . '/Translator/Loader/PhpArray.php',
    'Zend\I18n\Translator\TextDomain' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/Translator'
        . '/TextDomain.php',
    'Zend\I18n\Translator\Translator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/Translator'
        . '/Translator.php',
    'Zend\I18n\Translator\TranslatorAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/I18n/Translator/TranslatorAwareInterface.php',
    'Zend\I18n\Translator\TranslatorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n'
        . '/Translator/TranslatorInterface.php',
    'Zend\I18n\Validator\Alnum' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/Validator'
        . '/Alnum.php',
    'Zend\I18n\View\HelperConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/View'
        . '/HelperConfig.php',
    'Zend\I18n\View\Helper\AbstractTranslatorHelper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/I18n/View/Helper/AbstractTranslatorHelper.php',
    'Zend\I18n\View\Helper\Translate' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/I18n/View/Helper'
        . '/Translate.php',
    'Zend\InputFilter\BaseInputFilter' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/InputFilter'
        . '/BaseInputFilter.php',
    'Zend\InputFilter\EmptyContextInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/EmptyContextInterface.php',
    'Zend\InputFilter\Factory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/InputFilter'
        . '/Factory.php',
    'Zend\InputFilter\Input' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/InputFilter/Input.php',
    'Zend\InputFilter\InputFilter' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/InputFilter'
        . '/InputFilter.php',
    'Zend\InputFilter\InputFilterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/InputFilterInterface.php',
    'Zend\InputFilter\InputFilterPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/InputFilterPluginManager.php',
    'Zend\InputFilter\InputInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/InputFilter'
        . '/InputInterface.php',
    'Zend\InputFilter\InputProviderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/InputProviderInterface.php',
    'Zend\InputFilter\ReplaceableInputInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/ReplaceableInputInterface.php',
    'Zend\InputFilter\UnknownInputsCapableInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/InputFilter/UnknownInputsCapableInterface.php',
    'Zend\Json\Json' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Json/Json.php',
    'Zend\Loader\AutoloaderFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/AutoloaderFactory.php',
    'Zend\Loader\ModuleAutoloader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/ModuleAutoloader.php',
    'Zend\Loader\PluginClassLoader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/PluginClassLoader.php',
    'Zend\Loader\PluginClassLocator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/PluginClassLocator.php',
    'Zend\Loader\ShortNameLocator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/ShortNameLocator.php',
    'Zend\Loader\StandardAutoloader' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Loader'
        . '/StandardAutoloader.php',
    'Zend\Log\Formatter\Base' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Formatter/Base.php',
    'Zend\Log\Formatter\FormatterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/Formatter/FormatterInterface.php',
    'Zend\Log\Logger' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Logger.php',
    'Zend\Log\LoggerAbstractServiceFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/LoggerAbstractServiceFactory.php',
    'Zend\Log\LoggerAwareTrait' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/LoggerAwareTrait.php',
    'Zend\Log\LoggerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/LoggerInterface.php',
    'Zend\Log\ProcessorPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/ProcessorPluginManager.php',
    'Zend\Log\Processor\ProcessorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/Processor/ProcessorInterface.php',
    'Zend\Log\Processor\RequestId' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Processor'
        . '/RequestId.php',
    'Zend\Log\WriterPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/WriterPluginManager.php',
    'Zend\Log\Writer\AbstractWriter' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Writer'
        . '/AbstractWriter.php',
    'Zend\Log\Writer\FormatterPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log'
        . '/Writer/FormatterPluginManager.php',
    'Zend\Log\Writer\Stream' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Writer/Stream.php',
    'Zend\Log\Writer\WriterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Log/Writer'
        . '/WriterInterface.php',
    'Zend\Math\Rand' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Math/Rand.php',
    'Zend\ModuleManager\Feature\AutoloaderProviderInterface' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Feature/AutoloaderProviderInterface.php',
    'Zend\ModuleManager\Feature\BootstrapListenerInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Feature/BootstrapListenerInterface.php',
    'Zend\ModuleManager\Feature\ConfigProviderInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Feature/ConfigProviderInterface.php',
    'Zend\ModuleManager\Feature\ControllerProviderInterface' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Feature/ControllerProviderInterface.php',
    'Zend\ModuleManager\Feature\DependencyIndicatorInterface' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Feature/DependencyIndicatorInterface.php',
    'Zend\ModuleManager\Feature\InitProviderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Feature/InitProviderInterface.php',
    'Zend\ModuleManager\Feature\ServiceProviderInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Feature/ServiceProviderInterface.php',
    'Zend\ModuleManager\Feature\ViewHelperProviderInterface' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Feature/ViewHelperProviderInterface.php',
    'Zend\ModuleManager\Listener\AbstractListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/AbstractListener.php',
    'Zend\ModuleManager\Listener\AutoloaderListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/AutoloaderListener.php',
    'Zend\ModuleManager\Listener\ConfigListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/ConfigListener.php',
    'Zend\ModuleManager\Listener\ConfigMergerInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Listener/ConfigMergerInterface.php',
    'Zend\ModuleManager\Listener\DefaultListenerAggregate' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Listener/DefaultListenerAggregate.php',
    'Zend\ModuleManager\Listener\InitTrigger' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/InitTrigger.php',
    'Zend\ModuleManager\Listener\ListenerOptions' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/ListenerOptions.php',
    'Zend\ModuleManager\Listener\LocatorRegistrationListener' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Listener/LocatorRegistrationListener.php',
    'Zend\ModuleManager\Listener\ModuleDependencyCheckerListener' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/ModuleManager/Listener/ModuleDependencyCheckerListener.php',
    'Zend\ModuleManager\Listener\ModuleLoaderListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/ModuleLoaderListener.php',
    'Zend\ModuleManager\Listener\ModuleResolverListener' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Listener/ModuleResolverListener.php',
    'Zend\ModuleManager\Listener\OnBootstrapListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/OnBootstrapListener.php',
    'Zend\ModuleManager\Listener\ServiceListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/Listener/ServiceListener.php',
    'Zend\ModuleManager\Listener\ServiceListenerInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/ModuleManager/Listener/ServiceListenerInterface.php',
    'Zend\ModuleManager\ModuleEvent' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/ModuleManager'
        . '/ModuleEvent.php',
    'Zend\ModuleManager\ModuleManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/ModuleManager'
        . '/ModuleManager.php',
    'Zend\ModuleManager\ModuleManagerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ModuleManager/ModuleManagerInterface.php',
    'Zend\Mvc\Application' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Application.php',
    'Zend\Mvc\ApplicationInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/ApplicationInterface.php',
    'Zend\Mvc\Controller\AbstractActionController' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/AbstractActionController.php',
    'Zend\Mvc\Controller\AbstractController' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/AbstractController.php',
    'Zend\Mvc\Controller\ControllerManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/ControllerManager.php',
    'Zend\Mvc\Controller\PluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Controller'
        . '/PluginManager.php',
    'Zend\Mvc\Controller\Plugin\AbstractPlugin' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/Plugin/AbstractPlugin.php',
    'Zend\Mvc\Controller\Plugin\Confirm' => false,
    'Zend\Mvc\Controller\Plugin\DataServiceManager' => false,
    'Zend\Mvc\Controller\Plugin\FlashMessenger' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/Plugin/FlashMessenger.php',
    'Zend\Mvc\Controller\Plugin\Forward' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/Plugin/Forward.php',
    'Zend\Mvc\Controller\Plugin\Params' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Controller'
        . '/Plugin/Params.php',
    'Zend\Mvc\Controller\Plugin\PluginInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/Plugin/PluginInterface.php',
    'Zend\Mvc\Controller\Plugin\Redirect' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Controller/Plugin/Redirect.php',
    'Zend\Mvc\Controller\Plugin\Url' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Controller'
        . '/Plugin/Url.php',
    'Zend\Mvc\DispatchListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/DispatchListener.php',
    'Zend\Mvc\Exception\ExceptionInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Exception/ExceptionInterface.php',
    'Zend\Mvc\Exception\RuntimeException' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Exception/RuntimeException.php',
    'Zend\Mvc\I18n\Translator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/I18n'
        . '/Translator.php',
    'Zend\Mvc\InjectApplicationEventInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/InjectApplicationEventInterface.php',
    'Zend\Mvc\ModuleRouteListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/ModuleRouteListener.php',
    'Zend\Mvc\MvcEvent' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/MvcEvent.php',
    'Zend\Mvc\ResponseSender\AbstractResponseSender' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/ResponseSender/AbstractResponseSender.php',
    'Zend\Mvc\ResponseSender\ConsoleResponseSender' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/ResponseSender/ConsoleResponseSender.php',
    'Zend\Mvc\ResponseSender\HttpResponseSender' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/ResponseSender/HttpResponseSender.php',
    'Zend\Mvc\ResponseSender\PhpEnvironmentResponseSender' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Mvc/ResponseSender/PhpEnvironmentResponseSender.php',
    'Zend\Mvc\ResponseSender\ResponseSenderInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/ResponseSender/ResponseSenderInterface.php',
    'Zend\Mvc\ResponseSender\SendResponseEvent' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/ResponseSender/SendResponseEvent.php',
    'Zend\Mvc\ResponseSender\SimpleStreamResponseSender' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Mvc/ResponseSender/SimpleStreamResponseSender.php',
    'Zend\Mvc\RouteListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/RouteListener.php',
    'Zend\Mvc\RouteMatch' => false,
    'Zend\Mvc\Router\Console\SimpleRouteStack' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Router/Console/SimpleRouteStack.php',
    'Zend\Mvc\Router\Exception\ExceptionInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Router/Exception/ExceptionInterface.php',
    'Zend\Mvc\Router\Exception\RuntimeException' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Router/Exception/RuntimeException.php',
    'Zend\Mvc\Router\Http\Literal' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router/Http'
        . '/Literal.php',
    'Zend\Mvc\Router\Http\Part' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router/Http'
        . '/Part.php',
    'Zend\Mvc\Router\Http\RouteInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/Http/RouteInterface.php',
    'Zend\Mvc\Router\Http\RouteMatch' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router/Http'
        . '/RouteMatch.php',
    'Zend\Mvc\Router\Http\Segment' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router/Http'
        . '/Segment.php',
    'Zend\Mvc\Router\Http\TreeRouteStack' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/Http/TreeRouteStack.php',
    'Zend\Mvc\Router\PriorityList' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/PriorityList.php',
    'Zend\Mvc\Router\RouteInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/RouteInterface.php',
    'Zend\Mvc\Router\RouteMatch' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/RouteMatch.php',
    'Zend\Mvc\Router\RoutePluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/RoutePluginManager.php',
    'Zend\Mvc\Router\RouteStackInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/RouteStackInterface.php',
    'Zend\Mvc\Router\SimpleRouteStack' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Router'
        . '/SimpleRouteStack.php',
    'Zend\Mvc\SendResponseListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/SendResponseListener.php',
    'Zend\Mvc\Service\AbstractPluginManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/Service/AbstractPluginManagerFactory.php',
    'Zend\Mvc\Service\ApplicationFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/ApplicationFactory.php',
    'Zend\Mvc\Service\ConfigFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/ConfigFactory.php',
    'Zend\Mvc\Service\ConsoleViewManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ConsoleViewManagerFactory.php',
    'Zend\Mvc\Service\ControllerLoaderFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ControllerLoaderFactory.php',
    'Zend\Mvc\Service\ControllerPluginManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/Service/ControllerPluginManagerFactory.php',
    'Zend\Mvc\Service\EventManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/EventManagerFactory.php',
    'Zend\Mvc\Service\FilterManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/FilterManagerFactory.php',
    'Zend\Mvc\Service\FormElementManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/FormElementManagerFactory.php',
    'Zend\Mvc\Service\HttpViewManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/HttpViewManagerFactory.php',
    'Zend\Mvc\Service\HydratorManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/HydratorManagerFactory.php',
    'Zend\Mvc\Service\InputFilterManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/InputFilterManagerFactory.php',
    'Zend\Mvc\Service\LogProcessorManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/LogProcessorManagerFactory.php',
    'Zend\Mvc\Service\LogWriterManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/LogWriterManagerFactory.php',
    'Zend\Mvc\Service\ModuleManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ModuleManagerFactory.php',
    'Zend\Mvc\Service\RequestFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/RequestFactory.php',
    'Zend\Mvc\Service\ResponseFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/ResponseFactory.php',
    'Zend\Mvc\Service\RoutePluginManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/RoutePluginManagerFactory.php',
    'Zend\Mvc\Service\RouterFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/RouterFactory.php',
    'Zend\Mvc\Service\SerializerAdapterPluginManagerFactory' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Mvc/Service/SerializerAdapterPluginManagerFactory.php',
    'Zend\Mvc\Service\ServiceListenerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ServiceListenerFactory.php',
    'Zend\Mvc\Service\ServiceManagerConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ServiceManagerConfig.php',
    'Zend\Mvc\Service\TranslatorServiceFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/TranslatorServiceFactory.php',
    'Zend\Mvc\Service\ValidatorManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ValidatorManagerFactory.php',
    'Zend\Mvc\Service\ViewHelperManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ViewHelperManagerFactory.php',
    'Zend\Mvc\Service\ViewJsonRendererFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ViewJsonRendererFactory.php',
    'Zend\Mvc\Service\ViewJsonStrategyFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/Service/ViewJsonStrategyFactory.php',
    'Zend\Mvc\Service\ViewManagerFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/ViewManagerFactory.php',
    'Zend\Mvc\Service\ViewResolverFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/Service'
        . '/ViewResolverFactory.php',
    'Zend\Mvc\Service\ViewTemplateMapResolverFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/Service/ViewTemplateMapResolverFactory.php',
    'Zend\Mvc\Service\ViewTemplatePathStackFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/Service/ViewTemplatePathStackFactory.php',
    'Zend\Mvc\View\Console\CreateViewModelListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/View/Console/CreateViewModelListener.php',
    'Zend\Mvc\View\Console\DefaultRenderingStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/View/Console/DefaultRenderingStrategy.php',
    'Zend\Mvc\View\Console\ExceptionStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/View'
        . '/Console/ExceptionStrategy.php',
    'Zend\Mvc\View\Console\InjectNamedConsoleParamsListener' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Mvc/View/Console/InjectNamedConsoleParamsListener.php',
    'Zend\Mvc\View\Console\InjectViewModelListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Mvc/View/Console/InjectViewModelListener.php',
    'Zend\Mvc\View\Console\RouteNotFoundStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Console/RouteNotFoundStrategy.php',
    'Zend\Mvc\View\Console\ViewManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/View'
        . '/Console/ViewManager.php',
    'Zend\Mvc\View\Http\CreateViewModelListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Http/CreateViewModelListener.php',
    'Zend\Mvc\View\Http\DefaultRenderingStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Http/DefaultRenderingStrategy.php',
    'Zend\Mvc\View\Http\ExceptionStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/View'
        . '/Http/ExceptionStrategy.php',
    'Zend\Mvc\View\Http\InjectTemplateListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Http/InjectTemplateListener.php',
    'Zend\Mvc\View\Http\InjectViewModelListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Http/InjectViewModelListener.php',
    'Zend\Mvc\View\Http\RouteNotFoundStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc'
        . '/View/Http/RouteNotFoundStrategy.php',
    'Zend\Mvc\View\Http\ViewManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Mvc/View/Http'
        . '/ViewManager.php',
    'Zend\Navigation\AbstractContainer' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation'
        . '/AbstractContainer.php',
    'Zend\Navigation\Navigation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation'
        . '/Navigation.php',
    'Zend\Navigation\Page\AbstractPage' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation'
        . '/Page/AbstractPage.php',
    'Zend\Navigation\Page\Mvc' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation/Page'
        . '/Mvc.php',
    'Zend\Navigation\Page\Uri' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation/Page'
        . '/Uri.php',
    'Zend\Navigation\Service\AbstractNavigationFactory' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Navigation/Service/AbstractNavigationFactory.php',
    'Zend\Navigation\Service\ConstructedNavigationFactory' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Navigation/Service/ConstructedNavigationFactory.php',
    'Zend\Navigation\Service\DefaultNavigationFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Navigation/Service/DefaultNavigationFactory.php',
    'Zend\Navigation\View\HelperConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Navigation'
        . '/View/HelperConfig.php',
    'Zend\Serializer\AdapterPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Serializer'
        . '/AdapterPluginManager.php',
    'Zend\ServiceManager\AbstractFactoryInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/AbstractFactoryInterface.php',
    'Zend\ServiceManager\AbstractPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/AbstractPluginManager.php',
    'Zend\ServiceManager\Config' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/ServiceManager'
        . '/Config.php',
    'Zend\ServiceManager\ConfigInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/ConfigInterface.php',
    'Zend\ServiceManager\DelegatorFactoryInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/DelegatorFactoryInterface.php',
    'Zend\ServiceManager\Exception\ExceptionInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/Exception/ExceptionInterface.php',
    'Zend\ServiceManager\Exception\RuntimeException' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/Exception/RuntimeException.php',
    'Zend\ServiceManager\FactoryInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/FactoryInterface.php',
    'Zend\ServiceManager\ServiceLocatorAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/ServiceLocatorAwareInterface.php',
    'Zend\ServiceManager\ServiceLocatorAwareTrait' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/ServiceLocatorAwareTrait.php',
    'Zend\ServiceManager\ServiceLocatorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/ServiceLocatorInterface.php',
    'Zend\ServiceManager\ServiceManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/ServiceManager/ServiceManager.php',
    'Zend\Service\Manager' => false,
    'Zend\Session\AbstractContainer' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/AbstractContainer.php',
    'Zend\Session\AbstractManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/AbstractManager.php',
    'Zend\Session\Config\ConfigInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/Config/ConfigInterface.php',
    'Zend\Session\Config\SessionConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session/Config'
        . '/SessionConfig.php',
    'Zend\Session\Config\StandardConfig' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/Config/StandardConfig.php',
    'Zend\Session\Container' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session/Container.php',
    'Zend\Session\ManagerInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/ManagerInterface.php',
    'Zend\Session\SessionManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/SessionManager.php',
    'Zend\Session\Storage\AbstractSessionArrayStorage' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Session/Storage/AbstractSessionArrayStorage.php',
    'Zend\Session\Storage\SessionArrayStorage' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/Storage/SessionArrayStorage.php',
    'Zend\Session\Storage\StorageInitializationInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Session/Storage/StorageInitializationInterface.php',
    'Zend\Session\Storage\StorageInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/Storage/StorageInterface.php',
    'Zend\Session\ValidatorChain' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Session'
        . '/ValidatorChain.php',
    'Zend\Stdlib\AbstractOptions' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/AbstractOptions.php',
    'Zend\Stdlib\ArrayObject' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/ArrayObject.php',
    'Zend\Stdlib\ArrayUtils' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/ArrayUtils.php',
    'Zend\Stdlib\CallbackHandler' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/CallbackHandler.php',
    'Zend\Stdlib\DispatchableInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/DispatchableInterface.php',
    'Zend\Stdlib\ErrorHandler' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/ErrorHandler.php',
    'Zend\Stdlib\Extractor\ExtractionInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/Extractor/ExtractionInterface.php',
    'Zend\Stdlib\Glob' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/Glob.php',
    'Zend\Stdlib\Hydrator\AbstractHydrator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/Hydrator/AbstractHydrator.php',
    'Zend\Stdlib\Hydrator\ArraySerializable' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/Hydrator/ArraySerializable.php',
    'Zend\Stdlib\Hydrator\FilterEnabledInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/Hydrator/FilterEnabledInterface.php',
    'Zend\Stdlib\Hydrator\Filter\FilterComposite' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/Hydrator/Filter/FilterComposite.php',
    'Zend\Stdlib\Hydrator\Filter\FilterInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/Hydrator/Filter/FilterInterface.php',
    'Zend\Stdlib\Hydrator\Filter\FilterProviderInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Stdlib/Hydrator/Filter/FilterProviderInterface.php',
    'Zend\Stdlib\Hydrator\HydrationInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/Hydrator/HydrationInterface.php',
    'Zend\Stdlib\Hydrator\HydratorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/Hydrator/HydratorInterface.php',
    'Zend\Stdlib\Hydrator\HydratorPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/Hydrator/HydratorPluginManager.php',
    'Zend\Stdlib\Hydrator\NamingStrategyEnabledInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Stdlib/Hydrator/NamingStrategyEnabledInterface.php',
    'Zend\Stdlib\Hydrator\StrategyEnabledInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/Hydrator/StrategyEnabledInterface.php',
    'Zend\Stdlib\InitializableInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/InitializableInterface.php',
    'Zend\Stdlib\Message' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/Message.php',
    'Zend\Stdlib\MessageInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/MessageInterface.php',
    'Zend\Stdlib\ParameterObjectInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/ParameterObjectInterface.php',
    'Zend\Stdlib\Parameters' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/Parameters.php',
    'Zend\Stdlib\ParametersInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/ParametersInterface.php',
    'Zend\Stdlib\PriorityList' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/PriorityList.php',
    'Zend\Stdlib\PriorityQueue' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/PriorityQueue.php',
    'Zend\Stdlib\RequestInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/RequestInterface.php',
    'Zend\Stdlib\ResponseInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/ResponseInterface.php',
    'Zend\Stdlib\SplPriorityQueue' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/SplPriorityQueue.php',
    'Zend\Stdlib\SplQueue' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/SplQueue.php',
    'Zend\Stdlib\SplStack' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/SplStack.php',
    'Zend\Stdlib\StringUtils' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib/StringUtils.php',
    'Zend\Stdlib\StringWrapper\AbstractStringWrapper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/StringWrapper/AbstractStringWrapper.php',
    'Zend\Stdlib\StringWrapper\Intl' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Stdlib'
        . '/StringWrapper/Intl.php',
    'Zend\Stdlib\StringWrapper\StringWrapperInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Stdlib/StringWrapper/StringWrapperInterface.php',
    'Zend\Test\PHPUnit\Controller\AbstractControllerTestCase' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Test/PHPUnit/Controller/AbstractControllerTestCase.php',
    'Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/Test/PHPUnit/Controller/AbstractHttpControllerTestCase.php',
    'Zend\Uri\Http' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Uri/Http.php',
    'Zend\Uri\Uri' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Uri/Uri.php',
    'Zend\Uri\UriFactory' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Uri/UriFactory.php',
    'Zend\Uri\UriInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Uri/UriInterface.php',
    'Zend\Validator\AbstractValidator' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator'
        . '/AbstractValidator.php',
    'Zend\Validator\Between' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Between.php',
    'Zend\Validator\Csrf' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Csrf.php',
    'Zend\Validator\Date' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Date.php',
    'Zend\Validator\Digits' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Digits.php',
    'Zend\Validator\Explode' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Explode.php',
    'Zend\Validator\Hostname' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Hostname.php',
    'Zend\Validator\InArray' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/InArray.php',
    'Zend\Validator\Ip' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/Ip.php',
    'Zend\Validator\LessThan' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/LessThan.php',
    'Zend\Validator\NotEmpty' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator/NotEmpty.php',
    'Zend\Validator\StringLength' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator'
        . '/StringLength.php',
    'Zend\Validator\Translator\TranslatorAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Validator/Translator/TranslatorAwareInterface.php',
    'Zend\Validator\Translator\TranslatorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/Validator/Translator/TranslatorInterface.php',
    'Zend\Validator\ValidatorChain' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator'
        . '/ValidatorChain.php',
    'Zend\Validator\ValidatorInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator'
        . '/ValidatorInterface.php',
    'Zend\Validator\ValidatorPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/Validator'
        . '/ValidatorPluginManager.php',
    'Zend\Validator\ValidatorPluginManagerAwareInterface' => $rootPath . '/vendor/zendframework/zendframework/library'
        . '/Zend/Validator/ValidatorPluginManagerAwareInterface.php',
    'Zend\View\HelperPluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/HelperPluginManager.php',
    'Zend\View\Helper\AbstractHelper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/AbstractHelper.php',
    'Zend\View\Helper\AbstractHtmlElement' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/AbstractHtmlElement.php',
    'Zend\View\Helper\BasePath' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/BasePath.php',
    'Zend\View\Helper\Doctype' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/Doctype.php',
    'Zend\View\Helper\EscapeHtml' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/EscapeHtml.php',
    'Zend\View\Helper\EscapeHtmlAttr' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/EscapeHtmlAttr.php',
    'Zend\View\Helper\Escaper\AbstractHelper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Escaper/AbstractHelper.php',
    'Zend\View\Helper\FlashMessenger' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/FlashMessenger.php',
    'Zend\View\Helper\HeadLink' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/HeadLink.php',
    'Zend\View\Helper\HeadMeta' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/HeadMeta.php',
    'Zend\View\Helper\HeadScript' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/HeadScript.php',
    'Zend\View\Helper\HeadTitle' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/HeadTitle.php',
    'Zend\View\Helper\HelperInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/HelperInterface.php',
    'Zend\View\Helper\InlineScript' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/InlineScript.php',
    'Zend\View\Helper\Navigation' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/Navigation.php',
    'Zend\View\Helper\Navigation\AbstractHelper' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Navigation/AbstractHelper.php',
    'Zend\View\Helper\Navigation\Breadcrumbs' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Navigation/Breadcrumbs.php',
    'Zend\View\Helper\Navigation\HelperInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Navigation/HelperInterface.php',
    'Zend\View\Helper\Navigation\Listener\AclListener' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/View/Helper/Navigation/Listener/AclListener.php',
    'Zend\View\Helper\Navigation\Menu' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/Navigation/Menu.php',
    'Zend\View\Helper\Navigation\PluginManager' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Navigation/PluginManager.php',
    'Zend\View\Helper\Partial' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/Partial.php',
    'Zend\View\Helper\Placeholder' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/Placeholder.php',
    'Zend\View\Helper\Placeholder\Container' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Helper/Placeholder/Container.php',
    'Zend\View\Helper\Placeholder\Container\AbstractContainer' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/View/Helper/Placeholder/Container/AbstractContainer.php',
    'Zend\View\Helper\Placeholder\Container\AbstractStandalone' => $rootPath . '/vendor/zendframework/zendframework'
        . '/library/Zend/View/Helper/Placeholder/Container/AbstractStandalone.php',
    'Zend\View\Helper\Url' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper/Url.php',
    'Zend\View\Helper\ViewModel' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Helper'
        . '/ViewModel.php',
    'Zend\View\Model\ClearableModelInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Model/ClearableModelInterface.php',
    'Zend\View\Model\ConsoleModel' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Model'
        . '/ConsoleModel.php',
    'Zend\View\Model\JsonModel' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Model'
        . '/JsonModel.php',
    'Zend\View\Model\ModelInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Model'
        . '/ModelInterface.php',
    'Zend\View\Model\RetrievableChildrenInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend'
        . '/View/Model/RetrievableChildrenInterface.php',
    'Zend\View\Model\ViewModel' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Model'
        . '/ViewModel.php',
    'Zend\View\Renderer\JsonRenderer' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Renderer'
        . '/JsonRenderer.php',
    'Zend\View\Renderer\PhpRenderer' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Renderer'
        . '/PhpRenderer.php',
    'Zend\View\Renderer\RendererInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Renderer/RendererInterface.php',
    'Zend\View\Renderer\TreeRendererInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Renderer/TreeRendererInterface.php',
    'Zend\View\Resolver\AggregateResolver' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Resolver/AggregateResolver.php',
    'Zend\View\Resolver\ResolverInterface' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Resolver/ResolverInterface.php',
    'Zend\View\Resolver\TemplateMapResolver' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Resolver/TemplateMapResolver.php',
    'Zend\View\Resolver\TemplatePathStack' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Resolver/TemplatePathStack.php',
    'Zend\View\Strategy\JsonStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Strategy'
        . '/JsonStrategy.php',
    'Zend\View\Strategy\PhpRendererStrategy' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View'
        . '/Strategy/PhpRendererStrategy.php',
    'Zend\View\Variables' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/Variables.php',
    'Zend\View\View' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/View.php',
    'Zend\View\ViewEvent' => $rootPath . '/vendor/zendframework/zendframework/library/Zend/View/ViewEvent.php',
    'demeter_getEventManager' => false,
    'demeter_getRouteMatch' => false,
    'demeter_redirect' => false,
);
