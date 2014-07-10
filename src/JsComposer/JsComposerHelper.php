<?php
namespace Developer\JsComposer;

use Developer\Stuff\JsComposer\Composer;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsComposerHelper extends AbstractHelper
{
	private $controller;
	private $action;
	private $error;
	private $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function __invoke()
	{
		$classesPath = $this->config['classes'];
		$binPath = $this->config['bin'];
		$bootPath = $this->config['boot'];
		$publicPath = $this->config['public'];

		$bootfiles = $this->resolveBootfiles();

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

	private function resolveBootfiles()
	{
		$bootfiles = [];
		if (!isset($this->config['bootfiles'])) return $bootfiles;
		$bootfilesConfig = $this->config['bootfiles'];

		if (isset($bootfilesConfig['default']))
		{
			$bootfiles = $bootfilesConfig['default'];
		}

		if (isset($this->error))
		{
			if (!isset($bootfilesConfig['errors'])) return $bootfiles;
			$errorBootfiles = $bootfilesConfig['errors'];

			if (isset($errorBootfiles['default']))
			{
				$bootfiles = $errorBootfiles['default'];
			}

			if (isset($errorBootfiles['specific'][$this->error]))
			{
				$bootfiles = $errorBootfiles['specific'][$this->error];
			}

			return $bootfiles;
		}

		if (!isset($this->controller)) return $bootfiles;

		if (!isset($bootfilesConfig['pages'][$this->controller])) return $bootfiles;
		$pageBootfiles = $bootfilesConfig['pages'][$this->controller];

		if (isset($pageBootfiles['bootfiles']))
		{
			$bootfiles = $pageBootfiles['bootfiles'];
		}

		if (isset($pageBootfiles['actions'][$this->action]))
		{
			$bootfiles = $pageBootfiles['actions'][$this->action];
		}

		return $bootfiles;
	}

	public function setPage($controller, $action)
	{
		$this->controller = $controller;
		$this->action = $action;
	}

	public function setError($error)
	{
		$this->error = $error;
	}
}