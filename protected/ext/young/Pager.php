<?php
/*
 * Created on 2010-4-5
 *
 * Author	:	ToNorth
 * QQ		:	176013294
 * E-Mail	:	176013294@qq.com
 *
 * $style:
 * digg, yahoo, meneame, flickr, sabrosus, scott, quotes, black
 * black2, black-red, grayr, yellow, jogger, starcraft2, tres
 * megas512, technorati, youtube, msdn, badoo, manu, green-black
 * viciao, yahoo2
 */
class Pager extends CWidget
{
    public $pagesize = 10;
    public $total = 0;
    public $style = 'digg';
    public $urlGenerator;
    public $page;

    protected $lastPage;
    protected $prevPage;
    protected $nextPage;

    protected $html;

    protected $url;

    /**
     * 分页类
     *
     * @param int $total 记录总数
     * @param string $style 样式名
     */
    public function init()
    {
        $this->style = $this->style ? $this->style : 'digg';
        $this->page = $this->page ? $this->page : ($_GET['page'] ? intval($_GET['page']) : 1);

        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
        Yii::app()->getClientScript()->registerCssFile($assetsUrl . "/{$this->style}.css");
    }

    public function run()
    {
        $this->initPage();
        $this->initHtml();
        echo $this->html;
    }

    protected function generateurl($page)
    {
        if ($this->urlGenerator) {
            return call_user_func($this->urlGenerator, $page);
        }

        $params = $_GET;
        $params['page'] = $page;

        return Yii::app()->getUrlManager()->createUrl(Yii::app()->getController()->getRoute(), $params);
    }

    protected function initPage()
    {
        $this->lastPage = ceil($this->total / $this->pagesize);
        $this->page = min($this->lastPage, $this->page);
        $this->prevPage = $this->page - 1;
        $this->nextPage = ($this->page == $this->lastPage ? 0 : $this->page + 1);
    }

    protected function initPageNum()
    {
        $html = '';
        $pageLenth = 8;
        if ($this->lastPage < 1) {
            return false;
        }
        if ($this->prevPage) {
            $html .= '<a href="' . $this->generateUrl($this->prevPage) . '">&lt;</a>';
        } else {
            $html .= '<span class="disabled">&lt; </span>';
        }
        $startPage = (($this->page - 1) > 4) ? $this->page - 4 : 1;
        $endPage = (($startPage + $pageLenth) > $this->lastPage) ? $this->lastPage : ($startPage + $pageLenth);

        if ($this->lastPage == $endPage) {
            $startPage = (($this->lastPage - $pageLenth) > 0) ? $this->lastPage - $pageLenth : 1;
        }
        if ($startPage > 1) {
            $html .= '<a href="' . $this->generateUrl('1') . '">1</a>...';
        }
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $this->page) {
                $html .= '<span class="current">' . $this->page . '</span>';
            } else {
                $html .= '<a href="' . $this->generateUrl($i) . '">' . $i . '</a>';
            }
        }

        if ($endPage < $this->lastPage) {
            $html .= '.......<a href="' . $this->generateUrl($this->lastPage) . '">' . $this->lastPage . '</a>';
        }

        if ($this->nextPage) {
            $html .= '<a href="' . $this->generateUrl($this->nextPage) . '">&gt;</a>';
        } else {
            $html .= '<span class="disabled">&gt; </span>';
        }

        return $html;
    }

    protected function initHtml()
    {
        $this->html = '<div class="pager"><div class="' . $this->style . '">' . $this->initPageNum() . '</div></div>';
    }
}

?>