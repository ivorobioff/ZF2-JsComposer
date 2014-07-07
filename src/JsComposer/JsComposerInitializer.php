<?php
namespace Developer\JsComposer;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsComposerInitializer 
{
	public function __invoke(MvcEvent $event)
	{
		if (!$event->getTarget() instanceof AbstractActionController) return ;

		$controller = $event->getTarget();

		$config = $event
			->getApplication()
			->getServiceManager()
			->get('Config')['js_composer'];

		$action = $event->getRouteMatch()->getParam('action');

		$event->getApplication()
			->getServiceManager()
			->get('ViewHelperManager')
			->setFactory('jsComposer',
				function() use ($controller, $action, $config){
					return new JsComposerHelper($controller, $action, $config);
				}
			);
	}
} 