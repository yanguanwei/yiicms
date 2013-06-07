<?php
class ChannelBehaviorController extends FrontendController
{
  public function actionIndex($cid)
  {
    $template = (string)Channel::getChannelTemplate($cid);

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

    $topid = Channel::getTopChannelId($cid);
    $this->activeNavKey = $this->getChannelAlias($topid);

    if ($topid != $cid) {
      $this->subTitle = Channel::getChannelTitle($cid);
    }

    return $this->render(
      $template,
      array(
        'channel_id' => $cid,
        'top_id' => $topid
      )
    );
  }
}

?>