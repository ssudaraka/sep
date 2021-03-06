<div class="container">

    <div class="row">

        <div class="col-md-3">
            <?php $this->view('student/sidebar_nav'); ?>
        </div>

        <div class="col-md-9">
            <?php if ($this->session->flashdata('succ_message')) { ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('succ_message'); ?>
                </div>
            <?php } ?>
            <?php if ($this->session->flashdata('err_message')) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('err_message'); ?>
                </div>
            <?php } ?>

            <div class="progress" style="border: ">
                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:5%">
                    0% Complete (success)
                </div>
            </div>
            <br>

            <div >


                <?php
                // Change the css classes to suit your needs    

                $attributes = array('class' => 'formCon', 'id' => '');
                echo form_open_multipart('student/create_student', $attributes);
                ?>

                <div class="panel panel-warning" style="background-color: #fef7ee">
                    <div class="panel-body" >
                        <div class="row ">
                            <div class="col-md-3 col-md-push-1  form-group">

                                <label for="admissionnumber">Admission No</label>
                                <input type="text" name="admissionnumber" value="<?php echo set_value('admissionnumber'); ?>" class="form-control warning " id="admissionnumber" >
                                <div><?php echo form_error('admissionnumber'); ?></div>

                            </div>
                            <div class="col-md-3 col-md-offset-4 form-group">

                                <label for="addmissiondate">Admission Date</label>
                                <input type="date" name="admissiondate" value="<?php echo set_value('admissiondate'); ?>" class="form-control" id="addmissiondate">
                                <div><?php echo form_error('admissiondate'); ?></div>
                            </div>



                        </div>
                    </div>
                </div>    

                <div class="panel  panel-default"  >
                    <div class="panel-heading panel-default " >
                        <b>ADMISSION / Personal Details</b>
                    </div>
                    <div class="panel panel-body" >

                        <!-- first row-->
                        <div class="row">


                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" value="<?php echo set_value('firstname'); ?>" class="form-control" id="firstname" placeholder="First name">
                                <div><?php echo form_error('firstname'); ?></div>

                            </div>
                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" value="<?php echo set_value('lastname'); ?>" class="form-control" id="lastname" placeholder="Last Name">
                                <div> <?php echo form_error('lastname'); ?></div>

                            </div>

                        </div>
                        <!-- secound row-->
                        <div class="row">


                            <div class="col-md-5 col-md-push-1  form-group">

                                <label for="initials">Name With Initials</label>
                                <input type="text" name="initials" value="<?php echo set_value('initials'); ?>" class="form-control" id="initials" placeholder="Name With Initials">
                                <div><?php echo form_error('initials'); ?></div>

                            </div>   
                        </div>
                        <!-- Third row-->
                        <div class="row">



                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" value="<?php echo set_value('dob'); ?>" class="form-control " id="dob" placeholder="DOB">
                                <div> <?php echo form_error('dob'); ?></div>
                            </div>
                            <div class="col-md-3 col-md-push-1 form-group">

                                <label for="nic">NIC No</label>
                                <input type="text" name="nic" value="<?php echo set_value('nic'); ?>" class="form-control" id="nic" placeholder="NIC No">
                                <div><?php echo form_error('nic'); ?></div>

                            </div>
                        </div>
                        <!-- Forth row-->
                        <div class="row">


                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="language">Medium</label>
                                <select  name="language" id="language" class="form-control">
                                    <option value="n"<?php if (set_value('language') == "n") {
                    echo"selected";
                } ?>>Select Medium</option>
                                    <option value="s"<?php if (set_value('language') == "s") {
                    echo"selected";
                } ?>>Sinhala</option>
                                    <option value="e"<?php if (set_value('language') == "e") {
                    echo"selected";
                } ?>>English</option>
                                    <option value="t"<?php if (set_value('language') == "t") {
                    echo"selected";
                } ?>>Tamil</option>
                                </select>
                                <div><?php echo form_error('language'); ?></div>

                            </div>
                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="religion">Religion</label>
                                <select  name="religion" id="religion" class="form-control">
                                    <option value="0" <?php if (set_value('religion') == 0) {
                    echo"selected";
                } ?>>Select Religion</option>
                                    <option value="1" <?php if (set_value('religion') == 1) {
                    echo"selected";
                } ?>>Buddhism</option>
                                    <option value="2" <?php if (set_value('religion') == 2) {
                    echo"selected";
                } ?>>Hinduism</option>
                                    <option value="3" <?php if (set_value('religion') == 3) {
                    echo"selected";
                } ?>>Islam</option>
                                    <option value="4" <?php if (set_value('religion') == 4) {
                    echo"selected";
                } ?>>Catholicism</option>
                                    <option value="5" <?php if (set_value('religion') == 5) {
                    echo"selected";
                } ?>>Christianity</option>
                                    <option value="6" <?php if (set_value('religion') == 6) {
                    echo"selected";
                } ?>>Other</option>
                                </select>
                                <div><?php echo form_error('religion'); ?></div>
                            </div>
                        </div>
                        <!-- Fifth row-->
                        <div class="row">

                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="house">House</label>
                                <select  name="houseid" id="houseid" class="form-control">
                                    <option value="0" <?php if (set_value('houseid') == 0) {
                    echo"selected";
                } ?>>Select House</option>
                                    <option value="1" <?php if (set_value('houseid') == 1) {
                    echo"selected";
                } ?>>H1</option>
                                    <option value="2" <?php if (set_value('houseid') == 2) {
                    echo"selected";
                } ?>>H2</option>
                                    <option value="3" <?php if (set_value('houseid') == 3) {
                    echo"selected";
                } ?>>H3</option>
                                    <option value="4" <?php if (set_value('houseid') == 4) {
                    echo"selected";
                } ?>>H4</option>
                                </select>
                                <div><?php echo form_error('houseid'); ?></div>
                            </div>

                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="admission_grade">Admission Grade</label>
                                <select class="form-control" name='admission_grade' id="admission_grade">
