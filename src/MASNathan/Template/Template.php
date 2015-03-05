<?php

namespace MASNathan\Template;

/**
 * Template - Lightweight Template class
 * 
 * @package MASNathan
 * @subpackage Template
 * @author André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/Template GitHub repo
 * @license MIT
 * @version 0.0.2
 */
class Template
{
	/**
	 * This var contains the path for the templates folder
	 * @var string
	 */
	protected $root;

	/**
	 * Main template filename
	 * @var string
	 */
	protected $templateFileName;

	/**
	 * Hols all the files and vars setted via Template::addFile() and Template::addVar()
	 * @var array
	 */
	protected $innerFiles = array();

	/**
	 * Template class constructor
	 * @param string $rootFolderPath Path for the template folder
	 * @param string $templateFileName Main template name
	 */
	public function __construct($rootFolderPath, $templateFileName = '')
	{
		$this->root             = $rootFolderPath;
		$this->templateFileName = $templateFileName;
	}

	/**
	 * Magic funtion to string, returns the generated content
	 * @return string
	 */
	public function __toString()
	{
		try {
			return $this->getContent();
		} catch (\Exception $e) {
			return '';
		}
	}

	/**
	 * Returns the generated content
	 * @return string
	 */
	public function getContent()
	{
		if (empty($this->templateFileName)) {
			throw new Exception\NoTemplateException("There is no main template setted");
		}
		
		return $this->getFileContent($this->templateFileName, $this->innerFiles);
	}

	/**
	 * Fetches a file content, extracting the $data contents
	 * @param string $filename
	 * @param array $data
	 * @return string
	 */
	public function getFileContent($filename, array $data = array())
	{
		$filePath = $this->root . $filename . '.php';
		if (!is_file($filePath)) {
			throw new Exception\InvalidFileException("The file that you are trying to load \"$filename\" does not exist.");
		}

		//Lets put it out
		ob_start();

		extract($data);
		include $filePath;
		
		return ob_get_clean();
	}

	/**
	 * Setter for the main template
	 * @param string $templateFileName
	 * @return Template
	 */
	public function setTemplate($templateFileName)
	{
		$this->templateFileName = $templateFileName;
		return $this;
	}

	/**
	 * Adds a file to be loaded
	 * @param string $filename
	 * @param array $data
	 * @return Template
	 */
	public function addFile($filename, array $data = array(), $alias = false)
	{
		if (!$alias) {
			$alias = explode('/', $filename);
			$alias = end($alias);
		}
		
		$this->innerFiles[$alias] = $this->getFileContent($filename, $data);
		return $this;
	}

	/**
	 * Adds a variable to be loaded
	 * @param string $filename
	 * @param array $data
	 * @return Template
	 */
	public function addVar($alias, $data)
	{
		$this->innerFiles[$alias] = $data;
		return $this;
	}
}
