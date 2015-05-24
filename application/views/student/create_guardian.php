<div class="container">

    <div class="row">

        <div class="col-md-3">
            <?php $this->view('student/sidebar_nav'); ?>
        </div>

        <div class="col-md-9">
            
            <div class="row">
                <ul class="nav nav-tabs ">
                    <li role="presentation" ><a href="#">Student Details</a></li>
                  <li role="presentation" class="active"><a href="#">Guardian Details</a></li>
                  <li role="presentation" ><a href="#">Profile</a></li>
                </ul>
            </div>
            <br>
           
            <div >
                
              
               
                    <?php
                    // Change the css classes to suit your needs    

                    $attributes = array('class' => 'formCon', 'id' => '');
                    echo form_open('student/create_guardian', $attributes);
                    ?>
                   
                <div class="panel panel-warning" style="background-color: #fef7ee">
                    <div class="panel-body" >
                    <div class="row ">
                    
                   
                    <div class="col-md-3 col-md-push-1  form-group">
                        
                                <label for="studentid">Student No</label>
                                <input type="text" name="studentid" value="<?php echo $row->user_id; ?>" class="form-control warning " id="admissionnumber" readonly>
                                <div><?php echo form_error('studentid'); ?></div>
                                
                     </div>
                     <div class="col-md-3 col-md-offset-4 form-group">
                                
                                <label for="studentname">Student Name</label>
                                <input type="text" name="studentname" value="<?php echo $row->name_with_initials;?>" class="form-control" id="addmissiondate" readonly>
                                <div><?php echo form_error('studentname'); ?></div>
                                
                    </div>
                     
                   
                    
                      </div>
                     </div>
                </div>    
          
             <div class="panel  panel-default"  >
                 <div class="panel-heading panel-default " >
                    GUARDIAN DETAILS
                </div>
                 <div class="panel panel-body" >
                 
                <!-- first row-->
                 <div class="row">
                     
                    
                    <div class="col-md-5 col-md-push-1 form-group">
                                
                                <label for="fullname">First Name</label>
                                <input type="text" name="fullname" value="<?php echo set_value('fullname'); ?>" class="form-control" id="fullname" placeholder="Full Name" >
                                <div><?php echo form_error('fullname'); ?></div>
                                
                     </div>
                     <div class="col-md-5 col-md-push-1 form-group">
                                
                                <label for="initials">Name With Initials</label>
                                <input type="text" name="initial" value="<?php echo set_value('initial'); ?>" class="form-control" id="initial" placeholder="Name with Initials">
                                <div> <?php echo form_error('initial'); ?></div>
                                
                    </div>
                     
                </div>
                <!-- secound row-->
                <div class="row">
                    
                    
                     <div class="col-md-5 col-md-push-1  form-group">
                                
                                <label for="relation">Relation</label>
                                <select  name="relation" id="relation" class="form-control">
                                 <option value="n">Select Your Relation</option>
                                    <option value="f">Father</option>
                                    <option value="m">Mother</option>
                                    <option value="g">Guardian</option>
                                    
                                 </select>
                                
                                <div><?php echo form_error('relation'); ?></div>
                     </div>   
                </div>
                <!-- third row-->
                 <div class="row">
                     
                     
                    <div class="col-md-5 col-md-push-1 form-group">
                                
                                <label for="contact_home">Contact No</label>
                                <input type="text" name="contact_home" value="<?php echo set_value('contact_home'); ?>" class="form-control " id="contact_home" placeholder="Contact No">
                                <div><?php echo form_error('contact_home'); ?></div>
                                
                     </div>
                     <div class="col-md-5 col-md-push-1 form-group">
                               
                                <label for="contact_mobile">Contact Mobile</label>
                                <input type="text" name="contact_mobile" value="<?php echo set_value('contact_mobile'); ?>" class="form-control" id="contact_mobile" placeholder="Contact Moblile">
                                <div> <?php echo form_error('contact_mobile'); ?></div>
                                
                     </div>
                </div>
                <!-- fourth row-->
                <div class="row">
                   
                    
                    
                    <div class="col-md-5 col-md-push-1 form-group">
                                
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" value="<?php echo set_value('dob'); ?>" class="form-control " id="dob" placeholder="DOB">
                                <div> <?php echo form_error('dob'); ?></div>
                                
                     </div>
                    
                </div>
                
                   
                    
                 <div class="row">
                   <div class="col-md-3 col-md-push-1 form-group">
                    <label for="gender" >Gender</label>
                    </div>
                    <div class="col-sm-3">
                         
                            <label class="radio-inline">
                                <input id="male" type="radio" name="gender"  value="m" type="radio"  id="male"> Male
                            </label>
                            <label class="radio-inline">
                                <input id="female" type="radio" name="gender"  value="f" type="radio" id="female"> Female
                            </label>
                            <?php echo form_error('gender'); ?>
                        </div>
                   
                </div>
                    
            
                 <!-- Fifth row-->
                 <div class="row">
                     <div class="col-md-5 col-md-push-1 form-group">
                                <div><?php echo form_error('occupation'); ?></div>
                                <label for="occupation">Contact No</label>
                                <input type="text" name="occupation" value="<?php echo set_value('occupation'); ?>" class="form-control " id="occupation" placeholder="Occupation">
                     </div>
                     
                    
                   
                </div>
               
                 
                  <!-- Sixth row-->
                <div class="row">
                    
                     <div class="col-md-5 col-md-push-1  form-group">
                                <div><?php echo form_error('address'); ?></div>
                                <label for="address">Permenent Address</label>
                                <textarea name="address" value="<?php echo set_value('address'); ?>" class="form-control" id="address"></textarea>
                                
                     </div>   
                </div>
                 
                
                <!-- Seventh row-->
                 <div class="row">
                     <div class="col-md-5 col-md-push-1 form-group">
                                <div class="checkbox">
                                       <label>
                                         <input type="checkbox" name="pastpupil" id="pastpupil">Is a Past Pupil Of this Institute
                                       </label>
                                </div>
                     </div>
                     
                    
                   
                </div>  
               
                 <div class="row">
                     <div class="col-md-1 col-md-push-1">
                         
                       <button type="submit" class="btn btn-success ">Next </button>
                       
                     </div>
                     <div class="col-md-2 col-md-push-1">
                         
                       <button type="reset" class="btn btn-default ">Reset </button>
                       
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

