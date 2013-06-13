<?php
class FrontendController extends YController
{
    public $activeNavKey;
    protected $subTitle;

    private $_navs = array();
    private $_channels = array();
    private $_channelAlias = array();
    private $_channelTags = array();

    public function getPageTitle()
    {
        $navs = $this->getMainNavigations();
        if ($navs && $navs[0]) {
            foreach ($navs[0] as $nav) {
                if ($nav['active']) {
                    $cur = $nav['title'];
                    break;
                }
            }
        }

        return ($this->subTitle ? $this->subTitle . ' - ' : '') . ($cur ? "{$cur} - " : '') . Yii::app(
        )->params['title'];
    }

    protected function beforeRender($view)
    {
        $cs = Yii::app()->getClientScript();

        $cs->registerCssFile($this->asset('css/common.css'))
          ->registerScriptFile($this->asset('js/common.js'));

        return parent::beforeRender($view);
    }

    /**
     * 创建栏目对应的URL；
     * 栏目如果设置了别名，则会生成“channel/别名”这种形式的URL；
     * 否则，会生成“channel/栏目ID”这种形式的URL
     *
     * @param int $cid 栏目ID
     * @param array $params 附加参数数组
     * @return string
     */
    public function createChannelUrl($cid, array $params = array())
    {
        $alias = $this->getChannelAlias($cid);
        if ($alias) {
            $route = "channel/{$alias}";
        } else {
            $route = 'channel/index';
            $params['cid'] = $cid;
        }

        return $this->createUrl($route, $params);
    }

    public function createChannelTagUrl($cid, array $params = array())
    {
        $tagParams = array();
        foreach ($this->getChannelTags($cid) as $name) {
            $tagParams[$name] = isset($_GET[$name]) ? $_GET[$name] : 0;
        }

        return $this->createChannelUrl($cid, array_merge($params, $tagParams));
    }

    /**
     * 根据栏目ID返回栏目别名
     * @param int $cid 栏目ID
     * @return string|null
     */
    public function getChannelAlias($cid)
    {
        if (!isset($this->_channelAlias[$cid])) {
            $alias = ChannelAlias::getChannelAlias($cid);
            $this->_channelAlias[$cid] = $alias ? $alias : false;
        }

        return $this->_channelAlias[$cid];
    }

    public function getChannelTags($cid)
    {
        if (!isset($this->_channelTags[$cid])) {
            $this->_channelTags[$cid] = Channel::getChannelTags($cid);
        }

        return $this->_channelTags[$cid];
    }

    /**
     * 根据文档ID返回对应的文档记录
     *
     * @param int $id 文档ID
     * @return Archive
     */
    public function getArchive($id)
    {
        return Archive::model()->findByPk($id);
    }

    /**
     * 根据栏目ID返回指定条数的文档数组
     *
     * @param int|array $cid 栏目ID
     * @param int $limit 限制条数，-1表示不限制
     * @return array
     */
    public function getArchivesByChannelId($cid, $limit = -1)
    {
        return Archive::model()->inChannels($cid)->published()->recently($limit)->findAll();
    }

    /**
     * 根据栏目ID返回指定条数的置顶文档数组
     *
     * @param int|array $cid 栏目ID
     * @param int $limit 限制条数，-1表示不限制
     * @return array
     */
    public function getTopArchivesByChannelId($cid, $limit = -1)
    {
        return Archive::model()->inChannels($cid)->top()->published()->recently($limit)->findAll();
    }

    /**
     * 根据顶级栏目ID其下所有子栏目的所有文档列表数组
     *
     * @param int $cid 顶级栏目ID
     * @param int $limit 限制条数，0表示不限制
     * @return array
     */
    public function getArchivesByTopChannelId($cid, $limit = 0)
    {
        $subIds = Channel::getSubChannelIds($cid);
        $subIds[] = $cid;

        return Archive::getArchivesByChannelId($subIds, $limit);
    }

