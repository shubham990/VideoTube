<?php
require_once("ProfileData.php");


class ProfileGenerator
{
  private $con, $userLoggedInObj, $profileData;

  public function __construct($con, $userLoggedInObj, $profileUsername)
  {
    $this->con = $con;
    $this->userLoggedInObj = $userLoggedInObj;
    $this->profileData = new ProfileData($con, $profileUsername);
  }

  public function create()
  {
    $profileUsername = $this->profileData->getProfileUsername();

    if (!$this->profileData->userExists()) {
      return "User does not exist";
    }

    $coverPhotoSection = $this->createCoverPhotoSection();
    $headerSection = $this->createHeaderSection();
    $tabsSection = $this->createTabsSection();
    $contentSection = $this->createContentSection();

    return "<div class='profileContainer'>
              $coverPhotoSection
              $headerSection
              $tabsSection
              $contentSection
            </div>";
  }


  private function createCoverPhotoSection()
  {
    $coverPhotoSrc = $this->profileData->getCoverPhoto();
    $name = $this->profileData->getProfileUserFullName();

    return "<div class='coverPhotoContainer'>
              <img src=\"$coverPhotoSrc\" class='coverPhoto' alt='Cover Photo'>
              <span class='channelName'>$name</span>
            </div>";
  }
  private function createHeaderSection()
  {
    $profileImage = $this->profileData->getProfilePic();
    $name = $this->profileData->getProfileUserFullName();
    $subCount = $this->profileData->getSubscriberCount();

    $button = $this->createHeaderButton();

    return "<div class='profileHeader'>
              <div class='userInfoContainer'>
                <img class='profileImage' src='$profileImage'>
                <div class='userInfo'>
                  <span class='title'>$name</span>
                  <span class='subscriberCount'>$subCount subscribers</span>
                </div>
              </div>
              <div class='buttonContainer'>
                <div class='buttonItem'>
                  $button
                </div>
              </div>
            </div>";
  }
  private function createTabsSection()
  {
    return "<ul class='nav nav-tabs'  role='tablist'>
              <li class='nav-item'>
                <a class='nav-link active' id='videos-tab' data-toggle='tab' href='#videos' role='tab' aria-controls='about' aria-selected='true'>VIDEOS</a>
              </li>
              <li class='nav-item'>
                <a class='nav-link' id='about-tab' data-toggle='tab' href='#about' role='tab' aria-controls='about' aria-selected='false'>ABOUT</a>
              </li>
            </ul>";
  }
  private function createContentSection()
  {
    $videos = $this->profileData->getUserVideos($this->userLoggedInObj);
    if (sizeof($videos) > 0) {
      $videoGrid = new VideoGrid($this->con, $this->userLoggedInObj);
      $videoGridHtml = $videoGrid->create($videos, null, false);
    } else {
      $videoGridHtml = "<span>This user has no videos to show</span>";
    }

    $aboutSection = $this->createAboutSection();

    return "<div class='tab-content channelContent'>
              <div class='tab-pane fade show active' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
                $videoGridHtml
              </div>
              <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
                $aboutSection
              </div>
            </div>";
  }

  private function createHeaderButton()
  {
    if ($this->userLoggedInObj->getUserName() == $this->profileData->getProfileUsername()) {
      return "";
    }
    return ButtonProvider::createSubscriberButton($this->con, $this->profileData->getProfileUserObj(), $this->userLoggedInObj);
  }

  private function createAboutSection()
  {
    $html = "<div class='section'>
              <div class='title'>
                <span>Details</span>
              </div>
              <div class='values'>";

    $details = $this->profileData->getAllUserDetails();

    foreach ($details as $key => $value) {
      $html .= "<span>$key: $value</span>";
    }
    $html .= "</div></div>";
    return $html;
  }
}
