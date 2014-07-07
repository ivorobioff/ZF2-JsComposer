<?php
namespace Developer\JsComposer;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface, BootstrapListenerInterface
{
	/**
	 * Returns configuration to merge with application configuration
	 *
	 * @return array|\Traversable
	 */
	public function getConfig()
	{
		return [
			'js_composer' => [
				'classes' => '',
				'bin' => '',
				'public' => '',
				'boot' => '',
				'bootfiles' => [
//					'{CONTROLLER_CLASS}' => [
//						'actions' => [
//							'{ACTION_NAME}' => ['{BOOTFILE1}', '{BOOTFILE3}']
//						],
//						'bootfiles' => ['{BOOTFILE1}', '{BOOTFILE2}']
//					]
				]
			]
		];
	}

	/**
	 * Return an array for passing to Zend\Loader\AutoloaderFactory.
	 *
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' =>[
				'namespaces' => [
					__NAMESPACE__ => __DIR__ . '/src/JsComposer',
				],
			]
		];
	}

	/**
	 * Listen to the bootstrap event
	 *
	 * @param EventInterface|MvcEvent $e
	 * @return array
	 */
	public function onBootstrap(EventInterface $e)
	{
		$e->getApplication()
			->getEventManager()
			->attach(MvcEvent::EVENT_DISPATCH, new JsComposerInitializer());
	}
}