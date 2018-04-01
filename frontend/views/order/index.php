<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>填写核对订单信息</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/fillin.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">

	<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
	<!-- 顶部导航 start -->
    <?php
    include Yii::getAlias('@app')."/views/common/nav.php";
    ?>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>
	
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="/index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow2">
				<ul>
					<li>1.我的购物车</li>
					<li class="cur">2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>

		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
				<p>
					<input type="radio" value="1" name="address_id"/>许坤  17002810530  北京市 昌平区 仙人跳区 仙人跳大街 </p>
					<input type="radio" value="1" name="address_id"/>许坤  17002810530  四川省 成都市 高新区 仙人跳大街 </p>
				</div>


			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式</h3>

				<div class="delivery_select ">
					<table>

						<tbody>
							<tr class="cur">	
								<td>
									<input type="radio" name="delivery" checked="checked" />普通快递送货上门

								</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio" name="delivery" />特快专递</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio" name="delivery" />加急快递送货上门</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>

								<td><input type="radio" name="delivery" />平邮</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
						</tbody>
					</table>
<!--					<a href="/" class="confirm_btn"><span>确认送货方式</span></a>-->
				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式</h3>


				<div class="pay_select">
					<table> 
						<tr class="cur">
							<td class="col1"><input type="radio" name="pay" />货到付款</td>
							<td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" />在线支付</td>
							<td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" />上门自提</td>
							<td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" name="pay" />邮局汇款</td>
							<td class="col2">通过快钱平台收款 汇款后1-3个工作日到账</td>
						</tr>
					</table>
<!--					<a href="/" class="confirm_btn"><span>确认支付方式</span></a>-->
				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->

			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
						<tr>
							<td class="col1"><a href="/"><img src="/images/cart_goods1.jpg" alt="" /></a>  <strong><a href="/">【1111购物狂欢节】惠JackJones杰克琼斯纯羊毛菱形格</a></strong></td>
							<td class="col3">￥499.00</td>
							<td class="col4"> 1</td>
							<td class="col5"><span>￥499.00</span></td>
						</tr>
						<tr>
							<td class="col1"><a href="/"><img src="/images/cart_goods2.jpg" alt="" /></a> <strong><a href="/">九牧王王正品新款时尚休闲中长款茄克EK01357200</a></strong></td>
							<td class="col3">￥1102.00</td>
							<td class="col4">1</td>
							<td class="col5"><span>￥1102.00</span></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span>4 件商品，总商品金额：</span>
										<em>￥5316.00</em>
									</li>
									<li>
										<span>返现：</span>
										<em>-￥240.00</em>
									</li>
									<li>
										<span>运费：</span>
										<em>￥10.00</em>
									</li>
									<li>
										<span>应付总额：</span>
										<em>￥5076.00</em>
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- 商品清单 end -->
		
		</div>

		<div class="fillin_ft">
			<a href="/"><span>提交订单</span></a>
			<p>应付总额：<strong>￥5076.00元</strong></p>
			
		</div>
	</div>
	<!-- 主体部分 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="/">关于我们</a> |
			<a href="/">联系我们</a> |
			<a href="/">人才招聘</a> |
			<a href="/">商家入驻</a> |
			<a href="/">千寻网</a> |
			<a href="/">奢侈品网</a> |
			<a href="/">广告服务</a> |
			<a href="/">移动终端</a> |
			<a href="/">友情链接</a> |
			<a href="/">销售联盟</a> |
			<a href="/">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href="/"><img src="/images/xin.png" alt="" /></a>
			<a href="/"><img src="/images/kexin.jpg" alt="" /></a>
			<a href="/"><img src="/images/police.jpg" alt="" /></a>
			<a href="/"><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->
</body>
</html>
