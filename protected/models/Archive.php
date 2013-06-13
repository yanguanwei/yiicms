<?php
/**
 * 信息表
 *
 * @author yanguanwei@qq.com
 */
class Archive extends CActiveRecord
{

    /**
     * 状态：草稿
     *
     * @var int
     */
    const STATUS_DRAFT = 0;

    /**
     * 状态：已发布
     * @var int
     */
    const STATUS_PUBLISHED = 1;

    public $id;
    public $title;
    public $cover;
    public $cid;
    public $uid;
    public $model_name;
    public $template;
    public $status;
    public $is_highlight;
    public $is_top;
    public $post_time;
    public $keywords;
    public $description;
    public $update_time;
    public $visits = 0;

    private $channel;

    public static function fetchArchiveStatusOptions($status = null)
    {
        $array = array(
            self::STATUS_DRAFT => '未发布',
            self::STATUS_PUBLISHED => '已发布'
        );

        if ($status === null) {
            return $array;
        }

        return $array[$status];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Archive the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{archive}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('cid, title, model_name', 'required', 'on' => 'insert'),
            array('id', 'required', 'on' => 'update')
        );
    }

    protected function beforeSave()
    {
        switch ($this->getScenario()) {
            case 'insert':
                //if ( $this->checkTitleExists() ) {
                //	$this->addError('title', '已经存在同样标题的信息！');
                //	return false;
                //}
                $this->post_time = time();
                $this->uid = Yii::app()->user->id;
                break;
            case 'update':

                break;
        }

        if ($this->update_time && (
            strpos($this->update_time, ':') > 0 ||
            strpos($this->update_time, '-') > 0 ||
            strpos($this->update_time, '/') > 0 ||
            strpos($this->update_time, ' ') > 0
          )
        ) {
            $this->update_time = strtotime($this->update_time);
        }

        if (!$this->update_time) {
            $this->update_time = time();
        }

        return parent::beforeSave();
    }

    protected function afterFind()
    {
        $this->update_time = date('Y-m-d H:i', $this->update_time);
    }

    protected function checkTitleExists()
    {
        return $this->exists('title=:title', array(':title' => $this->title));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'cid' => '所属栏目',
            'uid' => '发布者',
            'post_time' => '创建时间',
            'update_time' => '更新时间',
            'template' => '模板',
            'keywords' => '关键字',
            'visits' => '点击数',
            'description' => '描述'
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'uid'),
            'category' => array(self::BELONGS_TO, 'Category', 'cid')
        );
    }

    public function published()
    {
        $this->getDbCriteria()->addCondition("status='" . self::STATUS_PUBLISHED . "'");

        return $this;
    }

    public function top()
    {
        $this->getDbCriteria()->addCondition("is_top='1'");

        return $this;
    }

    public function recently($limit = 5)
    {
        $this->getDbCriteria()->mergeWith(
            array(
                'order' => 'is_top DESC, update_time DESC',
                'limit' => $limit,
            )
        );

        return $this;
    }

    public function inChannels($cid)
    {
        $this->getDbCriteria()->compare('cid', $cid);

        return $this;
    }

    public function getViewUrl()
    {
        return Yii::app()->controller->createUrl('archive/detail', array('id' => $this->id));
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel === null ? ($this->channel = Channel::model()->findByPk($this->cid)) : $this->channel;
    }

    /**
     * 根据栏目ID返回文档数组
     *
     * @param int|array $cid
     * @param $count
     * @param int $offset
     * @internal param int $limit
     * @return array
     */
    public static function getArchivesByChannelId($cid, $count, $offset = 0)
    {
        $sql = "SELECT * FROM {{archive}} WHERE cid";
        if (is_array($cid)) {
            $sql .= " IN ('" . implode("', '", $cid) . "')";
        } else {
            $sql .= "='{$cid}'";
        }

        $sql .= " AND status='" . self::STATUS_PUBLISHED . "' ORDER BY `is_top` DESC, update_time DESC, id DESC";

        if ($count) {
            $sql .= " LIMIT {$offset}, {$count}";
        }

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public static function getFirstArchiveIdByChannelId($cid)
    {
        $cid = intval($cid);
        $sql = "SELECT id FROM {{archive}} WHERE cid='{$cid}' AND status='" . self::STATUS_PUBLISHED . "' ORDER BY `is_top` DESC, update_time DESC, id DESC LIMIT 1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return intval($row['id']);
        }

        return 0;
    }

    /**
     * 根据栏目ID返回其文档数
     *
     * @param int|array $cid
     * @param int $status
     * @return number
     */
    public static function countByChannelId($cid, $status = null)
    {
        $sql = "SELECT COUNT(id) FROM {{archive}} WHERE cid";
        if (is_array($cid)) {
            $sql .= " IN ('" . implode("', '", $cid) . "')";
        } else {
            $sql .= "='{$cid}'";
        }

        if (null !== $status) {
            $sql .= " AND status='{$status}'";
        }

        $row = Yii::app()->db->createCommand($sql)->queryRow(false);

        return intval($row[0]);
    }

    public static function findArchiveTemplate($id)
    {
        $id = intval($id);
        $sql = "SELECT a.template, c.archive_template FROM {{archive}} a LEFT JOIN {{channel}} c ON c.id=a.cid WHERE a.id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return $row['template'] ? $row['template'] : $row['archive_template'];
        }
    }

    public static function findArchiveTitle($id)
    {
        $id = intval($id);
        $sql = "SELECT title FROM {{archive}} WHERE id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return $row['title'];
        }
    }

    public static function findChannelId($id)
    {
        $id = intval($id);
        $sql = "SELECT cid FROM {{archive}} WHERE id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return intval($row['cid']);
        }

        return 0;
    }

    public static function visit($id)
    {
        $ip = Yii::app()->request->userHostAddress;
        $id = intval($id);

        $name = md5('archive' . $ip . $id);

        $cookie = Yii::app()->request->cookies[$name];

        if (!$cookie) {
            Yii::app()->db->createCommand("UPDATE {{archive}} SET visits=visits+1 WHERE id='{$id}'")->execute();

            $cookie = new CHttpCookie($name, 'true');
            $cookie->expire = time() + 60 * 60 * 24 * 30;
            Yii::app()->request->cookies[$name] = $cookie;
        }
    }

    public static function findArchive($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM `{{archive}}` WHERE `id`='{$id}'";

        return Yii::app()->db->createCommand($sql)->queryRow();
    }
}
