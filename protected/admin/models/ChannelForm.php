<?php
class ChannelForm extends CFormModel
{
    public $id;
    public $title;
    public $parent_id = 0;
    public $model_name;
    public $theme_id;
    public $visible = 1;
    public $sort_id = 0;
    public $channel_template;
    public $archive_template;
    public $keywords;
    public $description;
    public $alias;
    public $tags;
    public $channel_attach;

    public function rules()
    {
        return array(
            array('id', 'required', 'on' => 'update'),
            array('title, ', 'required'),
            array('model_name, parent_id, theme_id', 'required','on' => 'insert'),
            array(
                'alias',
                'match',
                'pattern' => '/^[a-zA-Z0-9_]{0,}$/',
                'message' => '栏目别名必须为字母、数字、下划线'
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '分类名称',
            'parent_id' => '所属父分类',
            'model_name' => '模型',
            'sort_id' => '排序',
            'visible' => '是否可见',
            'keywords' => '关键字',
            'description' => '描述',
            'channel_template' => '栏目模板',
            'archive_template' => '内容模板',
            'theme_id' => '发布在',
            'alias' => '栏目别名',
            'channel_attach' => '栏目附件模型'
        );
    }

    /**
     * @param array $data
     * @param boolean $insert
     * @return boolean 插入成功返回true，验证失败返回false
     */
    public function post(array $data, $insert = true)
    {
        $this->setAttributes($data, false);

        if ($insert) {
            $channel = new Channel('insert');
            $channel->id = $this->id = null;
            if ($this->parent_id) {
                //model_name、theme_id只能继承自父栏目
                $this->model_name = Channel::findChannelModelName($this->parent_id);
                $this->theme_id = Channel::findChannelThemeId($this->parent_id);
            }
        } else {
            $channel = Channel::model()->findByPk($this->id);
            //model_name、parent_id、$theme_id不能更改
            $this->model_name = $channel->model_name;
            $this->parent_id = $channel->parent_id;
            $this->theme_id = $channel->theme_id;
            $this->channel_attach = $channel->channel_attach;
        }

        if ($this->validate()) {
            //通过事务来达到栏目表与栏目别名表数据同步
            $transaction = Yii::app()->db->beginTransaction();

            try {
                $channel->setAttributes($this->getAttributes(), false);

                if (is_array($channel->tags)) {
                    $tags = array();
                    foreach ($channel->tags as $tagTypeName => $checked) {
                        if ($checked) {
                            $tags[] = $tagTypeName;
                        }
                    }
                    $channel->tags = $tags;
                }

                if ($channel->save()) {

                    $this->id = $channel->id;

                    $this->alias = trim($this->alias);
                    if ($this->alias) {
                        $alias = ChannelAlias::model()->findByPk($channel->id);
                        if (!$alias) {
                            $alias = new ChannelAlias();
                            $alias->id = $channel->id;
                        }
                        $alias->alias = $this->alias;

                        if (!$alias->save()) {
                            $this->addErrors($alias->getErrors());
                            throw new CException();
                        }
                    } else { //没有指定栏目别名的，删除原来的
                        $alias = ChannelAlias::model()->findByPk($channel->id);
                        if ($alias) {
                            $alias->delete();
                        }
                    }

                    //没有错误，提交
                    $transaction->commit();

                    ChannelAlias::updateChannelController();

                    return true;
                } else {
                    $this->addErrors($channel->getErrors());
                }

            } catch (CException $e) {
                $transaction->rollback();
                $this->addError(null, $e->getMessage());

                return false;
            }
        }

        return false;
    }
}

?>