<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
/**
 * Actions class for Recruitment Module
 *
 * -------------------------------------------------------------------------------------------------------
 *  Author    - Givantha Kalansuriya
 *  On (Date) - 27 July 2011 
 *  Comments  - Employee Recruitment Functions 
 *  Version   - Version 1.0
 * -------------------------------------------------------------------------------------------------------
 * */
include ('../../lib/common/LocaleUtil.php');

class recruitmentActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeVacancyRequestWork(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();
            $this->vacancyRequestService = $RecSearchService;

            $this->sorter = new ListSorter('VacancyRequest', 'recruitment', $this->getUser(), array('rec_vac_req_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/VacancyRequest');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'r.rec_vac_req_id' : $request->getParameter('sort');
            $this->sort2 = ($request->getParameter('sort2') == '') ? 'r.rec_vac_year' : $request->getParameter('sort2');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchVacancyRequest($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->sort2, $this->order);
            $this->vacancyRequestList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSaveVacancyRequest(sfWebRequest $request) {

        $encrypt = new EncryptionHandler();

        try {
            if (!strlen($request->getParameter('lock'))) {
                $this->mode = 0;
            } else {
                $this->mode = $request->getParameter('lock');
            }
            $ebLockid = $encrypt->decrypt($request->getParameter('id'));

            if (isset($this->mode)) {
                if ($this->mode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock('hs_hr_rec_vacancy_request', array($ebLockid), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->mode = 1;
                    } else {

                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->mode = 0;
                    }
                } else if ($this->mode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock('hs_hr_rec_vacancy_request', array($ebLockid), 1);
                    $this->mode = 0;
                }
            }



            $this->userCulture = $this->getUser()->getCulture();
            $vacancyRequestService = new VacancyRequestService();

            $this->YearList = $vacancyRequestService->readYearList();
            $this->currentUser = $vacancyRequestService->readEmployee($_SESSION['empNumber']);
//            die (print_r($this->currentUser->empNumber));
            $requestId = $request->getParameter('id');
            if (strlen($requestId)) {
                $requestId = $encrypt->decrypt($request->getParameter('id'));
                if (!strlen($this->mode)) {
                    $this->mode = 0;
                }
                $this->vacancyRequestGetById = $vacancyRequestService->readVacanyRequest($requestId);

                if (!$this->vacancyRequestGetById) {
                    $this->setMessage('WARNING', array($this->getContext()->geti18n()->__('Record Not Found')));
                    $this->redirect('recruitment/VacancyRequestWork');
                }
            } else {
                $this->mode = 1;
            }


            if ($request->isMethod('post')) {

                if (strlen($request->getParameter('txtHiddenReqID'))) {
                    $VacancyRequest = $vacancyRequestService->readVacanyRequest($request->getParameter('txtHiddenReqID'));
                } else {
                    $VacancyRequest = new VacancyRequest();
                }

                if ($_SESSION['empNumber'] != null) {
                    $VacancyRequest->setEmp_number($this->currentUser->empNumber);
                } else {
                    $VacancyRequest->setEmp_number(null);
                }
                if (strlen($request->getParameter('txtVacanyTitle'))) {
                    $VacancyRequest->setRec_vac_vacancy_title(trim($request->getParameter('txtVacanyTitle')));
                } else {
                    $VacancyRequest->setRec_vac_vacancy_title(null);
                }
                if (strlen($request->getParameter('txtVacanyTitleSi'))) {
                    $VacancyRequest->setRec_vac_vacancy_title_si(trim($request->getParameter('txtVacanyTitleSi')));
                } else {
                    $VacancyRequest->setRec_vac_vacancy_title_ta(null);
                }
                if (strlen($request->getParameter('txtVacanyTitleTa'))) {
                    $VacancyRequest->setRec_vac_vacancy_title_ta(trim($request->getParameter('txtVacanyTitleTa')));
                } else {
                    $VacancyRequest->setRec_vac_vacancy_title_ta(null);
                }
                if (strlen($request->getParameter('cmbYear'))) {
                    $VacancyRequest->setRec_vac_year(trim($request->getParameter('cmbYear')));
                } else {
                    $VacancyRequest->setRec_vac_year(null);
                }
                if (strlen($request->getParameter('txtNoVacancies'))) {
                    $VacancyRequest->setRec_vac_no_of_vacancies(trim($request->getParameter('txtNoVacancies')));
                } else {
                    $VacancyRequest->setRec_vac_no_of_vacancies(null);
                }

                $vacancyRequestService->saveVacanyRequest($VacancyRequest);
                if (strlen($requestId)) {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Updated')));
                    $this->redirect('recruitment/SaveVacancyRequest?id=' . $requestId . '&lock=0');
                } else {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Added')));
                    $this->redirect('recruitment/VacancyRequestWork');
                }
            }
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/VacancyRequestWork');
        } catch (sfStopException $sf) {
            $this->redirect('recruitment/VacancyRequestWork');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/VacancyRequestWork');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDeleteVacancyRequest(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $RecSearchService = new RecSearchService();
            $vacancyRequestService = new VacancyRequestService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_vacancy_request', array($ids[$i]), 1);
                    if ($isRecordLocked) {
                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        if ($_POST['mode'] == "delete") {
                            $RecSearchService->deleteVacanyRequest($ids[$i]);
                        } else {
                            $status = '1';
                            $field = 'user_flag';
                            $vacancyRequestService->updateVacancyRequestStatus($ids[$i], $status, $field);
                        }
                        $conHandler->resetTableLock('hs_hr_rec_vacancy_request', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/VacancyRequestWork');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/VacancyRequestWork');
            }
            if ($_POST['mode'] == "delete") {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
                }
            } else {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Submitted.", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be submitted as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not submit as them  Locked by another user ", $args, 'messages')));
                }
            }
        } else {
            if ($_POST['mode'] == "delete") {
                $this->setMessage('NOTICE', array('Select at least one record to reject.'));
            } else {
                $this->setMessage('NOTICE', array('Select at least one record to submit'));
            }
        }
        $this->redirect('recruitment/VacancyRequestWork');
    }

    public function executeHRVacancyRequest(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('VacancyRequest', 'recruitment', $this->getUser(), array('rec_vac_req_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/VacancyRequest');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'r.rec_vac_req_id' : $request->getParameter('sort');
            $this->sort2 = ($request->getParameter('sort2') == '') ? 'r.rec_vac_year' : $request->getParameter('sort2');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchHRVacancyRequest($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->sort2, $this->order);
            $this->vacancyRequestList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeUpdateHRVacancyRequest(sfWebRequest $request) {

        $vacancyRequestService = new VacancyRequestService();
        $this->vacancyRequestService = $vacancyRequestService;

        $id = $request->getParameter('cId');
        $noOfVacancies = $request->getParameter('value');
        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
            if ($noOfVacancies != 0) {
                $this->isupdated = $vacancyRequestService->updateHRVacancyRequest($id, $noOfVacancies);
                $conn->commit();
            }
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/HRVacancyRequest');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('recruitment/HRVacancyRequest');
        }
        $this->isupdated = "true";
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeUpdateDGVacancyRequest(sfWebRequest $request) {

        $vacancyRequestService = new VacancyRequestService();
        $this->vacancyRequestService = $vacancyRequestService;

        $id = $request->getParameter('cId');
        $noOfVacancies = $request->getParameter('value');
        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
            if ($noOfVacancies != 0) {
                $this->isupdated = $vacancyRequestService->updateDGVacancyRequest($id, $noOfVacancies);
                $conn->commit();
            }
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/DGVacancyRequest');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('recruitment/DGVacancyRequest');
        }
        $this->isupdated = "true";
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSubmitHRVacancyRequest(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $vacancyRequestService = new VacancyRequestService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $exploed = explode("_", $ids[$i]);
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_vacancy_request', array($exploed[1]), 1);
                    if ($isRecordLocked) {
                        $countArr = $exploed[1];
                    } else {
                        $saveArr = $exploed[1];
                        if ($_POST['mode'] == "delete") {
                            $status = '-1';
                            $field = 'hr_flag';
                            $vacancyRequestService->updateVacancyRequestStatus($exploed[1], $status, $field);
                        } else {
                            $status = '2';
                            $field = 'hr_flag';
                            $vacancyRequestService->updateVacancyRequestStatus($exploed[1], $status, $field);
                        }
                        $conHandler->resetTableLock('hs_hr_rec_vacancy_request', array($exploed[1]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/HRVacancyRequest');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/HRVacancyRequest');
            }
            if ($_POST['mode'] == "delete") {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Rejected", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be rejected as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Cannot reject the record as it is locked by.", $args, 'messages')));
                }
            } else {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Submitted.", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be submitted as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not submit as them  Locked by another user ", $args, 'messages')));
                }
            }
        } else {
            if ($_POST['mode'] == "delete") {
                $this->setMessage('NOTICE', array('Select at least one record to reject.'));
            } else {
                $this->setMessage('NOTICE', array('Select at least one record to submit'));
            }
        }
        $this->redirect('recruitment/HRVacancyRequest');
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDGVacancyRequest(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();
            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('VacancyRequest', 'recruitment', $this->getUser(), array('rec_vac_req_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/DGVacancyRequest');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'r.rec_vac_req_id' : $request->getParameter('sort');
            $this->sort2 = ($request->getParameter('sort2') == '') ? 'r.rec_vac_year' : $request->getParameter('sort2');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchDGVacancyRequest($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->sort2, $this->order);
            $this->vacancyRequestList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSubmitDGVacancyRequest(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $vacancyRequestService = new VacancyRequestService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    
                    $exploed = explode("_", $ids[$i]);
                    
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_vacancy_request', array($exploed[1]), 1);
                    if ($isRecordLocked) {
                        $countArr = $exploed[1];
                    } else {
                        $saveArr = $exploed[1];
                        if ($_POST['mode'] == "delete") {
                            $status = '-1';
                            $field = 'dg_flag';
                            $vacancyRequestService->updateVacancyRequestStatus($exploed[1], $status, $field);
                        } else {
                            $status = '3';
                            $field = 'dg_flag';
                            $vacancyRequestService->updateVacancyRequestStatus($exploed[1], $status, $field);
                        }
                        $conHandler->resetTableLock('hs_hr_rec_vacancy_request', array($exploed[1]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/DGVacancyRequest');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/DGVacancyRequest');
            }
            if ($_POST['mode'] == "delete") {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Rejected", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be rejected as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not reject as them  Locked by another user ", $args, 'messages')));
                }
            } else {
                if (count($saveArr) > 0 && count($countArr) == 0) {
                    $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Submitted.", $args, 'messages')));
                } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be submitted as them  Locked by another user ", $args, 'messages')));
                } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not submit as them  Locked by another user ", $args, 'messages')));
                }
            }
        } else {
            if ($_POST['mode'] == "delete") {
                $this->setMessage('NOTICE', array('Select at least one record to reject.'));
            } else {
                $this->setMessage('NOTICE', array('Select at least one record to submit'));
            }
        }
        $this->redirect('recruitment/DGVacancyRequest');
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeOverallVacancyRequest(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();

            $this->sorter = new ListSorter('VacancyRequest', 'recruitment', $this->getUser(), array('rec_vac_req_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/VacancyRequest');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'r.rec_vac_req_id' : $request->getParameter('sort');
            $this->sort2 = ($request->getParameter('sort2') == '') ? 'r.rec_vac_year' : $request->getParameter('sort2');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchOverallVacancyRequest($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->sort2, $this->order);
            $this->vacancyRequestList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeVacancyRequisition(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();
            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('VacancyRequisition', 'recruitment', $this->getUser(), array('v.rec_req_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/VacancyRequisition');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'v.rec_req_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchVacancyRequisition($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->vacancyRequisitionList = $res['data'];
//            die(print_r(vacancyRequisitionList));


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSaveVacancyRequisition(sfWebRequest $request) {
        $encrypt = new EncryptionHandler();

        try {
            if (!strlen($request->getParameter('lock'))) {
                $this->mode = 0;
            } else {
                $this->mode = $request->getParameter('lock');
            }
            $ebLockid = $encrypt->decrypt($request->getParameter('id'));

            if (isset($this->mode)) {
                if ($this->mode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock(' hs_hr_rec_vacancy_requisition', array($ebLockid), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->mode = 1;
                    } else {

                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->mode = 0;
                    }
                } else if ($this->mode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock(' hs_hr_rec_vacancy_requisition', array($ebLockid), 1);
                    $this->mode = 0;
                }
            }

            $this->userCulture = $this->getUser()->getCulture();
            $vacancyRequisitionService = new VacancyRequisitionService();

            $this->GradeList = $vacancyRequisitionService->getGradeList();
            $this->DesignationList = $vacancyRequisitionService->getDesignationList();
            $this->RecruitmentTypeList = $vacancyRequisitionService->getRecruitmentTypeList();

            $requestId = $request->getParameter('id');
            if (strlen($requestId)) {
                $requestId = $encrypt->decrypt($request->getParameter('id'));
                if (!strlen($this->mode)) {
                    $this->mode = 0;
                }
                $this->vacancyRequisitiontGetById = $vacancyRequisitionService->readVacancyRequisition($requestId);

                if (!$this->vacancyRequisitiontGetById) {
                    $this->setMessage('WARNING', array($this->getContext()->geti18n()->__('Record Not Found')));
                    $this->redirect('recruitment/VacancyRequisition');
                }
            } else {
                $this->mode = 1;
            }


            if ($request->isMethod('post')) {

                if (strlen($request->getParameter('txtHiddenReqID'))) {
                    $VacancyRequisition = $vacancyRequisitionService->readVacancyRequisition($request->getParameter('txtHiddenReqID'));
                } else {
                    $VacancyRequisition = new VacancyRequisition();
                }


                $vacancyRequisitionService->saveVacancyRequisition($VacancyRequisition, $request);
                if (strlen($requestId)) {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Updated')));
                    $this->redirect('recruitment/SaveVacancyRequisition?id=' . $requestId . '&lock=0');
                } else {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Added')));
                    $this->redirect('recruitment/VacancyRequisition');
                }
            }
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/VacancyRequisition');
        } catch (sfStopException $sf) {
            $this->redirect('recruitment/VacancyRequisition');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/VacancyRequisition');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDeleteVacancyRequisition(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {

            $RecDeleteService = new RecDeleteService();

            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_vacancy_requisition', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $RecDeleteService->deleteVacancyRequisition($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_rec_vacancy_requisition', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (sfStorageException $e) { 
                
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/VacancyRequisition');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/VacancyRequisition');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
            
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to reject.'));
        }
        $this->redirect('recruitment/VacancyRequisition');
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAdvertisement(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('Advertisement', 'recruitment', $this->getUser(), array('rec_adv_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/Advertisement');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'a.rec_adv_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchAdvertisement($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->advertisementList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSaveAdvertisement(sfWebRequest $request) {

//        die(print_r($request));
        $encrypt = new EncryptionHandler();

            if (!strlen($request->getParameter('lock'))) {
                $this->mode = 0;
            } else {
                $this->mode = $request->getParameter('lock');
            }
            $ebLockid = $encrypt->decrypt($request->getParameter('id'));

            if (isset($this->mode)) {
                if ($this->mode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock('hs_hr_rec_advertisement', array($ebLockid), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->mode = 1;
                    } else {

                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->mode = 0;
                    }
                } else if ($this->mode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock('hs_hr_rec_advertisement', array($ebLockid), 1);
                    $this->mode = 0;
                }
            }

            $this->userCulture = $this->getUser()->getCulture();
            $advertisementService = new AdvertisementService();
            $this->advertisementService = new AdvertisementService();
            $this->vacancyTitleList = $advertisementService->getVacancyTitleList();

            //Max
            $this->maxid = $advertisementService->readMaxAdvertisementid();
            if ($this->maxid[0]['MAX'] == 0) {
                $this->advid = 1;
            }
            $this->advid = $this->maxid[0]['MAX'] + 1;

            $requestId = $request->getParameter('id');
            if (strlen($requestId)) {
                $requestId = $encrypt->decrypt($request->getParameter('id'));
                if (!strlen($this->mode)) {
                    $this->mode = 0;
                }
                $this->advertisementGetById = $advertisementService->readAdvertisement($requestId);


                $isAttach = $advertisementService->readattach($requestId);
                $isChargeSheet = $isAttach[0]['count'];
                $this->isChargeSheet = $isChargeSheet;

                $this->chargeSheet = $advertisementService->readChargeSheet($requestId);


                if (!$this->advertisementGetById) {
                    $this->setMessage('WARNING', array($this->getContext()->geti18n()->__('Record Not Found')));
                    $this->redirect('recruitment/Advertisement');
                }
            } else {
                $this->mode = 1;
            }

            if ($request->isMethod('POST')) {

                try {
                if (strlen($request->getParameter('txtHiddenReqID'))) {
                    $advertisement = $advertisementService->readAdvertisement($request->getParameter('txtHiddenReqID'));
                } else {
                    $advertisement = new Advertisement();
                }

                $advertisementService->saveAdvertisement($advertisement, $request);
                 } catch (Doctrine_Connection_Exception $e) {
                    $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('recruitment/Advertisement');
                } catch (sfStopException $sf) {

                } catch (Exception $e) {

                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('recruitment/Advertisement');
                }
            
                try {
                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                //$maxFileSizeDis = $sysConfs->getMaxFileSizeDis();
                //$sysConfinst = OrangeConfig::getInstance()->getSysConf();
//            if($request->getParameter('txtletter')){
//                    throw new Exception("Maxfile size  Should be less than 10MB", 1);
//
//                }
            //die (print_r($_FILES));
                if (array_key_exists('txtletter', $_FILES)) {
                    foreach ($_FILES as $file) {

                        if ($file['tmp_name'] > '') {
                            if (!in_array(end(explode(".", strtolower($file['name']))), $sysConfs->allowedExtensions)) { 
                                throw new Exception("Invalid File Type", 8);
                            }
                        }
                    }
                } else {
                    throw new Exception("Invalid File Type", 6);
                }

                $fileName = $_FILES['txtletter']['name'];
                $tmpName = $_FILES['txtletter']['tmp_name'];
                $fileSize = $_FILES['txtletter']['size'];
                $fileType = $_FILES['txtletter']['type'];

                //$sysConfinst = OrangeConfig::getInstance()->getSysConf();
                //$sysConfs = new sysConf();
                $maxFileSizeDis = $sysConfs->getMaxFilesize();

                if ($fileSize > $maxFileSizeDis) { 

                    throw new Exception("Maxfile size  Should be less than 10MB", 1);
                }
                
                } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/Advertisement');
            }
                
                try {
                $fp = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                $content = addslashes($content);


                if (strlen($content)) {                    
                    $deleteAttachment = $advertisementService->readattach($requestId);

                    foreach ($deleteAttachment as $attach) {
                        if ($attach['count'] == 1) {
                            $advertisementService->updatch($requestId);
                            $advertisementAttachment = new AdvertisementAttachment();
                            $advertisementAttachment->setRec_adv_id($requestId);
                            $advertisementAttachment->setRec_adv_attach_filename($fileName);
                            $advertisementAttachment->setRec_adv_attach_type($fileType);
                            $advertisementAttachment->setRec_adv_attach_size($fileSize);
                            $advertisementAttachment->setRec_adv_attach_attachment($content);
                            $advertisementService->saveAdvertisementAttachment($advertisementAttachment);
                        } else {
                            $advertisementAttachment = new AdvertisementAttachment();
                            if($requestId!=null){
                            $advertisementAttachment->setRec_adv_id($requestId);
                            }else{
                            $advertisementAttachment->setRec_adv_id($this->advid);
                            }
                            $advertisementAttachment->setRec_adv_attach_filename($fileName);
                            $advertisementAttachment->setRec_adv_attach_type($fileType);
                            $advertisementAttachment->setRec_adv_attach_size($fileSize);
                            $advertisementAttachment->setRec_adv_attach_attachment($content);
                            $advertisementService->saveAdvertisementAttachment($advertisementAttachment);
                        }
                    }
                }

                if (strlen($requestId)) {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Updated')));
                    $this->redirect('recruitment/SaveAdvertisement?id=' . $encrypt->encrypt($requestId) . '&lock=0');
                } else {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Added')));
                    $this->redirect('recruitment/Advertisement');
                }
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/Advertisement');
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {

            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/Advertisement');
        }
            }
        
    }

    public function executeImagePopup(sfWebRequest $request) {
        $advertisementService = new AdvertisementService();

        $encrypt = new EncryptionHandler();
        $id = $encrypt->decrypt($request->getParameter('id'));
        $aid = $encrypt->decrypt($request->getParameter('adid'));
//          die(print_r($id ."########".$aid));
        $attachment = $advertisementService->getAttachment($id, $aid);

        $outname = stripslashes($attachment[0]['rec_adv_attach_attachment']);
        $type = stripslashes($attachment[0]['rec_adv_attach_type']);

        $name = stripslashes($attachment[0]['rec_adv_attach_filename']);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header('Content-Description: File Transfer');
        header("Content-type:" . $type);
        header('Content-disposition: attachment; filename=' . $name);
        echo($outname);
        exit;
    }

    public function executeImagePopup2(sfWebRequest $request) {
        $candidateService = new CandidateService();

        $encrypt = new EncryptionHandler();
        $id = $encrypt->decrypt($request->getParameter('id'));
        $aid = $encrypt->decrypt($request->getParameter('adid'));
        
        $attachment = $candidateService->getCvAttachment($id, $aid);
      
        $outname = stripslashes($attachment[0]['rec_cv_attach_attachment']);

        
        $type = stripslashes($attachment[0]['rec_cv_attach_type']);

        $name = stripslashes($attachment[0]['rec_cv_attach_filename']);
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header('Content-Description: File Transfer');
        header("Content-type:" . $type);
        header('Content-disposition: attachment; filename=' . $name);
        echo($outname);
        exit;
        die;
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDeleteAdvertisement(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {
            $advertisementService = new AdvertisementService();
            $RecDeleteService = new RecDeleteService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_advertisement', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $advertisementService->updatch($ids[$i]);
                        $RecDeleteService->deleteAdvertisement($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_rec_advertisement', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/Advertisement');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('recruitment/Advertisement');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to reject.'));
        }
        $this->redirect('recruitment/Advertisement');
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeCandidateInterview(sfWebRequest $request) {
        try {

            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('Candidate', 'recruitment', $this->getUser(), array('c.rec_can_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));
//
//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/CandidateInterview');
//                }
//            }

            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'c.rec_can_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchCanidateInterview($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->candidateList = $res['data'];

            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeHRCandidateInterview(sfWebRequest $request) {
        try {

            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();

            $this->sorter = new ListSorter('Candidate', 'recruitment', $this->getUser(), array('c.rec_can_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'c.rec_can_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchHRCanidateInterview($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->candidateList = $res['data'];

            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDGCandidateInterview(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $RecSearchService = new RecSearchService();


            $this->sorter = new ListSorter('Candidate', 'recruitment', $this->getUser(), array('c.rec_can_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/CandidateInterview');
//                }
//            }

            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'c.rec_can_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchDGCanidateInterview($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->candidateList = $res['data'];

            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeSaveCandidateInterview(sfWebRequest $request) {

        $encrypt = new EncryptionHandler();

        try {
            if (!strlen($request->getParameter('lock'))) {
                $this->mode = 0;
            } else {
                $this->mode = $request->getParameter('lock');
            }
            $ebLockid = $encrypt->decrypt($request->getParameter('id'));

            if (isset($this->mode)) {
                if ($this->mode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock(' hs_hr_rec_candidate', array($ebLockid), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->mode = 1;
                    } else {

                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->mode = 0;
                    }
                } else if ($this->mode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock(' hs_hr_rec_candidate', array($ebLockid), 1);
                    $this->mode = 0;
                }
            }


            $this->userCulture = $this->getUser()->getCulture();
            $interviewService = new InterviewService();
            $this->interviewService = $interviewService;

            $this->languageList = $interviewService->getLanguageList();
            $this->VacancyTitle = $interviewService->getVacancyTitleList();
            $this->genderList = $interviewService->getGenderList();

            $requestId = $request->getParameter('id');

            if (strlen($requestId)) {
                $requestId = $encrypt->decrypt($request->getParameter('id'));
                if (!strlen($this->mode)) {
                    $this->mode = 0;
                }
                $this->candidateGetById = $interviewService->readCandidate($requestId);

                if (!$this->candidateGetById) {
                    $this->setMessage('WARNING', array($this->getContext()->geti18n()->__('Record Not Found')));
                    $this->redirect('recruitment/CandidateInterview');
                }
            } else {
                $this->mode = 1;
            }
            $this->type = $request->getParameter('type');
            if ($request->isMethod('post')) {
                if (strlen($request->getParameter('txtHiddenReqID'))) {
                    $candidate = $interviewService->readCandidate($request->getParameter('txtHiddenReqID'));
                } else {
                    $candidate = new Candidate();
                }
//           
                if (strlen($request->getParameter('txtMarks'))) {
                    $candidate->setRec_can_interview_marks(trim($request->getParameter('txtMarks')));
                } else {
                    $candidate->setRec_can_interview_marks(null);
                }
                if (strlen($request->getParameter('optrate'))) {
                    $candidate->setRec_can_interview_status(trim($request->getParameter('optrate')));
                } else {
                    $candidate->setRec_can_interview_status(null);
                }

                $this->saveCandidate = $interviewService->saveInterviewInfo($candidate);

                if (strlen($requestId)) {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Updated')));
                    $this->redirect('recruitment/SaveCandidateInterview?id=' . $requestId . '&lock=0');
                } else {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Added')));
                    $this->redirect('recruitment/CandidateInterview');
                }
            }
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/CandidateInterview');
        } catch (sfStopException $sf) {
            $this->redirect('recruitment/CandidateInterview');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/CandidateInterview');
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAjaxTableLock(sfWebRequest $request) {

        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $ebLockid = $request->getParameter('cId');

        if (isset($this->lockMode)) {

            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_rec_vacancy_request', array($ebLockid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {

                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->mode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_rec_vacancy_request', array($ebLockid), 1);
                $this->lockMode = 0;
            }
        }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAjaxTableLockCandidate(sfWebRequest $request) {

        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $ebLockid = $request->getParameter('cId');

        if (isset($this->lockMode)) {

            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_rec_candidate', array($ebLockid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {

                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->mode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_rec_candidate', array($ebLockid), 1);
                $this->lockMode = 0;
            }
        }
    }

    public function executeIsRefNumberNoExists(sfWebRequest $request) {

        $refNumber = $request->getParameter('RefNumber');


        $vacancyRequestService = new VacancyRequestService();
        $result = $vacancyRequestService->isRefNumberNoExists(trim($refNumber));
        die(print_r($result));
        echo json_encode($result[0]);
        die;
    }

//FinalizedVacancy
    public function executeFinalizedVacancy(sfWebRequest $request) {
        try {

            $this->Culture = $this->getUser()->getCulture();

            $RecSearchService = new RecSearchService();

            $this->sorter = new ListSorter('Advertisement', 'recruitment', $this->getUser(), array('rec_adv_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/FinalizedVacancy');
//                }
//            }
            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            //$this->searchValue = $request->getParameter('id');

            $this->sort = ($request->getParameter('sort') == '') ? 'a.rec_adv_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $RecSearchService->searchAdvertisement($this->searchMode, $this->searchValue, $this->Culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->advertisementList = $res['data'];


            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

//    Candidate
    public function executeCandidate(sfWebRequest $request) {
        try {
            $encrypt = new EncryptionHandler();

            $this->Culture = $this->getUser()->getCulture();

            $candidateService = new CandidateService();
            $this->candidateService = $candidateService;

            $this->sorter = new ListSorter('Candidate', 'recruitment', $this->getUser(), array('c.rec_can_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/Candidate');
//                }
//            }

            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'c.rec_can_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $this->vacancyId = $request->getParameter('vacancyId');
            $VacancyRequisitionDao= new VacancyRequisitionDao();
            $this->vacency=$VacancyRequisitionDao->getVacancyRequisitionById($request->getParameter('vacancyId'));
            //die(print_r($this->vacency));
            $res = $candidateService->searchCandidate($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order, $this->vacancyId);
            $this->candidateList = $res['data'];

            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveCandidate(sfWebRequest $request) {

        $encrypt = new EncryptionHandler();
        
            if (!strlen($request->getParameter('lock'))) {
                $this->mode = 0;
            } else {
                $this->mode = $request->getParameter('lock');
            }
            $ebLockid = $encrypt->decrypt($request->getParameter('id'));

            $this->vacancyId = $request->getParameter('vacancyId');

            if (isset($this->mode)) {
                if ($this->mode == 1) {

                    $conHandler = new ConcurrencyHandler();

                    $recordLocked = $conHandler->setTableLock(' hs_hr_rec_candidate', array($ebLockid), 1);

                    if ($recordLocked) {
                        // Display page in edit mode
                        $this->mode = 1;
                    } else {

                        $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                        $this->mode = 0;
                    }
                } else if ($this->mode == 0) {
                    $conHandler = new ConcurrencyHandler();
                    $recordLocked = $conHandler->resetTableLock(' hs_hr_rec_candidate', array($ebLockid), 1);
                    $this->mode = 0;
                }
            }

            $this->userCulture = $this->getUser()->getCulture();
            $candidateService = new CandidateService();
            $this->candidateService = $candidateService;

            $this->languageList = $candidateService->getLanguageList();
            $this->VacancyTitle = $candidateService->getVacancyTitleList();
            $this->genderList = $candidateService->getGenderList();
            $this->maxId = $candidateService->readmaxretid();
                $maxid = $this->maxId[0]['MAX'];
                if ($this->maxid[0]['MAX'] == 0) {
                    $this->nextId = 1;
                }
                $this->nextId = $maxid+1;


            $requestId = $request->getParameter('id');
            if (strlen($requestId)) {
                $requestId = $encrypt->decrypt($request->getParameter('id'));
                if (!strlen($this->mode)) {
                    $this->mode = 0;
                }
                $this->candidateGetById = $candidateService->readCandidate($requestId);
                $this->vacancyId = $this->candidateGetById->rec_req_id;
                if (!$this->candidateGetById) {
                    $this->setMessage('WARNING', array($this->getContext()->geti18n()->__('Record Not Found')));
                    $this->redirect('recruitment/Candidate?vacancyId=' . $this->vacancyId);
                }
            } else {
                $this->mode = 1;
            }
            $isAttach = $candidateService->getAttachment($request->getParameter('txtHiddenReqID'));
            $this->isChargeSheet = $isAttach[0]['count'];

            $this->chargeSheet = $candidateService->readChargeSheet($requestId);
            $this->type = $request->getParameter('type');
            if ($request->isMethod('post')) {
                try {
                if ($request->getParameter('txtHiddenReqID')!= null) {
                    $candidate = $candidateService->readCandidate($request->getParameter('txtHiddenReqID'));

                    $this->chargeSheet = $candidateService->readChargeSheet($requestId);
                } else {
                    $candidate = new Candidate();
                }

                $this->saveCandidate = $candidateService->saveCandidate($candidate, $request);

                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                //$maxFileSizeDis = $sysConfs->getMaxFileSizeDis();
                //$sysConfinst = OrangeConfig::getInstance()->getSysConf();

                //Max
                $this->maxId = $candidateService->readmaxretid();
                $maxid = $this->maxId[0]['MAX'];
                if ($this->maxid[0]['MAX'] == 0) {
                    $this->nextId = 1;
                }
                $this->nextId = $maxid;

//                die(print_r($this->nextId));

                if (array_key_exists('txtletter', $_FILES)) {
                    foreach ($_FILES as $file) {

                        if ($file['tmp_name'] > '') {
                            if (!in_array(end(explode(".", strtolower($file['name']))), $sysConfs->allowedExtensions)) {
                                throw new Exception("Invalid File Type", 8);
                            }
                        }
                    }
                } else {
                    throw new Exception("Invalid File Type", 6);
                }

                $fileName = $_FILES['txtletter']['name'];
                $tmpName = $_FILES['txtletter']['tmp_name'];
                $fileSize = $_FILES['txtletter']['size'];
                $fileType = $_FILES['txtletter']['type'];

                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                $maxFileSizeDis = $sysConfs->getMaxFilesize();

                if ($fileSize > $maxFileSizeDis) {

                    throw new Exception("Maxfile size  Should be less than 10MB", 1);
                }

                $fp = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                $content = addslashes($content);

                if (strlen($content)) {
                    $deleteAttachment = $candidateService->getAttachment($requestId);

                    foreach ($deleteAttachment as $attach) {
                        if ($attach['count'] == 1) {

                            $candidateService->updatch($requestId);
                            $cvAttachment = new CvAttachment();
                            $cvAttachment->setRec_can_id($request->getParameter('txtHiddenReqID'));
                            $cvAttachment->setRec_cv_attach_filename($fileName);
                            $cvAttachment->setRec_cv_attach_type($fileType);
                            $cvAttachment->setRec_cv_attach_size($fileSize);
                            $cvAttachment->setRec_cv_attach_attachment($content);
                            $candidateService->saveCandidateCvAttachment($cvAttachment);
                        } else {
                            $cvAttachment = new CvAttachment();
                            if (strlen($request->getParameter('txtHiddenReqID'))) {
                            $cvAttachment->setRec_can_id($request->getParameter('txtHiddenReqID'));
                            }else{
                            $cvAttachment->setRec_can_id($this->nextId);    
                            }
                            $cvAttachment->setRec_cv_attach_filename($fileName);
                            $cvAttachment->setRec_cv_attach_type($fileType);
                            $cvAttachment->setRec_cv_attach_size($fileSize);
                            $cvAttachment->setRec_cv_attach_attachment($content);
                            $candidateService->saveCandidateCvAttachment($cvAttachment);
                        }
                    }
                }
                
                } catch (Doctrine_Connection_Exception $e) {
                    $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('recruitment/Candidate?vacancyId=' . $encrypt->encrypt($this->vacancyId));
                } catch (sfStopException $sf) {

                } catch (Exception $e) {
                    $errMsg = new CommonException($e->getMessage(), $e->getCode());
                    $this->setMessage('WARNING', $errMsg->display());
                    $this->redirect('recruitment/Candidate?vacancyId=' . $encrypt->encrypt($this->vacancyId));
                }

                if (strlen($requestId)) {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Updated')));
                    $this->redirect('recruitment/SaveCandidate?id=' . $encrypt->encrypt($requestId) . '&lock=0');
                } else {
                    $this->setMessage('SUCCESS', array($this->getContext()->geti18n()->__('Successfully Added')));
                    $this->redirect('recruitment/Candidate?vacancyId=' . $encrypt->encrypt($this->vacancyId));
                }
            }
        
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeUpdateDGCandidateRequest(sfWebRequest $request) {

        $candidateService = new CandidateService();
        $this->candidateService = $candidateService;

        $id = $request->getParameter('cId');
        $status = $request->getParameter('value');

        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
            if ($status != null) {
                $this->isupdated = $candidateService->updateDGCandidateRequest($id, $status);
                $conn->commit();
            }
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/DGCandidateInterview');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('recruitment/DGCandidateInterview');
        }
        $this->isupdated = "true";
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDeleteCandidate(sfWebRequest $request) {

        $formId = $request->getParameter('formId');
        $vacancyId= $request->getParameter('vacancyId');
//die(print_r($formId));
        if (count($request->getParameter('chkLocID')) > 0) {
            $candidateService = new CandidateService();
            $RecDeleteService = new RecDeleteService();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_rec_candidate', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $candidateService->updatch($ids[$i]);
                        $RecDeleteService->deleteCandidate($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_rec_candidate', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                if($vacancyId!=null){
                    $this->redirect('recruitment/Candidate?vacancyId='.$vacancyId);
                }
                if ($formId == '1') {
                    $this->redirect('recruitment/CandidateInterview');
                } else if ($formId == '2') {
                    $this->redirect('recruitment/HRCandidateInterview');
                } else if ($formId == '3') {
                    $this->redirect('recruitment/DGCandidateInterview');
                } else {
                    $this->redirect('recruitment/CandidatePIMRegistation');
                }
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                if($vacancyId!=null){
                    $this->redirect('recruitment/Candidate?vacancyId='.$vacancyId);
                }
                if ($formId == '1') {
                    $this->redirect('recruitment/CandidateInterview');
                } else if ($formId == '2') {
                    $this->redirect('recruitment/HRCandidateInterview');
                } else if ($formId == '3') {
                    $this->redirect('recruitment/DGCandidateInterview');
                } else {
                    $this->redirect('recruitment/CandidatePIMRegistation');
                }
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to reject.'));
        }
        if($vacancyId!=null){
                    $this->redirect('recruitment/Candidate?vacancyId='.$vacancyId);
                }
        if ($formId == '1') {
            $this->redirect('recruitment/CandidateInterview');
        } else if ($formId == '2') {
            $this->redirect('recruitment/HRCandidateInterview');
        } else if ($formId == '3') {
            $this->redirect('recruitment/DGCandidateInterview');
        } else {
            $this->redirect('recruitment/CandidatePIMRegistation');
        }
    }

    public function executeViewAttachment(sfWebRequest $request) {

        $canId = $request->getParameter('canId');

        $candidateService = new CandidateService();
        $attachment = $candidateService->getAttachment($canId);

        if (!empty($attachment)) {

            $contents = $attachment->attachment;
            $contentType = $attachment->file_type;
            $fileName = $attachment->filename;
            $fileLength = $attachment->size;

            $response = $this->getResponse();
            $response->setHttpHeader('Pragma', 'public');

            $response->setHttpHeader('Expires', '0');
            $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
            $response->setHttpHeader("Cache-Control", "private", false);
            $response->setHttpHeader("Content-Type", $contentType);
            $response->setHttpHeader("Content-Disposition", 'attachment; filename="' . $fileName . '";');
            $response->setHttpHeader("Content-Transfer-Encoding", "binary");
            $response->setHttpHeader("Content-Length", $fileLength);

            $response->setContent($contents);
            $response->send();
        } else {
            $response = $this->getResponse();
            $response->setStatusCode(404, 'This attachment does not exist');
        }

        return sfView::NONE;
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeUpdateDGInterviewRequest(sfWebRequest $request) {

        $interviewService = new InterviewService();
        $this->interviewService = $interviewService;

        $id = $request->getParameter('cId');
        $status = $request->getParameter('value');
        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
//            if ($noOfVacancies != 0) {
            $this->isupdated = $interviewService->updateDGInterviewRequest($id, $status);
            $conn->commit();
//            }
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/UpdateDGInterviewRequest');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('recruitment/UpdateDGInterviewRequest');
        }
        $this->isupdated = "true";
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeUpdateHRInterviewRequest(sfWebRequest $request) {

        $interviewService = new InterviewService();
        $this->interviewService = $interviewService;
        $id = $request->getParameter('cId');
        $status = $request->getParameter('value');
        $conn = Doctrine_Manager::getInstance()->connection();
        $conn->beginTransaction();

        try {
//            if ($noOfVacancies != 0) {
            $this->isupdated = $interviewService->updateHRInterviewRequest($id, $status);
            $conn->commit();
//            }
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/UpdateHRInterviewRequest');
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());

            $this->redirect('recruitment/UpdateHRInterviewRequest');
        }
        $this->isupdated = "true";
    }

    public function executeCandidatePIMRegistation(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();

            $interviewService = new InterviewService();
            $this->interviewService = $interviewService;

            $this->sorter = new ListSorter('Candidate', 'recruitment', $this->getUser(), array('c.rec_can_id', ListSorter::ASCENDING));

            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

//            if ($request->getParameter('mode') == 'search') {
//                if ($request->getParameter('searchMode') != 'all' && trim($request->getParameter('searchValue')) == '') {
//                    $this->setMessage('NOTICE', array('Select the field to search'));
//                    $this->redirect('recruitment/CandidateInterview');
//                }
//            }

            $this->searchMode = ($request->getParameter('searchMode') == '') ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == '') ? '' : trim($request->getParameter('searchValue'));

            $this->sort = ($request->getParameter('sort') == '') ? 'c.rec_can_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');

            $res = $interviewService->searchSelectedCanidateInterview($this->searchMode, $this->searchValue, $this->culture, $request->getParameter('page'), $this->sort, $this->order);
            $this->candidateList = $res['data'];

            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
            if (count($res['data']) <= 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Sorry,Your Search did not Match any Records.", $args, 'messages')));
            }
        } catch (sfStopException $sf) {
            
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeDeleteImage(sfWebRequest $request) {

        $encryption=new EncryptionHandler();
        $id = $request->getParameter('id');
        $advertisementService = new AdvertisementService();
        
        try {
            $advertisementService->deleteImage($id, $adid);
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/SaveAdvertisement?id=' . $encryption->encrypt($request->getParameter('id')) . '&lock=0');
        }
        $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__('Successfully Saved', $args, 'messages')));
        $this->redirect('recruitment/SaveAdvertisement?id=' . $encryption->encrypt($request->getParameter('id')). '&lock=0');
    }

    public function executeDeleteImage2(sfWebRequest $request) {
       $encrypt = new EncryptionHandler();
        $id =  $encrypt->decrypt($request->getParameter('id'));
        $cdid = $encrypt->decrypt($request->getParameter('cdid'));

        $AdvertisementDao= new AdvertisementDao();

        try {
            $AdvertisementDao->deleteImage2($id);
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('recruitment/SaveCandidate?id=' . $encrypt->encrypt($cdid) . '&lock=0');
        }
        $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__('Successfully Saved', $args, 'messages')));
        $this->redirect('recruitment/SaveCandidate?id=' . $encrypt->encrypt($cdid) . '&lock=0');
    }
    
      public function executeAdvertisementDates(sfWebRequest $request) {

        $titleId = $request->getParameter('titleId');
        $AdvertisementDao = new AdvertisementDao();
        $this->VacancyRequisition = $AdvertisementDao->getVacancyRequisition($titleId);
            $arr = array();
        foreach ($this->VacancyRequisition as $row) {           
            $arr[0] = $row['rec_req_opening_date'];
            $arr[1] = $row['rec_req_closing_date'];            
        }
        echo json_encode($arr);
        die;
    }

    public function setMessage($messageType, $message = array(), $persist=true) {
        $this->getUser()->setFlash('messageType', $messageType, $persist);
        $this->getUser()->setFlash('message', $message, $persist);
    }

}
