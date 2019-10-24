<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery-latest.js') ?>"></script> 
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery.tablesorter.js') ?>"></script> 
<style type="text/css">
    .header{
        text-align: left;
        padding-left: 0px;
    }
</style>    
    <?php
$encrypt = new EncryptionHandler();
?>
<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Applicants Summary") ?></h2></div>
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
                    <option value="opening_date" <?php
                            if ($searchMode == "opening_date") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Opening Date") ?></option>
                    <option value="closing_date" <?php
                            if ($searchMode == "closing_date") {
                                echo "selected=selected";
                            }
        ?> ><?php echo __("Closing Date") ?></option>                    
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
            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />

        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('recruitment/DeleteVacancyRequest') ?>">

            <input type="hidden" name="mode" id="mode" value=""/>
            <!--  summary table  -->
            <table cellpadding="0" cellspacing="0" class="data-table" id="test">
                <thead>
                    <tr>
                        <th width="50">

<!--                            <input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" />-->

                        </th>
                        <th scope="col">

                            <a href="#" ><?php echo  __('Ref No'); ?></a>
                        </th>
                        <th scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $vacancy_title = 'v.rec_req_vacancy_title';
                            } else {
                                $vacancy_title = 'v.rec_req_vacancy_title_' . $Culture;
                            }
                            ?>
                            <?php //echo $sorter->sortLink($vacancy_title, __('Vacancy Title'), '@FinalizedVacancy', ESC_RAW); ?>
                        <a href="#" ><?php echo  __('Vacancy Title'); ?></a>
                        </th>

                        <th scope="col">
                            <?php
                            $opening_date = 'a.rec_adv_opening_date';
                            ?>
                            <?php //echo $sorter->sortLink($opening_date, __('Opening Date'), '@FinalizedVacancy', ESC_RAW); ?>
                            <a href="#" ><?php echo  __('Opening Date'); ?></a>
                        </th>
                        <th scope="col">
                            <?php
                            $closing_date = 'a.rec_adv_closing_date';
                            ?>
                            <?php //echo $sorter->sortLink($closing_date, __('Closing Date'), '@FinalizedVacancy', ESC_RAW); ?>
                            <a href="#" ><?php echo  __('Closing Date'); ?></a>
                        </th>
                        <th scope="col">
                            <?php
                            $closing_date = 'a.rec_adv_closing_date';
                            ?>
                            <?php //echo $sorter->sortLink($closing_date, __('Attachment'), '@FinalizedVacancy', ESC_RAW); ?>
                            <a href="#" ><?php echo  __('Attachment'); ?></a>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $row = 0;
//                            die(print_r($vacancyRequestList));
                    foreach ($advertisementList as $advertisement) {
                        $cssClass = ($row % 2) ? 'even' : 'odd';
                        $row = $row + 1;
                        ?>
                        <tr class="<?php echo $cssClass ?>">
                            <td >
<!--                                <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $advertisement->rec_adv_id ?>' />-->
                            </td>
                            <td class="">
                                <a href="<?php echo url_for('recruitment/Candidate?vacancyId=' . $advertisement->rec_req_id) ?>"><?php
                                echo $advertisement->VacancyRequisition->rec_req_ref_number;
                                ?></a>
                            </td> 

                            <td class="">
                                <a href="<?php echo url_for('recruitment/Candidate?vacancyId=' . $advertisement->rec_req_id) ?>"><?php
                    if ($Culture == 'en') {
                        echo $advertisement->VacancyRequisition->rec_req_vacancy_title;
                    } else {
                        $abc = 'rec_vac_vacancy_title_' . $Culture;
                        echo $advertisement->VacancyRequisition->rec_req_vacancy_title->$abc;
                        if ($advertisement->VacancyRequisition->rec_req_vacancy_title->$abc == null) {
                            echo $advertisement->VacancyRequisition->rec_req_vacancy_title;
                        }
                    }
                        ?></a>                        
                            </td>
                            <td class="">
                                <?php
                                echo $advertisement->rec_adv_opening_date;
                                ?>
                            </td>                
                            <td class="">
                                <?php
                                echo $advertisement->rec_adv_closing_date;
                                ?>
                            </td>
                            <td class="">
                                
                                <a href="#" onclick="popupimage(link='<?php  echo url_for('recruitment/ImagePopup?id=' . $encrypt->encrypt($advertisement->AdvertisementAttachment->rec_adv_attach_id) . '&adid=' . $encrypt->encrypt($advertisement->AdvertisementAttachment->rec_adv_id)) ?>')"><?php
                if (strlen($advertisement->AdvertisementAttachment->rec_adv_attach_filename)
                )
                    echo __("View");
                    ?>
                           </a>
                               <?php
                                //echo $advertisement->rec_adv_closing_date;
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
    function popupimage(link){
        window.open(link, "myWindow",
        "top=100 left=25 status = 1, height = 450, width = 950, resizable = 0" )
    }
    function popupimage1(link){
        window.open(link, "myWindow",
        " status = 1, height = 300, width = 300, resizable = 0" )
    }
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
        $("#test").tablesorter();
 
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
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/recruitment/FinalizedVacancy')) ?>";
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
