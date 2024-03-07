<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
	    <title><?php echo get_phrase($page_title); ?> | <?php echo $this->db->get_where('schools', array('id' => school_id()))->row('name'); ?></title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
	    <meta content="Creativeitem" name="author" />
	    <!-- App favicon -->
		<link rel="shortcut icon" href="<?php echo $this->settings_model->get_favicon(); ?>">

		<?php include 'includes_top.php';?>


		<style>
			body{
				padding-top: 50px;
				padding-bottom: 50px;
			}

			.payment-header-text{
				font-size: 23px;

			}

			.close-btn-light{
				padding-left: 10px;
				padding-right: 10px;
				height: 35px;
				line-height: 35px;
				text-align: center;
				font-size: 25px;
				background-color: #F1EAE9;
				color: #a45e72;
				border-radius: 5px;
			}
			.close-btn-light:hover{
				padding-left: 10px;
				padding-right: 10px;
				height: 35px;
				line-height: 35px;
				text-align: center;
				font-size: 25px;
				background-color: #a45e72;
				color: #FFFFFF;
				border-radius: 5px;
			}

			.payment-header{
				font-size: 18px;
			}

			.item{
				width: 100%;
				height: 50px;
				display: block;
			}

			.count-item{
				padding-left: 13px;
				padding-right: 13px;
				padding-top: 5px;
				padding-bottom: 5px;

				margin-bottom: 100%;
				margin-right: 18px;
				margin-top: 8px;

				color: #00B491;
				background-color: #DEF6F3;
				border-radius: 5px;
				float: left;
			}
			.item-title{
				font-weight: bold;
				font-size: 13.5px;
				display: block;
				margin-top: 6px;
			}
			.item-price{
				float: right;
				color: #00B491;
			}
			.by-owner{
				font-size: 11px;
				color: #76767E;
				display:block;
				margin-top: -3px;
			}

			.total{
				border-radius: 8px 0px 0px 8px;
				background-color: #DBF3F0;
				padding: 10px;
				padding-left: 30px;
				padding-right: 30px;
				font-size: 18px;
			}
			.total-price{
				border-radius: 0px 8px 8px 0px;
				background-color: #CCD4DD;
				padding: 10px;
				padding-left: 25px;
				padding-right: 25px;
				font-size: 18px;
			}
			.indicated-price{
				padding-bottom: 20px;
				margin-bottom: 0px;
			}

			.payment-button{
				background-color: #1DBDA0;
				border-radius: 8px;
				padding: 10px;
				padding-left: 30px;
				padding-right: 30px;
				color: #fff;
				border: none;
				font-size: 18px;
			}

			.payment-gateway{
				border: 2px solid #D3DCDD;
				border-radius: 5px;
				padding-top: 15px;
				padding-bottom: 15px;
				margin-bottom: 15px;
				cursor: pointer;
			}
			.payment-gateway:hover{
				border: 2px solid #00D04F;
				border-radius: 5px;
				padding-top: 15px;
				padding-bottom: 15px;
				margin-bottom: 15px;
				cursor: pointer;
			}

			.payment-gateway-icon{
				width: 80%;
				float: right;
			}
			.tick-icon{
				margin: 0px;
				padding: 0px;
				width: 15%;
				float: left;
				display: none;
			}
			.paypal-form, .stripe-form{
				display: none;
			}

			@media only screen and (max-width: 600px) {
			  .paypal, .stripe{
			    margin-left: 5px;
			    width: 70%;
			  }
			}

		</style>
	</head>
	<body>

	<?php
	    $paypal_activity = json_decode(get_payment_settings('paypal_settings'));
	    $stripe_activity = json_decode(get_payment_settings('stripe_settings'));
	?>

	<div class="container">
		<div class="row justify-content-center mb-5">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<span class="payment-header-text float-start"><b><?php echo get_phrase('make_payment'); ?></b></span>
						<a href="<?php echo route('invoice'); ?>" class="close-btn-light float-end"><i class="fa fa-times"></i></a>
					</div>
				</div>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-3">
						<p class="pb-2 payment-header"><?php echo get_phrase('payment'); ?> <?php echo get_phrase('gateway'); ?></p>

						<?php if ($paypal_activity[0]->paypal_active == 'yes'): ?>
							<div class="row payment-gateway paypal" onclick="selectedPaymentGateway('paypal')">
								<div class="col-12">
									<img class="tick-icon paypal-icon" src="<?php echo base_url('assets/payment/tick.png'); ?>">
									<img class="payment-gateway-icon" src="<?php echo base_url('assets/payment/paypal.png'); ?>">
								</div>
							</div>
						<?php endif; if ($stripe_activity[0]->stripe_active == 'yes'): ?>
							<div class="row payment-gateway stripe" onclick="selectedPaymentGateway('stripe')">
								<div class="col-12">
									<img class="tick-icon stripe-icon" src="<?php echo base_url('assets/payment/tick.png'); ?>">
									<img class="payment-gateway-icon" src="<?php echo base_url('assets/payment/stripe.png'); ?>">
								</div>
							</div>
						<?php endif; ?>
						<?php if(addon_status('payumoney') == 1): ?>
			            	<?php include 'payumoney_payment_gateway.php'; ?>
			        	<?php endif; ?>
			        	<?php if(addon_status('paystack') == 1): ?>
			            	<?php include 'paystack_payment_gateway.php'; ?>
			        	<?php endif; ?>
					</div>

					<div class="col-md-1"></div>

					<div class="col-md-8">
						<div class="w-100">
							<p class="pb-2 payment-header"><?php echo get_phrase('invoice_list'); ?></p>
	                        <p class="item float-start">
								<span class="count-item"><?php echo get_phrase('1'); ?></span>
								<span class="item-title"><?php echo $invoice_details['title']; ?>
									<span class="item-price">
										<?php
											$total_amount_in_this_invoice = $invoice_details['total_amount'] - $invoice_details['paid_amount'];
										?>
										<?php echo currency($total_amount_in_this_invoice); ?>
									</span>
								</span>
								<span class="by-owner">
									<!-- owner name -->
								</span>
							</p>
						</div>
						<div class="w-100 float-start mt-4 indicated-price">
							<div class="float-end total-price"><?php echo currency($total_amount_in_this_invoice); ?></div>
							<div class="float-end total">
								<?php if($invoice_details['paid_amount'] > 0): ?>
									<?php echo get_phrase('due'); ?>
								<?php else: ?>
									<?php echo get_phrase('total'); ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="w-100 float-start">
							<form action="<?php echo route('paypal_checkout'); ?>" method="post" class="paypal-form form">
								<hr class="border mb-4">
								<input type="hidden" name="invoice_id" value="<?php echo $invoice_details['id']; ?>">
								<button type="submit" class="payment-button float-end"><?php echo get_phrase('pay_by_paypal'); ?></button>
				            </form>
				            <form action="<?php echo route('stripe_checkout'); ?>" method="post" class="stripe-form form">
				            	<hr class="border mb-4">
								<input type="hidden" name="invoice_id" value="<?php echo $invoice_details['id']; ?>">
								<button type="submit" class="payment-button float-end"><?php echo get_phrase('pay_by_stripe'); ?></button>
				            </form>
				            <?php if(addon_status('payumoney') == 1): ?>
				            	<?php include 'payumoney_payment_gateway_form.php'; ?>
				        	<?php endif; ?>
				            <?php if(addon_status('paystack') == 1): ?>
				            	<?php include 'paystack_payment_gateway_form.php'; ?>
				        	<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function selectedPaymentGateway(gateway){
			if(gateway == 'paypal'){
				$(".payment-gateway").css("border","2px solid #D3DCDD");
				$('.tick-icon').hide();
				$('.form').hide();

				$(".paypal").css("border","2px solid #00D04F");
				$('.paypal-icon').show();
				$('.paypal-form').show();
			}else if(gateway == 'stripe'){
				$(".payment-gateway").css("border","2px solid #D3DCDD");
				$('.tick-icon').hide();
				$('.form').hide();

				$(".stripe").css("border","2px solid #00D04F");
				$('.stripe-icon').show();
				$('.stripe-form').show();
			}
		}
	</script>
	</body>
</html>