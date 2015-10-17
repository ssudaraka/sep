<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-ellipsis-h" style="margin-right:10px"></i> NEED YOUR ATTENTION</strong>
                </div>
                <div class="panel-body">
                    <div class="row" style="padding:20px">

                        <div id="big_stats" class="cf">
                            <div class="stat"> <i class="fa fa-bed"></i> <span class="value">8</span>
                                <br/>Leaves
                            </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="glyphicon glyphicon-bullhorn"></i> 
                                <span class="value">
                                    <?php
                                    $cnt = 0;
                                    foreach ($count as $row) {
                                        $cnt = $cnt + 1;
                                    }
                                    echo $cnt;
                                    ?>
                                </span>
                                <br/>Events
                            </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="fa fa-newspaper-o"></i> <span class="value">22</span>
                                <br/>News
                            </div>
                            <!-- .stat -->

                            <div class="stat"> <i class="fa fa-envelope"></i> <span class="value">25</span>
                                <br/>Messages
                            </div>
                            <!-- .stat --> 
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-newspaper-o" style="margin-right:10px"></i> RECENT NEWS</strong>
                </div>
                <div class="panel-body">
                    <ul class="news-items">
                        <?php
                        $cnt = 0;
                        foreach ($news as $row) {
                            $cnt = $cnt + 1;
                            ?>
                            <?php if ($cnt < 3) { ?>
                                <li>
                                    <div class = "media">
                                        <div class="pull-right">
                                            <small>Published on <?php echo $row->create_at ?></small>
                                        </div>
                                        <div class = "media-body">
                                            <strong><?php echo $row->name ?></strong>
                                            <p class="news-item-preview"><?php echo $row->description ?></p>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        <li>
                            <a href=""  id="fillgrid" title='view'>view more</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><i class="fa fa-exchange" style="margin-right:10px"></i> ACTIVITY FEED</strong>
                </div>
                <div class="panel-body">
                    <!-- Activity Feed element -->
                    <?php
                    $add = 0;
                    foreach ($activity as $row) {
                        $add = $add + 1;
                        if ($add < 3) {
                            ?>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="thumbnail">
                                        <img class="img-responsive user-photo" src="<?php echo $row->pro_img; ?>">
                                    </div>
                                </div><!-- /col-sm-1 -->

                                <div class="col-sm-10">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><?php echo $row->user_fullname; ?></strong> <span class="text-muted">
                                                <div class="pull-right"> <?php echo $row->created_at; ?></span></div>
                                    </div>
                                    <div class="panel-body">
        <?php echo $row->content; ?>
                                    </div><!-- /panel-body -->
                                </div><!-- /panel panel-default -->
                            </div><!-- /col-sm-5 -->
                        </div>
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class="fa fa-calendar" style="margin-right:10px"></i> UPCOMING EVENTS</strong>
            </div>
            <div class="panel-body">
                <ul class="news-items">
<?php foreach ($count as $row) { ?>
                        <li>
                            <div class="news-item-date"> <span class="news-item-day">
                                    <?php
                                    $day = $row->start_date;
                                    $get_date = explode("-", $day);
                                    echo $get_date[2];
                                    ?>
                                </span> <span class="news-item-month">
                                    <?php
                                    if ($get_date[1] == 1) {
                                        echo 'Jan';
                                    } else if ($get_date[1] == 2) {
                                        echo 'Feb';
                                    } else if ($get_date[1] == 3) {
                                        echo 'Mar';
                                    } else if ($get_date[1] == 4) {
                                        echo 'Apr';
                                    } else if ($get_date[1] == 5) {
                                        echo 'may';
                                    } else if ($get_date[1] == 6) {
                                        echo 'Jun';
                                    } else if ($get_date[1] == 7) {
                                        echo 'Jul';
                                    } else if ($get_date[1] == 8) {
                                        echo 'Aug';
                                    } else if ($get_date[1] == 9) {
                                        echo 'Sep';
                                    } else if ($get_date[1] == 10) {
                                        echo 'Oct';
                                    } else if ($get_date[1] == 11) {
                                        echo 'Nov';
                                    } else {
                                        echo 'Dec';
                                    }
                                    ?>
                                </span> </div>
                            <div class="news-item-detail"> <a href="<?php echo base_url("index.php/event/view_upcoming_event_details") . "/" . $row->id; ?>" class="news-item-title" target="_blank"><?php echo $row->title; ?></a>
                                <p class="news-item-preview"><?php echo $row->description; ?></p>
                            </div>
                        </li>
<?php } ?>

                    <li>

                        <div class="news-item-date"> <span class="news-item-day"></span> <span class="news-item-month"></span> </div>
                        <div class="news-item-detail"> <i class="news-item-title" target="_blank">No more events</i>
                            <p class="news-item-preview"></p>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        var btnedit = '';
        btnedit = $("#fillgrid");
        btnedit.on('click', function (e) {
            e.preventDefault();
            $.colorbox({
                href: "<?php echo base_url() ?>index.php/dashboard/get_news",
                top: 50,
                width: 700,
                onClosed: function () {
                    fillgrid();
                }
            });
        });

    });
</script>