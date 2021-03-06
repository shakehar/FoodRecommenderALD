<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <?php
        include_once 'meta.php';
        ?>
        <script type="text/javascript">

            function populateFoodHistoryTable(id, name, time, date) {
                var table = $('#diet_history');
                table.append($(
                        '<tr><td>' + name + '</td>'
                        + '<td>' + date + '</td>'
                        + '<td>' + time + '</td></tr>'
                        ));

            }
            function addToUserHistory(id, name) {
                var date = $('#datepicker_t').val();
                var time = $('#meal_t :selected').val();
                //alert(id + " " + date + " " + time);
                var query = '/ALDWebsite/saveUserHistory.php?user_id=1&date_t=' + date
                        + '&food_id=' + id + '&meal_t=' + time;
                $.ajax({
                    url: query,
                    //data: JSON.stringify({'user_id': 1, 'date_t': date, 'food_id': id, 'meal_t': time}),
                    type: "GET",
                    contentType: 'application/json; charset=utf-8',
                    dataType: "json",
                    success: function(data) {
                        debugger;
                        populateFoodHistoryTable(id, name, time, date);
                    },
                    error: function(data) {
                        debugger;
                        alert("Some Error Occured ");
                        // alert(data.message);
                    }
                });


            }
            function populateFoodTable(data) {
                var items = data.items;
                var table = $('#food_items');
                $("#food_items tr:gt(0)").remove();
                $.each(items, function() {
                    table.append($('<tr><td>' + this.FNM_Name + '</td>' +
                            '<td>' + this.Total_Fat + '</td>' +
                            '<td>' + this.Sat_Fat + '</td>' +
                            '<td>' + this.Trans_Fat + '</td>' +
                            '<td>' + this.Chol + '</td>' +
                            '<td>' + this.Sodium + '</td>' +
                            '<td>' + this.Total_Carb + '</td>' +
                            '<td>' + this.Diet_Fibre + '</td>' +
                            '<td>' + this.Sugar + '</td>' +
                            '<td>' + this.Protein + '</td>' +
                            '<td>' + this.Calcium + '</td>' +
                            '<td>' + this.Potassium + '</td>' +
                            '<td>' + this.Alcohol + '</td>' +
                            '<td>' + this.Calories + '</td>' +
                            '<td>' + this.MS_Name + '</td>' +
                            '<td align="right"><a class="btn btn-success" href="#" onclick="addToUserHistory(' + this.FNM_ID
                            + ',\'' + this.FNM_Name + '\');">\n\
                <i class="icon-ok icon-white"></i> Add</a>\n\
                </td>' +
                            '</tr>'));
                });
            }
            function fetchFood() {
                var pfc = $('#pfc :selected').val();
                var query = "/ALDWebsite/getallfndetails.php?pfc_id=" + pfc;
                $.ajax({
                    url: query,
                    type: "GET",
                    contentType: 'application/json; charset=utf-8',
                    dataType: "json",
                    success: function(data) {
                        populateFoodTable(data);
                    },
                    error: function(data) {
                        //alert("Some Error Occured ");
                        //alert(data.message);
                    }
                });
            }
            function populatePFC(data) {
                var items = data.items;
                var subType = $("#pfc");
                subType.empty();
                subType.append($('<option></option>').
                        text("Select One"));
                $.each(items, function() {
                    subType.append($('<option></option>').attr("value", this.id).
                            text(this.pfc_val));
                });
            }
            function fetchPFC() {
                var sfc = $('#sfc :selected').val();
                var query = "/ALDWebsite/getpfc.php?sfc_id=" + sfc;
                $.ajax({
                    url: query,
                    type: "GET",
                    contentType: 'application/json; charset=utf-8',
                    dataType: "json",
                    success: function(data) {
                        populatePFC(data);
                    },
                    error: function(data) {

                        // alert("Some Error Occured ");
                        // alert(data.message);
                    }
                });
            }

            function populateSFC(data) {
                //debugger;
                var $subType = $("#sfc");
                $subType.empty();
                $subType.append($('<option></option>').
                        text("Select One"));
                var items = data.items;
                $.each(items, function() {
                    $subType.append($('<option></option>').attr("value", this.id).
                            text(this.sfc_val));
                });
            }

            function fetchSFC()
            {
                var mfc = $('#mfc :selected').val();
                var query = "/ALDWebsite/getSFC.php?mfc_id=" + mfc;

                $.ajax({
                    url: query,
                    type: "GET",
                    contentType: 'application/json; charset=utf-8',
                    dataType: "json",
                    success: function(data) {
                        populateSFC(data);
                    },
                    error: function(data) {

                        //alert("Some Error Occured ");
                        //alert(data.message);
                    }
                });
            }

            function populateMFC(data) {
                var $subType = $("#mfc");
                //$subType.empty();
                var items = data.items;
                $.each(items, function() {
                    $subType.append($('<option></option>').attr("value", this.id).
                            text(this.mfc_val));
                });
            }

            function fetchMFC() {
                $.ajax({
                    url: "/ALDWebsite/getMFC.php",
                    type: "GET",
                    contentType: 'application/json; charset=utf-8',
                    dataType: "json",
                    success: function(data) {
                        populateMFC(data);

                    },
                    error: function() {
                        //alert("Some Error Occured");
                        //location.reload(true);
                    }
                });
            }

            $(document).ready(function() {
                fetchMFC();
            });
            $(function() {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1; //January is 0!

                var yyyy = today.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }
                today = mm + '/' + dd + '/' + yyyy;
                //document.write(today);
                $("#datepicker_t").val(mm+'/'+dd+'/'+yyyy);
                $("#datepicker_t").datepicker();
            });
        </script>
    </head>
    <body>
        <?php
        include_once 'header.php';
        ?>
        <div class="well-large">
            Major Food Category <select id="mfc" name="mfc_items" onchange ="fetchSFC();">
                <option>Select One</option>
            </select> 
            Sub Category <select id="sfc" name="sfc_items" onchange="fetchPFC();" >
                <option> Select One</option>
            </select>
            Primary Category <select id="pfc" name="pfc_items" onchange="fetchFood();">
                <option> Select One</option>
            </select>
        </div>
        <p class="well"> <i class="icon-calendar"></i> Date <input type="text" id="datepicker_t" />
            <i class="icon-glass"></i> Meal: <select name="meal_type" id="meal_t">
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
                <option value="Snacks">Snacks</option>
            </select>
        </p>
        <div class="well-large">
            <legend>Select items:</legend>
            <table id="food_items" class="table-bordered table-hover table-striped" style="width: 100%">
                <th>Name</th><th>Total Fat</th><th>Sat Fat</th><th>Trans Fat</th><th>Chol</th><th>Sodium
                <th>Total Carb</th><th>Diet Fiber</th><th>Sugar</th>
                <th>Protein</th><th>Calcium</th><th>Potassium</th><th>Alcohol</th>
                <th>Calories</th><th>Portions</th>
            </table>
        </div>

        <div class="well">
            <legend>Your diet history</legend>
            <table id="diet_history" class="table table-striped table-condensed table-hover">
                <th>Food</th><th>Date</th><th>Meal</th>
            </table>
        </div>

    </body>
</html>
