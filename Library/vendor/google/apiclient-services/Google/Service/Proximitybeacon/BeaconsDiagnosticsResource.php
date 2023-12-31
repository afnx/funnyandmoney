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

/**
 * The "diagnostics" collection of methods.
 * Typical usage is:
 *  <code>
 *   $proximitybeaconService = new Google_Service_Proximitybeacon(...);
 *   $diagnostics = $proximitybeaconService->diagnostics;
 *  </code>
 */
class Google_Service_Proximitybeacon_BeaconsDiagnosticsResource extends Google_Service_Resource
{
  /**
   * List the diagnostics for a single beacon. You can also list diagnostics for
   * all the beacons owned by your Google Developers Console project by using the
   * beacon name `beacons/-`. (diagnostics.listBeaconsDiagnostics)
   *
   * @param string $beaconName Beacon that the diagnostics are for.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Specifies the maximum number of results to return.
   * Defaults to 10. Maximum 1000. Optional.
   * @opt_param string pageToken Requests results that occur after the
   * `page_token`, obtained from the response to a previous request. Optional.
   * @opt_param string alertFilter Requests only beacons that have the given
   * alert. For example, to find beacons that have low batteries use
   * `alert_filter=LOW_BATTERY`.
   * @return Google_Service_ListDiagnosticsResponse
   */
  public function listBeaconsDiagnostics($beaconName, $optParams = array())
  {
    $params = array('beaconName' => $beaconName);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Proximitybeacon_ListDiagnosticsResponse");
  }
}
