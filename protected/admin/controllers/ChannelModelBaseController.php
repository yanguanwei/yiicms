<?php

abstract class ChannelModelBaseController extends AdminController
{
    protected $cid;
    private $channel;
    protected $isAttach = false;

    public function actionIndex($cid)
    {
        $this->cid = $cid = intval($cid);

        $channel = $this->getChannel();

        if (!$channel) {
            throw new CHttpException(404);
        }

        $cids = array_keys($channel->getSubChannels());
        $cids[] = $cid;

        return $this->doList($channel, $cids);
    }

    public function actionList($cid)
    {
        $this->cid = $cid = intval($cid);

        $channel = $this->getChannel();
        if (!$channel) {
            throw new CHttpException(404);
        }

        $parentChannel = $channel->getParentChannel();

        if (!$parentChannel) {
            return $this->redirect(array('index', 'cid' => $cid));
        }

        if ($parentChannel) {
            $this->navigationCurrentItemKey = 'theme_' . $parentChannel->theme_id . '/' . $parentChannel->id;
        } else {
            $this->navigationCurrentItemKey = 'theme_' . $channel->theme_id . '/' . $channel->id;
        }

        return $this->doList($channel, $cid);
    }

    protected function doList(Channel $channel, $cids)
    {
        $model = $this->getChannelModel();

        Yii::import('apps.ext.young.SelectSQL');
        Yii::import('apps.ext.young.SelectDataProvider');

        $sql = new SelectSQL();
        $sql->from(array('{{'.$model->table_name.'}}', 'base'), '*')
            ->in('cid', $cids);

        $this->prepareListSQL($sql);

        $dataProvider = new SelectDataProvider(Yii::app()->db, $sql);

        $this->render(
            $this->getListTemplate(),
            array(
                'dataProvider' => $dataProvider,
                'channel' => $channel,
                'model' => $model,
                'filters' => $this->getListFilters(),
                'isAttach' => $this->isAttach
            )
        );
    }

    public function actionCreate($cid)
    {
        $form = $this->createFormModel('insert', $cid);

        if (isset($_POST[get_class($form)])) {
            $form->setAttributes($_POST[get_class($form)], false);
            if ($form->save()) {
                $this->onFormCreateSubmitSuccess($form);
                $this->onFormSubmitSuccess($form);
                $this->setFlashMessage(
                    'success',
                  '创建成功！点击<a href="' . $this->createUrl('create', array('cid' => $form->cid)) . '">继续创建</a>'
                );
                $this->redirect($this->createUrl('list', array('cid' => $form->cid)));
            } else {
                $this->onFormCreateSubmitError($form);
                $this->onFormSubmitError($form);
                $this->setFlashMessage('error', "创建失败！");
            }
        } else {
            $form->cid = intval($cid);
            $this->onFormCreate($cid, $form);
        }

        $this->cid = $form->cid;

        if (!$this->isAttach) {
            $topChannel = $this->getChannel()->getTopChannel();
            $this->navigationCurrentItemKey = 'theme_' . $topChannel->theme_id . '/' . $topChannel->id;
        }

        $this->render(
            '//form_template',
            array(
                'form' => $form,
                'model' => $this->getChannelModel(),
                'channel' => $this->getChannel(),
                'title' => '创建' . $this->getChannelModel()->title,
                'view' => $this->getFormTemplate(),
                'isAttach' => $this->isAttach
            )
        );
    }

    public function actionUpdate($id)
    {
        $form = $this->createFormModel('update', $id);

        if (isset($_POST[get_class($form)])) {
            $form->setAttributes($_POST[get_class($form)], false);
            if ($form->save()) {
                $this->onFormUpdateSubmitSuccess($form);
                $this->onFormSubmitSuccess($form);
                $this->setFlashMessage('success', '更新成功！');
                $this->redirect($this->createUrl('list', array('cid' => $form->cid)));
            } else {
                $this->onFormUpdateSubmitError($form);
                $this->onFormSubmitError($form);
                $this->setFlashMessage('error', "更新失败！");
            }
        } else {
            $this->onFormUpdate($id, $form);
        }

        if (!$form->cid) {
            throw new CHttpException(404);
        }
        $this->cid = $form->cid;

        if (!$this->isAttach) {
            $topChannel = $this->getChannel()->getTopChannel();
            $this->navigationCurrentItemKey = 'theme_' . $topChannel->theme_id . '/' . $topChannel->id;
        }

        $this->render(
            '//form_template',
            array(
                'form' => $form,
                'model' => $this->getChannelModel(),
                'channel' => $this->getChannel(),
                'title' => '更新' . $this->getChannelModel()->title,
                'view' => $this->getFormTemplate(),
                'isAttach' => $this->isAttach
            )
        );
    }

