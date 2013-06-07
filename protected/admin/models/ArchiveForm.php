<?php
class ArchiveForm extends CFormModel
{
    public $id;
    public $title;
    public $cover;
    public $cid;
    public $model_id;
    public $status;
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
            'cover' => '图片/Flash地址',
            'status' => '状态',
            'template' => '模板名',
            'update_time' => '发布时间',
            'keywords' => '关键字',
            'description' => '描述',
            'is_highlight' => '高亮',
            'is_top' => '置顶'
        );
    }

    public function post(array $data, $insert = true)
    {
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $this->save($data, $insert);
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
     * 子类覆盖此方法时应该在开始处调用parent::save($data, $insert)
     *
     * @param array $data
     * @param unknown_type $insert
     * @throws CException 如果出错，抛出异常
     */
    protected function save(array $data, $insert = true)
    {
        $this->setAttributes($data, false);

        if ($this->validate()) {
            $model_id = Channel::getChannelModelId($this->cid);
            if (!$model_id) {
                $this->addError('cid', '该栏目没有绑定文档模型');
                throw new CException();
            }

            if ($insert) {
                $archive = new Archive();
                $this->id = null;
            } else {
                $archive = Archive::model()->findByPk($this->id);
                if (!$archive) {
                    throw new CException("没有找到ID为{$this->id}的记录！");
                }
            }

            $this->model_id = $model_id;

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
            Archive::updateTags($this->id, $this->tags);
        }
    }
}

?>