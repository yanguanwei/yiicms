<script type="text/javascript">
$(function() {
    var errors = '<?php echo (isset($errors) && $errors) ? implode('\n', $errors) : ''?>';
    if (errors) {
        alert(errors);
    }
});
</script>
<div class="breadcrumb"><a href="/">首页</a>&lt;<a class="current">商家报名</a></div>
<form method="post" enctype="multipart/form-data">
    <div class="apply">
        <p><img src="<?php echo $this->asset('images/apply-img0.png')?>" /></p>
        <div class="apply-form1">
            <p class="info-txt" align="right">备注说明：已报名的商家请直接填写联系电话即可！</p>
            <h4 class="tt"><span>商家信息</span></h4>
            <ul>
                <li class="clearfix">
                    <span class="span1 fl">商家全称：</span>
                    <span class="span2 fl"><input type="text" name="MerchantApplyForm[title]" value="<?php echo $_POST['MerchantApplyForm']['title']?>" class="input-txt" /></span>
                </li>
                <li class="clearfix">
                    <span class="span1 fl">商铺网址：</span>
                    <span class="span2 fl"><input type="text" name="MerchantApplyForm[address]" value="<?php echo $_POST['MerchantApplyForm']['address']?>" class="input-txt" /></span>
                </li>
                <li class="clearfix">
                    <span class="span1 fl"><em>*</em>联系方式：</span>
                    <span class="span2 fl"><input type="text" name="MerchantApplyForm[phone]" value="<?php echo $_POST['MerchantApplyForm']['phone']?>" class="input-txt" /></span>
                </li>
                <li class="clearfix">
                    <span class="span1 fl">商家简介：</span>
                    <span class="span2 fl"><textarea name="MerchantApplyForm[content]" cols="" rows=""><?php echo $_POST['MerchantApplyForm']['content']?></textarea></span>
                </li>
                <li class="clearfix">
                    <span class="span1 fl">验证码：</span>
                <span class="span2 fl">
                    <input type="text" value="" class="input-txt2 fl" name="MerchantApplyForm[verifyCode]" />
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
                </li>
            </ul>
        </div>
    </div>
</form>