    public function actionDelete()
    {
        if (isset($_POST['id'])) {
            $ids = $_POST['id'];
        } else {
            $ids = intval($_GET['id']);
        }

        $ids = (array) $ids;

        if (false !== $this->onPrevDelete($ids)) {
            $count = $this->deleteModel($ids);

            if ($count) {
                $this->setFlashMessage('success', "删除成功：共删除 {$count} 条记录！");
            } else {
                $this->setFlashMessage('information', "该记录不存在或已经被删除！");
            }
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }

    /**
     * @return ChannelModel
     */
    abstract protected function getChannelModel();

    /**
     * @param $scenario
     * @param $idOrCid
     * @return CModel
     */
    abstract protected function createFormModel($scenario, $idOrCid);

    protected function onFormCreate($cid, $form)
    {
    }

    protected function onFormCreateSubmitError($form)
    {
    }

    protected function onFormUpdateSubmitError($form)
    {
    }

    protected function onFormSubmitError($form)
    {
    }

    protected function onFormCreateSubmitSuccess($form)
    {
    }

    protected function onFormUpdateSubmitSuccess($form)
    {
    }

    protected function onFormSubmitSuccess($form)
    {
        if (isset($_POST[get_class($form)]['tags'])) {
            $tags = array();
            foreach ($_POST[get_class($form)]['tags'] as $key => $value) {
                if ($value) {
                    $tags[$key] = $value;
                }
            }
            if ($tags) {
                ModelTag::update($this->getChannelModel()->name, $form->id, $tags);
            }
        }
    }

    protected function onFormUpdate($id, $form)
    {
        $form->tags = ModelTag::find($this->getChannelModel()->name, $id);
    }

    protected function onPrevDelete(array $ids)
    {
    }

    protected function prepareListSQL(SelectSQL $sql)
    {
        $this->prepareListSQLForTagsFilter($sql);
    }

    protected function prepareListSQLForTagsFilter(SelectSQL $sql)
    {
        $tags = $this->getChannel()->tags;
        if ($tags) {
            $model_name = $this->getChannelModel()->name;
            foreach ($tags as $key) {
                if (isset($_GET[$key]) && $_GET[$key]) {
                    $tid = intval($_GET[$key]);
                    $sql->where("base.id in (SELECT id FROM {{model_tag}} WHERE model_name='{$model_name}' AND tid='{$tid}')");
                }
            }
        }
    }

    protected function getListFilters()
    {
        $filters = array();
        $tagTypeNames = $this->getChannel()->tags;
        if ($tagTypeNames) {
            $tagTypes = TagType::getTagTypeTitles($tagTypeNames);
            $tagOptions = Tag::getTagOptions($tagTypeNames);
            foreach ($tagTypes as $name => $typeTitle) {
                $options = isset($tagOptions[$name]) ? $tagOptions[$name] : array();
                $filters[$name] = CHtml::dropDownList($name, isset($_GET[$name]) ? $_GET[$name] : '', $options, array('empty' => '--' . $tagTypes[$name] . '--'));
            }
        }
        return $filters;
    }

    /**
     * @param array $ids
     * @return int
     */
    protected function deleteModel(array $ids)
    {
        ModelTag::delete($this->getChannelModel()->name, $ids);
    }

    /**
     * @return Channel
     */
    protected function getChannel()
    {
        if (null === $this->channel) {
            $this->channel = Channel::model()->findByPk($this->cid);
        }
        return $this->channel;
    }

    protected function beforeRender($view)
    {
        if ($this->isAttach) {
            $this->layout = '/layouts/iframe';
        }
        return parent::beforeRender($view);
    }

    protected function getListTemplate()
    {
        return '/' . $this->getChannelModel()->controller . '/list';
    }

    protected function getFormTemplate()
    {
        return '/' . $this->getChannelModel()->controller . '/form';
    }

    public function getShortcuts()
    {
        $shortcuts = parent::getShortcuts();

        if ($this->cid) {
            $model = $this->getChannelModel();
            if (!$this->isAttach) {
                $channel = $this->getChannel();
                $parent = $channel->getParentChannel();
                if (!$parent) {
                    $parent = $channel;
                }

                $shortcuts[] = array(
                    'shortcut' => $this->getChannelModelIcon($model->name, 'list'),
                    'label' => $channel->title,
                    'url' => $this->createUrl('list', array('cid' => $channel->id))
                );

                $subs = $parent->getSubChannels();
                if ($subs) {
                    foreach ($subs as $sub) {
                        $shortcuts[] = array(
                            'shortcut' => $this->getChannelModelIcon($model->name, 'create'),
                            'label' => '创建' . $sub->title,
                            'url' => $this->createUrl('create', array('cid' => $sub->id))
                        );
                    }
                } else {
                    $shortcuts[] = array(
                        'shortcut' => $this->getChannelModelIcon($model->name, 'create'),
                        'label' => '创建' . $channel->title,
                        'url' => $this->createUrl('create', array('cid' => $channel->id))
                    );
                }
            }
        }

        return $shortcuts;
    }

    protected function getChannelModelIcon($model_id, $op)
    {
        $defaults = array(
            'list' => $this->asset('images/icons/paper_content_pencil_48.png'),
            'create' => $this->asset('images/icons/edit.png')
        );

        $icons = array(
            'link' => array(
                'list' => $this->asset('images/icons/favorite.png'),
                'create' => $this->asset('images/icons/add.png')
            ),
            'picture' => array(
                'list' => $this->asset('images/icons/picture.png'),
                'create' => $this->asset('images/icons/image_add_48.png')
            ),
            'video' => array(
                'list' => $this->asset('images/icons/video_list.png'),
                'create' => $this->asset('images/icons/video.png')
            )
        );

        if (isset($icons[$model_id]) && $icons[$model_id][$op]) {
            return $icons[$model_id][$op];
        } else {
            return $defaults[$op];
        }
    }
}