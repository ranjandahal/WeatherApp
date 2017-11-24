 <!-- Note from eoneil: 
   $app_path is now set in main.php, included in all controllers
 -->
    
<!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/html">
<head>
    <title>WeatherMate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <div class="text-center">
        <img src="<?php echo $app_path?>images/WeatherLogo.png" alt="WeatherApp">
    </div>
     <hr>
    <div class="container">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="input-group">
                <input id="search" type="text" class="form-control input-lg" name="search" placeholder="Search Loations">
                <span class="input-group-addon"><i class="glyphicon glyphicon glyphicon-search"></i></span>
            </div>
        </div>
    <br />
        <br />