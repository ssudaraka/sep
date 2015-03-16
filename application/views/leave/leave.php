<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>LEAVE STATUS</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Available</th>
                                <th>Taken</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Casual</td>
                                <td><?php echo $casual_leaves ?></td>
                                <td><?php echo $applied_casual_leaves ?></td>
                            </tr>
                            <tr>
                                <td>Medical</td>
                                <td><?php echo $medical_leaves ?></td>
                                <td><?php echo $applied_medical_leaves ?></td>
                            </tr>
                            <tr>
                                <td>Duty</td>
                                <td><?php echo $duty_leaves ?></td>
                                <td><?php echo $applied_duty_leaves ?></td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td><?php echo $other_leaves ?></td>
                                <td><?php echo $applied_other_leaves ?></td>
                            </tr>
                            <tr>
                                <td>Maternity</td>
                                <td><?php echo $maternity_leaves ?></td>
                                <td><?php echo $applied_maternity_leaves ?></td>
                            </tr>
                            <tr>
                                <td><b>Total</b></td>
                                <td></td>
                                <td><b><?php echo $total_leaves ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>APPLY FOR LEAVE</strong>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <?php
                        // Change the css classes to suit your needs

                        $attributes = array('class' => '', 'id' => '');
                        echo form_open('leave/apply_leave', $attributes);
                        ?>

                        <div class="row" style="margin-bottom:5px;">
                            <div class="col-xs-6 col-md-4">
                                <b>Leave Type</b>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <b>Start Date</b>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <b>End Date</b>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:15px;">
                            <div class="col-xs-6 col-md-4">
                                <?php
                                echo "<select class='form-control'>" . PHP_EOL;
                                foreach ($leave_types as $row) {
                                    echo "<option value='" . $row->id . "'>" . $row->name . "</option>" . PHP_EOL;
                                }
                                echo "</select>" . PHP_EOL;
                                ?>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <input class="form-control" placeholder="Start Date" name="startdate"   type="date">
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <input class="form-control" placeholder="End Date" name="enddate"   type="date">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:5px;">
                            <div class="col-xs-12 col-md-8"><b>Reason</b></div>
                            <div class="col-xs-6 col-md-4"></div>
                        </div> 						
                        <div class="row">
                            <div class="col-xs-12 col-md-8"><textarea class="form-control" rows="3"></textarea></div>
                            <div class="col-xs-6 col-md-4"><button type="submit" class="btn btn-primary">Apply</button></div>
                        </div>
<?php echo form_close(); ?>
                    </div>			
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>LEAVE REQUESTS</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Leave Type</th>
                                <th>Applied Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>No Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($applied_leaves as $row) {
                                echo "<tr>" . PHP_EOL;
                                echo "<td scope='row'><span class='glyphicon glyphicon-folder-close'></span></td>";
                                echo "<td>" . $row->name . "</td>" . PHP_EOL;
                                echo "<td>" . $row->applied_date . "</td>" . PHP_EOL;
                                echo "<td>" . $row->start_date . "</td>" . PHP_EOL;
                                echo "<td>" . $row->end_date . "</td>" . PHP_EOL;
                                echo "<td>" . $row->no_of_days . "</td>" . PHP_EOL;



                                if ($row->status == "Pending") {
                                    echo "<td><span class='label label-primary'>" . $row->status . "</span></td>" . PHP_EOL;
                                } elseif ($row->status == "Approved") {
                                    echo "<td><span class='label label-success'>" . $row->status . "</span></td>" . PHP_EOL;
                                } else {
                                    echo "<td><span class='label label-danger'>" . $row->status . "</span></td>" . PHP_EOL;
                                }
                                echo "</tr>" . PHP_EOL;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


