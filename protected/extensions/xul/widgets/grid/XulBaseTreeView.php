<?php

Yii::import('ext.xul.widgets.grid.XulTreeDataColumn');

class XulBaseTreeView extends CWidget
{
    private $_formatter;
    
    public $id;
    
    public $filter;
    
    public $treeXulOptions = array();
    
    public $dataProvider = null;
    public $columns = array();
    
    public $hideHeader = false;
    
    public $rowCssClassExpression = '';
    public $evaluateExpression = '';
    
    public $template="{items}\n{pager}";
    
    public $treeRowOptions = array();
    public $treeItemOptions= array();
    
    public $nullDisplay = '';
    
    public $enableSorting = true;
    
    public $columnSplitter = false;

    public function init()
    {
        parent::init();
        
        if(is_null($this->id))
           $this->id = 'tree_'.Xul::generateID();
        else
            $this->treeXulOptions['id']=$this->id;

        
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
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        if ($this->columns === array()) {
            if ($this->dataProvider instanceof CActiveDataProvider)
                $this->columns = $this->dataProvider->model->attributeNames();
            else
                if ($this->dataProvider instanceof IDataProvider) {
                    // use the keys of the first row of data as the default columns
                    $data = $this->dataProvider->getData();
                    if (isset($data[0]) && is_array($data[0]))
                        $this->columns = array_keys($data[0]);
                }
        }
        $id = $this->getId();
        foreach ($this->columns as $i => $column) {
            if (is_string($column))
                $column = $this->createDataColumn($column);
            else if(is_array($column)){
                if (!isset($column['class']))
                    $column['class'] = 'XulTreeDataColumn';
                $column = Yii::createComponent($column, $this);
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            if ($column->id === null)
                $column->id = $id . '_c' . $i;
            $this->columns[$i] = $column;
        }

        foreach ($this->columns as $column)
            $column->init();
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
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			echo Xul::xopenTag('tree', $this->treeXulOptions);
			$this->renderTableHeader();
			$this->renderTableBody();
			$this->renderTableFooter();
			echo Xul::xcloseTag('tree');
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
     * <x:treecols>  
        <x:treecol id="sender" label="Sender" flex="1"/>  
        <x:treecol id="subject" label="Subject" flex="2"/>  
      </x:treecols>
	 */
	public function renderTableHeader()
	{
		if(!$this->hideHeader)
		{
			echo Xul::xopenTag('treecols')."\n";
            
            $i = 0;
			foreach($this->columns as $column){
				echo Xul::xtag('treecol', array(
                        'flex'=>1,
                        'sortActive'=>'true',
                        'sortDirection'=>'descending',
                        'id'=>$this->id.'_treecol_'.$column->name,                        
                        'label'=>$column->getHeaderCellContent()))."\n";
            
                if($this->columnSplitter && $i++ < (count($this->columns)-1))
                    echo Xul::xtag('splitter', array('class'=>'tree-splitter'));
            }
   		
			echo Xul::xcloseTag('treecols')."\n";
		}

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

	/**
	 * Renders the table footer.
	 */
	public function renderTableFooter()
	{
		$hasFilter=$this->filter!==null && $this->filterPosition===self::FILTER_POS_FOOTER;
		$hasFooter=$this->getHasFooter();
		if($hasFilter || $hasFooter)
		{
			echo "<tfoot>\n";
			if($hasFooter)
			{
				echo "<tr>\n";
				foreach($this->columns as $column)
					$column->renderFooterCell();
				echo "</tr>\n";
			}
			if($hasFilter)
				$this->renderFilter();
			echo "</tfoot>\n";
		}
	}

	/**
	 * Renders the table body.
     * 
     *   <x:treechildren>  
        <x:treeitem>  
          <x:treerow>  
            <x:treecell label="joe@somewhere.com"/>  
            <x:treecell label="Top secret plans"/>  
          </x:treerow>  
        </x:treeitem>  
        <x:treeitem>  
          <x:treerow>  
            <x:treecell label="mel@whereever.com"/>  
            <x:treecell label="Let's do lunch"/>  
          </x:treerow>  
        </x:treeitem>  
      </x:treechildren>  
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo Xul::xopenTag('treechildren')."\n";

		if($n>0)
		{
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row)."\n";
		}
		else
		{
			$this->renderEmptyText();

		}
		echo Xul::xcloseTag('treechildren')."\n";
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