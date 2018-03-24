<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24
 * Time: 21:11
 */

namespace backend\filters;


class RbacFilter extends \yii\base\ActionFilter
{
    public function beforeAction($action)
    {
        //判断当前用户有没有权限
        if(!\Yii::$app->user->can($action->uniqueId)){

            $html=<<<HTML
            <style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>

<div class="system-message">
<h1>:(</h1>
<p class="error">你没有权限</p><p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="javascript:history.back(-1);">跳转</a> 等待时间： <b id="wait">3</b>
[ <a href="http://www.thinkphp.cn/">返回首页</a> ]</p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time == 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>

HTML;

            echo $html;
            return false;
        }

        return parent::beforeAction($action);
    }
}