<?php
$grades = $this->class_model->get_grades(); ?>
<?php foreach ($grades as $grade) { ?>
                                        <option value="<?php echo $grade->id; ?>" <?php echo set_select('grade', $grade->id); ?>><?php echo $grade->name; ?></option>
<?php } ?>
                                </select>
                                <div> <?php echo form_error('admission_grade'); ?></div>

                            </div>

                        </div>
                        <!-- sixth row-->
                        <div class="row">
                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="contact_home">Contact No</label>
                                <input type="text" name="contact_home" value="<?php echo set_value('contact_home'); ?>" class="form-control " id="contact_home" placeholder="Contact No">
                                <div><?php echo form_error('contact_home'); ?></div>
                            </div>
                            <div class="col-md-5 col-md-push-1 form-group">

                                <label for="email">Email</label>
                                <input type="email" name="email"  value="<?php echo set_value('email'); ?>" class="form-control" id="email" placeholder="Email">
                                <div> <?php echo form_error('email'); ?></div>

                            </div>
                        </div>
                        <!-- seventh row-->
                        <div class="row">

                            <div class="col-md-5 col-md-push-1  form-group">

                                <label for="address">Permenent Address</label>
                                <textarea name="address" value="" class="form-control" id="address"><?php echo set_value('address'); ?></textarea>
                                <div><?php echo form_error('address'); ?></div>

                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-md-push-1">

                                <button type="submit" class="btn btn-success ">Next </button>

                            </div>
                            <div class="col-md-1 col-md-push-1">

                                <button type="reset" class="btn btn-default">Reset </button>

                            </div>
                        </div>   


                    </div>

                </div>       
<?php echo form_close(); ?>


            </div>
        </div>
    </div>

</div>

</div>


