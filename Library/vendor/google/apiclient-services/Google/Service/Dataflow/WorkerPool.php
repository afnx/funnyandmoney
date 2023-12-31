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

class Google_Service_Dataflow_WorkerPool extends Google_Collection
{
  protected $collection_key = 'packages';
  protected $autoscalingSettingsType = 'Google_Service_Dataflow_AutoscalingSettings';
  protected $autoscalingSettingsDataType = '';
  protected $dataDisksType = 'Google_Service_Dataflow_Disk';
  protected $dataDisksDataType = 'array';
  public $defaultPackageSet;
  public $diskSizeGb;
  public $diskSourceImage;
  public $diskType;
  public $kind;
  public $machineType;
  public $metadata;
  public $network;
  public $numThreadsPerWorker;
  public $numWorkers;
  public $onHostMaintenance;
  protected $packagesType = 'Google_Service_Dataflow_Package';
  protected $packagesDataType = 'array';
  public $poolArgs;
  public $subnetwork;
  protected $taskrunnerSettingsType = 'Google_Service_Dataflow_TaskRunnerSettings';
  protected $taskrunnerSettingsDataType = '';
  public $teardownPolicy;
  public $workerHarnessContainerImage;
  public $zone;

  public function setAutoscalingSettings(Google_Service_Dataflow_AutoscalingSettings $autoscalingSettings)
  {
    $this->autoscalingSettings = $autoscalingSettings;
  }
  public function getAutoscalingSettings()
  {
    return $this->autoscalingSettings;
  }
  public function setDataDisks($dataDisks)
  {
    $this->dataDisks = $dataDisks;
  }
  public function getDataDisks()
  {
    return $this->dataDisks;
  }
  public function setDefaultPackageSet($defaultPackageSet)
  {
    $this->defaultPackageSet = $defaultPackageSet;
  }
  public function getDefaultPackageSet()
  {
    return $this->defaultPackageSet;
  }
  public function setDiskSizeGb($diskSizeGb)
  {
    $this->diskSizeGb = $diskSizeGb;
  }
  public function getDiskSizeGb()
  {
    return $this->diskSizeGb;
  }
  public function setDiskSourceImage($diskSourceImage)
  {
    $this->diskSourceImage = $diskSourceImage;
  }
  public function getDiskSourceImage()
  {
    return $this->diskSourceImage;
  }
  public function setDiskType($diskType)
  {
    $this->diskType = $diskType;
  }
  public function getDiskType()
  {
    return $this->diskType;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setMachineType($machineType)
  {
    $this->machineType = $machineType;
  }
  public function getMachineType()
  {
    return $this->machineType;
  }
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }
  public function getMetadata()
  {
    return $this->metadata;
  }
  public function setNetwork($network)
  {
    $this->network = $network;
  }
  public function getNetwork()
  {
    return $this->network;
  }
  public function setNumThreadsPerWorker($numThreadsPerWorker)
  {
    $this->numThreadsPerWorker = $numThreadsPerWorker;
  }
  public function getNumThreadsPerWorker()
  {
    return $this->numThreadsPerWorker;
  }
  public function setNumWorkers($numWorkers)
  {
    $this->numWorkers = $numWorkers;
  }
  public function getNumWorkers()
  {
    return $this->numWorkers;
  }
  public function setOnHostMaintenance($onHostMaintenance)
  {
    $this->onHostMaintenance = $onHostMaintenance;
  }
  public function getOnHostMaintenance()
  {
    return $this->onHostMaintenance;
  }
  public function setPackages($packages)
  {
    $this->packages = $packages;
  }
  public function getPackages()
  {
    return $this->packages;
  }
  public function setPoolArgs($poolArgs)
  {
    $this->poolArgs = $poolArgs;
  }
  public function getPoolArgs()
  {
    return $this->poolArgs;
  }
  public function setSubnetwork($subnetwork)
  {
    $this->subnetwork = $subnetwork;
  }
  public function getSubnetwork()
  {
    return $this->subnetwork;
  }
  public function setTaskrunnerSettings(Google_Service_Dataflow_TaskRunnerSettings $taskrunnerSettings)
  {
    $this->taskrunnerSettings = $taskrunnerSettings;
  }
  public function getTaskrunnerSettings()
  {
    return $this->taskrunnerSettings;
  }
  public function setTeardownPolicy($teardownPolicy)
  {
    $this->teardownPolicy = $teardownPolicy;
  }
  public function getTeardownPolicy()
  {
    return $this->teardownPolicy;
  }
  public function setWorkerHarnessContainerImage($workerHarnessContainerImage)
  {
    $this->workerHarnessContainerImage = $workerHarnessContainerImage;
  }
  public function getWorkerHarnessContainerImage()
  {
    return $this->workerHarnessContainerImage;
  }
  public function setZone($zone)
  {
    $this->zone = $zone;
  }
  public function getZone()
  {
    return $this->zone;
  }
}
