<?php
class AdminController extends YController
{
    public $layout = '//layouts/frame';
    public $navigationCurrentItemKey;

    protected $prompts = array();

    private $_navigations;
    private $_contentTitle;

    public function setContentTitle($title)
    {
        $this->_contentTitle = $title;
    }

    public function getContentTitle()
    {
        return $this->_contentTitle;
    }

    public function getPageTitle()
    {
        $navs = $this->getNavigations();
        $title = '';

        if ($this->navigationCurrentItemKey) {
            $keys = explode('/', $this->navigationCurrentItemKey);
            foreach ($keys as $key) {
                if (isset($navs [$key])) {
                    $title = $navs [$key] ['label'] . ' - ' . $title;
                    $navs = $navs [$key] ['items'];
                } else {
                    break;
                }
            }
        } else {
            $title = $this->getNavigationTitle($navs);
        }

        return $title . Yii::app()->name;
    }

    protected function getNavigationTitle($navs)
    {
        $route = $this->getRoute();
        $title = '';
        foreach ($navs as $item) {
            if ($this->isNavigationActived($item, $route, $title)) {
                break;
            }
        }

        return $title;
    }

    protected function isNavigationActived($item, $route, &$title)
    {
        if ($item ['url'] && is_array($item ['url']) && $item ['url'] [0] === $route) {
            $title = $item ['label'] . ' - ' . $title;

            return true;
        } else {
            if ($item ['items']) {
                foreach ($item ['items'] as $i) {
                    if ($this->isNavigationActived($i, $route, $title)) {
                        $title = $title . $item ['label'] . ' - ';

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function init()
    {
        if ($this->getUser()->isGuest) {
            $this->setFlashMessage('attention', '请先登录');
        } else {
            if (!in_array(
                intval($this->getUser()->getState('role_id')),
                array(
                    User::ROLE_ADMIN,
                    User::ROLE_MANAGER
                )
            )
            ) {
                $this->setFlashMessage('error', "您已登录，但是无权访问");
            } else {
                return parent::init();
            }
        }

        $this->redirect(array('site/login', 'return' => Yii::app()->request->getUrl()));
    }

    public function getNavigations()
    {
        if (!$this->_navigations) {
            $this->_navigations = array(
                'admin' => array(
                    'label' => '管理首页',
                    'items' => array(
                        array(
                            'label' => '管理首页',
                            'url' => array(
                                'default/index'
                            )
                        )
                    )

                )
            );

            $this->_navigations = $this->_navigations + $this->getConfigNavs() + $this->getChannelNavs();

            if (Yii::app()->user->isAdmin()) {
                $this->_navigations['system'] = array(
                    'label' => '系统管理',
                    'items' => array(
                        'config' => array(
                            'label' => '配置管理',
                            'url' => array(
                                'config/index'
                            )
                        ),
                        'tag' => array(
                            'label' => '标签管理',
                            'url' => array(
                                'tag/index'
                            )
                        ),
                        'channel' => array(
                            'label' => '栏目管理',
                            'url' => array(
                                'channel/index'
                            )
                        ),
                        'collect' => array(
                            'label' => '采集管理',
                            'url' => array('collectTaskDb/index')
                        ),
                        'nav' => array(
                            'label' => '导航管理',
                            'url' => array(
                                'nav/index'
                            )
                        ),
                        'user' => array(
                            'label' => '用户管理',
                            'url' => array(
                                'user/index'
                            )
                        ),
                        'theme' => array(
                            'label' => '主题管理',
                            'url' => array(
                                'theme/index'
                            )
                        )
                    )
                );
            }
        }

        return $this->_navigations;
    }

    protected function beforeRender($view)
    {
        Yii::app()->getClientScript()
            ->registerCssFile($this->asset('css/reset.css'))
            ->registerCssFile($this->asset('css/style.css'))
            ->registerCssFile($this->asset('css/invalid.css'))
            ->registerCssFile($this->asset('css/custom.css'))
            ->registerScriptFile($this->asset('scripts/jquery.easing.1.3.js'))
            ->registerScriptFile($this->asset('scripts/popuplayer/jquery.popuplayer-v1.2.js'))
            ->registerCssFile($this->asset('scripts/popuplayer/jquery.popuplayer.css'))
            ->registerCssFile($this->asset('scripts/dateTimer/jquery.dateTimer.css'))
            ->registerScriptFile($this->asset('scripts/dateTimer/jquery.dateTimer-1.0.min.js'))
            ->registerScriptFile($this->asset('scripts/simpla.jquery.configuration.js'));

        return parent::beforeRender($view);
    }

    protected function getConfigNavs()
    {

        $items = array();

        $themes = Theme::getThemeSelectOptions();
        foreach ($themes as $theme_id => $theme_name) {
            $items['theme_' . $theme_id] = array(
                'label' => $theme_name,
                'url' => array('config/theme', 'id' => $theme_id)
            );
        }

        $navs = array(
            'config' => array(
                'label' => '配置管理',
                'items' => $items
            )
        );

        return $navs;
    }

    protected function getChannelNavs()
    {

        $navs = array();
        $themes = Theme::getThemeSelectOptions();
        foreach ($themes as $theme_id => $theme_name) {
            $navs['theme_' . $theme_id] = array(
                'label' => $theme_name
            );
        }

        foreach (Channel::getTopChannels() as $row) {
            $navs['theme_' . $row['theme_id']]['items'][$row ['id']] = array(
                'label' => $row ['title'],
                'url' => array(
                    $row ['model_alias'] . '/index',
                    'cid' => $row ['id']
                )
            );
        }

        return $navs;
    }

    public function getShortcuts()
    {
        return array();
    }

    public function getPrompts()
    {
        return $this->prompts;
    }

    public function doUpdateSort($table)
    {
        if (isset($_POST['sort_id'])) {
            foreach ($_POST['sort_id'] as $id => $sort_id) {
                $id = intval($id);
                $sort_id = intval($sort_id);
                Yii::app()->db->createCommand(
                    "UPDATE {{{$table}}} SET sort_id='{$sort_id}' WHERE id='{$id}'"
                )->execute();
                $this->setFlashMessage('success', "更新排序成功！");
            }
        } else {
            $this->setFlashMessage('attention', "没有要更新的记录！");
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }
}
