<?php

Yii::import('ext.xul.widgets.grid.XulBaseTreeView');

class XulJsonTreeView extends XulBaseTreeView
{
    private $_formatter;
    
    public $filter;
    
    public $treeXulOptions = array();
    
    public $jsonProvider = null;
    
    public $pageVar = 'page';
    
    public $jsDataVar;
    
    public $jsBeforeFill = '';
    
    public $jsAfterFill = '';
    
    public $progressmeter;
    
    public $columns = array();
    
    
    public $hideHeader = false;
    
    public $rowCssClassExpression = '';
    public $evaluateExpression = '';
    
    public $template="{items}\n{script}\n{pager}";
    
    public $treeRowOptions = array();
    public $treeItemOptions= array();
    
    public $nullDisplay = '';
    
    public $enableSorting = true;
    
    public $treechildrenId = false;

    public function init()
    {
        parent::init();
        $this->initColumns();
    }
    
    public function run()
	{

		$this->renderContent();
	//	$this->renderKeys();

	}

	/**
	 * Renders the main content of the view.
	 * The content is divided into sections, such as summary, items, pager.
	 * Each section is rendered by a method named as "renderXyz", where "Xyz" is the section name.
	 * The rendering results will replace the corresponding placeholders in {@link template}.
	 */
	public function renderContent()
	{
		ob_start();
		echo preg_replace_callback("/{(\w+)}/",array($this,'renderSection'),$this->template);
		ob_end_flush();
	}

	/**
	 * Renders a section.
	 * This method is invoked by {@link renderContent} for every placeholder found in {@link template}.
	 * It should return the rendering result that would replace the placeholder.
	 * @param array $matches the matches, where $matches[0] represents the whole placeholder,
	 * while $matches[1] contains the name of the matched placeholder.
	 * @return string the rendering result of the section
	 */
	protected function renderSection($matches)
	{
		$method='render'.$matches[1];
		if(method_exists($this,$method))
		{
			$this->$method();
			$html=ob_get_contents();
			ob_clean();
			return $html;
		}
		else
			return $matches[0];
	}



    /**
     * Creates a {@link CDataColumn} based on a shortcut column specification string.
     * @param string $text the column specification string
     * @return CDataColumn the column instance
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
            throw new CException(Yii::t('zii',
                'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
        $column = new XulTreeDataColumn($this);
        $column->name = $matches[1];
        
        if (isset($matches[3]) && $matches[3] !== '')
            $column->type = $matches[3];
        if (isset($matches[5]))
            $column->header = $matches[5];
        return $column;
    }
    
    /**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
			echo Xul::xopenTag('tree', $this->treeXulOptions);
			$this->renderTableHeader();
			$this->renderTableBody();
			$this->renderTableFooter();
			echo Xul::xcloseTag('tree');	
	}



	/**
	 * Renders the filter.
	 * @since 1.1.1
	 */
	public function renderFilter()
	{
		if($this->filter!==null)
		{
			echo "<tr class=\"{$this->filterCssClass}\">\n";
			foreach($this->columns as $column)
				$column->renderFilterCell();
			echo "</tr>\n";
		}
	}

	public function renderTableBody()
	{
	   if(!$this->treechildrenId)
        $this->treechildrenId = 'treechildren_'.Xul::generateID();
		echo Xul::xtag('treechildren', array('id'=>$this->treechildrenId));
	}
    
    public function renderScript(){
       
        $this->jsDataVar = is_null($this->jsDataVar) ? 'data_'.$this->treechildrenId : $this->jsDataVar;
        
        $colScripts = '';
        $i=0;
        foreach($this->columns as $col){
            $property=$col->getPropertyName();
            
            $colScripts .= "
            var cell$i = document.createElement(\"treecell\");
            cell$i.setAttribute(\"label\", model.$property);
            row.appendChild(cell$i);
            ";
            $i++;
        }
        
        $startProgressmeter = '';
        $endProgressmeter = '';
        
        if(!is_null($this->progressmeter)){
            
            $startProgressmeter = "
                document.getElementById(\"{$this->progressmeter}\").mode = \"undetermined\";
                document.getElementById(\"{$this->progressmeter}\").setAttribute(\"hidden\", false);  
            ";
            
            $endProgressmeter = "
                document.getElementById(\"{$this->progressmeter}\").mode = \"determined\";
                document.getElementById(\"{$this->progressmeter}\").value = \"0\";
                document.getElementById(\"{$this->progressmeter}\").setAttribute(\"hidden\", true);
            ";
            
        }
        
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScript('tree-'.$this->treechildrenId, <<<SCRIPT


var {$this->jsDataVar} = [];

/**
  * Fills the '{$this->treechildrenId}' table.
  * @param data data array
  */
function fill_{$this->treechildrenId}(data){
    
    {$this->jsBeforeFill};
        
    var treechildren = document.getElementById("{$this->treechildrenId}");

    while(treechildren.hasChildNodes()){
    treechildren.removeChild(treechildren.firstChild);
    }
    
    for(i in data){
        var model = data[i];
        
        var item = document.createElement("treeitem");
        var row = document.createElement("treerow");
        
        $colScripts;
        
        item.appendChild(row);
        treechildren.appendChild(item);
    }
    
    {$this->jsAfterFill};

}

function init_{$this->treechildrenId}(){
    
    $startProgressmeter
    
    jQuery.getJSON("{$this->jsonProvider}", function(data){
       {$this->jsDataVar} = data;
        fill_{$this->treechildrenId}(data.data);
        
        $endProgressmeter
    });
}
       
SCRIPT
        , XulClientScript::POS_BEGIN );
        
        Yii::app()->clientScript->registerScript('init_'.$this->treechildrenId, "\n init_{$this->treechildrenId}();\n");

    }

	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
     * 
     * <x:treeitem>  
          <x:treerow>  
            <x:treecell label="joe@somewhere.com"/>  
            <x:treecell label="Top secret plans"/>  
          </x:treerow>  
        </x:treeitem>  
	 */
	public function renderTableRow($row)
	{
	   $treeItemOptions = $this->treeItemOptions;
		if($this->rowCssClassExpression!==null)
		{
			$data=$this->dataProvider->data[$row];
            $treeItemOptions['class']=$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data));			
		}
		else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0){
			$treeItemOptions['class']=$this->rowCssClass[$row%$n];
        }
		
        echo Xul::xopenTag('treeitem', $treeItemOptions);
        
        echo Xul::xopenTag('treerow', $this->treeRowOptions);
		foreach($this->columns as $column)       
			$column->renderDataCell($row);
   
        echo Xul::xcloseTag('treerow');
		echo Xul::xcloseTag('treeitem');
	}
    
    	/**
	 * Renders the pager.
	 */
	public function renderPager()
	{

	}

	/**
	 * @return boolean whether the table should render a footer.
	 * This is true if any of the {@link columns} has a true {@link CGridColumn::hasFooter} value.
	 */
	public function getHasFooter()
	{
		foreach($this->columns as $column)
			if($column->getHasFooter())
				return true;
		return false;
	}

	/**
	 * @return CFormatter the formatter instance. Defaults to the 'format' application component.
	 */
	public function getFormatter()
	{
		if($this->_formatter===null)
			$this->_formatter=Yii::app()->format;
		return $this->_formatter;
	}

	/**
	 * @param CFormatter $value the formatter instance
	 */
	public function setFormatter($value)
	{
		$this->_formatter=$value;
	}

}

?>