    public function getArchivesForPager(array $conditions, $pageSize = 10, $page = null, array $joins = array())
    {
        if (null === $page) {
            $page = intval($_GET['page']);
        }

        $page = $page ? $page : 1;

        Yii::import('apps.ext.young.SelectSQL');

        $sql = new SelectSQL();
        $sql->from(array('{{archive}}', 'archive'), '*');

        foreach ($joins as $table => $info) {
            foreach ($info['on'] as $s => $t) {
                $sql->leftJoin(array("{{{$table}}}", "{$table}"), $info['fields'], "archive.{$s}={$table}.{$t}");
            }
        }

        foreach ($conditions as $condition => $value) {
            if (is_string($condition)) {
                $sql->where($condition, $value);
            } else {
                $sql->where($value);
            }
        }

        $sql->where('archive.status=?', Archive::STATUS_PUBLISHED)
          ->limit($pageSize, ($page - 1) * $pageSize);

        $db = Yii::app()->db;

        return array(
            $db->createCommand($sql->toSQL())->queryAll(),
            $db->createCommand($sql->toTotalCountSQL())->queryScalar()
        );
    }

    /**
     * 根据栏目ID返回文档分页列表数组
     *
     * @param int $cid 栏目ID
     * @param int $pageSize 每页条数
     * @param int $page 当前是第几页
     * @return array($archives, $total)
     */
    public function getArchivesForPagerByChannelId($cid, $pageSize = 10, $page = null)
    {
        return $this->getArchivesForPager(array('archive.cid=?' => $cid), $pageSize, $page);
    }

    public function getArchivesForPagerByChannelWithTags(Channel $channel, $pageSize = 10, $page = null, array $joins = array())
    {
        $conditions = array(
            'archive.cid=?' => $channel->id
        );

        foreach ($channel->tags as $type) {
            if (isset($_GET[$type]) && $_GET[$type]) {
                $tid = intval($_GET[$type]);
                $conditions[] = "archive.id IN (SELECT id FROM {{model_tag}} WHERE model_name='{$channel->model_name}' AND tid='{$tid}')";
            }
        }

        return $this->getArchivesForPager($conditions, $pageSize, $page, $joins);
    }

    /**
     * 根据文档ID返回其所对应的模板名称；
     * 如果该文档没有设置模板，则返回其对应栏目设置的模板；
     * 如果栏目也没有设置，则返回NULL
     *
     * @param string $id
     * @return string|null
     */
    public function getArchiveTemplate($id)
    {
        return Archive::getArchiveTemplate($id);
    }

    /**
     * 根据栏目ID返回栏目信息
     *
     * @param int $cid 栏目ID
     * @return Channel
     */
    public function getChannel($cid)
    {
        return Channel::model()->findByPk($cid);
    }

    /**
     * 根据文档ID返回其对应的栏目ID
     *
     * @param int $id 文档ID
     * @return int 栏目ID
     */
    public function getChannelIdByArchiveId($id)
    {
        return Archive::getChannelId($id);
    }

    /**
     * 根据栏目ID返回栏目模板
     *
     * @param int $cid 栏目ID
     * @return string|null
     */
    public function getChannelTemplate($cid)
    {
        return Channel::getChannelTemplate($cid);
    }

    /**
     * 根据栏目ID返回栏目名称
     *
     * @param int $cid
     * @return string
     */
    public function getChannelTitle($cid)
    {
        return Channel::getChannelTitle($cid);
    }

    /**
     * 根据键名返回配置信息
     *
     * @param string $key
     * @return string
     */
    public function getConfig($key)
    {
        return Yii::app()->params[$key];
    }

    /**
     * 根据栏目ID返回其第一个子栏目ID
     *
     * @param int $cid 栏目ID
     * @return int 子栏目ID；不存在返回0
     */
    public function getFirstSubChannelId($cid)
    {
        return Channel::fetchFirstSubChannelId($cid);
    }

    /**
     * 根据栏目ID返回第一条文档ID
     *
     * @param int $cid 栏目ID
     * @return int 文档ID或0（文档不存在时返回0）
     */
    public function getFirstArchiveIdByChannelId($cid)
    {
        return Archive::getFirstArchiveIdByChannelId($cid);
    }

    /**
     * 返回底部导航数组
     *
     * @return array
     */
    public function getFooterNavigations()
    {
        $navs = array();

        foreach (Nav::getNavigations(Yii::app()->params['theme_id'], 1) as $nav) {
            if (!preg_match('/^http:\/\//', $nav['url'])) {
                list($route, $params) = explode('?', $nav['url']);
                $parameters = array();
                if ($params) {
                    foreach (explode('&', $params) as $param) {
                        list($key, $value) = explode('=', $param, 2);
                        $parameters[$key] = $value;
                    }
                }
                $nav['url'] = $this->createUrl($route, $parameters);
            }
            $navs[intval($nav['parent_id'])][] = $nav;
        }

        return $navs;
    }

