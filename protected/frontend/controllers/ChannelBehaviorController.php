<?php
class ChannelBehaviorController extends FrontendController
{
    public function actionIndex($cid)
    {
        $template = (string) Channel::fetchChannelTemplate($cid);

        return $this->perform($template, $cid);
    }

    protected function perform($template, $cid)
    {
        if (!$template) {
            throw new CHttpException(404);
        }

        if ($template === '1') {
            return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($cid)));
        }

        $channel = $this->getChannel($cid);

        $topChannel = $channel->getTopChannel();
        $channelAlias = $topChannel->getChannelAlias();
        if ($channelAlias) {
            $this->activeNavKey = $channelAlias->alias;
        }

        if ($topChannel->id != $cid) {
            $this->subTitle = $channel->title;
        }

        return $this->render(
            $template,
            array(
                'channel' => $channel,
                'topChannel' => $topChannel
            )
        );
    }
}

?>