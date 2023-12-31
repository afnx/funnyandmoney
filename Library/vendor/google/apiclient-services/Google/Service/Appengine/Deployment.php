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

class Google_Service_Appengine_Deployment extends Google_Collection
{
  protected $collection_key = 'sourceReferences';
  protected $containerType = 'Google_Service_Appengine_ContainerInfo';
  protected $containerDataType = '';
  protected $filesType = 'Google_Service_Appengine_FileInfo';
  protected $filesDataType = 'map';
  protected $sourceReferencesType = 'Google_Service_Appengine_SourceReference';
  protected $sourceReferencesDataType = 'array';

  public function setContainer(Google_Service_Appengine_ContainerInfo $container)
  {
    $this->container = $container;
  }
  public function getContainer()
  {
    return $this->container;
  }
  public function setFiles($files)
  {
    $this->files = $files;
  }
  public function getFiles()
  {
    return $this->files;
  }
  public function setSourceReferences($sourceReferences)
  {
    $this->sourceReferences = $sourceReferences;
  }
  public function getSourceReferences()
  {
    return $this->sourceReferences;
  }
}
