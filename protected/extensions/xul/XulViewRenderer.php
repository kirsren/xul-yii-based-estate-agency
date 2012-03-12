<?php

class XulViewRenderer extends CViewRenderer {
  
  public $fileExtension = '.xul';
	private $_input;
	private $_output;
	private $_sourceFile;

	/**
	 * Parses the source view file and saves the results as another file.
	 * This method is required by the parent class.
	 * @param string $sourceFile the source view file path
	 * @param string $viewFile the resulting view file path
	 */
	protected function generateViewFile($sourceFile,$viewFile)
	{		
		file_put_contents($viewFile,file_get_contents($sourceFile));
	}
	
	/**
	 * Renders a view file.
	 * This method is required by {@link IViewRenderer}.
	 * @param CBaseController $context the controller or widget who is rendering the view file.
	 * @param string $sourceFile the view file path
	 * @param mixed $data the data to be passed to the view
	 * @param boolean $return whether the rendering result should be returned
	 * @return mixed the rendering result, or null if the rendering result is not needed.
	 */
	public function renderFile($context,$sourceFile,$data,$return)
	{
		if(!is_file($sourceFile) || ($file=realpath($sourceFile))===false)
			throw new CException(Yii::t('yii','View file "{file}" does not exist.',array('{file}'=>$sourceFile)));
		$viewFile=$this->getViewFile($sourceFile);
		if(@filemtime($sourceFile)>@filemtime($viewFile))
		{
			$this->generateViewFile($sourceFile,$viewFile);
			@chmod($viewFile,$this->filePermission);
		}
		return $context->renderInternal($viewFile,$data,$return);
	}
	
	protected function getViewFile($file)
	{
		if($this->useRuntimePath)
		{
			$crc=sprintf('%x', crc32(get_class($this).Yii::getVersion().dirname($file)));
			$viewFile=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$crc.DIRECTORY_SEPARATOR.basename($file);
			if(!is_file($viewFile))
				@mkdir(dirname($viewFile),$this->filePermission,true);
			return $viewFile;
		}
		else
			return $file.'c';
	}

}