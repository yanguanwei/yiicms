<?php
class SelectSQL
{
	private $_sql = array(
		'from' => array(),
		'where' => array(),
		'order' => array(),
		'limit' => array()
	);
	
	public function from($table, $fields)
	{
		$this->addColumns($table, $fields, 'FROM');
		
		return $this;
	}
	
	public function leftJoin($table, $fields, $on)
	{
		$this->addColumns($table, $fields, 'LEFTJOIN', $on);
		
		return $this;
	}
	
	public function where($cond, $value = null)
	{
		$this->addWhere('AND', $cond, $value);
		
		return $this;
	}
	
	public function orWhere($cond, $value = null)
	{
		$this->addWhere('OR', $cond, $value);
		
		return $this;
	}
	
	public function in($column, $value)
	{
		$this->addIn('AND', $column, $value);
		
		return $this;
	}
	
	public function orIn($column, $value)
	{
		$this->addIn('OR', $column, $value);
		
		return $this;
	}
	
	public function order($column, $order = null)
	{
		if ($order === null) {
			$this->_sql['order'][] = $column;
		} else {
			$this->order("{$this->quoteColumnName($column)} {$order}");
		}
		
		return $this;
	}
	
	public function limit($count, $offset = 0)
	{
		$this->_sql['limit'] = array($offset, $count);
		
		return $this;
	}
	
	public function paging($page, $pagesize)
	{
		$this->limit($pagesize, ($page-1) * $pagesize);
		
		return $this;
	}
	
	public function quote($value, $type = null)
	{
		if ( is_array($value) ) {
			$v = array();
			foreach ($value as $val)
				$v[] = $this->quote($val, $type);
			return implode(',', $v);
		} else {
			return "'".mysql_real_escape_string($value)."'";
		}
	}
	
	/**
	 * 对$value两边加引号并转义，然后在$text中的“?”占位符进行替换
	 *
	 * @param string $text
	 * @param string|array $value
	 * @param mixed $type
	 * @param null|int $count
	 */
	public function quoteInto($text, $value, $type = null, $count = null) {
		if ($count === null) {
			return str_replace('?', $this->quote($value, $type), $text);
		} else {
			while ($count > 0) {
				if (strpos($text, '?') !== false) {
					$text = substr_replace($text, $this->quote($value, $type), strpos($text, '?'), 1);
				}
				--$count;
			}
			return $text;
		}
	}
	
	public function quoteLike($value)
	{
		return strtr($value, array("\\" => "\\\\", '_' => '\_', '%' => '\%', "'" => "\\'"));
	}
	
	public function toTotalCountSQL()
	{
		$s = array();
		foreach ($this->_sql as $method => $meta) {
			if ( $method === 'from' ) {
				$s[] = $this->parseFrom($meta, true);
			} else if ($method === 'limit') {
				continue;
			} else {
				$method = 'parse'.ucfirst($method);
				$r = $this->$method($meta);
				if ( $r )
					$s[] = $r;
			}
		}
		return implode(' ', $s);
	}
	
	public function toSQL()
	{
		$s = array();
		foreach ($this->_sql as $method => $meta) {
			if ( $method === 'from' ) {
				$s[] = $this->parseFrom($meta, false);
			} else {
				$method = 'parse'.ucfirst($method);
				$r = $this->$method($meta);
				if ( $r )
					$s[] = $r;
			}
		}
		return implode(' ', $s);
	}
	
	protected function parseFrom($from, $isCountField = false)
	{
		$a = array(); $f = array();
		foreach ($from as $table => $meta) {
			$s = "{$meta['type']} {$this->quoteTableName($table)} {$meta['alias']}";
			if ( $meta['on'] !== null )
				$s .= " ON {$meta['on']}";
			$a[] = $s;
			
			if ( !$isCountField ) {
				if ( $meta['columns'] ) {
					foreach ($meta['columns'] as $column) {
						if ( $column !== '*' )
							$column = $this->quoteColumnName($column);
						$f[] = "{$meta['alias']}.{$column}";
					}
				}
			}
		}
		
		$a = implode(' ', $a);
		
		if ( $isCountField ) {
			$f = 'COUNT(*)';
		} else {
			$f = implode(',', $f);
		}
		
		return "SELECT {$f} {$a}";
	}
	
	protected function parseWhere($where)
	{
		if ( $where ) {
			$s = array('1=1');
			foreach ($where as $meta) {
				$s[] = "{$meta['op']} ({$meta['cond']})";
			}
			return 'WHERE ' . implode(' ', $s);
		}
	}
	
	protected function parseOrder($order)
	{
		if ( $order )
			return 'ORDER BY '.implode(',', $order);
	}
	
	protected function parseLimit($limit)
	{
		if ( $limit )
			return "LIMIT {$limit[0]}, $limit[1]";
	}
	
	protected function addColumns($table, $fields, $type, $on = null)
	{
		if ( is_string($fields) ) {
			$fields = explode(',', $fields);
		}
		
		if ( is_array($table) ) {
			list($table, $alias) = $table;
		} else {
			$alias = $table;
		}
		
		if ( !isset($this->_sql['from'][$table]) )
			$this->_sql['from'][$table] = array(
				'columns' => array(),
				'type' => $type,
				'on' => $on,
				'alias' => $alias
			);
		
		if ( is_array($fields) ) {
			foreach ($fields as $field)
				if ( !in_array($field, $this->_sql['from'][$table]['columns']) )
					 $this->_sql['from'][$table]['columns'][] = trim($field);
		}
		
		return $this;
	}
	
	protected function addIn($op, $column, $value)
	{
		if ( strpos($column, '.') > 0) {
			list($table, $column) = explode('.', $column, 2);
			$column = $table.'.'.$this->quoteColumnName($column);
		} else {
			$column = $this->quoteColumnName($column);
		}
			
		$this->addWhere($op, "{$column} IN ({$this->quote($value)})");
		
		return $this;
	}
	
	/**
	 * $this->addWhere('AND', "`title`='some title'");
	 * $this->addWhere('AND', "`title`=?", $title)
	 * $this->addWhere('AND', array(
	 * 		'`id`=?' => $id,
	 * 		'`title`<>:title',
	 * 		"`visible`='1'"
	 * ))
	 * 
	 * @param string $op AND | OR
	 * @param string $column
	 * @param string|array|null $value
	 */
	protected function addWhere($op, $cond, $value = null)
	{
		if ( is_array($cond) ) {
			$c = array();
			foreach ($cond as $key => $value)
				if ( is_int($key) ) {
					$c[] = $value;
				} else {
					$c[] = $this->quoteInto($key, $value);
				}
			$c = implode(' AND ', $c);
		} else if ( $value === null ) {
			$c = $cond;
		} else {
			$c = $this->quoteInto($cond, $value);
		}
		
		$this->_sql['where'][] = array(
			'op' => $op,
			'cond' => $c
		);
	}
	
	protected function quoteTableName($name)
	{
		return '`'.$name.'`';
	}
	
	protected function quoteColumnName($name)
	{
		return '`'.$name.'`';
	}
}
?>