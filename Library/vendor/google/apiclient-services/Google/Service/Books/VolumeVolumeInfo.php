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

class Google_Service_Books_VolumeVolumeInfo extends Google_Collection
{
  protected $collection_key = 'industryIdentifiers';
  public $allowAnonLogging;
  public $authors;
  public $averageRating;
  public $canonicalVolumeLink;
  public $categories;
  public $contentVersion;
  public $description;
  protected $dimensionsType = 'Google_Service_Books_VolumeVolumeInfoDimensions';
  protected $dimensionsDataType = '';
  protected $imageLinksType = 'Google_Service_Books_VolumeVolumeInfoImageLinks';
  protected $imageLinksDataType = '';
  protected $industryIdentifiersType = 'Google_Service_Books_VolumeVolumeInfoIndustryIdentifiers';
  protected $industryIdentifiersDataType = 'array';
  public $infoLink;
  public $language;
  public $mainCategory;
  public $maturityRating;
  public $pageCount;
  public $previewLink;
  public $printType;
  public $printedPageCount;
  public $publishedDate;
  public $publisher;
  public $ratingsCount;
  public $readingModes;
  public $samplePageCount;
  protected $seriesInfoType = 'Google_Service_Books_Volumeseriesinfo';
  protected $seriesInfoDataType = '';
  public $subtitle;
  public $title;

  public function setAllowAnonLogging($allowAnonLogging)
  {
    $this->allowAnonLogging = $allowAnonLogging;
  }
  public function getAllowAnonLogging()
  {
    return $this->allowAnonLogging;
  }
  public function setAuthors($authors)
  {
    $this->authors = $authors;
  }
  public function getAuthors()
  {
    return $this->authors;
  }
  public function setAverageRating($averageRating)
  {
    $this->averageRating = $averageRating;
  }
  public function getAverageRating()
  {
    return $this->averageRating;
  }
  public function setCanonicalVolumeLink($canonicalVolumeLink)
  {
    $this->canonicalVolumeLink = $canonicalVolumeLink;
  }
  public function getCanonicalVolumeLink()
  {
    return $this->canonicalVolumeLink;
  }
  public function setCategories($categories)
  {
    $this->categories = $categories;
  }
  public function getCategories()
  {
    return $this->categories;
  }
  public function setContentVersion($contentVersion)
  {
    $this->contentVersion = $contentVersion;
  }
  public function getContentVersion()
  {
    return $this->contentVersion;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setDimensions(Google_Service_Books_VolumeVolumeInfoDimensions $dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setImageLinks(Google_Service_Books_VolumeVolumeInfoImageLinks $imageLinks)
  {
    $this->imageLinks = $imageLinks;
  }
  public function getImageLinks()
  {
    return $this->imageLinks;
  }
  public function setIndustryIdentifiers($industryIdentifiers)
  {
    $this->industryIdentifiers = $industryIdentifiers;
  }
  public function getIndustryIdentifiers()
  {
    return $this->industryIdentifiers;
  }
  public function setInfoLink($infoLink)
  {
    $this->infoLink = $infoLink;
  }
  public function getInfoLink()
  {
    return $this->infoLink;
  }
  public function setLanguage($language)
  {
    $this->language = $language;
  }
  public function getLanguage()
  {
    return $this->language;
  }
  public function setMainCategory($mainCategory)
  {
    $this->mainCategory = $mainCategory;
  }
  public function getMainCategory()
  {
    return $this->mainCategory;
  }
  public function setMaturityRating($maturityRating)
  {
    $this->maturityRating = $maturityRating;
  }
  public function getMaturityRating()
  {
    return $this->maturityRating;
  }
  public function setPageCount($pageCount)
  {
    $this->pageCount = $pageCount;
  }
  public function getPageCount()
  {
    return $this->pageCount;
  }
  public function setPreviewLink($previewLink)
  {
    $this->previewLink = $previewLink;
  }
  public function getPreviewLink()
  {
    return $this->previewLink;
  }
  public function setPrintType($printType)
  {
    $this->printType = $printType;
  }
  public function getPrintType()
  {
    return $this->printType;
  }
  public function setPrintedPageCount($printedPageCount)
  {
    $this->printedPageCount = $printedPageCount;
  }
  public function getPrintedPageCount()
  {
    return $this->printedPageCount;
  }
  public function setPublishedDate($publishedDate)
  {
    $this->publishedDate = $publishedDate;
  }
  public function getPublishedDate()
  {
    return $this->publishedDate;
  }
  public function setPublisher($publisher)
  {
    $this->publisher = $publisher;
  }
  public function getPublisher()
  {
    return $this->publisher;
  }
  public function setRatingsCount($ratingsCount)
  {
    $this->ratingsCount = $ratingsCount;
  }
  public function getRatingsCount()
  {
    return $this->ratingsCount;
  }
  public function setReadingModes($readingModes)
  {
    $this->readingModes = $readingModes;
  }
  public function getReadingModes()
  {
    return $this->readingModes;
  }
  public function setSamplePageCount($samplePageCount)
  {
    $this->samplePageCount = $samplePageCount;
  }
  public function getSamplePageCount()
  {
    return $this->samplePageCount;
  }
  public function setSeriesInfo(Google_Service_Books_Volumeseriesinfo $seriesInfo)
  {
    $this->seriesInfo = $seriesInfo;
  }
  public function getSeriesInfo()
  {
    return $this->seriesInfo;
  }
  public function setSubtitle($subtitle)
  {
    $this->subtitle = $subtitle;
  }
  public function getSubtitle()
  {
    return $this->subtitle;
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
