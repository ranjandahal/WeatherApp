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
                            //$('#accu').text(data);
                            try {
                                var day_or_night = '';
                                var json_content = JSON.parse(data);
                                for (var i = 0; i < json_content.length; i++) {
                                    if (json_content[i].length > 0) {
                                        var block = '';
                                        var block6 = '';
                                        var inner_json_content = json_content[i];
                                        for (var j = 0; j < inner_json_content.length; j++) {
                                            block += '<div class="well">'
                                            var hour = inner_json_content[j]['hour'];
                                            //<i class="wi wi-day-sunny">25 &deg;F</i>
                                            if (hour > 6 && hour <= 1700) {
                                                day_or_night = 'wi wi-day';
                                                block += '<span>' + hour + '</span>';
                                            }
                                            else {
                                                day_or_night = 'wi wi-night';
                                                block += '<span style="background: black;color: white;">' + hour + '</span>';
                                            }
                                            block += '<i class="' + day_or_night + '">' + inner_json_content[j]['temp'] + '&deg;F</i>';
                                            block += '<i class="' + day_or_night + '-windy">' + inner_json_content[j]['wind_speed'] + '</i>';
                                            block += '<i class="wi wi-humidity">' + inner_json_content[j]['humidity'] + '</i>';
                                            block += '<i class="' + day_or_night + '-cloudy">' + inner_json_content[j]['cloud_cover'] + '</i>';
                                            block += '<i class="wi wi-rain">' + (inner_json_content[j]['rain_probability'] * 100) + '</i>';
                                            block += '<i class="' + day_or_night + '-snow">' + inner_json_content[j]['snow_probability'] + '</i>';
                                            //var icon = day_or_night + '-' + inner_json_content[j]['icon_phrase'].toLowerCase();
                                            block += '</div>'
                                            if(j == 5)
                                                block6 = block;
                                        }
                                        if (i == 0) {
                                            $('#accu6').append(block6);
                                            $('#accu12').append(block);
                                        }
                                    }
                                }
                            }catch(err) {
                                $('#accu6').append(err.message);
                                $('#accu12').append(err.message);
                            }
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
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="row">
                    <h2>6 Hours</h2>
                    <div class="col-sm-3">
                        <h3>Weather Channel</h3>
                        <div id="weather6">
                        </div>
                        <!--<p>The Weather Channel and weather.com provide a national and local weather
                            forecast for cities, as well as weather radar, report and hurricane coverage.</p>-->
                        <div class="input-group">
                            <span>11:00 AM</span>
                            <i class="wi wi-day-sunny">25 &deg;F</i>
                            <i class="wi wi-day-cloudy"> 25 &deg;F</i>
                            <i class="wi wi-day-storm-showers"> 25 &deg;F</i>
                            <i class="wi wi-day-lightning"> 25 &deg;F</i>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>Dark Sky</h3>
                        <div id="darksky6">

                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>Accu Weather</h3>
                        <div id="accu6">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>NOAA</h3>
                        <div id="nooa6">
                        </div>
                    </div>
                </div>
            </div>
            <div id="option1" class="tab-pane fade">
                <div class="row">
                    <h2>12 Hours</h2>
                    <div class="col-sm-3">
                        <h3>Weather Channel</h3>
                        <div id="weather12">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>Dark Sky</h3>
                        <div id="darksky12">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>Accu Weather</h3>
                        <div id="accu12">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3>NOAA</h3>
                        <div id="nooa12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php include 'view/footer.php';
