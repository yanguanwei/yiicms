<?php

/**
 * JTreeTable class file.
 *
 * @author jerry2801 <jerry2801@gmail.com>
 *
 * @version alpha 4 (2010-10-20 18:48)
 * @version alpha 3 (2010-8-12 14:05)
 * @version alpha 2 (2010-6-4 13:28)
 * @version alpha 1 (2010-4-30 15:57)
 * @requires treeTable v2.3.0 (http://ludo.cubicphuse.nl/jquery-plugins/treeTable/doc/index.html, http://plugins.jquery.com/project/treeTable)
 *
 * A typical usage of JTreeTable is as follows:
 * <pre>
 * $this->widget('ext.treetable.JTreeTable',array(
 *     'id'=>'treeTable',
 *     'primaryColumn'=>'id',
 *     'parentColumn'=>'parent_id',
 *     'columns'=>array(
 *         'id'=>array(
 *             'label'=>'Id',
 *             'headerHtmlOptions'=>array('width'=>80),
 *             'htmlOptions'=>array('align'=>'center'),
 *         ),
 *         'name'=>'Name',
 *     ),
 *     'items'=>array(
 *         array('id'=>1,'parent_id'=>0,'name'=>'test 1'),
 *         array('id'=>2,'parent_id'=>1,'name'=>'test 1\'s children 1'),
 *     ),
 *     'options'=>array(
 *         'initialState'=>'expanded',
 *     ),
 * ));
 * </pre>
 */

class JTreeTable extends CWidget
{
    public $id;
    public $columns;
    public $items;
    public $primaryColumn = 'id';
    public $parentColumn = 'parent_id';
    public $rootId = 0;
    public $options = array();

    public function init()
    {
        $this->generateTable();
    }

    public function run()
    {
		$options=CJavaScript::encode($this->options);

        $path=dirname(__FILE__).DIRECTORY_SEPARATOR.'source';
        $baseUrl=Yii::app()->getAssetManager()->publish($path);

        $id=$this->id;

        $js='$("#'.$id.'").treeTable('.$options.');';

		$cs = Yii::app()->getClientScript();

        /*$juiBasePath=Yii::getPathOfAlias('zii.vendors.jui');
        $juiBaseUrl=Yii::app()->getAssetManager()->publish($juiBasePath);
        $cs->registerCssFile($juiBaseUrl.'/css/base/jquery-ui.css');
        $cs->registerScriptFile($juiBaseUrl.'/js/jquery-ui.min.js');*/

        $cs->registerCssFile($baseUrl.'/stylesheets/jquery.treeTable.css');
        $cs->registerScriptFile($baseUrl.'/javascripts/jquery.treeTable.js');

		$cs->registerScript(__CLASS__.'#'.$id,$js);
    }

    protected function generateTable()
    {
        $keys=array();
        echo '<table id="'.$this->id.'" width="100%">';
        echo '<thead>';
        echo '<tr>';
        foreach($this->columns as $key => $props)
        {
            if(is_array($props))
                $label=$props['label'];
            $keys[]=$key;

            if(is_array($props))
            {
                $htmlOptions=isset($props['headerHtmlOptions'])?$props['headerHtmlOptions']:array();
                echo CHtml::openTag('th',$htmlOptions).$props['label'].CHtml::closeTag('th');
            }
            else
            {
                echo '<th>'.$props.'</th>';
            }
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $data = $this->parseData($this->items);
        foreach($data as $id=>$item)
        {
            if($item[$this->parentColumn] && $item[$this->parentColumn] != $this->rootId)
                echo '<tr id="node-'.$item[$this->primaryColumn].'" class="child-of-node-'.$item[$this->parentColumn].'">';
            else
                echo '<tr id="node-'.$item[$this->primaryColumn].'">';
            foreach($keys as $key)
            {
                if(is_array($this->columns[$key]) && isset($this->columns[$key]['htmlOptions']))
                {
                    $htmlOptions=$this->columns[$key]['htmlOptions'];
                }
                else
                {
                    $htmlOptions=array();
                }
                
                if ($key == $this->primaryColumn) $htmlOptions['class'] = (isset($htmlOptions['class']) ? ' ' : ''). 'primaryColumn';
                
                if (is_array($this->columns[$key]) && isset($this->columns[$key]['value'])) {
                	$value = $this->matchValue($this->columns[$key]['value'], $item);
                } else {
                	$value = $item[$key];
                }
               
                echo CHtml::openTag('td',$htmlOptions). $value . CHtml::closeTag('td');
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    
    protected function matchValue($value, $row)
	{
		if (is_callable($value)) {
			$value = call_user_func($value, $row);
		} else {
			if (preg_match_all('/__(.*?)__/', $value, $matches)) {
				if ($matches[0])
					foreach ($matches[0] as $i=>$search) {
						$value = str_replace($search, $row[$matches[1][$i]], $value);
					}
			}
		}
		
		return $value;
	}
	
    protected function parseData($data)
    {
    	$parent = $list = $array = array();
    	foreach ($data as $row) {
    		$parent[$row[$this->parentColumn]][] = $row[$this->primaryColumn];
    		$list[$row[$this->primaryColumn]] = $row;
    	}
    	
    	if (isset($parent[$this->rootId])) {
	    	foreach ($parent[$this->rootId] as $cid) {
	    		$this->parseDataLoop($cid, $parent, $list, $array);
	    	}
    	}
    	
    	return $array;
    }
    
    protected function parseDataLoop($pid, $parent, $list, &$array)
    {
    	$array[] = $list[$pid];
    	if (isset($parent[$pid])) {
    		foreach ($parent[$pid] as $cpid) {
    			$this->parseDataLoop($cpid, $parent, $list, $array);
    		}
    	}
    }
}