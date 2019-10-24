<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Selected Candidate Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="vacancy_title" <?php
        if ($searchMode == "vacancy_title") {
            echo "selected=selected";
        }
        ?> ><?php echo __("Vacancy Title") ?></option> 
                    <option value="ref_number" <?php
                            if ($searchMode == "ref_number") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Ref Number") ?></option> 
                    <option value="marks" <?php
                            if ($searchMode == "marks") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Marks") ?></option>   
                    <option value="nic_number" <?php
                            if ($searchMode == "nic_number") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("NIC Number") ?></option>     
                    <option value="candidates_name" <?php
                            if ($searchMode == "candidates_name") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Candidate Name") ?></option>
                    /option>
                    <option value="gender" <?php
                            if ($searchMode == "gender") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Gender") ?></option>
                    <option value="status" <?php
                            if ($searchMode == "status") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Status") ?></option>
                    <option value="language" <?php
                            if ($searchMode == "language") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Preferred Language") ?></option>
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
            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />

        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('recruitment/DeleteCandidate') ?>">

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
                            $ref_number = 'c.rec_can_reference_no';
                            ?>
                            <?php echo $sorter->sortLink($ref_number, __('Ref Number'), '@DGCandidateInterview', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $nic_number = 'c.rec_can_nic_number';
                            ?>
                            <?php echo $sorter->sortLink($nic_number, __('NIC Number'), '@DGCandidateInterview', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $candidate_name = 'c.rec_can_candidate_name';
                            ?>
                            <?php echo $sorter->sortLink($candidate_name, __('Candidate Name'), '@DGCandidateInterview', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $tel_number = 'c.rec_can_tel_number';
                            ?>
                            <?php echo $sorter->sortLink($tel_number, __('Tel.No'), '@DGCandidateInterview', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $gender = 'g.gender_name ';
                            } else {
                                $gender = 'g.gender_name_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($gender, __('Gender'), '@DGCandidateInterview', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            
                            <?php echo __('Address'); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $interview_status = 'c.rec_can_interview_marks ';
                            ?>
                            <?php echo $sorter->sortLink($gender, __('Interview Marks'), '@HRCandidateInterview', ESC_RAW); ?>
                        </td>                           
                        <td scope="col">

                        </td>                                 
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $row = 0;
                    foreach ($candidateList as $candidate) {
                        $cssClass = ($row % 2) ? 'even' : 'odd';
                        $row = $row + 1;
                        ?>
                        <tr class="<?php echo $cssClass ?>">
                            <td >
                                <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $candidate->rec_can_id ?>' />
                            </td>
                            <td class="">
                                <a href="<?php echo url_for('recruitment/SaveCandidateInterview?id=' . $encrypt->encrypt($candidate->rec_can_id)."&type=CN") ?>"> <?php
                    echo $candidate->rec_can_reference_no;
                        ?></a>                        
                            </td>
                            <td class=""><?php
                                echo $candidate->rec_can_nic_number;
                        ?></a>                        
                            </td>               
                            <td class="">

                                <a href="<?php echo url_for('recruitment/SaveCandidate?id=' . $encrypt->encrypt($candidate->rec_can_id)."&type=CN") ?>"> <?php
                            echo $candidate->rec_can_candidate_name;
                        ?></a>                         
                            </td>
                            <td class=""><?php
                                echo $candidate->rec_can_tel_number;
                        ?></a>                        
                            </td>
                            <td class=""><?php
                            if ($Culture == 'en') {
                                echo $candidate->Gender->gender_name;
                            } else {
                                $abc = 'gender_name_' . $Culture;
                                echo $candidate->Gender->gender_name->$abc;
                                if ($candidate->Gender->gender_name->$abc == null) {
                                    echo $candidate->Gender->gender_name;
                                }
                            }
                        ?>                       
                            </td>
                           <td class="" style="width: 100px;"><?php
                            
                                echo $candidate->rec_can_address;
                            
                        ?>                       
                            </td>
                            <td class=""><?php
                            echo $candidate->rec_can_interview_marks;
                        ?></a>                        
                            </td>                     
                            <td class="">
                                <a href="<?php echo url_for('pim/addEmployee?id=' . $encrypt->encrypt($candidate->rec_can_id)."&type=CN") ?>"><?php
                            echo __('PIM Registation');
                        ?></a>                        
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>     
        </form>

    </div>
</div>
<script type="text/javascript">

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
    
    mode='edit';

    $(".formSelect").attr('disabled', true);
    $('#standardView :button').removeAttr('disabled');
    $('#searchValue').removeAttr('disabled');
    function saveDG(id,value,cId,row){

        $('#'+id).removeAttr('disabled');
        $('#parti11_'+row).removeAttr('disabled');


        if(mode=='edit'){
           
            $.post(

            "<?php echo url_for('recruitment/ajaxTableLockCandidate') ?>", //Ajax file
            
            { lock : 1 ,cId:cId },  // create an object will all values

            //function that is called when server returns a value.
            function(data){


                if(data.lockMode==0){
                    alert("<?php echo __("Can not Update Record Lock") ?>");
                    $('#standardView :button').removeAttr('disabled');
                    $('#parti11_'+row).attr('disabled', true);
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

            $.post(
            "<?php echo url_for('recruitment/UpdateDGInterviewRequest') ?>", //Ajax file

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

                    "<?php echo url_for('recruitment/ajaxTableLockCandidate') ?>", //Ajax file

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
        buttonSecurityCommon("buttonAdd","null","null","buttonRemove");

        // When Click Main Tick box
        $("#allCheck").click(function() {
            if ($('#allCheck').attr('checked')){

                $('.innercheckbox').attr('checked','checked');
            }else{
                $('.innercheckbox').removeAttr('checked');
            }
        });

        $(".innercheckbox").click(function() {
            if($(this).attr('checked'))
            {

            }else
            {
                $('#allCheck').removeAttr('checked');
            }
        });


        //When click reset buton
        $("#resetBtn").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/DGCandidateInterview')) ?>";
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
        
        //When click Save Button
        $("#buttonRemove").click(function() {
            $("#mode").attr('value', 'save');
        });

    });


</script>
