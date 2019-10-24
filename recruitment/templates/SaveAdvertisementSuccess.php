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

    </div>
    <div id="status"></div>

    <div class="outerbox">
        <?php if ($mode == '1') {
            ?>
            <div class="mainHeading"><h2><?php echo __("Define Advertisement") ?></h2></div>
            <?php echo message(); ?>
        <?php } else {
            ?>
            <div class="mainHeading"><h2><?php echo __("Edit Advertisement") ?></h2></div>
            <?php echo message(); ?>
        <?php } ?>

        <form  enctype="multipart/form-data" name="frmSave" id="frmSave" method="POST"  action="">
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
                <select name="cmbVacanyTitle" id="cmbVacanyTitle" style="width: 190px;" onchange="loadAdvertisementDates()">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($vacancyTitleList as $VacancyTitle) {
                        ?>
                        <option value="<?php echo $VacancyTitle->rec_req_id; ?>" <?php
                    if ($VacancyTitle->rec_req_id == $advertisementGetById->rec_req_id
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
                <input id="txtHiddenReqID"  name="txtHiddenReqID" type="hidden"  class="inputText" value="<?php echo $advertisementGetById->rec_adv_id; ?>"  />
            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label for="labQualification"><?php echo __("Description") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtDescription"  name="txtDescription"  class="formTextArea" rows="3" cols="5" tabindex="1" ><?php echo $advertisementGetById->rec_adv_desc ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtDescriptionSi" class="formTextArea" rows="3" cols="5"  tabindex="2" name="txtDescriptionSi"><?php echo $advertisementGetById->rec_adv_desc_si; ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtDescriptionTa" class="formTextArea" rows="3" cols="5"  tabindex="3" name="txtDescriptionTa"><?php echo $advertisementGetById->rec_adv_desc_ta; ?></textarea>
            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label for="labOpeningDate"><?php echo __("Opening Date"); ?></label>
            </div>
            <div class="centerCol">
                <input type="text" class="formInputText"  name="txtOpeningDate" id="txtOpeningDate" value="<?php echo LocaleUtil::getInstance()->formatDate($advertisementGetById->rec_adv_opening_date) ?>">
            </div>

            <div class="leftCol" style="padding-left: 50px;">
                <label for="labClosingDate"><?php echo __("Closing Date"); ?></label>
            </div>
            <div class="centerCol">
                <input type="text" class="formInputText" name="txtClosingDate" id="txtClosingDate" value="<?php echo LocaleUtil::getInstance()->formatDate($advertisementGetById->rec_adv_closing_date) ?>">
            </div>
            <br class="clear"/>
            <div class="leftCol"><label  class="controlLabel" for="txtLocationCode" ><?php echo __("Upload Document") ?> </label></div>
            <div class="centerCol">
                <INPUT TYPE="file" class="formInputText" VALUE="Upload" name="txtletter" <?php echo $disabled; ?> ></div>
            <div class="centerCol" style="padding-left:65px;">


                <?php
                $encryptObj = new EncryptionHandler();
                ?>
                <?php if ($isChargeSheet > 0) { ?>

                    <label>
                        <a href="#" onclick="popupimage(link='<?php echo url_for('recruitment/ImagePopup?id=' . $encryptObj->encrypt($chargeSheet[0]->rec_adv_attach_id) . '&adid=' . $encryptObj->encrypt($chargeSheet[0]->rec_adv_id)) ?>')"><?php
                if (strlen($chargeSheet[0]->rec_adv_attach_filename)
                )
                    echo __("View");
                    ?></a>

                        <a href="#" id="deletelink" onclick="return deletelink(<?php echo $chargeSheet[0]->rec_adv_id ?>,'c')">  <?php echo __("Delete"); ?> </a>
                    <?php } ?>
                </label>
                <?php // }      ?>
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
   
    function loadAdvertisementDates(){
        var titleId = $('#cmbVacanyTitle').val();
        $.post(
        "<?php echo url_for('recruitment/AdvertisementDates') ?>", //Ajax file

        { titleId: titleId },  // create an object will all values
        function(data){
          $('#txtOpeningDate').val(data[0]);
          $('#txtClosingDate').val(data[1]);
        },
        "json"
    );
    }
            
    function deletelink(incid){
        var disable='<?php echo $disabled; ?>';
        if(disable==''){
            answer = confirm("<?php echo __("Do you really want to Delete?") ?>");

            if (answer !=0)
            {
                location.href = "<?php echo url_for('recruitment/DeleteImage?id=') ?>"+incid;

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
                cmbVacanyTitle:{required: true,maxlength:50},
                txtDescription: {required: true,noSpecialCharsOnly: false, maxlength:50 },
                txtDescriptionSi: {noSpecialCharsOnly: false, maxlength:50 },
                txtDescriptionTa: {noSpecialCharsOnly: true, maxlength:15},
                txtOpeningDate:{orange_date: function(){ return ['<?php echo $dateHint; ?>','<?php echo $format; ?>']}, vacancyOpenClose:true, required:true},
                txtClosingDate:{orange_date: function(){ return ['<?php echo $dateHint; ?>','<?php echo $format; ?>']}, vacancyOpenClose:true, required:true}
 
            },
            messages: {
                cmbVacanyTitle:{required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtDescription:{maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtDescriptionSi:{maxlength:"<?php echo __("Maximum 50 Characters") ?>"},
                txtDescriptionTa: {required:"<?php echo __("This field is required") ?>",maxlength:"<?php echo __("Maximum 15 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                txtOpeningDate:{orange_date: '<?php echo __("Invalid date."); ?>',
                    vacancyOpenClose: '<?php echo __("Invalid date period."); ?>',
                    required: '<?php echo __("This field is required.") ?>'},
                txtClosingDate:{orange_date: '<?php echo __("Invalid date."); ?>',
                    vacancyOpenClose: '<?php echo __("Invalid date period."); ?>',
                    required: '<?php echo __("This field is required.") ?>'}                        
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
    
        // When click edit button
        $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

        // When click edit button
        $("#editBtn").click(function() {
            var editMode = $("#frmSave").data('edit');
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock

                location.href="<?php echo url_for('recruitment/SaveAdvertisement?id=' . $encrypt->encrypt($advertisementGetById->rec_adv_id) . '&lock=1') ?>";
            }
            else {

                $('#frmSave').submit();
            }
        });

        //When click reset buton
        $("#btnClear").click(function() {
            if($("#frmSave").data('edit') != 1){
<?php if ($advertisementGetById->rec_adv_id != null) { ?>
                                  location.href="<?php echo url_for('recruitment/SaveAdvertisement?id=' . $encrypt->encrypt($advertisementGetById->rec_adv_id) . '&lock=0') ?>";
<?php } else { ?>
                                  location.href="<?php echo url_for('recruitment/SaveAdvertisement') ?>";  
<?php } ?>
                          }else{
                              document.forms[0].reset('');
                          }
                      });

                      //When Click back button
                      $("#btnBack").click(function() {
                          location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/Advertisement')) ?>";
                      });

                  });
</script>



