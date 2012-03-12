<?php

/**
 * ClientScript manager for XUL extension
 * @author Zsolt Lengyel <zsolt.lengyel.it@gmail.com>
 */
class XulClientScript extends CClientScript{
	/**
	 * The script is rendered in begin of root element
	 */
	const POS_HEAD=0;
	/**
	 * The script is rendered in begin of root element
	 */
	const POS_BEGIN=1;
	/**
	 * The script is rendered at the end of the body section.
	 */
	const POS_END=2;
	/**
	 * The script is rendered inside window onload function.
	 */
	const POS_LOAD=3;
	/**
	 * The body script is rendered inside a jQuery ready function.
	 */
	const POS_READY=4;

	private $_baseUrl;

	/**
	 * Renders the registered scripts.
	 * This method is called in {@link CController::render} when it finishes
	 * rendering content. CClientScript thus gets a chance to insert script tags
	 * at <code>head</code> and <code>body</code> sections in the HTML output.
	 * @param string $output the existing output that needs to be inserted with script tags
	 */
	public function render(&$output)
	{
		if(!$this->hasScripts)
			return;

		$this->renderCoreScripts();

		if(!empty($this->scriptMap))
			$this->remapScripts();

		$this->unifyScripts();

		$this->renderHead($output);
		if($this->enableJavaScript)
		{
			//$this->renderBodyBegin($output);
			$this->renderBodyEnd($output);
		}
	}

	/**
	 * Inserts the scripts in the head section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderHead(&$output)
	{
		$html='';
		foreach($this->metaTags as $meta)
			$html.=Xul::metaTag($meta['content'],null,null,$meta)."\n";
		foreach($this->linkTags as $link)
			$html.=Xul::linkTag(null,null,null,null,$link)."\n";
		foreach($this->css as $css)
			$html.=Xul::css($css[0],$css[1])."\n";
		if($this->enableJavaScript)
		{
			if(isset($this->scriptFiles[self::POS_HEAD]))
			{
				foreach($this->scriptFiles[self::POS_HEAD] as $scriptFile)
					$html.=Xul::scriptFile($scriptFile)."\n";					
			}
			
			// In XUL POS_HEAD == POS_BEGIN
			if(isset($this->scriptFiles[self::POS_BEGIN]))
			{
				foreach($this->scriptFiles[self::POS_BEGIN] as $scriptFile)
					$html.=Xul::scriptFile($scriptFile)."\n";	
			}

			if(isset($this->scripts[self::POS_HEAD]))
				$html.=Xul::script(implode("\n",$this->scripts[self::POS_HEAD]))."\n";
			
			// In XUL POS_HEAD == POS_BEGIN	
			if(isset($this->scripts[self::POS_BEGIN]))
				$html.=Xul::script(implode("\n",$this->scripts[self::POS_BEGIN]))."\n";
		}        
        
        $rootElement = null;
		if($html!=='')
		{
			$count=0;
			$rootElement = $this->getRootElement($output);
			$output=preg_replace('/(<'. $rootElement .'[^>]*?>)/siu','${1}'."\n<###root###>",$output,1,$count);
			if($count)
				$output=str_replace('<###root###>',$html,$output);
			else
				$output=$html.$output;
		}
                
        /*
         * XUL handles XML stylesheets, place it before root element
         */
        $stylesheet = '';
        foreach($this->cssFiles as $url=>$media)
			$stylesheet.=Xul::cssFile($url)."\n";
        
