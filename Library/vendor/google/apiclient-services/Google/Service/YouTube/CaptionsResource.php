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
 * The "captions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google_Service_YouTube(...);
 *   $captions = $youtubeService->captions;
 *  </code>
 */
class Google_Service_YouTube_CaptionsResource extends Google_Service_Resource
{
  /**
   * Deletes a specified caption track. (captions.delete)
   *
   * @param string $id The id parameter identifies the caption track that is being
   * deleted. The value is a caption track ID as identified by the id property in
   * a caption resource.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner Note: This parameter is intended
   * exclusively for YouTube content partners.
   *
   * The onBehalfOfContentOwner parameter indicates that the request's
   * authorization credentials identify a YouTube CMS user who is acting on behalf
   * of the content owner specified in the parameter value. This parameter is
   * intended for YouTube content partners that own and manage many different
   * YouTube channels. It allows content owners to authenticate once and get
   * access to all their video and channel data, without having to provide
   * authentication credentials for each individual channel. The actual CMS
   * account that the user authenticates with must be linked to the specified
   * YouTube content owner.
   */
  public function delete($id, $optParams = array())
  {
    $params = array('id' => $id);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }
  /**
   * Downloads a caption track. The caption track is returned in its original
   * format unless the request specifies a value for the tfmt parameter and in its
   * original language unless the request specifies a value for the tlang
   * parameter. (captions.download)
   *
   * @param string $id The id parameter identifies the caption track that is being
   * retrieved. The value is a caption track ID as identified by the id property
   * in a caption resource.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner Note: This parameter is intended
   * exclusively for YouTube content partners.
   *
   * The onBehalfOfContentOwner parameter indicates that the request's
   * authorization credentials identify a YouTube CMS user who is acting on behalf
   * of the content owner specified in the parameter value. This parameter is
   * intended for YouTube content partners that own and manage many different
   * YouTube channels. It allows content owners to authenticate once and get
   * access to all their video and channel data, without having to provide
   * authentication credentials for each individual channel. The actual CMS
   * account that the user authenticates with must be linked to the specified
   * YouTube content owner.
   * @opt_param string tfmt The tfmt parameter specifies that the caption track
   * should be returned in a specific format. If the parameter is not included in
   * the request, the track is returned in its original format.
   * @opt_param string tlang The tlang parameter specifies that the API response
   * should return a translation of the specified caption track. The parameter
   * value is an ISO 639-1 two-letter language code that identifies the desired
   * caption language. The translation is generated by using machine translation,
   * such as Google Translate.
   */
  public function download($id, $optParams = array())
  {
    $params = array('id' => $id);
    $params = array_merge($params, $optParams);
    return $this->call('download', array($params));
  }
  /**
   * Uploads a caption track. (captions.insert)
   *
   * @param string $part The part parameter specifies the caption resource parts
   * that the API response will include. Set the parameter value to snippet.
   * @param Google_Caption $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner Note: This parameter is intended
   * exclusively for YouTube content partners.
   *
   * The onBehalfOfContentOwner parameter indicates that the request's
   * authorization credentials identify a YouTube CMS user who is acting on behalf
   * of the content owner specified in the parameter value. This parameter is
   * intended for YouTube content partners that own and manage many different
   * YouTube channels. It allows content owners to authenticate once and get
   * access to all their video and channel data, without having to provide
   * authentication credentials for each individual channel. The actual CMS
   * account that the user authenticates with must be linked to the specified
   * YouTube content owner.
   * @opt_param bool sync The sync parameter indicates whether YouTube should
   * automatically synchronize the caption file with the audio track of the video.
   * If you set the value to true, YouTube will disregard any time codes that are
   * in the uploaded caption file and generate new time codes for the captions.
   *
   * You should set the sync parameter to true if you are uploading a transcript,
   * which has no time codes, or if you suspect the time codes in your file are
   * incorrect and want YouTube to try to fix them.
   * @return Google_Service_YouTube_Caption
   */
  public function insert($part, Google_Service_YouTube_Caption $postBody, $optParams = array())
  {
    $params = array('part' => $part, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_YouTube_Caption");
  }
  /**
   * Returns a list of caption tracks that are associated with a specified video.
   * Note that the API response does not contain the actual captions and that the
   * captions.download method provides the ability to retrieve a caption track.
   * (captions.listCaptions)
   *
   * @param string $part The part parameter specifies a comma-separated list of
   * one or more caption resource parts that the API response will include. The
   * part names that you can include in the parameter value are id and snippet.
   * @param string $videoId The videoId parameter specifies the YouTube video ID
   * of the video for which the API should return caption tracks.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string id The id parameter specifies a comma-separated list of IDs
   * that identify the caption resources that should be retrieved. Each ID must
   * identify a caption track associated with the specified video.
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is on behalf of.
   * @opt_param string onBehalfOfContentOwner Note: This parameter is intended
   * exclusively for YouTube content partners.
   *
   * The onBehalfOfContentOwner parameter indicates that the request's
   * authorization credentials identify a YouTube CMS user who is acting on behalf
   * of the content owner specified in the parameter value. This parameter is
   * intended for YouTube content partners that own and manage many different
   * YouTube channels. It allows content owners to authenticate once and get
   * access to all their video and channel data, without having to provide
   * authentication credentials for each individual channel. The actual CMS
   * account that the user authenticates with must be linked to the specified
   * YouTube content owner.
   * @return Google_Service_YouTube_CaptionListResponse
   */
  public function listCaptions($part, $videoId, $optParams = array())
  {
    $params = array('part' => $part, 'videoId' => $videoId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_YouTube_CaptionListResponse");
  }
  /**
   * Updates a caption track. When updating a caption track, you can change the
   * track's draft status, upload a new caption file for the track, or both.
   * (captions.update)
   *
   * @param string $part The part parameter serves two purposes in this operation.
   * It identifies the properties that the write operation will set as well as the
   * properties that the API response will include. Set the property value to
   * snippet if you are updating the track's draft status. Otherwise, set the
   * property value to id.
   * @param Google_Caption $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner Note: This parameter is intended
   * exclusively for YouTube content partners.
   *
   * The onBehalfOfContentOwner parameter indicates that the request's
   * authorization credentials identify a YouTube CMS user who is acting on behalf
   * of the content owner specified in the parameter value. This parameter is
   * intended for YouTube content partners that own and manage many different
   * YouTube channels. It allows content owners to authenticate once and get
   * access to all their video and channel data, without having to provide
   * authentication credentials for each individual channel. The actual CMS
   * account that the user authenticates with must be linked to the specified
   * YouTube content owner.
   * @opt_param bool sync Note: The API server only processes the parameter value
   * if the request contains an updated caption file.
   *
   * The sync parameter indicates whether YouTube should automatically synchronize
   * the caption file with the audio track of the video. If you set the value to
   * true, YouTube will automatically synchronize the caption track with the audio
   * track.
   * @return Google_Service_YouTube_Caption
   */
  public function update($part, Google_Service_YouTube_Caption $postBody, $optParams = array())
  {
    $params = array('part' => $part, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_YouTube_Caption");
  }
}
