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

class Google_Service_AndroidEnterprise_AppRestrictionsSchemaRestriction extends Google_Collection
{
  protected $collection_key = 'entryValue';
  protected $defaultValueType = 'Google_Service_AndroidEnterprise_AppRestrictionsSchemaRestrictionRestrictionValue';
  protected $defaultValueDataType = '';
  public $description;
  public $entry;
  public $entryValue;
  public $key;
  public $restrictionType;
  public $title;

  public function setDefaultValue(Google_Service_AndroidEnterprise_AppRestrictionsSchemaRestrictionRestrictionValue $defaultValue)
  {
    $this->defaultValue = $defaultValue;
  }
  public function getDefaultValue()
  {
    return $this->defaultValue;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setEntry($entry)
  {
    $this->entry = $entry;
  }
  public function getEntry()
  {
    return $this->entry;
  }
  public function setEntryValue($entryValue)
  {
    $this->entryValue = $entryValue;
  }
  public function getEntryValue()
  {
    return $this->entryValue;
  }
  public function setKey($key)
  {
    $this->key = $key;
  }
  public function getKey()
  {
    return $this->key;
  }
  public function setRestrictionType($restrictionType)
  {
    $this->restrictionType = $restrictionType;
  }
  public function getRestrictionType()
  {
    return $this->restrictionType;
  }
  public function setTitle($title)
  {
    $this->title = $title;
  }
  public function getTitle()
  {
    return $this->title;
  }
}
