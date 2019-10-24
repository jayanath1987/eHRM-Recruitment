<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Vacancy Requisition Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>
                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="rec_req_ref_number" <?php
                            if ($searchMode == "rec_req_ref_number") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Ref Number") ?></option>
                    <option value="rec_req_vacancy_title" <?php
                            if ($searchMode == "rec_req_vacancy_title") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Vacancy Title") ?></option>
                    <option value="rec_req_year" <?php
                            if ($searchMode == "rec_req_year") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Year") ?></option>
                    <option value="cmp_stur_id" <?php
                            if ($searchMode == "cmp_stur_id") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Section") ?></option>
                    <option value="grade_code" <?php
                            if ($searchMode == "grade_code") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Grade") ?></option>
                    <option value="jobtit_code" <?php
                            if ($searchMode == "jobtit_code") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Designation") ?></option>
                    <option value="estat_code" <?php
                            if ($searchMode == "estat_code") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Employment Type") ?></option>
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

                <input type="button" class="plainbtn" id="buttonAdd"
                       value="<?php echo __("Add") ?>" />
                <input type="button" class="plainbtn" id="buttonRemove"
                       value="<?php echo __("Delete") ?>" />       
            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />

        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('recruitment/DeleteVacancyRequisition') ?>">

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
                            $vac_year = 'v.rec_req_ref_number';
                            ?>
                            <?php echo $sorter->sortLink($vac_year, __('Ref Number'), '@VacancyRequisition', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $vac_title = 'v.rec_req_vacancy_title';
                            } else {
                                $vac_title = 'v.rec_req_vacancy_title_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($vac_title, __('Vacancy Title'), '@VacancyRequisition', ESC_RAW); ?>
                        </td>

                        <td scope="col">
                            <?php
                            $rec_req_year = 'v.rec_req_year';
                            ?>
                            <?php echo $sorter->sortLink($rec_req_year, __('Year'), '@VacancyRequisition', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $section = 'c.title';
                            } else {
                                $section = 'c.title_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($section, __('Section'), '@VacancyRequisition', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $grade = 'g.grade_name';
                            } else {
                                $grade = 'g.grade_name_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($grade, __('Grade'), '@VacancyRequisition', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $jobTitle = 'j.name';
                            } else {
                                $jobTitle = 'j.name_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($jobTitle, __('Designation'), '@VacancyRequisition', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $estat_name = 'e.estat_name';
                            } else {
                                $estat_name = 'e.estat_name_' . $Culture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($estat_name, __('Employment Type'), '@VacancyRequisition', ESC_RAW); ?>
                        </td>
                        <td scope="col" style="width: 70px">
                            
                            <?php echo __('Requested'); ?>
                        </td>
                        <td scope="col" style="width: 50px">
                            
                            <?php echo __('Approved'); ?>
                        </td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $row = 0;
//                            die(print_r($vacancyRequestList));
                    foreach ($vacancyRequisitionList as $VacancyRequisition) {
                        $cssClass = ($row % 2) ? 'even' : 'odd';
                        $row = $row + 1;
                        ?>
                        <tr class="<?php echo $cssClass ?>">
                            <td >
                                <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $VacancyRequisition->rec_req_id ?>' />
                            </td>
                            <td class="">
                                <a href="<?php echo url_for('recruitment/SaveVacancyRequisition?id=' . $encrypt->encrypt($VacancyRequisition->rec_req_id)) ?>">                       
                                    <?php
                                    echo $VacancyRequisition->rec_req_ref_number;
                                    ?></a>    

                            </td>


                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequisition->rec_req_vacancy_title;
                                } else {
                                    $abc = 'rec_req_vacancy_title_' . $Culture;
                                    echo $VacancyRequisition->$abc;
                                    if ($VacancyRequisition->$abc == null) {
                                        echo $VacancyRequisition->rec_req_vacancy_title;
                                    }
                                }
                                ?>
                            </td>

                            <td class="">
                                <?php
                                echo $VacancyRequisition->rec_req_year;
                                ?>
                            </td>
                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequisition->CompanyStructure->title;
                                } else {
                                    $abc = 'title_' . $Culture;
                                    echo $VacancyRequisition->CompanyStructure->title->$abc;
                                    if ($VacancyRequisition->CompanyStructure->title->$abc == null) {
                                        echo $VacancyRequisition->CompanyStructure->title;
                                    }
                                }
                                ?>
                            </td>

                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequisition->Grade->grade_name;
                                } else {
                                    $abc = 'grade_name_' . $Culture;
                                    echo $VacancyRequisition->Grade->grade_name->$abc;
                                    if ($VacancyRequisition->Grade->grade_name->$abc == null) {
                                        echo $VacancyRequisition->Grade->grade_name;
                                    }
                                }
                                ?>
                            </td>
                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequisition->JobTitle->name;
                                } else {
                                    $abc = 'jobtit_name_' . $Culture;
                                    echo $VacancyRequisition->JobTitle->name->$abc;
                                    if ($VacancyRequisition->JobTitle->name->$abc == null) {
                                        echo $VacancyRequisition->JobTitle->name;
                                    }
                                }
                                ?>
                            </td>
                            <td class="">
                                <?php
                                if ($Culture == 'en') {
                                    echo $VacancyRequisition->EmployeeStatus->name;
                                } elseif ($Culture == 'si') {
                                    if (($VacancyRequisition->EmployeeStatus->estat_name_si) == null) {
                                        echo $VacancyRequisition->EmployeeStatus->name;
                                    } else {
                                        echo $VacancyRequisition->EmployeeStatus->estat_name_si;
                                    }
                                } elseif ($Culture == 'ta') {
                                    if (($VacancyRequisition->EmployeeStatus->estat_name_ta) == null) {
                                        echo $VacancyRequisition->EmployeeStatus->name;
                                    } else {
                                        echo $VacancyRequisition->EmployeeStatus->estat_name_ta;
                                    }
                                }
                                ?>
                            </td>
                            <td class="">
                                <?php
                                       echo $VacancyRequisition->rec_req_requested_vacancies;
                                   
                                ?>
                            </td>
                            <td class="">
                                <?php
                                       echo $VacancyRequisition->rec_req_approved_vacancies;
                                ?>
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
    $(document).ready(function() {
        buttonSecurityCommon("buttonAdd","null","null","buttonRemove");
        //When click add button
        $("#buttonAdd").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/SaveVacancyRequisition')) ?>";

        });

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
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/VacancyRequisition')) ?>";
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
        
    });


</script>
