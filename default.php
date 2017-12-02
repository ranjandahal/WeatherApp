<?php
/**
 * Created by PhpStorm.
 * User: Ranjan Dahal
 * Date: 11/23/17
 * Time: 7:49 PM
 */
include 'view/header.php'; ?>

    <script>
        $(document).ready(function () {
            data = '';
            $("#searchform").submit(function(e)
            {
                var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");
                $.ajax(
                    {
                        url : formURL,
                        type: "GET",
                        data : postData,
                        success:function(data, textStatus, jqXHR)
                        {
                            //data: return data from server
                            $('#accu').text(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            //if fails
                        }
                    });
                e.preventDefault(); //STOP default action
            });
            $('#searchsubmit').click(function () {
                $("#searchform").submit(); //Submit  the FORM
            })
            $('#search').keypress(function(e){
                if(e.keyCode==13)
                    $('#searchsubmit').click();
            });
        })
    </script>
    <div class="container">
        <div class="col-sm-6 col-sm-offset-3">
            <form name="searchform" id="searchform" action="view/search.php" method="GET">
                <div class="input-group">
                    <input id="search" type="text" class="form-control input-lg" name="search" placeholder="Search Loations">
                    <span class="input-group-addon"><i id="searchsubmit" class="glyphicon glyphicon glyphicon-search"></i></span>
                </div>
            </form>
        </div>
        <br />
        <br />
        <h2>Location: </h2>
        <hr>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">6 Hours</a></li>
            <li><a data-toggle="tab" href="#option1">12 Hours</a></li>
            <li><a data-toggle="tab" href="#option2">24 Hours</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="row">
                    <h2>6 Hours</h2>
                    <div class="col-sm-3">
                        <h3>Weather Channel</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Dark Sky</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Accu Weather</h3>
                        <div id="accu">
                        <?php echo is_null($weather_data)?'':$weather_data; ?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>NOAA</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                </div>
            </div>
            <div id="option1" class="tab-pane fade">
                <div class="row">
                    <h2>12 Hours</h2>
                    <div class="col-sm-3">
                        <h3>Weather Channel</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Dark Sky</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Accu Weather</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>NOAA</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                </div>
            </div>
            <div id="option2" class="tab-pane fade">
                <div class="row">
                    <h2>24 Hours</h2>
                    <div class="col-sm-3">
                        <h3>Weather Channel</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Dark Sky</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>Accu Weather</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                    <div class="col-sm-3">
                        <h3>NOAA</h3>
                        <p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>
                    </div>
                </div>
            </div>
        </div>
<?php include 'view/footer.php';
