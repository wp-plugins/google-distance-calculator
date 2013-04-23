<?php
if ($_POST['origin'] != "" && $_POST['destination'] != "") {
  $origin = str_replace(' ', '+', $_POST['origin']);
  $destination = str_replace(' ', '+', $_POST['destination']);
  $language = $_POST['language'];

  $url = "http://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $destination . "&sensor=false&language=" . $language;

// sendRequest
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_REFERER, 'http://yourdemolink.com/php/googlemaps-json/index.php');
  $body = curl_exec($ch);
  curl_close($ch);

  $json = json_decode($body);
  //echo "<pre>";  print_r($json);  echo "</pre>";

  if ($json->status != 'ZERO_RESULTS') {
    $legs = $json->routes[0]->legs[0];
    $drivingSteps = $json->routes[0]->legs[0]->steps;
    ?>
    <h4>Distance between <?php echo $legs->start_address; ?> and <?php echo $legs->end_address; ?> is: <?php echo $legs->distance->text; ?></hh4>
    <h4>Approx. time of journey: <?php echo $legs->duration->text; ?></h4>
    <h4>Driving directions:</h4>
    <ul>
      <?php foreach ($drivingSteps as $drivingStep) { ?>
        <li><?php echo $drivingStep->html_instructions; ?></li>
        <?php
      }
    } else{
    echo "<h4 class=\"mkgd-error\">Google cannot find directions for the Origin and Destination addess entered by you.</h4>";
  }
  }
  ?>

