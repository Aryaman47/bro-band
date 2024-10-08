<?php
include('db.php');
include('services/plan_services.php');
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	$phone = $_SESSION["phone"];
	if (isset($_POST["service"]) && $_POST["service"] !== '') {
		$service_id = $_POST["service"];
		$service_deets = get_service_details($service_id, $db);
	} else if (isset($_POST["plan"]) && $_POST["plan"] !== '') {
		$plan_id = $_POST["plan"];
		$plan_deets = get_plan_details($plan_id, $db);
		$min_period =  preg_replace('/m/', '', $plan_deets->min_first_bill_period);
		$total_cost = (int) $min_period * (int) $plan_deets->cost;
	} else {
		header("location: plans.php");
		exit;
	}
} else {
	header("location: login.php");
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MyWebsite</title>
	<link rel="stylesheet" href="service.css">
	<link rel="stylesheet" href="profile.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
		.service_card {
			flex-grow: 0;
			flex-direction: column;
			align-items: flex-start;
			align-items: center;
			padding: 1rem;
			background-color: white;
			color: black;
			border-radius: 4%;
		}

		.profile-card__button {
			margin: unset !important;
		}

		.service_card_img img {
			max-height: 100%;
			max-width: 100%;
		}

		.service_card-container {
			display: flex;
			flex-wrap: wrap;
			flex-direction: column;
			gap: 1rem;
			color: white;
			background: linear-gradient(360deg, #141e30, #243b55);
			border-radius: 15px;
			border: 2px solid #243b55;
			padding: 50px;
			margin-top: 10rem;
			width: 90%;
			height: 700px;
			margin-right: auto;
			margin-left: auto;
			align-items: center;
			justify-content: center;
		}

		.label-text-container {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 0.7rem;
		}

		.label-text {
			font-size: 17px;
			font-weight: bold;
		}

		table {
			color: white;
		}

		td,
		th {
			border: 1px solid white;
			padding: 0.9rem;
			padding-inline: 4rem;
		}

		.profile-card__button {
			margin-top: 7.9rem !important;
		}
	</style>
</head>

<body>
	<?php include_once 'components/navbar.php' ?>
	<form action="services/purchase.php" class="service_card-container" method="post">
		<h1>Bill details</h1>
		<h4>Bill Date: <?php echo date("Y/m/d"); ?></h4>

		<?php if (isset($service_id)) : ?>
			<table>
				<tr>
					<td>Total Charges</td>
					<td>₹<?php echo $service_deets->cost; ?></td>
				</tr>
				<tr>
					<td>cgst 9.5%</td>
					<td>₹<?php echo (int) $service_deets->cost * 0.095; ?></td>
				</tr>
				<tr>
					<td>sgst 9.5%</td>
					<td>₹<?php echo (int) $service_deets->cost * 0.095; ?></td>
				</tr>
				<tr>
					<td>Total</td>
					<td>₹<?php echo ((int) $service_deets->cost * 0.19) + (int) $service_deets->cost; ?></td>
				</tr>
			</table>
			<input type="hidden" name="service" value="<?php echo $service_id ?>">
		<?php endif; ?>

		<?php if (isset($plan_id)) : ?>
			<table>
				<tr>
					<td>Total Charges</td>
					<td>₹<?php echo $plan_deets->cost;
								if ($plan_deets->min_first_bill_period !== '1m')
									echo '* ' . $plan_deets->min_first_bill_period . ' = ₹' . $total_cost; ?></td>
				</tr>
				<tr>
					<td>cgst 9.5%</td>
					<td>₹<?php echo $total_cost * 0.095; ?></td>
				</tr>
				<tr>
					<td>sgst 9.5%</td>
					<td>₹<?php echo $total_cost * 0.095; ?></td>
				</tr>
				<tr>
					<td>Total</td>
					<td>₹<?php echo ($total_cost * 0.19) + (int) $total_cost; ?></td>
				</tr>
			</table>
			<input type="hidden" name="plan" value="<?php echo $plan_id ?>">
		<?php endif; ?>

		<button type="submit" class="profile-card__button button--orange">Confirm Purchase</button>
	</form>

</body>

</html>
