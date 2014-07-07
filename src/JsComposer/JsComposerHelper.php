<?php
namespace Developer\JsComposer;
use Developer\Stuff\JsComposer\Composer;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsComposerHelper extends AbstractHelper
{
	private $controller;
	private $action;
	private $config;

	public function __construct(AbstractActionController $controller, $action, array $config)
	{
		$this->controller = $controller;
		$this->action = $action;
		$this->config = $config;
	}

	public function __invoke()
	{
		$classesPath = $this->config['classes'];
		$binPath = $this->config['bin'];
		$bootPath = $this->config['boot'];
		$bootfilesConfig = $this->config['bootfiles'];
		$publicPath = $this->config['public'];

		$controllerClass = get_class($this->controller);
		if (!isset($bootfilesConfig[$controllerClass])) return '';

		$config = $bootfilesConfig[$controllerClass];

		$bootfiles = [];

		if (isset($config['bootfiles']))
		{
			$bootfiles = $config['bootfiles'];
		}

		if (isset($config['actions'][$this->action]))
		{
			$bootfiles = $config['actions'][$this->action];
		}

		if (!$bootfiles) return '';

		$bin = md5(implode('|', $bootfiles));

		$composer = new Composer($classesPath);

		foreach ($bootfiles as $file)
		{
			$bootfile = $bootPath.'/'.$file;
			if (!is_readable($bootfile)) continue ;

			$composer->addBootfile($bootfile);
		}

		if (!$composer->process($binPath.'/'.$bin.'.js'))
		{
			return '';
		}

		return '<script src="'.$publicPath.'/'.$bin.'.js"></script>';
	}
}