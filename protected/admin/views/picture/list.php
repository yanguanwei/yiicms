<?php
echo $this->renderPartial('/archive_list', array(
  'channel_topid' => $channel_topid,
  'channels' => $channels,
  'title' => $title,
  'dataProvider' => $dataProvider,
  'channel_id' => $channel_id
));
