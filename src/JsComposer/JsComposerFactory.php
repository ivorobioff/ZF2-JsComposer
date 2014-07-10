<?php
namespace Developer\JsComposer;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsComposerFactory implements FactoryInterface
{
	/**
	 * @var MvcEvent
	 */
	private $event;

	public function __construct(MvcEvent $event)
	{
		$this->event = $event;
	}

	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $this->event->getApplication()->getServiceManager()->get('Config')['js_composer'];
		$jsComposerHelper = new JsComposerHelper($config);

		if ($this->event->isError())
		{
			$jsComposerHelper->setError($this->event->getError());
		}
		else
		{
			$controller = $this->event->getRouteMatch()->getParam('controller');
			$action = $this->event->getRouteMatch()->getParam('action');
			$jsComposerHelper->setPage($controller, $action);
		}

		return $jsComposerHelper;
	}
}