    /**
     * 根据栏目ID返回友情链接列表数组
     *
     * @param int $cid 栏目ID
     * @param int $limit 限制条数，0表示不限制
     * @return array
     */
    public function getFriendLinksByChannelId($cid, $limit = 0)
    {
        $cid = intval($cid);
        $sql = "SELECT id, title, url, cid, logo FROM {{link}} WHERE cid='{$cid}' AND visible='1' ORDER BY sort_id DESC, id ASC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 返回主导航数组
     *
     * @return array
     */
    public function getMainNavigations()
    {
        if (!$this->_navs) {
            $navs = array();

            foreach (Nav::getNavigations(Yii::app()->params['theme_id'], 0) as $nav) {
                if ($this->activeNavKey === $nav['identifier']) {
                    $nav['active'] = true;
                } else {
                    $nav['active'] = false;
                }

                if (!preg_match('/^http:\/\//', $nav['url'])) {
                    list($route, $params) = explode('?', $nav['url']);
                    $parameters = array();
                    if ($params) {
                        foreach (explode('&', $params) as $param) {
                            list($key, $value) = explode('=', $param, 2);
                            $parameters[$key] = $value;
                        }
                    }
                    $nav['url'] = $this->createUrl($route, $parameters);
                }
                $navs[intval($nav['parent_id'])][] = $nav;
            }
            $this->_navs = $navs;
        }

        return $this->_navs;
    }

    /**
     * 根据文档ID返回新闻信息数组
     *
     * @param int $id 文档ID
     * @return array
     */
    public function getNews($id)
    {
        return News::model()->findByPk($id);
    }

    /**
     * 根据栏目ID返回其所有子栏目列表数组
     *
     * @param int $cid 栏目ID
     * @return array
     */
    public function getSubChannels($cid)
    {
        return Channel::getSubChannelTitles($cid);
    }

    /**
     * 根据栏目ID返回其顶级栏目ID，
     * 如果该栏目已经是顶级栏目，则返回其自身
     *
     * @param int $cid 栏目ID
     * @return number
     */
    public function getTopChannelId($cid)
    {
        return Channel::getTopChannelId($cid);
    }

    /**
     * 注册主题资源文件，基路径是/themes/主题名/assets/
     * $this->register_asset(
     *    'path/to/asset.css',
     *    'path/to/asset.js'
     * );
     */
    public function register_asset()
    {
        $cs = Yii::app()->getClientScript();
        foreach (func_get_args() as $asset) {
            if (!preg_match('/^http:\/\//', $asset)) {
                $asset = $this->asset($asset);
            }

            if (substr($asset, strrpos($asset, '.') + 1) === 'css') {
                $cs->registerCssFile($asset);
            } else {
                $cs->registerScriptFile($asset);
            }
        }
    }

    /**
     * 注册全局资源文件，基路径是/assets/
     */
    public function register_assets()
    {
        $cs = Yii::app()->getClientScript();
        foreach (func_get_args() as $asset) {
            $asset = $this->assets($asset);
            if (substr($asset, strrpos($asset, '.') + 1, 3) === 'css') {
                $cs->registerCssFile($asset);
            } else {
                $cs->registerScriptFile($asset);
            }
        }
    }

    /**
     * 注册脚本代码
     *
     * @param string $id 区别代码段的唯一标识
     * @param string $script 代码
     * @param int $position CClientScript::POS_HEAD、CClientScript::POS_BEGIN、
     * CClientScript::POS_END、CClientScript::POS_LOAD、CClientScript::POS_READY（default）
     */
    public function register_script($id, $script, $position = null)
    {
        Yii::app()->getClientScript()->registerScript($id, $script, $position);
    }

    /**
     * 输出分页代码
     * 样式： digg, yahoo, meneame, flickr, sabrosus, scott, quotes, black
     * black2, black-red, grayr, yellow, jogger, starcraft2, tres
     * megas512, technorati, youtube, msdn, badoo, manu, green-black
     * viciao, yahoo2
     *
     * @param int $total 总记录数
     * @param string $style 样式名
     * @param int $page 当前页码，默认从$_GET['page']中获取
     */
    public function renderPager($total, $style = null, $page = null)
    {
        $this->widget(
            'apps.ext.young.Pager',
            array(
                'style' => $style,
                'total' => $total
            )
        );
    }

    /**
     * 访问文档并为其计数
     *
     * @param int $id 文档ID
     */
    public function visitArchive($id)
    {
        Archive::visit($id);
    }
}

?>