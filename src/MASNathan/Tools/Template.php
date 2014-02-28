<?php

namespace MASNathan\Tools;

class Template
{
	protected $root;

	protected $rootUrl;

	protected $templateFileName;

	protected $innerFiles = array();

	public function __construct($rootFolderPath, $rootUrl = '', $templateFileName = '')
	{
		$this->root             = $rootFolderPath;
		$this->rootUrl          = $rootUrl;
		$this->templateFileName = $templateFileName;
	}

	public function __toString()
	{
		if (empty($this->templateFileName)) {
			return '';
		}
		
		return $this->getFileContent($this->templateFileName, $this->innerFiles);
	}

	public function getFileContent($filename, array $data = array())
	{
		$filePath = $this->root . $filename . '.php';
		if (!is_file($filePath)) {
			throw new \Exception("The file that you are trying to load \"$filename\" does not exist.");
		}

		//Lets put it out
		ob_start();

		extract($data);
		include $filePath;
		
		return ob_get_clean();
	}

	public function setTemplate($templateFileName)
	{
		$this->templateFileName = $templateFileName;
		return $this;
	}

	public function addFile($alias, $filename, array $data = array())
	{
		$this->innerFiles[$alias] = $this->getFileContent($filename, $data);
		return $this;
	}

	public function addVar($alias, $data)
	{
		$this->innerFiles[$alias] = $data;
		return $this;
	}

	public function getUrl()
	{
		return $this->rootUrl;
	}
}