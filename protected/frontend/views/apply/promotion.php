<script type="text/javascript">
var errors = '<?php echo (isset($errors) && $errors) ? implode('\n', $errors) : ''?>';
if (errors) {
    alert(errors);
}
</script>
<div class="breadcrumb"><a href="/">首页</a>&lt;<a class="current">商家报名</a></div>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="PromotionApplyForm[phone]" value="<?php echo $phone?>">
<div class="apply">
    <p><img src="<?php echo $this->asset('images/apply-img0.png')?>" /></p>
    <div class="apply-form1">
        <p class="info-txt" align="right">备注说明：已报名的商家请直接填写联系电话即可！</p>
        <ul>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>促销类别：</span>
                <span class="span2 fl">
                    <?php
                    foreach ($this->getTags('promotion_type') as $id => $tag) {
                        echo sprintf(
                            '<input type="radio" name="PromotionApplyForm[promotion_type]" value="%s" id="promotion_type_0" class="fl radio" /><label class="fl">%s</label>',
                            $id, $tag['title']
                        );
                    }
                    ?>
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>报名分类：</span>
                <span class="span2 fl">
                    <select name="PromotionApplyForm[promotion_category]" id="promotion_category">
                        <?php
                        foreach ($this->getTags('promotion_category') as $id => $tag) {
                            echo sprintf(
                                '<option value="%s">%s</option>',
                                $id, $tag['title']
                            );
                        }
                        ?>
                    </select>
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>区域选择：</span>
                <span class="span2 fl">
                    <select name="PromotionApplyForm[location]" id="location">
                        <?php
                        foreach ($this->getTags('location') as $id => $tag) {
                            echo sprintf(
                                '<option value="%s">%s</option>',
                                $id, $tag['title']
                            );
                        }
                        ?>
                    </select>
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>活动标题：</span>
                <span class="span2 fl"><input type="text" name="PromotionApplyForm[title]" value="<?php echo $_POST['PromotionApplyForm']['title']?>" class="input-txt" /></span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>广告图：</span>
                <span class="span2 fl">
                    <p><input name="cover" type="file" class="input-file" /></p>
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>活动时间：</span>
                <span class="span2 fl">
                    <input type="text" name="PromotionApplyForm[start_time]" value="<?php echo $_POST['PromotionApplyForm']['start_time']?>" class="input-txt2" /> ~ <input type="text" name="PromotionApplyForm[end_time]" value="<?php echo $_POST['PromotionApplyForm']['end_time']?>" class="input-txt2" />
                     如：2013-7-10 ~ 2013-8-10
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl"><em>*</em>活动详情：</span>
                <span class="span2 fl"><textarea name="PromotionApplyForm[content]" cols="" rows=""><?php echo $_POST['PromotionApplyForm']['content']?></textarea></span>
            </li>
            <li class="clearfix">
                <span class="span1 fl">验证码：</span>
                <span class="span2 fl">
                    <input type="text" value="" class="input-txt2 fl" name="PromotionApplyForm[verifyCode]" />
                    <?php
                    $this->widget('CCaptcha',array(
                            'showRefreshButton' => false,
                            'clickableImage' => true,
                            'imageOptions' => array(
                                'alt'=>'点击换图',
                                'title'=>'点击换图',
                                'style'=>'cursor:pointer',
                                'class' => 'fl codeImg'
                            )));
                    ?>
                </span>
            </li>
            <li class="clearfix">
                <span class="span1 fl">&nbsp;</span>
                <span class="span2 fl"><input type="submit" value="" class="input-btn" /></span>
                <span class="fr">我们会尽快审核!</span>
            </li>
        </ul>
    </div>
</div>
</form>