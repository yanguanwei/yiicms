<?php

class ApplyController extends FrontendController
{
    public function actionIndex()
    {
        if (isset($_POST['PromotionApplyForm'])) {
            $form = new PromotionApplyForm();
            $form->setAttributes($_POST['PromotionApplyForm']);
            if ($form->save()) {
                exit('<script type="text/javascript">alert("报名成功！");window.location.href="/";</script>');
            } else {
                exit('<script type="text/javascript">alert("请检查错误！");window.location.href="'.$this->createUrl('').'";</script>');
            }
        }

        return $this->render('index');
    }

    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                'maxLength'=>'4',       // 最多生成几个字符
                'minLength'=>'2',       // 最少生成几个字符
                'height'=>'40'
            ),
        );
    }
}
