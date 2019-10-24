<?php
if ($mode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
$encrypt = new EncryptionHandler();
?>
<?php
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<div class="formpage4col" >
    <div class="navigation">
        <style type="text/css">
            div.formpage4col input[type="text"]{
                width: 180px;
            }
        </style>
        <script type="text/javascript">
            var user="<?php echo $_SESSION['user']; ?>"
    
            if(user=="USR001"){                          
                alert("<?php echo __('Admin cannot create vacancy request.'); ?>");
                $('#frmSave :input').attr('disabled', true);
                location.href="<?php echo url_for('recruitment/VacancyRequest') ?>";                   
            }        
        </script>
    </div>
    <div id="status"></div>
    <div id="emp_details">
        <table style="background-color:white; border: 2px solid #FAD163; width: 98%; margin-left: 15px; margin-bottom: 3px;">
            <tr>
                <td rowspan="2">

                    <span id="Currentimage">
                        <img id="currentImage" style="width:50px; height:50px; padding-left:5px; padding-bottom: 0px; border:none;" alt="Employee Photo"
                             src="<?php echo url_for("pim/viewPhoto?id=" . $encrypt->encrypt($_SESSION['PIM_EMPID'])); ?>" />

                    </span>


                </td>
                <td>

                    <?php
                    $Culture = $_SESSION['symfony/user/sfUser/culture'];
                    $ESSDao = new ESSDao();
                    $Employee = $ESSDao->readEmployee($_SESSION['PIM_EMPID']);
                    if ($Culture == "en") {
                        $EName = "getEmp_display_name";
                    } else {
                        $EName = "getEmp_display_name_" . $Culture;
                    }
                    if ($Employee->$EName() == null) {
                        $empName = $Employee->emp_display_name;
                    } else {
                        $empName = $Employee->$EName();
                    }
                    ?>
                    <b><?php echo __('Name') ?> </b>  : <?php echo $empName; ?>
                </td>
                <td style="">
                    <b><?php echo __('Employee Id') ?></b>  : <?php echo $Employee->employeeId; ?>
                </td>
                <td>
                    <b><?php echo __('NIC No') ?></b>  : <?php echo $Employee->emp_nic_no; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if ($Culture == "en") {
                        $jobName = "name";
                    } else {
                        $jobName = "name_" . $Culture;
                    }
                    if ($Employee->jobTitle->$jobName == null) {
                        $joblanName = $Employee->jobTitle->name;
                    } else {
                        $joblanName = $Employee->jobTitle->$jobName;
                    }
                    ?>
                    <b><?php echo __('Designation') ?></b>  : <?php echo $joblanName; ?>
                </td>
                <td>

                    <?php
                    if ($Culture == "en") {
                        $unitName = "title";
                    } else {
                        $unitName = "title_" . $Culture;
                    }
                    if ($Employee->subDivision->$unitName == null) {
                        $UnitName = $Employee->subDivision->title;
                    } else {
                        $UnitName = $Employee->subDivision->$unitName;
                    }
                    ?>
                    <b><?php echo __('Work Station/Division') ?></b>  : <?php echo $UnitName; ?>
                </td>
                <td>

                    <?php
                    if ($Culture == "en") {
                        $unitName = "title";
                    } else {
                        $unitName = "title_" . $Culture;
                    }
                    if ($Employee->subDivision->$unitName == null) {
                        $UnitName = $Employee->subDivision->title;
                    } else {
                        $UnitName = $Employee->subDivision->$unitName;
                    }
                    ?>
                    <b><?php echo __('Date of join') ?></b>  : <?php echo $Employee->emp_com_date; ?>
                </td>

            </tr>
        </table>  
    </div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Vacancy Request") ?></h2></div>
        <?php echo message(); ?>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <div class="leftCol">
                &nbsp;
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("English") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Sinhala") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Tamil") ?></label>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labVacanyTitle"><?php echo __("Vacancy Title") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtVacanyTitle"  name="txtVacanyTitle" type="text"  class="formInputText" maxlength="100" value="<?php echo $vacancyRequestGetById->rec_vac_vacancy_title; ?>"/>
            </div>


            <div class="centerCol">
                <input id="txtVacanyTitleSi"  name="txtVacanyTitleSi" type="text"  class="formInputText" maxlength="100" value="<?php echo $vacancyRequestGetById->rec_vac_vacancy_title_si; ?>"/>

            </div>
            <div class="centerCol">
                <input id="txtVacanyTitleTa"  name="txtVacanyTitleTa" type="text"  class="formInputText" maxlength="100" value="<?php echo $vacancyRequestGetById->rec_vac_vacancy_title_ta; ?>"/>

            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Year") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="cmbYear"  name="cmbYear" type="text"  class="formInputText" value="<?php echo $vacancyRequestGetById->rec_vac_year; ?>" tabindex="1" MAXLENGTH=4 />

            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label for="labNoVacancies"><?php echo __("No of Vacancies") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtNoVacancies"  name="txtNoVacancies" type="text"  style="width: 52px;" class="formInputText" value="<?php echo $vacancyRequestGetById->rec_vac_no_of_vacancies; ?>" maxlength="5" />
                <input id="txtHiddenReqID"  name="txtHiddenReqID" type="hidden"  class="inputText" value="<?php echo $vacancyRequestGetById->rec_vac_req_id; ?>" maxlength="100" />

            </div>

            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                       value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"<?php echo $disabled; ?>
                       value="<?php echo __("Reset"); ?>" />
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" tabindex="10" />
            </div>
        </form>
    </div>
    <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
    <br class="clear" />
