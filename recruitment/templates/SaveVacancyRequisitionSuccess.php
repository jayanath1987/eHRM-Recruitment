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
<link href="<?php echo public_path('../../themes/orange/css/style.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo public_path('../../themes/orange/css/message.css') ?>" rel="stylesheet" type="text/css"/>
<!--[if lte IE 6]>
<link href="<?php echo public_path('../../themes/orange/css/IE6_style.css') ?>" rel="stylesheet" type="text/css"/>
<![endif]-->
<!--[if IE]>
<link href="<?php echo public_path('../../themes/orange/css/IE_style.css') ?>" rel="stylesheet" type="text/css"/>
<![endif]-->
<script type="text/javascript" src="<?php echo public_path('../../themes/orange/scripts/style.js'); ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/archive.js'); ?>"></script>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.form.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>

<?php echo javascript_include_tag('orangehrm.validate.js'); ?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/showhidepane.js'); ?>"></script>

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
        <?php if ($mode == '1') {
            ?>
            <div class="mainHeading"><h2><?php echo __("Define Vacancy Requisition") ?></h2></div>
            <?php echo message(); ?>
        <?php } else {
            ?>
            <div class="mainHeading"><h2><?php echo __("Edit Vacancy Requisition") ?></h2></div>
            <?php echo message(); ?>
        <?php } ?>

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
                <label for="labRefNumber"><?php echo __("Ref Number") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtRefNumber"  name="txtRefNumber" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_ref_number; ?>" maxlength="15" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labVacancyTitle"><?php echo __("Vacancy Title") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtVacancyTitle"  name="txtVacancyTitle" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_vacancy_title; ?>" maxlength="100" />
            </div>
            <div class="centerCol">
                <input id="txtVacancyTitleSi"  name="txtVacancyTitleSi" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_vacancy_title_si; ?>" maxlength="100" />
            </div>
            <div class="centerCol">
                <input id="txtVacancyTitleTa"  name="txtVacancyTitleTa" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_vacancy_title_ta; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labYear"><?php echo __("Year") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtYear"  name="txtYear" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_year; ?>" maxlength="4" />
            </div>

            <br class="clear"/>


            <div id="wrldiv" style="float: left;" >
                <div class="leftCol">
                    <label class="controlLabel" for="txtLocationCode"><?php echo __("Section") ?> <span class="required">*</span></label>
                </div>
                <div class="centerCol" >
                    <?php
                    if ($userCulture == "en") {
                        $companyCol = 'title';
                    } else {
                        $companyCol = 'title_' . $userCulture;
                    }
                    if ($employee->$vacancyRequisitiontGetById->CompanyStructure->title == "") {
                        $feild = $vacancyRequisitiontGetById->CompanyStructure->title;
                    } else {
                        $feild = $vacancyRequisitiontGetById->CompanyStructure->title;
                    }
                    ?>
                    <input type="text" name="txtDivision" id="txtDivision" class="formInputText" value="<?php echo $feild; ?>" readonly="readonly" />
                    <input type="hidden" name="txtDivisionid" id="txtDivisionid" value="<?php echo $vacancyRequisitiontGetById->cmp_stur_id; ?>" />&nbsp;

                </div>
                <label for="txtLocation" style="width: 25px;">
                    <input class="button" type="button"  onclick="returnLocDet()" value="..." id="divisionPopBtn"  />
                </label>
                <br class="clear"/>
            </div> 
            <br class="clear"/>
            <div class="leftCol">
                <label for="labGrade"><?php echo __("Grade") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 10px; padding-left: 10px;">
                <select name="cmbGrade" id="cmbGrade" style="width: 190px;">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($GradeList as $Grade) {
                        ?>
                        <option value="<?php echo $Grade->grade_code; ?>" <?php
                    if ($Grade->grade_code == $vacancyRequisitiontGetById->grade_code
                    )
                        echo "selected";
                        ?>> <?php
                            if ($userCulture == 'en') {
                                echo $Grade->grade_name;
                            } elseif ($userCulture == 'si') {
                                if (($Grade->grade_name_si) == null) {
                                    echo $Grade->grade_name;
                                } else {
                                    echo $Grade->grade_name_si;
                                }
                            } elseif ($userCulture == 'ta') {
                                if (($Grade->grade_name_ta) == null) {
                                    echo $Grade->grade_name;
                                } else {
                                    echo $Grade->grade_name_ta;
                                }
                            }
                        ?></option>
                    <?php } ?>
                </select>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labDesignation"><?php echo __("Designation") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 10px; padding-left: 10px;">
                <select name="cmbDesignation" id="cmbDesignation" style="width: 190px;">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($DesignationList as $Designation) {
                        ?>
                        <option value="<?php echo $Designation->id; ?>" <?php
                    if ($Designation->id == $vacancyRequisitiontGetById->jobtit_code
                    )
                        echo "selected";
                        ?>> <?php
                            if ($userCulture == 'en') {
                                echo $Designation->name;
                            } elseif ($userCulture == 'si') {
                                if (($Designation->name_si) == null) {
                                    echo $Designation->name;
                                } else {
                                    echo $Designation->name_si;
                                }
                            } elseif ($userCulture == 'ta') {
                                if (($Designation->name_ta) == null) {
                                    echo $Designation->name;
                                } else {
                                    echo $Designation->name_ta;
                                }
                            }
                        ?></option>
                    <?php } ?>
                </select>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labRecruitmentType"><?php echo __("Recruitment Type") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtRecruitmentType"  name="txtRecruitmentType" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_recruitment_type; ?>" maxlength="100" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labReportTo"><?php echo __("Report To") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtReportTo"  name="txtReportTo" type="text"  class="formInputText" value="<?php echo $vacancyRequisitiontGetById->report_to; ?>" maxlength="10" />
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labEmploymentType"><?php echo __("Employment Type") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <div class="centerCol" style="padding-top: 10px; padding-left: 10px;">
                    <select name="cmbEmploymentType" id="cmbEmploymentType" style="width: 190px;">
                        <option value=""><?php echo __("--Select--") ?></option>
                        <?php foreach ($RecruitmentTypeList as $RecruitmentType) {
                            ?>
                            <option value="<?php echo $RecruitmentType->id; ?>" <?php
                        if ($RecruitmentType->id == $vacancyRequisitiontGetById->estat_code
                        )
                            echo "selected";
                            ?>> <?php
                                if ($userCulture == 'en') {
                                    echo $RecruitmentType->name;
                                } elseif ($userCulture == 'si') {
                                    if (($RecruitmentType->estat_name_si) == null) {
                                        echo $RecruitmentType->estat_name;
                                    } else {
                                        echo $RecruitmentType->estat_name_si;
                                    }
                                } elseif ($userCulture == 'ta') {
                                    if (($RecruitmentType->estat_name_ta) == null) {
                                        echo $RecruitmentType->name;
                                    } else {
                                        echo $RecruitmentType->estat_name_ta;
                                    }
                                }
                            ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labQualification"><?php echo __("Justification / Expected Qualification / Experience / Any Other Comments") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtQualification"  name="txtQualification"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $vacancyRequisitiontGetById->rec_req_qualification ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtQualificationSi" class="formTextArea" rows="3" cols="5"  tabindex="2" name="txtQualificationSi"><?php echo $vacancyRequisitiontGetById->rec_req_qualification_si; ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtQualificationTa" class="formTextArea" rows="3" cols="5"  tabindex="3" name="txtQualificationTa"><?php echo $vacancyRequisitiontGetById->rec_req_qualification_ta; ?></textarea>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="labOpeningDate"><?php echo __("Opening Date"); ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input type="text" class="formInputText" name="txtOpeningDate" id="txtOpeningDate" value="<?php echo LocaleUtil::getInstance()->formatDate($vacancyRequisitiontGetById->rec_req_opening_date) ?>">
            </div>

            <div class="leftCol" style="width: 200px;">
                <label for="labClosingDate"><?php echo __("Closing Date"); ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input type="text" class="formInputText" name="txtClosingDate" id="txtClosingDate" value="<?php echo LocaleUtil::getInstance()->formatDate($vacancyRequisitiontGetById->rec_req_closing_date) ?>">
            </div>
            <br class="clear"/>
            <div class="formbuttons"></div>
            <div class="leftCol">
                <label for="labReqNoVacancies"><?php echo __("Number of Vacancies requested by others") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtNoVacancies"  name="txtReqNoVacancies" type="text"  style="width: 52px;" class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_requested_vacancies; ?>" maxlength="4" />
            </div>
            <div class="leftCol" style="padding-left: 50px;">
                <label for="labApprovedNoVacancies"><?php echo __("Approved No of Vacancies") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtApprovedNoVacancies"  name="txtApprovedNoVacancies" type="text"  style="width: 52px;" class="formInputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_approved_vacancies; ?>" maxlength="4" />
                <input id="txtHiddenReqID"  name="txtHiddenReqID" type="hidden"  class="inputText" value="<?php echo $vacancyRequisitiontGetById->rec_req_id; ?>" maxlength="100" />
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
    function returnLocDet(){

        // TODO: Point to converted location popup
        var popup=window.open('<?php echo public_path('../../symfony/web/index.php/admin/listCompanyStructure?mode=select_subunit&method=mymethod'); ?>','Locations','height=450,resizable=1,scrollbars=1');
        if(!popup.opener) popup.opener=self;
    }
                   
    function LoadGradeSlot(id){
        $.post(

        "<?php echo url_for('pim/LoadGradeSlot') ?>", //Ajax file

        { id: id },  // create an object will all values

        //function that is called when server returns a value.
        function(data){

            var selectbox="<option value='-1'><?php echo __('--Select--') ?></option>";
            $.each(data, function(key, value) {
                var word=value.split("|");
                selectbox=selectbox +"<option value="+word[1]+">"+word[1]+" --> "+word[3]+"</option>";
            });
            selectbox=selectbox +"</select>";
            $('#cmbGradeSlot').html(selectbox);

        },
        //How you want the data formated when it is returned from the server.
        "json"
    );


    }
   
    function submitValidation(){          
        var errorCount=0;
        var RefNumber=$("#txtRefNumber").val();
                 
        if(RefNumber!=""){                                
            $.ajax({
                type: "POST",
                async:false,
                url: "<?php echo url_for('recruitment/IsRefNumberNoExists') ?>",
                data: {
                    RefNumber: RefNumber
                },
                dataType: "json",
                success: function(data){
                    if(data.count>0){
                        alert("<?php echo __('RefNumber No can not duplicated'); ?>");
                        errorCount=errorCount+1;
                    }else{
                        saveFlag=1;
                    }
                }
            });
        }
        return  errorCount;

    }

    function returnActLocDet(){

        // TODO: Point to converted location popup
        var Actpopup=window.open('<?php echo public_path('../../symfony/web/index.php/admin/listCompanyStructure?mode=select_subunit&method=Actmymethod'); ?>','Locations','height=450,resizable=1,scrollbars=1');
        if(!Actpopup.opener) Actpopup.opener=self;
    }
    function Actmymethod(Actid,Actname){

        $("#txtActDivisionid").val(Actid);
        $("#txtActDivision").val(Actname);
        return false;
       
        //        DisplayEmpHirache(Actid,"Display2");

    }                                                  
    function mymethod(id,name){

        $("#txtDivisionid").val(id);
        $("#txtDivision").val(name);
        return true;
    }
    
   
    $(document).ready(function() {
        $("#txtOpeningDate").datepicker({ dateFormat: '<?php echo $dateHint; ?>',changeYear: true,changeMonth: true });
        $("#txtClosingDate").datepicker({ dateFormat: '<?php echo $dateHint; ?>',changeYear: true,changeMonth: true });

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
                txtRefNumber:{required: true,maxlength:15,noSpecialCharsOnly: true},
                txtVacancyTitle:{required: true,noSpecialCharsOnly: false,maxlength:100},
                txtVacancyTitleSi:{noSpecialCharsOnly: true,maxlength:100},
                txtVacancyTitleTa:{noSpecialCharsOnly: true,maxlength:100},
                txtYear:{required: true, digits: true,maxlength:4,minlength:4},
                txtLocationCode:{required: true},
                txtDivision:{required: true},
                cmbGrade:{required: true},
                cmbDesignation:{required: true},
                txtRecruitmentType:{required: true},
                txtReportTo:{required: true,noSpecialCharsOnly: false,maxlength:100},
                cmbEmploymentType:{required: true},
                txtQualification:{required: true,noSpecialCharsOnly: true,maxlength:300},
                txtQualificationSi:{noSpecialCharsOnly: true,maxlength:300},
                txtQualificationTa:{noSpecialCharsOnly: true,maxlength:300},
                txtOpeningDate:{orange_date: function(){ return ['<?php echo $dateHint; ?>','<?php echo $format; ?>']}, vacancyOpenClose:true, required:true},
                txtClosingDate:{orange_date: function(){ return ['<?php echo $dateHint; ?>','<?php echo $format; ?>']}, vacancyOpenClose:true, required:true},
                txtReqNoVacancies:{required: true,digits: true,noSpecialCharsOnly: true,maxlength:4},
                txtApprovedNoVacancies:{required: true,digits: true,noSpecialCharsOnly: true,maxlength:4}
            },
            messages: {
                txtRefNumber:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtVacancyTitle:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtVacancyTitleSi:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtVacancyTitleTa:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtYear:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 4 Characters") ?>"},
                txtLocationCode:{required:"<?php echo __("This field is required") ?>"},
                txtDivision:{required:"<?php echo __("This field is required") ?>"},
                cmbGrade:{required:"<?php echo __("This field is required") ?>"},
                cmbDesignation:{required:"<?php echo __("This field is required") ?>"},
                txtRecruitmentType:{required:"<?php echo __("This field is required") ?>"},
                txtReportTo:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                cmbEmploymentType:{required:"<?php echo __("This field is required") ?>"},
                txtQualification:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 300 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtQualificationSi:{maxlength:"<?php echo __("Maximum 300 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtQualificationTa:{maxlength:"<?php echo __("Maximum 300 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtOpeningDate:{orange_date: '<?php echo __("Invalid date."); ?>',
                    vacancyOpenClose: '<?php echo __("Invalid date period."); ?>',
                    required: '<?php echo __("This field is required.") ?>'},
                txtClosingDate:{orange_date: '<?php echo __("Invalid date."); ?>',
                    vacancyOpenClose: '<?php echo __("Invalid date period."); ?>',
                    required: '<?php echo __("This field is required.") ?>'},
                txtReqNoVacancies:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 4 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtApprovedNoVacancies:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 4 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"}
                               
            }
        });
        
        jQuery.validator.addMethod("vacancyOpenClose",
        function(value, element) {

            var hint = '<?php echo $dateHint; ?>';
            var format = '<?php echo $format; ?>';
            var fromDate = strToDate($('#txtOpeningDate').val(), format)
            var toDate = strToDate($('#txtClosingDate').val(), format);

            if (fromDate && toDate && (fromDate >= toDate)) {
                return false;
            }
            return true;
        }, ""
    );
    
        jQuery.validator.addMethod("eduCode",
        function() {
            var editMode = $("#frmEmpEducation").data('edit');
            // Add = 2
            if (editMode!=2) {
                return true;
            } else {
                var code = $('#cmbEduName').val();
                return code != "0";
            }
        }, ""
    );


        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        // When click edit button
        $("#editBtn").click(function() {
            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock

                location.href="<?php echo url_for('recruitment/SaveVacancyRequisition?id=' . $encrypt->encrypt($vacancyRequisitiontGetById->rec_req_id) . '&lock=1') ?>";
            }
            else {
                if(submitValidation()==0){
                    $('#frmSave').submit();
                    isOktoSubmit=1; 
                }
            }
        });

        //When click reset buton
        $("#btnClear").click(function() {
            if($("#frmSave").data('edit') != 1){
             <?php if($vacancyRequisitiontGetById->rec_req_id!= null){ ?>
                location.href="<?php echo url_for('recruitment/SaveVacancyRequisition?id=' . $encrypt->encrypt($vacancyRequisitiontGetById->rec_req_id) . '&lock=0') ?>";
            <?php  }else{ ?>
                location.href="<?php echo url_for('recruitment/SaveVacancyRequisition') ?>";
            <?php  } ?>    
        }else{
                document.forms[0].reset('');
            }
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/VacancyRequisition')) ?>";
        });

    });
</script>



