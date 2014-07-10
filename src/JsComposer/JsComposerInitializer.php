<?php
namespace Developer\JsComposer;

use Zend\Mvc\MvcEvent;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsComposerInitializer 
{
	public function __invoke(MvcEvent $event)
	{
		$event->getApplication()
			->getServiceManager()
			->get('ViewHelperManager')
			->setFactory('jsComposer', new JsComposerFactory($event));
	}
} 