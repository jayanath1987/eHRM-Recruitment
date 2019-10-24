<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Request Summary - DG") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>
                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>                  
                    <option value="vac_title" <?php
        if ($searchMode == "rec_vac_vacancy_title") {
            echo "selected=selected";
        }
        ?> ><?php echo __("Vacancy Title") ?></option>
                    <option value="year" <?php
                            if ($searchMode == "rec_vac_year") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Year") ?></option>
                    <option value="emp_display_name" <?php
                            if ($searchMode == "emp_display_name_") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Employee Name") ?></option>
                    /option>
                    <option value="no_of_vacancies" <?php
                            if ($searchMode == "rec_vac_no_of_vacancies") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("DG Approved No of Employees") ?></option>
                </select>

                <label for="searchValue"><?php echo __("Search For") ?></label>
                <input type="text" size="20" name="searchValue" id="searchValue" value="<?php echo $searchValue ?>" />
                <input type="submit" class="plainbtn"
                       value="<?php echo __("Search") ?>" />
                <input type="reset" class="plainbtn"
                       value="<?php echo __("Reset") ?>" id="resetBtn"/>
                <br class="clear"/>
            </div>
        </form>
        <div class="actionbar">
            <div class="actionbuttons">

                <input type="button" class="plainbtn" id="buttonRemove"
                       value="<?php echo __("Delete") ?>" />
                <input type="button" class="plainbtn" id="buttonSubmit"
                       value="<?php echo __("Submit") ?>"/>
            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />

        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('recruitment/SubmitDGVacancyRequest') ?>">

            <input type="hidden" name="mode" id="mode" value=""/>
            <!--  summary table  -->
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                            <input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" />

                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $vacancy_title = 'r.rec_vac_vacancy_title';
                            } else {
                                $vacancy_title = 'r.rec_vac_vacancy_title_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($vacancy_title, __('Vacancy Title'), '@DGVacancyRequest', ESC_RAW); ?>
                        </td>

                        <td scope="col">
                            <?php
                            $vac_year = 'r.rec_vac_year';
                            ?>
                            <?php echo $sorter->sortLink($vac_year, __('Year'), '@DGVacancyRequest', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $emp_name = 'e.emp_display_name';
                            } else {
                                $emp_name = 'e.emp_display_name_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($emp_name, __('Employee Name'), '@DGVacancyRequest', ESC_RAW); ?>
                        </td>

                        <td scope="col">
                            <?php
                            $noOfVacancies = 'r.rec_vac_no_of_vacancies';
                            ?>
                            <?php echo $sorter->sortLink($noOfVacancies, __('Requested Number of Employees'), '@DGVacancyRequest', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $noOfVacanciesby_hr = 'r.rec_vac_no_of_vacancies_by_hr';
                            ?>
                            <?php echo $sorter->sortLink($noOfVacanciesby_hr, __('Admin Recomended No of Employees'), '@DGVacancyRequest', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $noOfVacanciesby_dg = 'r.rec_vac_no_of_vacancies_by_dg';
                            ?>
                            <?php echo $sorter->sortLink($noOfVacanciesby_dg, __('DG Approved No of Employees'), '@DGVacancyRequest', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php echo '' ?>
                        </td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $row = 0;
//                            die(print_r($vacancyRequestList));
                    foreach ($vacancyRequestList as $VacancyRequest) {
                        $cssClass = ($row % 2) ? 'even' : 'odd';
                        $row = $row + 1;
                        ?>
                        <tr class="<?php echo $cssClass ?>">
                            <td >
                                <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $row."_".$VacancyRequest->rec_vac_req_id; ?>' />
                            </td>
                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequest->rec_vac_vacancy_title;
                                } else {
                                    $abc = 'rec_vac_vacancy_title_' . $Culture;
                                    echo $VacancyRequest->rec_vac_vacancy_title->$abc;
                                    if ($VacancyRequest->rec_vac_vacancy_title->$abc == null) {
                                        echo $VacancyRequest->rec_vac_vacancy_title;
                                    }
                                }
                                ?>
                            </td>

                            <td class="">
                                <?php
                                echo $VacancyRequest->rec_vac_year;
                                ?>
                            </td>
                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequest->Employee->emp_display_name;
                                } else {
                                    $abc = 'emp_display_name_' . $Culture;
                                    echo $VacancyRequest->Employee->emp_display_name->$abc;
                                    if ($VacancyRequest->Employee->emp_display_name->$abc == null) {
                                        echo $VacancyRequest->Employee->emp_display_name;
                                    }
                                }
                                ?>
                            </td>
                            <td class="">
                                <?php
                                echo $VacancyRequest->rec_vac_no_of_vacancies;
                                ?>
                            </td>
                            <td class="">
                                <?php
                                echo $VacancyRequest->rec_vac_no_of_vacancies_by_hr;
                                ?>
                            </td>
                            <td class="" >
                                <input id="parti11_<?php echo $row ?>"  name="txtNoVacancies" type="text"  style="width: 52px; margin-left: 0px;" class="formInputText" value="<?php echo $VacancyRequest->rec_vac_no_of_vacancies_by_dg; ?>" maxlength="5" />
                            </td>
                            <td class="">
                                <input type="button" class="plainbtn editBtn" id="editBtn_<?php echo $row ?>"  value="<?php echo __("Edit") ?>" onclick="       
                                        save(this.id,$('#parti11_<?php echo $row ?>').val(),<?php echo $VacancyRequest->rec_vac_req_id ?>,<?php echo $row ?>);"/>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>     
        </form>

    </div>
</div>
<script type="text/javascript">
            var error=0;
    function validateform(){

        if($("#searchValue").val()=="")
        {

            alert("<?php echo __('Please enter search value') ?>");
            return false;

        }
        if($("#searchMode").val()=="all"){
            alert("<?php echo __('Please select the search mode') ?>");
            return false;
        }
        else{
            $("#frmSearchBox").submit();
        }

    }
    
function validateText(type) {
    $(type).change(function() {
        if(isNaN(this.value)){
            $(this).val("");
            alert("Please Enter Numeric Values");
            return false;
        }
    });
}
          
    mode='edit';

    $("input[type='text'],text").attr('disabled', true);
    $('#standardView :button').removeAttr('disabled');
    $('#searchValue').removeAttr('disabled');
    
    function save(id,value,cId,row){

//        if(!isNaN(value)){
//            alert("Please Enter Numeric Value.");                          
//        }
        $('#'+id).removeAttr('disabled');
        $('#parti11_'+row).removeAttr('disabled');


        if(mode=='edit'){
           
            $.post(

            "<?php echo url_for('recruitment/ajaxTableLock') ?>", //Ajax file
            
            { lock : 1 ,cId:cId },  // create an object will all values

            //function that is called when server returns a value.
            function(data){


                if(data.lockMode==0){
                    alert("<?php echo __("Can not Update Record Lock") ?>");
                    $('#standardView :button').removeAttr('disabled');
                    $('#parti11_'+row).attr('disabled', true);
                    //va
                }
                else{
                    mode='save';
                    
                    $('#'+id).attr('value', '<?php echo __("Save") ?>');

                }

            },


            "json"

        );


        }
        else{
            
                   
        
        if(value==""){
                    alert("<?php echo __("No of Vacancies Can't Empty") ?>");
                    return false;
            }
        

            $.post(
            "<?php echo url_for('recruitment/UpdateDGVacancyRequest') ?>", //Ajax file

            { lock : 0, cId : cId , value : value },  // create an object will all values
  
            //function that is called when server returns a value.
            function(data){

                if(data.isupdated=="true"){

                    mode='edit';
                    $('#'+id).attr('value', '<?php echo __("Edit") ?>');
                    alert("<?php echo __("Successfully Updated") ?>");
                    $('#standardView :button').removeAttr('disabled');
                    $('#parti11_'+row).attr('disabled', true);
                    $.post(

                    "<?php echo url_for('recruitment/ajaxTableLock') ?>", //Ajax file

                    { lock : 0 ,cId:cId , value : value},  // create an object will all values

                    //function that is called when server returns a value.
                    function(data){


                        if(data.lockMode==0){
                            mode='edit';
                        }
                        else{
                            mode='edit';
                      
                            $('#'+id).attr('value', '<?php echo __("Save") ?>');

                        }

                    },
                    "json"

                );
                }
                else{
                    alert("<?php echo __("Error") ?>");
                }

            },


            "json"

        );

        }
   } 
                        
    $(document).ready(function() {
    
       
        buttonSecurityCommonMultiple(null,null,"editBtn",null);
        
        validateText('input[name="txtNoVacancies"]');

        //When click add button
        $("#buttonAdd").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/SaveVacancyRequest')) ?>";

        });

        // When Click Main Tick box
        $("#allCheck").click(function() {
            if ($('#allCheck').attr('checked')){

                $('.innercheckbox').attr('checked','checked');
                <?php for($i=0; $i <= $row; $i++){?>
                  var i="<?php echo $i; ?>";  

                if($('#parti11_'+i).val()==""){
                    error++
                }
                <?php } ?>
            }else{
                $('.innercheckbox').removeAttr('checked');
                error=0;
            }
        });


        $(".innercheckbox").click(function() {
            if($(this).attr('checked'))
            {
                myArr=new Array();
                var data=this.value;
                myArr = data.split('_');

                //alert($('#parti11_'+this.value).val());
                if($('#parti11_'+myArr[0]).val()==""){
                    error++
                }
                 
            }else
            {
                myArr=new Array();
                var data=this.value;
                myArr = data.split('_');

                //alert($('#parti11_'+this.value).val());
                if($('#parti11_'+myArr[0]).val()==""){
                    error--;
                }
            }
        });

        //When click reset buton
        $("#resetBtn").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/DGVacancyRequest')) ?>";
        });

        $("#buttonRemove").click(function() {
            $("#mode").attr('value', 'delete');
            if($('input[name=chkLocID[]]').is(':checked')){
                answer = confirm("<?php echo __("Do you really want to Delete?") ?>");
            }

            else{
                alert("<?php echo __("Select at least one record to delete") ?>");

            }

            if (answer !=0)
            {

                $("#standardView").submit();

            }
            else{
                return false;
            }

        });
        
        $("#buttonSubmit").click(function() {
    

        var answer;
            $("#mode").attr('value', 'submit');

            if($('input[name=chkLocID[]]').is(':checked')){   
              
                $(function(){ 

                    $('.formInputText').each(function(){ 
                  
                    var test=this.id;  
          //alert($('#'+this.id).val());              
        //alert($('input[name=chkLocID[]]').val());
                      if ($('#'+this.id).val() == '' &&  $('input[name=chkLocID[]]').is(':checked')) {
                        //alert("Please enter no of Vacancies are approved before submit.");
                        //error++;
                        //return false;
                        }
                    
                    });
                });
                if(error=='0'){
                    answer = confirm("<?php echo __("Do you really want to submit?") ?>");
                    error=0;
                    }else{
                      alert("Please enter the no of Vacancies to be approved before submitting."); 
                      
                      return false;
                    }
            }
    
            else{
                alert("<?php echo __("Select at least one record to submit") ?>");
                return false;
            }
    
            if (answer !=0)
            {
                $("#standardView").submit();
            }
            else{
                return false;
            }
            
        });
        
        //When click Save Button
        $("#buttonRemove").click(function() {
            $("#mode").attr('value', 'save');
        });


    
    });


</script>
