<?php
?>
<script type="text/javascript">

$(document).ready(function() 

{	

	"use strict";

    $('#curr_date').datepicker({

        maxDate:'0',

        dateFormat: "yy-mm-dd",
		
        beforeShow: function (textbox, instance) 

        {

            instance.dpDiv.css({

                marginTop: (-textbox.offsetHeight) + 'px'                   

            });

        }

	});

}); 

</script>
<?php
if(get_option('smgt_enable_video_popup_show') == 'yes')
{
?>
<a href="#" class="view_video_popup youtube-icon" link="<?php echo "https://www.youtube.com/embed/Ed5SkDCKiu4?si=4rsfAczrulo_l8if";?>" title="Student Attendance With QR Code">
	<img src="<?php echo SMS_PLUGIN_URL."/assets/images/youtube-icon.png" ?>" alt="YouTube">
</a>
<?php
}
?>
<div class="panel-body attendence_penal_body">

    <form method="post">

        <div class="form-body user_form"> <!-- user_form Strat-->

            <div class="row"><!--Row Div Strat-->

                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">

					<div class="form-group input">

						<div class="col-md-12 form-control">

                        <input id="curr_date" class="form-control date_picker qr_date" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  mj_smgt_getdate_in_input_box(date("Y-m-d"));?>" name="curr_date" readonly>		

                        <label class="l date_label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>

						</div>

					</div>

				</div>

                <!-- <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">

                    <label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			

                    <?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>                 

                    <select name="class_id"  id="class_list"  class="form-control validate[required] qr_class_id">

                        <option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>

                            <?php 

                            foreach(mj_smgt_get_allclass() as $classdata)

                            {  

                                ?>

                                <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>

                                <?php 

                            }?>

                    </select>			

                </div>  

				-->

				

				<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			



							<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>



							<select name="class_id"  id="class_list"  class="line_height_30px form-control validate[required] qr_class_id">



								<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>



								<?php



								foreach(mj_smgt_get_allclass() as $classdata)



								{ ?>



									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>



									<?php 



								}?>



							</select>			



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>			



							<?php 



							$class_section="";



							if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>



							<select name="class_section" class="line_height_30px form-control qr_class_section" id="class_section">



							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>



								<?php if(isset($_REQUEST['class_section'])){



								$class_section=$_REQUEST['class_section']; 



									foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)



									{  ?>



										<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>



									<?php } 



									} ?>		



							</select>



						</div>



						<div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 input">



							<label class="ml-1 custom-top-label top" for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field"></span></label>



							<select name="sub_id"  id="subject_list"  class="line_height_30px form-control validate[required] qr_class_subject">



								<option value=" "><?php esc_attr_e('Select Subject','school-mgt');?></option>



								<?php $sub_id=0;



								if(isset($_POST['sub_id']))



								{



									$sub_id=$_POST['sub_id'];



									?>



									<?php $allsubjects = mj_smgt_get_subject_by_classid($_POST['class_id']);



									foreach($allsubjects as $subjectdata)



									{ ?>



										<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>



										<?php



									}



								} ?>



							</select>			



						</div>



				

            </div>

        </div> 

            <script type="text/javascript" src="<?php echo SMS_PLUGIN_URL. '/lib/jsqrscanner/jsqrscanner.nocache.js'; ?>"></script>

            <div class="panel-heading">

                <h4 class="panel-title"><?php _e('Scan QR Code To Take Attendance','school-mgt');?> 			

            </div>

            <div class="col-md-12">

             <div class="qrscanner" id="scanner">

             </div>

                <hr>



                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">

  		        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

               <script type="text/javascript">





								function onQRCodeScanned(result)

								{

								    

								       const result_obj = JSON.parse(result);

                                        var user_id = result_obj.user_id;

                                        var user_class_id = result_obj.class_id;
										
                                        var user_section_id = result_obj.section_id;

                                        var qr_code_name = result_obj.qr_type;

                                        

                                        if (qr_code_name == 'schoolqr')

                                        {
    										var selected_class_id = $(".qr_class_id").val();

    										var selected_class_section = $(".qr_class_section").val();

    										var selected_class_subject = $(".qr_class_subject").val();

    										var qr_date = $(".qr_date").val();

    										var attendance_url=user_id+'_'+user_class_id+'_'+qr_date+'_'+user_section_id+'_'+selected_class_id+'_'+selected_class_subject+'_'+selected_class_section;

    										var serch = attendance_url.search("data");

    										if(user_class_id != " ")

    										{
												if(user_class_id == selected_class_id && selected_class_id !="")
												{

													if(qr_date != " ")

													{

														var myString = attendance_url.substr(attendance_url.indexOf("=") + 1)

														

														$.ajax({

		

															type: "POST",  

		

															url: "<?php echo admin_url('admin-ajax.php'); ?>",

		

															data: { action: 'MJ_smgt_qr_code_take_attendance',attendance_url:myString},

		

															dataType: "json",

		

															complete: function (e)

															{

																if(e.responseText == 1)

																{

		

																swal("<?php esc_html_e('Success!','school-mgt'); ?>", "<?php esc_html_e('Attendance successfully','school-mgt'); ?>", "success");



																return true;

																}

																else if(e.responseText == 2)

																{

		

																swal("<?php esc_html_e('Oops!','school-mgt'); ?>", "<?php esc_html_e('Please select correct class!','school-mgt'); ?>", "error");

																return true;


																}

																else if(e.responseText == 3)

																{

		

																swal("<?php esc_html_e('Oops!','school-mgt'); ?>", "<?php esc_html_e('Student Not Found!','school-mgt'); ?>", "error");

																return true;

																}

																else

																{

		

																	swal("<?php esc_html_e('Oops!','school-mgt'); ?>", "<?php esc_html_e('Something went wrong, you should choose again!','school-mgt'); ?>", "error");

																return true;

																}

		

															}

		

														});	

		

													}

		

													else

		

													{

		

													

														swal("<?php esc_html_e('Warning!','school-mgt'); ?>", "<?php esc_html_e('Please select date!','school-mgt'); ?>", "warning");

														return true;

													}

												}
												else
												{

													swal("<?php esc_html_e('Warning!','school-mgt'); ?>", "<?php esc_html_e('Selected class not match to student class!','school-mgt'); ?>", "warning");

													return true;

												}

    					                 	}

    										else

    										{

    

    										swal("<?php esc_html_e('Warning!','school-mgt'); ?>", "<?php esc_html_e('Please select class!','school-mgt'); ?>", "warning");

                                            return true;

    						                 }

										

                                        }

                                        else if (result == 'Invalid constraint')

                                        {

                                        }

                                        else if (result == 'Requested device not found')

                                        {

                                            swal("<?php esc_html_e('Oops!','school-mgt'); ?>", "<?php esc_html_e('Camera device not found!','school-mgt'); ?>", "error"); 

                                         return true;

                                            

                                        }

                                        else

                            			{

                            		    	swal("<?php esc_html_e('Oops!','school-mgt'); ?>", "<?php esc_html_e('QR code does not match, you should choose again!','school-mgt'); ?>", "error"); 

                                        return true;

                            			}



							    	}



									function JsQRScannerReady()

									{



										//create a new scanner passing to it a callback function that will be invoked when



										//the scanner succesfully scan a QR code



										var jbScanner = new JsQRScanner(onQRCodeScanned);



										//reduce the size of analyzed images to increase performance on mobile devices



										jbScanner.setSnapImageMaxSize(200);



										var scannerParentElement = document.getElementById("scanner");



										if(scannerParentElement)



										{



											//append the jbScanner to an existing DOM element



											jbScanner.appendTo(scannerParentElement);



										}        



									}



							</script> 

            </div>   

    </form>

</div>