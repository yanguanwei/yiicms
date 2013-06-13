<?php
/**
 * 栏目表
 *
 * @author yanguanwei@qq.com
 *
 */
class Channel extends CActiveRecord
{
    /**
     * ID
     * @var int
     */
    public $id;
    public $title;
    public $parent_id = 0;

    public $theme_id;

    /**
     * 文档模型名
     *
     * @var string
     */
    public $model_name;

    /**
     * 排序，越大越靠前
     *
     * @var int
     */
    public $sort_id = 0;

    public $channel_template;

    public $archive_template;

    public $keywords;

    public $description;

    public $visible = 1;

    public $channel_attach = 0;

    public $tags;

    public $post_time;

    private $channelAttachModel = false;
    private $topChannel = false;
    private $parentChannel = false;
    private $channelModel = false;
    private $subChannels = false;
    private $channelAlias = false;

    /**
     * @return ChannelModel
     */
    public function getChannelAttach()
    {
        if ($this->channel_attach && false === $this->channelAttachModel) {
            $this->channelAttachModel = ChannelModel::findModel($this->channel_attach);
        }
        return $this->channelAttachModel;
    }

    /**
     * @return Channel[]
     */
    public function getSubChannels()
    {
        if ($this->id && false === $this->subChannels) {
            $this->subChannels = array();
            $channels = self::model()->findAll(array(
                    'condition' => 'parent_id=:parent_id',
                    'params' => array(':parent_id' => $this->id)
                ));
            foreach ($channels as $channel) {
                $this->subChannels[$channel->id] = $channel->setParentChannel($this);
            }
        }
        return $this->subChannels;
    }

    public function setParentChannel(Channel $channel)
    {
        $this->parentChannel = $channel;

        return $this;
    }

    public function setTopChannel(Channel $topChannel)
    {
        $this->topChannel = $topChannel;

        return $this;
    }

    /**
     * @return Channel
     */
    public function getParentChannel()
    {
        if ($this->parent_id && false === $this->parentChannel) {
            $this->parentChannel = Channel::model()->findByPk($this->parent_id);
        }
        return $this->parentChannel;
    }

    /**
     * @return Channel
     */
    public function getTopChannel()
    {
        if ($this->id && false === $this->topChannel) {
            $topid = self::getTopChannelId($this->id);
            if ($topid !== $this->id) {
                $this->topChannel = Channel::model()->findByPk($topid);
            } else {
                $this->topChannel = $this;
            }
        }
        return $this->topChannel;
    }

    /**
     * @return ChannelModel
     */
    public function getChannelModel()
    {
        if ($this->model_name && false === $this->channelModel) {
            $this->channelModel = ChannelModel::findModel($this->model_name);
        }
        return $this->channelModel;
    }

    /**
     * @return ChannelAlias
     */
    public function getChannelAlias()
    {
        if ($this->id && false === $this->channelAlias) {
            $this->channelAlias = ChannelAlias::model()->findByPk($this->id);
        }
        return $this->channelAlias;
    }

    public function getViewUrl(array $params = array())
    {
        $alias = $this->getChannelAlias();
        if ($alias) {
            $route = "channel/{$alias->alias}";
        } else {
            $route = 'channel/index';
            $params['cid'] = $this->id;
        }
        return Yii::app()->controller->createUrl($route, $params);
    }

    public function getTagTypeViewUrl($type)
    {
        return $this->getViewUrl(array(
                'type' => $type
            ));
    }

    public function getTagViewUrl($type, $tid)
    {
        $params = array();
        foreach ($this->tags as $t) {
            if (isset($_GET[$t])) {
                $params[$t] = $_GET[$t];
            }
        }
        $params[$type] = $tid;
        return $this->getViewUrl($params);
    }

