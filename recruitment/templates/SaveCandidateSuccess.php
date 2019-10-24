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
        <div class="mainHeading"><h2><?php echo __("Candidate Information Entry") ?></h2></div>
        <?php echo message(); ?>   
        <form enctype="multipart/form-data" name="frmSave" id="frmSave" method="post"  action="">
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
            <div class="centerCol" style="padding-top: 10px; padding-left: 10px;">
                <select name="cmbVacanyTitle" id="cmbVacanyTitle" style="width: 190px;">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($VacancyTitle as $VacancyTitle) {
                        ?>
                        <option value="<?php echo $VacancyTitle->rec_req_id; ?>" <?php
                    if ($VacancyTitle->rec_req_id == $candidateGetById->rec_req_id
                    )
                        echo "selected";
                        ?>> <?php
                            if ($userCulture == 'en') {
                                echo $VacancyTitle->rec_req_vacancy_title;
                            } elseif ($userCulture == 'si') {
                                if (($VacancyTitle->rec_req_vacancy_title_si) == null) {
                                    echo $VacancyTitle->rec_req_vacancy_title;
                                } else {
                                    echo $VacancyTitle->rec_req_vacancy_title_si;
                                }
                            } elseif ($userCulture == 'ta') {
                                if (($VacancyTitle->rec_req_vacancy_title_ta) == null) {
                                    echo $VacancyTitle->rec_req_vacancy_title;
                                } else {
                                    echo $VacancyTitle->rec_req_vacancy_title_ta;
                                }
                            }
                        ?></option>
                    <?php } ?>
                </select>
                <input id="txtHiddenReqID"  name="txtHiddenReqID" type="hidden"  class="inputText" value="<?php echo $candidateGetById->rec_can_id; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labRefNumber"><?php echo __("Ref Number") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtRefNumber" readonly="readonly" name="txtRefNumber" type="text"  class="formInputText" value="<?php if($candidateGetById->rec_can_reference_no!= null){ echo $candidateGetById->rec_can_reference_no; }else{ echo $nextId; } ?>" maxlength="15" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labNicNumber"><?php echo __("NIC Number") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtNicNumber"  name="txtNicNumber" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_nic_number; ?>" maxlength="10" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labCandidateName"><?php echo __("Candidate Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtCandidateName"  name="txtCandidateName" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_candidate_name; ?>" maxlength="100" />
            </div>
            <div class="centerCol">
                <input id="txtCandidateNameSi"  name="txtCandidateNameSi" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_candidate_name_si; ?>" maxlength="100" />
            </div>
            <div class="centerCol">
                <input id="txtCandidateNameTa"  name="txtCandidateNameTa" type="text"  class="formInputText" value="<?php echo $candidateGetById->rec_can_candidate_name_ta; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labAddress"><?php echo __("Address") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtAddress"  name="txtAddress"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $candidateGetById->rec_can_address ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtAddress"  name="txtAddressSi"  class="formTextAreaSi" rows="3" cols="5" tabindex="1" ><?php echo $candidateGetById->rec_can_address_si ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtAddress"  name="txtAddressTa"  class="formTextAreaTa" rows="3" cols="5" tabindex="1" ><?php echo $candidateGetById->rec_can_address_ta ?></textarea>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labTelNumber"><?php echo __("Tel.No") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtTelNumber"  name="txtTelNumber" type="text" class="formInputText"value="<?php echo $candidateGetById->rec_can_tel_number; ?>" maxlength="10" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="cmbGender"><?php echo __("Gender"); ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <select class="formSelect"   id="cmbGender" name="cmbGender">
                    <option value=""><?php echo __("--Select--"); ?></option>
                    <?php foreach ($genderList as $gender) {
                        ?>
                        <option value="<?php echo $gender->gender_code; ?>" <?php
                    if ($gender->gender_code == $candidateGetById->gender_code
                    )
                        echo "selected";
                        ?>> <?php
                            if ($userCulture == 'en') {
                                echo $gender->gender_name;
                            } elseif ($userCulture == 'si') {
                                if (($gender->gender_name_si) == null) {
                                    echo $gender->gender_name;
                                } else {
                                    echo $gender->gender_name_si;
                                }
                            } elseif ($userCulture == 'ta') {
                                if (($gender->gender_name_ta) == null) {
                                    echo $gender->gender_name;
                                } else {
                                    echo $gender->gender_name_ta;
                                }
                            }
                        ?></option>
                    <?php } ?>
                </select>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtDOB"><?php echo __("Date Of Birth"); ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input type="text" class="formInputText" name="txtDOB" id="txtDOB"
                       value="<?php echo $candidateGetById->rec_can_birthday; ?>">
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labEducationQualification"><?php echo __("Educational Qualification") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtEducationQualification"  name="txtEducationQualification"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $candidateGetById->rec_can_edu_qualification ?></textarea>
            </div>

            <br class="clear"/>
            <div class="leftCol">
                <label for="labWorkExperience"><?php echo __("Work Experience") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtWorkExperience"  name="txtWorkExperience"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $candidateGetById->rec_can_work_experiences ?></textarea>
            </div>
            <br class="clear"/> 
            <div class="leftCol">
                <label for="cmbLanguage"><?php echo __("Preferred Language"); ?><span class="required">*</span></label>
            </div>
            <div class="leftCol">
                <select class="formSelect"   id="cmbLanguage" name="cmbLanguage">
                    <option value=""><?php echo __("--Select--"); ?></option>
                    <?php foreach ($languageList as $language) {
                        ?>
                        <option value="<?php echo $language->lang_code; ?>" <?php
                    if ($language->lang_code == $candidateGetById->lang_code
                    )
                        echo "selected";
                        ?>> <?php
                            if ($userCulture == 'en') {
                                echo $language->lang_name;
                            } elseif ($userCulture == 'si') {
                                if (($language->lang_name_si) == null) {
                                    echo $language->lang_name;
                                } else {
                                    echo $language->lang_name_si;
                                }
                            } elseif ($userCulture == 'ta') {
                                if (($language->lang_name_ta) == null) {
                                    echo $language->lang_name;
                                } else {
                                    echo $language->lang_name_ta;
                                }
                            }
                        ?></option>
                    <?php } ?>                 
                </select>
            </div>

            <br class="clear"/>
            <div class="leftCol"><label  class="controlLabel" for="txtLocationCode" ><?php echo __("Attach CV") ?> </label></div>
            <div class="centerCol"><input type="file" class="formInputText" value="Upload" name="txtletter" /></div>
            <div class="centerCol" style="padding-left:65px;">


                <?php
                $encryptObj = new EncryptionHandler();
                $isChargeShee;
                ?>
                <?php if ($isChargeSheet > 0) { ?>
                    <label>
                        <a href="#" onclick="popupimage(link='<?php echo url_for('recruitment/ImagePopup2?id=' . $encryptObj->encrypt($chargeSheet[0]->rec_cv_attach_id) . '&adid=' . $encryptObj->encrypt($chargeSheet[0]->rec_can_id)) ?>')"><?php
                if (strlen($chargeSheet[0]->rec_cv_attach_filename)
                )
                    echo __("View");
                 ?></a> 
                       <?php if($chargeSheet[0]->rec_can_id){ ?>
                        <a href="#" id="deletelink" onclick="return deletelink(<?php echo $chargeSheet[0]->rec_can_id ?>,'c')">  <?php echo __("Delete"); ?> </a>
                    <?php } ?>
                </label>
                <?php  }     ?>
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
             
                         
    function deletelink(){
        var disable='<?php echo $disabled; ?>';
        if(disable==''){
            answer = confirm("<?php echo __("Do you really want to Delete?") ?>");

            if (answer !=0)
            {
                location.href = "<?php echo url_for('recruitment/DeleteImage2?id='. $encrypt->encrypt($chargeSheet[0]->rec_can_id)."&cdid=".$encrypt->encrypt($candidateGetById->rec_can_id )); ?>";

            }
            else{
                return false;
            }
        }
    }
    
    function popupimage(link){
        window.open(link, "myWindow",
        "top=100 left=25 status = 1, height = 450, width = 950, resizable = 0" )
    }
    
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
                cmbVacanyTitle:{required: true,maxlength:50},
                txtRefNumber: {required: true,noSpecialCharsOnly: false, number: true, maxlength:50 },
                txtNicNumber: {required: true,noSpecialCharsOnly: false, maxlength:10 },
                txtCandidateName: {required: true,noSpecialCharsOnly: true, maxlength:100},
                txtAddress:{required: true,noSpecialCharsOnly: false, maxlength:100},
                txtTelNumber: {required: true ,maxlength: 10, phone: true},
                cmbGender: {required: true },
                txtDOB: {required: true ,orange_date:true},
                txtEducationQualification: {required: true,maxlength:100},
                txtWorkExperience: {required: true,maxlength:200},
                cmbLanguage: {required: true }

            },
            messages: {
                cmbVacanyTitle:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtRefNumber:{required:"<?php echo __("This field is required") ?>",number:"<?php echo __("Invalid Ref Number") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtNicNumber:{required:"<?php echo __("This field is required") ?>", noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",maxlength:"<?php echo __("Maximum 10 Characters") ?>"},
                txtCandidateName: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtAddress:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>"},
                txtTelNumber:{required:"<?php echo __("This field is required") ?>",maxlength: "<?php echo __("Maximum length should be 10 characters") ?>", phone: "<?php echo __("Invalid phone number") ?>"},
                cmbGender:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtDOB:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"},
                txtEducationQualification:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>"},
                txtWorkExperience:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 200 Characters") ?>"},
                cmbLanguage:{required:"<?php echo __("This field is required") ?>"}  
            }

        });

        jQuery.validator.addMethod("orange_date",
                                function(value, element, params) {

                                    //var hint = params[0];
                                    var format = params[0];

                                    // date is not required
                                    if (value == '') {

                                        return true;
                                    }
                                    var d = strToDate(value, "<?php echo $format ?>");


                                    return (d != false);

                                }, ""
                            );

        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        // When click edit button
        $("#editBtn").click(function() {
            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock

                location.href="<?php echo url_for('recruitment/SaveCandidate?id=' . $encrypt->encrypt($candidateGetById->rec_can_id) . '&lock=1') ?>";
            }
            else {
                //                if($('#txtDOB').val()>'<?php echo $today; ?>'){
                //                    alert("<?php echo __("Date of Birth can not be future Date.") ?>");
                //                    return false;
                //                } else{
                $('#frmSave').submit();
            }
            //                }
        });

        //When click reset buton
        $("#btnClear").click(function() {
            if($("#frmSave").data('edit') != 1){
                location.href="<?php echo url_for('recruitment/SaveCandidate?id=' . $encrypt->encrypt($candidateGetById->rec_can_id) . '&lock=0') ?>";
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




