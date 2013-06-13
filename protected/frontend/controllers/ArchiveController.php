<?php
class ArchiveController extends FrontendController
{
    public function actionDetail($id)
    {
        $id = intval($id);
        $archive = $this->getArchive($id);

        if (!$archive) {
            throw new CHttpException(404);
        }

        $channel = $archive->getChannel();
        $template = $archive->template ? $archive->template : $channel->archive_template;
        if (!$template) {
            throw new CHttpException(404);
        }

        $topChannel = $channel->getTopChannel();
        $this->subTitle = $archive->title;

        $channelAlias = $topChannel->getChannelAlias();
        if ($channelAlias) {
            $this->activeNavKey = $channelAlias->alias;
        }

        return $this->render(
            $template,
            array(
                'archive' => $archive,
                'channel' => $channel,
                'topChannel' => $topChannel
            )
        );
    }

    public function actionSearch($key)
    {
        $keystr = strtr($key, array("\\" => "\\\\", '_' => '\_', '%' => '\%', "'" => "\\'"));

        $sql = "SELECT count(id) FROM {{archive}} WHERE model_name='news' AND status='" . Archive::STATUS_PUBLISHED . "' AND `title` LIKE '%{$keystr}%' ORD" . "ER BY update_time DESC, id DESC";

        $total = Yii::app()->db->createCommand($sql)->queryScalar();

        $page = intval($_GET['page']);
        $page = $page ? $page : 1;
        $pageSize = 20;
        $offset = ($page - 1) * $pageSize;
        $sql .= " LIMIT {$offset}, {$pageSize}";

        $archives = Yii::app()->db->createCommand($sql)->queryAll();

        return $this->render(
            '/news/search_list',
            array(
                'archives' => $archives,
                'total' => $total,
                'key' => $key,
                'pageSize' => $pageSize
            )
        );
    }
}

?>