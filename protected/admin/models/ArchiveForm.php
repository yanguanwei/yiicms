<?php
abstract class ArchiveForm extends CFormModel
{
    public $id;
    public $title;
    public $cover;
    public $cid;
    public $model_id;
    public $status = 1;
    public $template;
    public $keywords;
    public $description;
    public $update_time;
    public $is_highlight = 0;
    public $is_top = 0;
    public $tags = array();

    public function rules()
    {
        return array(
            array('id', 'required', 'on' => 'update'),
            array('title, cid, template', 'required'),
            array('keywords, description', 'length', 'max' => 255)
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'cid' => '所属栏目',
            'cover' => '图片',
            'status' => '状态',
            'template' => '模板名',
            'update_time' => '发布时间',
            'keywords' => '关键字',
            'description' => '描述',
            'is_highlight' => '高亮',
            'is_top' => '置顶'
        );
    }

    public function save()
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $this->doSave();
            $transaction->commit();
        } catch (CException $e) {
            $transaction->rollback();
            $this->addError(null, $e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 保存文档信息， 通过抛出异常来保证数据的一致性；
     * 子类覆盖此方法时应该在开始处调用parent::save()
     *
     * @throws CException
     * @return bool
     */
    protected function doSave()
    {
        if ($this->validate()) {
            if ($this->getScenario()=='insert') {
                $archive = new Archive();
                $this->id = null;
            } else {
                $archive = Archive::model()->findByPk($this->id);
                if (!$archive) {
                    throw new CException("没有找到ID为{$this->id}的记录！");
                }
            }

            $this->model_id = $this->getChannelModel()->id;

            if (!$this->update_time || false === ($update_time = strtotime($this->update_time))) {
                $this->update_time = time();
            } else {
                $this->update_time = $update_time;
            }
            $archive->setAttributes($this->getAttributes(), false);

            if ($archive->save()) {
                $this->id = $archive->id;

                $this->updateTags();

                return true;
            } else {
                $this->addErrors($archive->getErrors());
                throw new CException("添加失败！");
            }
        }

        throw new CException("验证失败！");
    }

    protected function updateTags()
    {
        if ($this->tags) {
            $tags = array();
            foreach ($this->tags as $key => $value) {
                if ($value) {
                    $tags[$key] = $value;
                }
            }
            if ($tags) {
                Archive::updateTags($this->id, $tags);
            }
        }
    }

    /**
     * @return ChannelModel
     */
    abstract protected function getChannelModel();
}
