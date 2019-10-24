<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Vacancy Request Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>
                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="rec_vac_vacancy_title" <?php
                            if ($searchMode == "rec_vac_vacancy_title") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Vacancy Title") ?></option>
                    <option value="rec_vac_year" <?php
                            if ($searchMode == "rec_vac_year") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Year") ?></option>
                    <option value="emp_display_name" <?php
                            if ($searchMode == "emp_display_name") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Employee Name") ?></option>
                    /option>
                    <option value="no_of_vacancies" <?php
                            if ($searchMode == "rec_vac_no_of_vacancies") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Requested No of Employees") ?></option>
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
                <input type="button" class="plainbtn" id="buttonSubmit"
                       value="<?php echo __("Submit") ?>"/>
            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />

        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('recruitment/DeleteVacancyRequest') ?>">

            <input type="hidden" name="mode" id="mode" value=""/>
            <!--  summary table  -->
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                            <input type="checkbox" disabled="true" class="checkbox" name="allCheck" value="" id="allCheck" />

                        </td>                       
                        <td scope="col">
                            <?php
                            if ($userCulture == 'en') {
                                $vacancy_title = 'r.rec_vac_vacancy_title';
                            } else {
                                $vacancy_title = 'r.rec_vac_vacancy_title' . $userCulture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($vacancy_title, __('Vacancy Title'), '@VacancyRequest', ESC_RAW); ?>
                        </td>

                        <td scope="col">
                            <?php
                            $vac_year = 'r.rec_vac_year';
                            ?>
                            <?php echo $sorter->sortLink($vac_year, __('Year'), '@VacancyRequest', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php
                            if ($userCulture == 'en') {
                                $emp_name = 'e.emp_display_name';
                            } else {
                                $emp_name = 'e.emp_display_name' . $userCulture;
                            }
                            ?>
                            <?php echo $sorter->sortLink($emp_name, __('Employee Name'), '@VacancyRequest', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $noOfVacancies = 'r.rec_vac_no_of_vacancies';
                            ?>
                            <?php echo $sorter->sortLink($noOfVacancies, __('Requested No of Employees'), '@VacancyRequest', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php
                            $status = 'r.rec_vac_is_submit';
                            ?>
                            <?php echo $sorter->sortLink($status, __('Status'), '@VacancyRequest', ESC_RAW); ?>
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
                                <?php if ($VacancyRequest->rec_vac_is_submit != "0") { ?>
                                    <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' disabled="true" id="chkLoc" value='<?php echo $VacancyRequest->rec_vac_req_id ?>' />
                                <?php } else { ?>
                                    <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $VacancyRequest->rec_vac_req_id ?>' />
                                <?php } ?>
                            </td>
                            <td class="">
                                <?php if ($VacancyRequest->rec_vac_is_submit != "0") { ?>
                                    <?php
                                    if ($userCulture == 'en') {
                                        echo $VacancyRequest->rec_vac_vacancy_title;
                                    } else {
                                        $abc = 'rec_vac_vacancy_title_' . $userCulture;
                                        echo $VacancyRequest->rec_vac_vacancy_title->$abc;
                                        if ($VacancyRequest->rec_vac_vacancy_title->$abc == null) {
                                            echo $VacancyRequest->rec_vac_vacancy_title;
                                        }
                                    }
                                    ?>
                                <?php } else { ?>
                                    <a href="<?php echo url_for('recruitment/SaveVacancyRequest?id=' . $encrypt->encrypt($VacancyRequest->rec_vac_req_id)) ?>">                       
                                        <?php
                                        if ($userCulture == 'en') {
                                            echo $VacancyRequest->rec_vac_vacancy_title;
                                        } else {
                                            $abc = 'rec_vac_vacancy_title_' . $userCulture;
                                            echo $VacancyRequest->rec_vac_vacancy_title->$abc;
                                            if ($VacancyRequest->rec_vac_vacancy_title->$abc == null) {
                                                echo $VacancyRequest->rec_vac_vacancy_title;
                                            }
                                        }
                                        ?></a>    
                                <?php } ?>
                            </td>

                            <td class="">
                                <?php
                                echo $VacancyRequest->rec_vac_year;
                                ?>
                            </td>
                            <td class="">
                                <?php
                                if ($userCulture == 'en') {
                                    echo $VacancyRequest->Employee->emp_display_name;
                                } else {
                                    $abc = 'emp_display_name' . $userCulture;
                                    echo $VacancyRequest->Employee->$abc;
                                    if ($VacancyRequest->Employee->$abc == null) {
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
                                    if ($VacancyRequest->rec_vac_is_submit == "-1") {
                                        echo __("Rejected");  
                                    } else if ($VacancyRequest->rec_vac_is_submit == "0") {
                                        echo __("Not Submitted");
                                    } else if ($VacancyRequest->rec_vac_is_submit == "1") {
                                        echo __("Submitted");
                                    } else if ($VacancyRequest->rec_vac_is_submit == "2") {
                                        echo __("Submitted");
                                    } else if ($VacancyRequest->rec_vac_is_submit == "3") {
                                        echo __("Approved");
                                    }                                          
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
    $(document).ready(function() {
        buttonSecurityCommon("buttonAdd","null","null","buttonRemove");
        //When click add button
        $("#buttonAdd").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/SaveVacancyRequest')) ?>";

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
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/VacancyRequestWork')) ?>";
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
            $("#mode").attr('value', 'submit');
            if($('input[name=chkLocID[]]').is(':checked')){
                answer = confirm("<?php echo __("Do you really want to submit?") ?>");
            }


            else{
                alert("<?php echo __("Select at least one record to submit") ?>");

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
