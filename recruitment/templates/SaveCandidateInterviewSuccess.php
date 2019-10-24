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
<?php
$hasAttachments = count($attachmentList) > 0;
if (isset($_GET['ATT_UPLOAD']) && $_GET['ATT_UPLOAD'] == 'FAILED') {
    echo "alert('" . __("Upload Failed") . "');";
}
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

    </div>
    <div id="status"></div>

    <div class="outerbox">
 
            <div class="mainHeading"><h2><?php echo __("Interview Information") ?></h2></div>
            <?php echo message(); ?>
        <form name="frmSave" id="frmSave" method="post"  action="" enctype="multipart/form-data">
            <div class="leftCol">
                &nbsp;
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labVacanyTitle"><?php echo __("Vacancy Title") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtVacancyTitle"  name="txtVacancyTitle"  disabled="true" type="text"  class="formInputText" value="<?php
        if ($userCulture == 'en') {
            echo $candidateGetById->VacancyRequisition->rec_req_vacancy_title;
        } elseif ($userCulture == 'si') {
            if (( $candidateGetById->VacancyRequisition->rec_req_vacancy_title_si) == null) {
                echo $candidateGetById->VacancyRequisition->rec_req_vacancy_title;
            } else {
                echo $candidateGetById->VacancyRequisition->rec_req_vacancy_title_si;
            }
        } elseif ($userCulture == 'ta') {
            if (( $candidateGetById->VacancyRequisition->rec_req_vacancy_title_ta) == null) {
                echo $candidateGetById->VacancyRequisition->rec_req_vacancy_title;
            } else {
                echo $candidateGetById->VacancyRequisition->rec_req_vacancy_title_ta;
            }
        }
        ?>" maxlength="15" />

                <input id="txtHiddenReqID"  name="txtHiddenReqID" type="hidden"  class="inputText" value="<?php echo $candidateGetById->rec_can_id; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labRefNumber"><?php echo __("Ref Number") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtRefNumber"  name="txtRefNumber"  disabled="true" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_reference_no; ?>" maxlength="15" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labNicNumber"><?php echo __("NIC Number") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtNicNumber"  name="txtNicNumber" type="text"  disabled="true" class="formInputText" value="<?php echo $candidateGetById->rec_can_nic_number; ?>" maxlength="15" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labCandidateName"><?php echo __("Candidate Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtCandidateName"  name="txtCandidateName"  disabled="true" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_candidate_name; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labAddress"><?php echo __("Address") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtAddress"  name="txtAddress"  disabled="true"  class="formTextArea" rows="3" cols="5" ><?php echo $candidateGetById->rec_can_address ?></textarea>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labTelNumber"><?php echo __("Tel.No") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtTelNumber"  name="txtTelNumber" disabled="true" type="text" class="formInputText"value="<?php echo $candidateGetById->rec_can_tel_number; ?>" maxlength="15" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labmarks"><?php echo __("Interview Marks") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtMarks"  name="txtMarks" type="text" style="width: 52px;" class="formInputText"value="<?php echo $candidateGetById->rec_can_interview_marks; ?>" maxlength="5" />
            </div>
            <br class="clear"/>   
            <div class="leftCol">
                <label for="labmarks"><?php echo __("Status") ?> <span class="required">*</span></label>
            </div>
            <?php $currentConfirm = $candidateGetById->rec_can_interview_status; ?>
            <div class="centerCol" style="width: 500px;">
                <label style="width: 100px; padding-left: 2px;"><input type="radio" name="optrate" id="optrate1"   value="1
                              " <?php if ($currentConfirm == 1)
                echo "checked" ?> /><?php echo __("Selected"); ?></label>
                <label style="width: 100px; padding-left: 2px;"><input type="radio" name="optrate" id="optrate2"   value="2" <?php if ($currentConfirm == 2)
                                  echo "checked" ?> /><?php echo __("Not Selected") ?></label>
                <label style="width: 100px; padding-left: 4px;"><input type="radio" name="optrate" id="optrate3"   value="3" <?php if ($currentConfirm == 3)
                                  echo "checked" ?> /><?php echo __("Hold") ?></label>
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
<?php
$sysConf = OrangeConfig::getInstance()->getSysConf();
$dateHint = $sysConf->getDateInputHint();
$format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
?>
    $(document).ready(function() {
        
        $("#txtDOB").datepicker({ dateFormat: '<?php echo $dateHint; ?>',changeYear: true,changeMonth: true });

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
                txtMarks: {required: true, number: true ,noSpecialCharsOnly: false, maxlength:5 }               
            },
            messages: {
                txtMarks:{required: "<?php echo __("This field is required") ?>",maxlength: "<?php echo __("Maximum 5 Characters") ?>",number: "<?php echo __("Interview Marks is invalid") ?>"}                                                
            }
        });


        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        // When click edit button
        $("#editBtn").click(function() {
            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock

                location.href="<?php echo url_for('recruitment/SaveCandidateInterview?id=' . $encrypt->encrypt($candidateGetById->rec_can_id) . '&lock=1') ?>";
            }
            else {
                var marks=$("#txtMarks").val();
                if( marks > 100 || marks < 0 ){
                    alert('<?php echo __("Invalid Interview Marks") ?>');
                    return false; 
                }
                if (undefined === $("input[name='optrate']:checked").val()) {
                    alert('<?php echo __("Please select status") ?>');
                    return false;
                }else{
                    $('#frmSave').submit();
                }
            }
        });

        //When click reset buton
        $("#btnClear").click(function() {
            if($("#frmSave").data('edit') != 1){
                location.href="<?php echo url_for('recruitment/SaveCandidateInterview?id=' . $encrypt->encrypt($candidateGetById->rec_can_id) . '&lock=0') ?>";
            }else{
                document.forms[0].reset('');
            }
        });

        //When Click back button
        $("#btnBack").click(function() {
            <?php if($type=="HR"){ ?>
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/HRCandidateInterview')) ?>";
 
            <?php }else if($type=="DG"){ ?>
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/DGCandidateInterview')) ?>";
            
           <?php }else if($type=="CN"){ ?>
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/CandidatePIMRegistation')) ?>";
             
           <?php }else{ ?>     
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/CandidateInterview')) ?>";
            <?php } ?>
        });

    });
</script>