</div>


<script type="text/javascript">

    function validateYear(type) {        
        $(type).change(function() {
           
            if(this.value < <?php echo date("Y"); ?> ){
                alert("<?php echo 'Please enter valied year.' ?>");  
            }
            if(isNaN(this.value)){
                $(this).val("");
                alert("<?php echo 'Please Enter Numeric Values.' ?>");
                return false;
            }
        });
    }
    
    $(document).ready(function() {
        validateYear('input[name="cmbYear"]');

        buttonSecurityCommon(null,"editBtn",null,null);    
<?php if ($mode == 0) { ?>
            $('#editBtn').show();
            buttonSecurityCommon(null,null,"editBtn",null);

            $('#frmSave :input').attr('disabled', true);
            $('#editBtn').removeAttr('disabled');
            $('#btnBack').removeAttr('disabled');
<?php } ?>

        //Validate the form
        $("#frmSave").validate({

            rules: {
                txtVacanyTitle:{required: true,maxlength:50},
                txtVacanyTitleSi: {noSpecialCharsOnly: false, maxlength:50 },
                txtVacanyTitleTa: {noSpecialCharsOnly: false, maxlength:50 },
                txtRefNumber: { required: true,noSpecialCharsOnly: true, maxlength:15},
                cmbYear:{required: true,maxlength:4,minlength:4},
                txtNoVacancies: {required: true, digits: true,noSpecialCharsOnly: true, maxlength:15 }
            },
            messages: {
                txtVacanyTitle:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtVacanyTitleSi:{maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtVacanyTitleTa:{maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtRefNumber: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                cmbYear:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 4 Characters") ?>"},
                txtNoVacancies:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"}
                                        
            },submitHandler: function(form) {
                $('#editBtn').unbind('click').click(function() {return false}).val("<?php echo __('Wait..'); ?>");
                form.submit();
            }
        });


        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        // When click edit button
        $("#editBtn").click(function() {
            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock
                <?php if($vacancyRequestGetById->rec_vac_req_id!= null){ ?>
                location.href="<?php echo url_for('recruitment/SaveVacancyRequest?id=' . $encrypt->encrypt($vacancyRequestGetById->rec_vac_req_id) . '&lock=1') ?>";
                <?php }else{ ?>
                 location.href="<?php echo url_for('recruitment/SaveVacancyRequest') ?>";   
                <?php } ?>     
            }
            else {

                $('#frmSave').submit();
            }
        });

        //When click reset buton
        $("#btnClear").click(function() {
            if($("#frmSave").data('edit') != 1){
                <?php if($vacancyRequestGetById->rec_vac_req_id!= null){ ?>
                location.href="<?php echo url_for('recruitment/SaveVacancyRequest?id=' . $encrypt->encrypt($vacancyRequestGetById->rec_vac_req_id) . '&lock=0') ?>";
                <?php }else{ ?>
                location.href="<?php echo url_for('recruitment/SaveVacancyRequest') ?>";
                <?php } ?>
        }else{
                document.forms[0].reset('');
            }
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/VacancyRequestWork')) ?>";
        });

    });
</script>



