<?php
/*
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

class Google_Service_Dataflow_Job extends Google_Collection
{
  protected $collection_key = 'tempFiles';
  public $clientRequestId;
  public $createTime;
  public $currentState;
  public $currentStateTime;
  protected $environmentType = 'Google_Service_Dataflow_Environment';
  protected $environmentDataType = '';
  protected $executionInfoType = 'Google_Service_Dataflow_JobExecutionInfo';
  protected $executionInfoDataType = '';
  public $id;
  public $name;
  public $projectId;
  public $replaceJobId;
  public $replacedByJobId;
  public $requestedState;
  protected $stepsType = 'Google_Service_Dataflow_Step';
  protected $stepsDataType = 'array';
  public $tempFiles;
  public $transformNameMapping;
  public $type;

  public function setClientRequestId($clientRequestId)
  {
    $this->clientRequestId = $clientRequestId;
  }
  public function getClientRequestId()
  {
    return $this->clientRequestId;
  }
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  public function getCreateTime()
  {
    return $this->createTime;
  }
  public function setCurrentState($currentState)
  {
    $this->currentState = $currentState;
  }
  public function getCurrentState()
  {
    return $this->currentState;
  }
  public function setCurrentStateTime($currentStateTime)
  {
    $this->currentStateTime = $currentStateTime;
  }
  public function getCurrentStateTime()
  {
    return $this->currentStateTime;
  }
  public function setEnvironment(Google_Service_Dataflow_Environment $environment)
  {
    $this->environment = $environment;
  }
  public function getEnvironment()
  {
    return $this->environment;
  }
  public function setExecutionInfo(Google_Service_Dataflow_JobExecutionInfo $executionInfo)
  {
    $this->executionInfo = $executionInfo;
  }
  public function getExecutionInfo()
  {
    return $this->executionInfo;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  public function getProjectId()
  {
    return $this->projectId;
  }
  public function setReplaceJobId($replaceJobId)
  {
    $this->replaceJobId = $replaceJobId;
  }
  public function getReplaceJobId()
  {
    return $this->replaceJobId;
  }
  public function setReplacedByJobId($replacedByJobId)
  {
    $this->replacedByJobId = $replacedByJobId;
  }
  public function getReplacedByJobId()
  {
    return $this->replacedByJobId;
  }
  public function setRequestedState($requestedState)
  {
    $this->requestedState = $requestedState;
  }
  public function getRequestedState()
  {
    return $this->requestedState;
  }
  public function setSteps($steps)
  {
    $this->steps = $steps;
  }
  public function getSteps()
  {
    return $this->steps;
  }
  public function setTempFiles($tempFiles)
  {
    $this->tempFiles = $tempFiles;
  }
  public function getTempFiles()
  {
    return $this->tempFiles;
  }
  public function setTransformNameMapping($transformNameMapping)
  {
    $this->transformNameMapping = $transformNameMapping;
  }
  public function getTransformNameMapping()
  {
    return $this->transformNameMapping;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}
