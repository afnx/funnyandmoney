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
 * The "permissions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $permissions = $driveService->permissions;
 *  </code>
 */
class Google_Service_Drive_PermissionsResource extends Google_Service_Resource
{
  /**
   * Creates a permission for a file. (permissions.create)
   *
   * @param string $fileId The ID of the file.
   * @param Google_Permission $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string emailMessage A custom message to include in the
   * notification email.
   * @opt_param bool sendNotificationEmail Whether to send a notification email
   * when sharing to users or groups. This defaults to true for users and groups,
   * and is not allowed for other requests. It must not be disabled for ownership
   * transfers.
   * @opt_param bool transferOwnership Whether to transfer ownership to the
   * specified user and downgrade the current owner to a writer. This parameter is
   * required as an acknowledgement of the side effect.
   * @return Google_Service_Drive_Permission
   */
  public function create($fileId, Google_Service_Drive_Permission $postBody, $optParams = array())
  {
    $params = array('fileId' => $fileId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('create', array($params), "Google_Service_Drive_Permission");
  }
  /**
   * Deletes a permission. (permissions.delete)
   *
   * @param string $fileId The ID of the file.
   * @param string $permissionId The ID of the permission.
   * @param array $optParams Optional parameters.
   */
  public function delete($fileId, $permissionId, $optParams = array())
  {
    $params = array('fileId' => $fileId, 'permissionId' => $permissionId);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }
  /**
   * Gets a permission by ID. (permissions.get)
   *
   * @param string $fileId The ID of the file.
   * @param string $permissionId The ID of the permission.
   * @param array $optParams Optional parameters.
   * @return Google_Service_Drive_Permission
   */
  public function get($fileId, $permissionId, $optParams = array())
  {
    $params = array('fileId' => $fileId, 'permissionId' => $permissionId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Drive_Permission");
  }
  /**
   * Lists a file's permissions. (permissions.listPermissions)
   *
   * @param string $fileId The ID of the file.
   * @param array $optParams Optional parameters.
   * @return Google_Service_Drive_PermissionList
   */
  public function listPermissions($fileId, $optParams = array())
  {
    $params = array('fileId' => $fileId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Drive_PermissionList");
  }
  /**
   * Updates a permission with patch semantics. (permissions.update)
   *
   * @param string $fileId The ID of the file.
   * @param string $permissionId The ID of the permission.
   * @param Google_Permission $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool transferOwnership Whether to transfer ownership to the
   * specified user and downgrade the current owner to a writer. This parameter is
   * required as an acknowledgement of the side effect.
   * @return Google_Service_Drive_Permission
   */
  public function update($fileId, $permissionId, Google_Service_Drive_Permission $postBody, $optParams = array())
  {
    $params = array('fileId' => $fileId, 'permissionId' => $permissionId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_Drive_Permission");
  }
}