    public function getTagTypes()
    {
        if ($this->tags) {
            return TagType::fetchTagTypeTitles($this->tags);
        }
        return array();
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Channel
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
        return '{{channel}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function rules()
    {
        return array(
            array('id', 'required', 'on' => 'update'),
            array('parent_id', 'required', 'on' => 'insert'),
            array('title, model_name, theme_id', 'required')
        );
    }

    protected function beforeSave()
    {
        if (in_array($this->getScenario(), array('insert', 'update'))) {
            $this->post_time = time();
        }

        if ($this->tags && is_array($this->tags)) {
            $this->tags = implode('|', $this->tags);
        } else {
            $this->tags = '';
        }

        return parent::beforeSave();
    }

    protected function afterFind()
    {
        $this->tags = self::formatChannelTags($this->tags);

        return parent::afterFind();
    }

    public function relations()
    {
        return array(
            'archive' => array(self::HAS_MANY, 'Archive', 'cid'),
            'counts' => array(self::STAT, 'Archive', 'cid'),
            'model' => array(self::BELONGS_TO, 'ChannelModel', 'model_name')
        );
    }

    /**
     * 返回所有子类的ID数组
     *
     * @param int $parent_id
     * @return array
     */
    public static function getChildrenIds($parent_id)
    {
        $children = array();

        $conn = Yii::app()->db;
        $command = $conn->createCommand();

        while (null != $data = $command->select('id')->from('{{channel}}')->where(
              array('and', array('in', 'parent_id', $parent_id), "visible='1'")
          )->queryAll()) {
            $parent_id = array();
            foreach ($data as $row) {
                $children[] = $parent_id[] = intval($row['id']);
            }
            $command->reset();
        }

        return $children;
    }

    /**
     * 返回指定ID栏目的第一个子栏目ID
     *
     * @param int $id
     * @return int
     */
    public static function fetchFirstSubChannelId($id)
    {
        $id = intval($id);
        $sql = "SELECT id FROM {{channel}} WHERE parent_id='{$id}' ORDER BY sort_id DESC, id ASC LIMIT 1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return intval($row['id']);
        }

        return 0;
    }

    /**
     * 返回指定栏目下所有子栏目ID
     *
     * @param int $parent_id
     * @internal param bool $hasModel 是否是指定了文档模型的栏目
     * @return multitype:number
     */
    public static function getSubChannelIds($parent_id)
    {
        $subs = array();
        $sql = "SELECT id From {{channel}} WHERE parent_id='{$parent_id}' ORDER BY id ASC";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $subs[] = intval($row['id']);
        }