        if($stylesheet!=='')
		{
			$count=0;
			if(is_null($rootElement)) $rootElement = $this->getRootElement($output);
            
			$output=preg_replace('/(<'. $rootElement .'[^>]*?>)/siu',"<###root###>".'${1}',$output,1,$count);
			if($count)
				$output=str_replace('<###root###>',$stylesheet,$output);
			else
				$output=$stylesheet.$output;
		}
        
	}

	/**
	 * Inserts the scripts at the beginning of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderBodyBegin(&$output)
	{
		return $this->renderHead($output);
	}

	/**
	 * Inserts the scripts at the end of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderBodyEnd(&$output)
	{
		if(!isset($this->scriptFiles[self::POS_END]) && !isset($this->scripts[self::POS_END])
			&& !isset($this->scripts[self::POS_READY]) && !isset($this->scripts[self::POS_LOAD]))
			return;

		$fullPage=0;
		$rootElement = $this->getRootElement($output);
		$output=preg_replace('/(<\\/'.$rootElement.'\s*>)/is','<###end###>$1',$output,1,$fullPage);
		$html='';
		if(isset($this->scriptFiles[self::POS_END]))
		{
			foreach($this->scriptFiles[self::POS_END] as $scriptFile)
				$html.=Xul::scriptFile($scriptFile)."\n";
		}
		$scripts=isset($this->scripts[self::POS_END]) ? $this->scripts[self::POS_END] : array();
		if(isset($this->scripts[self::POS_READY]))
		{
			if($fullPage)
				$scripts[]="jQuery(function($) {\n".implode("\n",$this->scripts[self::POS_READY])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_READY]);
		}
		if(isset($this->scripts[self::POS_LOAD]))
		{
			if($fullPage)
				$scripts[]="jQuery(window).load(function() {\n".implode("\n",$this->scripts[self::POS_LOAD])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_LOAD]);
		}
		if(!empty($scripts))
			$html.=Xul::script(implode("\n",$scripts))."\n";

		if($fullPage)
			$output=str_replace('<###end###>',$html,$output);
		else
			$output=$output.$html;
	}

	/**
	 * Returns the base URL of all core javascript files.
	 * If the base URL is not explicitly set, this method will publish the whole directory
	 * 'framework/web/js/source' and return the corresponding URL.
	 * @return string the base URL of all core javascript files
	 */
	public function getCoreScriptUrl()
	{
		if($this->_baseUrl!==null)
			return $this->_baseUrl;
		else
			return $this->_baseUrl=Yii::app()->getAssetManager()->publish(YII_PATH.'/web/js/source');
	}

	/**
	 * Sets the base URL of all core javascript files.
	 * This setter is provided in case when core javascript files are manually published
	 * to a pre-specified location. This may save asset publishing time for large-scale applications.
	 * @param string $value the base URL of all core javascript files.
	 */
	public function setCoreScriptUrl($value)
	{
		$this->_baseUrl=$value;
	}

	/**
	 * Registers a script package that is listed in {@link packages}.
	 * This method is the same as {@link registerCoreScript}.
	 * @param string $name the name of the script package.
	 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
	 * @since 1.1.7
	 * @see renderCoreScript
	 */
	public function registerPackage($name)
	{
		return $this->registerCoreScript($name);
	}

	/**
	 * Registers a script package that is listed in {@link packages}.
	 * @param string $name the name of the script package.
	 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
	 * @see renderCoreScript
	 */
	public function registerCoreScript($name)
	{
		if(isset($this->coreScripts[$name]))
			return $this;
		if(isset($this->packages[$name]))
			$package=$this->packages[$name];
		else
		{
			if($this->corePackages===null)
				$this->corePackages=require(YII_PATH.'/web/js/packages.php');
			if(isset($this->corePackages[$name]))
				$package=$this->corePackages[$name];
		}
		if(isset($package))
		{
			if(!empty($package['depends']))
			{
				foreach($package['depends'] as $p)
					$this->registerCoreScript($p);
			}
			$this->coreScripts[$name]=$package;
			$this->hasScripts=true;
			$params=func_get_args();
			$this->recordCachingAction('clientScript','registerCoreScript',$params);
		}
		return $this;
	}

	
	/**
	 * Get the root element of document
	 */
 	protected function getRootElement($context){
 			$matches = array();
			preg_match('/<([a-zA-Z]*:(dialog|window|wizard)) [^>]*>/siu', $context, $matches);
			if(!isset($matches[1]))
				return 'head';
			else return $matches[1];
 			
 	}
}