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

class Google_Service_Autoscaler_Zone extends Google_Collection
{
  protected $collection_key = 'maintenanceWindows';
  public $creationTimestamp;
  protected $deprecatedType = 'Google_Service_Autoscaler_DeprecationStatus';
  protected $deprecatedDataType = '';
  public $description;
  public $id;
  public $kind;
  protected $maintenanceWindowsType = 'Google_Service_Autoscaler_ZoneMaintenanceWindows';
  protected $maintenanceWindowsDataType = 'array';
  public $name;
  public $region;
  public $selfLink;
  public $status;

  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  public function setDeprecated(Google_Service_Autoscaler_DeprecationStatus $deprecated)
  {
    $this->deprecated = $deprecated;
  }
  public function getDeprecated()
  {
    return $this->deprecated;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setMaintenanceWindows($maintenanceWindows)
  {
    $this->maintenanceWindows = $maintenanceWindows;
  }
  public function getMaintenanceWindows()
  {
    return $this->maintenanceWindows;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function getStatus()
  {
    return $this->status;
  }
}
