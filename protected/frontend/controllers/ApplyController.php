<?php

class ApplyController extends FrontendController
{
    public function actionIndex()
    {
        if (isset($_POST['MerchantApplyForm'])) {
            $form = new MerchantApplyForm();
            $form->setAttributes($_POST['MerchantApplyForm']);
            if ($form->save()) {
                return $this->render('promotion', array(
                    'phone' => $form->phone
                ));
            } else {
                $errors = array();
                foreach ($form->getErrors() as $name => $error) {
                    $errors[$name] = reset($error);
                }
                return $this->render('merchant', array(
                    'errors' => $errors
                ));
            }
        } else if (isset($_POST['PromotionApplyForm'])) {
            $form = new PromotionApplyForm();
            $form->setAttributes($_POST['PromotionApplyForm']);
            if ($form->save()) {
                exit('<script type="text/javascript">alert("报名成功，我们会尽快审核！");window.location.href="/";</script>');
            } else {
                $errors = array();
                foreach ($form->getErrors() as $name => $error) {
                    $errors[$name] = reset($error);
                }
                return $this->render('promotion', array(
                        'phone' => $form->phone,
                        'errors' => $errors
                    ));
            }
        } else {
            return $this->render('merchant');
        }
    }

    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                'maxLength'=>'4',       // 最多生成几个字符
                'minLength'=>'4',       // 最少生成几个字符
                'height'=>'40'
            ),
        );
    }
}
