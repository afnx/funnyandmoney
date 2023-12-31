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
 * Service definition for ToolResults (v1beta3).
 *
 * <p>
 * Reads and publishes results from Cloud Test Lab.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/cloud-test-lab/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Service_ToolResults extends Google_Service
{
  /** View and manage your data across Google Cloud Platform services. */
  const CLOUD_PLATFORM =
      "https://www.googleapis.com/auth/cloud-platform";

  public $projects;
  public $projects_histories;
  public $projects_histories_executions;
  public $projects_histories_executions_steps;
  public $projects_histories_executions_steps_thumbnails;
  
  /**
   * Constructs the internal representation of the ToolResults service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client)
  {
    parent::__construct($client);
    $this->rootUrl = 'https://www.googleapis.com/';
    $this->servicePath = 'toolresults/v1beta3/projects/';
    $this->version = 'v1beta3';
    $this->serviceName = 'toolresults';

    $this->projects = new Google_Service_ToolResults_ProjectsResource(
        $this,
        $this->serviceName,
        'projects',
        array(
          'methods' => array(
            'getSettings' => array(
              'path' => '{projectId}/settings',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'initializeSettings' => array(
              'path' => '{projectId}:initializeSettings',
              'httpMethod' => 'POST',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->projects_histories = new Google_Service_ToolResults_ProjectsHistoriesResource(
        $this,
        $this->serviceName,
        'histories',
        array(
          'methods' => array(
            'create' => array(
              'path' => '{projectId}/histories',
              'httpMethod' => 'POST',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'requestId' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'get' => array(
              'path' => '{projectId}/histories/{historyId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{projectId}/histories',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'filterByName' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
                'pageSize' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),
          )
        )
    );
    $this->projects_histories_executions = new Google_Service_ToolResults_ProjectsHistoriesExecutionsResource(
        $this,
        $this->serviceName,
        'executions',
        array(
          'methods' => array(
            'create' => array(
              'path' => '{projectId}/histories/{historyId}/executions',
              'httpMethod' => 'POST',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'requestId' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'get' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{projectId}/histories/{historyId}/executions',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageSize' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'patch' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'requestId' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),
          )
        )
    );
    $this->projects_histories_executions_steps = new Google_Service_ToolResults_ProjectsHistoriesExecutionsStepsResource(
        $this,
        $this->serviceName,
        'steps',
        array(
          'methods' => array(
            'create' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps',
              'httpMethod' => 'POST',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'requestId' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'get' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps/{stepId}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'stepId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),'list' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageSize' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'patch' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps/{stepId}',
              'httpMethod' => 'PATCH',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'stepId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'requestId' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),'publishXunitXmlFiles' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps/{stepId}:publishXunitXmlFiles',
              'httpMethod' => 'POST',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'stepId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->projects_histories_executions_steps_thumbnails = new Google_Service_ToolResults_ProjectsHistoriesExecutionsStepsThumbnailsResource(
        $this,
        $this->serviceName,
        'thumbnails',
        array(
          'methods' => array(
            'list' => array(
              'path' => '{projectId}/histories/{historyId}/executions/{executionId}/steps/{stepId}/thumbnails',
              'httpMethod' => 'GET',
              'parameters' => array(
                'projectId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'historyId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'executionId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'stepId' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
                'pageSize' => array(
                  'location' => 'query',
                  'type' => 'integer',
                ),
                'pageToken' => array(
                  'location' => 'query',
                  'type' => 'string',
                ),
              ),
            ),
          )
        )
    );
  }
}