        return $subs;
    }

    /**
     * 返回指定栏目下所有子栏目的栏目名
     *
     * @param int $parent_id
     * @return array($cid => $title)
     */
    public static function getSubChannelTitles($parent_id)
    {
        $subs = array();
        $sql = "SELECT id, title From {{channel}} WHERE parent_id='{$parent_id}' ORDER BY sort_id DESC, id ASC";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $subs[intval($row['id'])] = $row['title'];
        }

        return $subs;
    }

    public static function getEnabledSubChannelTitles($parent_id)
    {
        $subs = array();
        $sql = "SELECT id, title From {{channel}} WHERE parent_id='{$parent_id}' AND visible='1' ORDER BY sort_id DESC, id ASC";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $subs[intval($row['id'])] = $row['title'];
        }

        return $subs;
    }

    /**
     * 返回可见的一级栏目数组，数组格式：
     * array(
     *  array(
     *    'id' => 栏目ID,
     *    'title' => 栏目名称,
     *    'table_name' => 内容模型名,
     *    'theme_id' => 主题ID
     *  )
     * )
     *
     * @return array
     */
    public static function fetchTopChannelsForNavigation()
    {
        $sql = "SELECT c.id, c.title, c.theme_id, am.table_name, am.controller From {{channel}} c LEFT JOIN {{channel_model}} am ON c.model_name=am.name WHERE c.parent_id='0' AND visible='1' ORDER BY c.sort_id DESC, c.id ASC";

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public static function fetchChannelsForList($theme_id)
    {
        return Yii::app()->db->createCommand(
            "SELECT c.id, c.sort_id, c.title, c.parent_id, c.visible, c.theme_id, am.title as archive_model FROM {{channel}} c LEFT JOIN {{channel_model}} am ON am.name=c.model_name WHERE theme_id='{$theme_id}' ORD" . "ER BY c.sort_id DESC, c.id ASC"
        )->queryAll();
    }

    /**
     * 根据栏目ID返回栏目名称
     *
     * @param int $id
     * @return string|null
     */
    public static function findChannelTitle($id)
    {
        return self::findAttribute($id, 'title');
    }

    public static function findChannelModelName($id)
    {
        return self::findAttribute($id, 'model_name');
    }

    public static function findAttribute($id, $attribute)
    {
        $id = intval($id);
        $sql = "SELECT {$attribute} FROM {{channel}} WHERE id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow(false);
        if ($row) {
            return $row[0];
        }
    }

    /**
     *
     * @param int $id
     * @param string $attribute
     * @return Ambigous <multitype:, number>
     */
    public static function getTreeTrace($id, $attribute)
    {
        $conn = Yii::app()->db;
        $command = $conn->createCommand();
        $tree = array();

        while (null != $row = $command->select("id, parent_id, {$attribute}")->from('{{channel}}')->where(
              'id=:id',
              array(':id' => $id)
          )->queryRow()) {
            $id = $row['parent_id'];
            $tree = array($row['id'] => $row[$attribute]) + $tree;
            $command->reset();
        }

        return $tree;
    }


    /**
     * 根据$id找出其祖父id，如果本身就为祖父id，则返回其本身；
     * 如果不存在$id，则返回0
     *
     * @param int $id
     * @return int
     */
    public static function getTopChannelId($id)
    {
        $rootId = 0;
        while (false !== $row = Yii::app()->db->createCommand(
              "SELECT parent_id FROM {{channel}} WHERE id='{$id}'"
          )->queryRow()) {
            if ($row['parent_id']) {
                $id = $row['parent_id'];
            } else {
                $rootId = intval($id);
                break;
            }
        }

        return $rootId;
    }

    /**
     * 根据栏目ID返回其绑定的模型名
     *
     * @param int $id
     * @return string|null
     */
    public static function findChannelModel($id)
    {
        $sql = "SELECT cm.table_name From {{channel}} c LEFT JOIN {{channel_model}} cm ON cm.name=c.model_name WHERE c.id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return $row['table_name'];
        }
    }

    /**
     * 根据栏目ID返回该栏目所属的主题ID
     *
     * @param int $id
     * @return number
     */
    public static function findChannelThemeId($id)
    {
        return intval(self::findAttribute($id, 'theme_id'));
    }

    /**
     * 返回顶级栏目下的可见子栏目。
     * 如果给定的是子栏目，则获取其父栏目下所有的子栏目；
     * 如果给定的是顶级栏目ID，则获取其所有的子栏目
     *
     * @param int $id
     * @return array
     */
    public static function getChannelTreeSelectOptionsForModel($id)
    {
        $conn = Yii::app()->db;

        $parent_id = $id = intval($id);

        $sql = "SELECT parent_id FROM {{channel}} WHERE id='{$id}'";
        $row = $conn->createCommand($sql)->queryRow();
        if (!$row) {
            return array();
        }

        if ($row['parent_id']) {
            $parent_id = $row['parent_id'];
        }

        $sql = "SELECT id, title, parent_id FROM {{channel}} WHERE (id='{$parent_id}' OR parent_id='{$parent_id}') AND visible='1' ORDER BY sort_id DESC, id ASC";

        return $conn->createCommand($sql)->queryAll();

        $options = array();

        foreach ($conn->createCommand($sql)->queryAll() as $row) {
            $options[$row['id']] = $row['title'];
        }

        return $options;
    }

    public static function fetchChannelTemplate($id)
    {
        $id = intval($id);
        $sql = "SELECT channel_template FROM {{channel}} WHERE id='{$id}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            return $row['channel_template'];
        }
    }

    public static function deleteChannel(Channel $channel)
    {
        ChannelAlias::deleteByChannelId($channel->id);
        return $channel->delete();
    }

    /**
     * 根据主题名称删除栏目，栏目下的所有模型信息，栏目别名
     * @param int $theme_id
     * @return array($channelCount, $channelAliasCount, array( $model=>$count ))
     */
    public static function deleteChannelByThemeId($theme_id)
    {
        $sql = "SELECT c.id, cm.table_name, cm.title FROM {{channel}} c LEFT JOIN {{channel_model}} cm ON cm.id=c.model_id WHERE c.theme_id='{$theme_id}'";
        $channels = Yii::app()->db->createCommand($sql)->queryAll();

        $count = $models = $cids = $sqls = array();

        foreach ($channels as $row) {
            $models[$row['table_name']][$row['title']][] = $row['id'];
            $cids[] = $row['id'];
        }
        $cids = "'" . implode("', '", $cids) . "'";

        //栏目数
        $sql = "DELETE FROM  {{channel}} WHERE id IN ({$cids}) ";
        $count[0] = Yii::app()->db->createCommand($sql)->execute();

        //删除栏目别名
        $sql = "DELETE FROM {{channel_alias}} WHERE id IN ({$cids})";
        $count[1] = Yii::app()->db->createCommand($sql)->execute();

        $count[2] = array();

        //删除栏目下所有模型中的信息
        foreach ($models as $tb => $row) {
            foreach ($row as $title => $ids) {
                $ids = implode("', '", $ids);
                $sql = "DELETE FROM {{{$tb}}} WHERE cid IN('{$ids}')";
                $count[2][$title] = Yii::app()->db->createCommand($sql)->execute();
            }
        }

        return $count;
    }

    public static function findChannel($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM {{channel}} WHERE id='{$id}'";

        return Yii::app()->db->createCommand($sql)->queryRow();
    }

    public static function getChannelTags($cid)
    {
        $cid = intval($cid);
        $sql = "SELECT tags FROM {{channel}} WHERE id='{$cid}'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return self::formatChannelTags($row['tags']);
    }

    public static function formatChannelTags($tags)
    {
        return $tags ? explode('|', $tags) : array();
    }
}