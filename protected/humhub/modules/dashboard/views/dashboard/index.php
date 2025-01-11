<?php

/**
 * @var \humhub\modules\user\models\User $contentContainer
 * @var bool $showProfilePostForm
 */

use humhub\modules\activity\widgets\ActivityStreamViewer;
use humhub\modules\dashboard\widgets\DashboardContent;
use humhub\modules\dashboard\widgets\Sidebar;
use humhub\widgets\FooterMenu;
use humhub\libs\Html;
use humhub\modules\user\widgets\Image;
use humhub\modules\user\widgets\PeopleActionButtons;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\Header;
use humhub\modules\space\widgets\Menu;
use humhub\modules\space\widgets\SpaceContent;
use humhub\modules\ui\view\components\View;
use humhub\modules\ui\icon\widgets\Icon;
use yii\helpers\Url;
use humhub\modules\space\permissions\CreatePrivateSpace;
use humhub\modules\space\permissions\CreatePublicSpace;

$manager = Yii::$app->user->permissionmanager;
$canCreateSpace = $manager->can(new CreatePublicSpace()) || $manager->can(new CreatePrivateSpace());

?>

<?= Html::beginContainer() ?>
<div class="container">
    <div class="row">

        <!-- Main Content -->

        <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
            <?= DashboardContent::widget([
                'contentContainer' => $contentContainer,
                'showProfilePostForm' => $showProfilePostForm
            ]);
            ?>

            <a id="load-more-button" href="#" class="btn btn-control btn-more" data-load-link="items-to-load.html"
                data-container="newsfeed-items-grid">
                <i class="fa fa-ellipsis-h"></i> <!-- Or use fa-ellipsis-v for vertical dots -->
            </a>
        </main>

        <!-- ... end Main Content -->
        <!-- Left Sidebar -->

        <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-12 col-12">
            <div class="ui-block rounded-[20px]">
                <!-- W-Weather -->

                <div class="widget w-wethear rounded-[20px]">
                    <a href="#" class="more"><svg class="olymp-three-dots-icon">
                            <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                        </svg></a>

                    <div class="wethear-now inline-items">
                        <div class="temperature-sensor">64°</div>
                        <div class="max-min-temperature">
                            <span>58°</span>
                            <span>76°</span>
                        </div>

                        <svg class="olymp-weather-partly-sunny-icon">
                            <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-partly-sunny-icon"></use>
                        </svg>
                    </div>

                    <div class="wethear-now-description">
                        <div class="climate">Partly Sunny</div>
                        <span>Real Feel: <span>67°</span></span>
                        <span>Chance of Rain: <span>49%</span></span>
                    </div>

                    <ul class="weekly-forecast">

                        <li>
                            <div class="day">sun</div>
                            <svg class="olymp-weather-sunny-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-sunny-icon"></use>
                            </svg>

                            <div class="temperature-sensor-day">60°</div>
                        </li>

                        <li>
                            <div class="day">mon</div>
                            <svg class="olymp-weather-partly-sunny-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-partly-sunny-icon">
                                </use>
                            </svg>
                            <div class="temperature-sensor-day">58°</div>
                        </li>

                        <li>
                            <div class="day">tue</div>
                            <svg class="olymp-weather-cloudy-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-cloudy-icon"></use>
                            </svg>

                            <div class="temperature-sensor-day">67°</div>
                        </li>

                        <li>
                            <div class="day">wed</div>
                            <svg class="olymp-weather-rain-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-rain-icon"></use>
                            </svg>

                            <div class="temperature-sensor-day">70°</div>
                        </li>

                        <li>
                            <div class="day">thu</div>
                            <svg class="olymp-weather-storm-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-storm-icon"></use>
                            </svg>

                            <div class="temperature-sensor-day">58°</div>
                        </li>

                        <li>
                            <div class="day">fri</div>
                            <svg class="olymp-weather-snow-icon">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-snow-icon"></use>
                            </svg>

                            <div class="temperature-sensor-day">68°</div>
                        </li>

                        <li>
                            <div class="day">sat</div>

                            <svg class="olymp-weather-wind-icon-header">
                                <use xlink:href="svg-icons/sprites/icons-weather.svg#olymp-weather-wind-icon-header">
                                </use>
                            </svg>

                            <div class="temperature-sensor-day">65°</div>
                        </li>

                    </ul>

                    <div class="date-and-place">
                        <h5 class="date">Saturday, March 26th</h5>
                        <div class="place">San Francisco, CA</div>
                    </div>

                </div>

                <!-- W-Weather -->
            </div>

            <div class="ui-block rounded-[20px]">

                <!-- W-Calendar -->

                <div class="w-calendar calendar-container">
                    <div class="calendar">
                        <header>
                            <h6 class="month">March 2017</h6>
                            <a class="calendar-btn-prev fas fa-angle-left" href="#"></a>
                            <a class="calendar-btn-next fas fa-angle-right" href="#"></a>
                        </header>
                        <table>
                            <thead>
                                <tr>
                                    <td>Mon</td>
                                    <td>Tue</td>
                                    <td>Wed</td>
                                    <td>Thu</td>
                                    <td>Fri</td>
                                    <td>Sat</td>
                                    <td>San</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-month="12" data-day="1">1</td>
                                    <td data-month="12" data-day="2" class="event-uncomplited event-complited">
                                        2
                                    </td>
                                    <td data-month="12" data-day="3">3</td>
                                    <td data-month="12" data-day="4">4</td>
                                    <td data-month="12" data-day="5">5</td>
                                    <td data-month="12" data-day="6">6</td>
                                    <td data-month="12" data-day="7">7</td>
                                </tr>
                                <tr>
                                    <td data-month="12" data-day="8">8</td>
                                    <td data-month="12" data-day="9">9</td>
                                    <td data-month="12" data-day="10" class="event-complited">10</td>
                                    <td data-month="12" data-day="11">11</td>
                                    <td data-month="12" data-day="12">12</td>
                                    <td data-month="12" data-day="13">13</td>
                                    <td data-month="12" data-day="14">14</td>
                                </tr>
                                <tr>
                                    <td data-month="12" data-day="15" class="event-complited-2">15</td>
                                    <td data-month="12" data-day="16">16</td>
                                    <td data-month="12" data-day="17">17</td>
                                    <td data-month="12" data-day="18">18</td>
                                    <td data-month="12" data-day="19">19</td>
                                    <td data-month="12" data-day="20">20</td>
                                    <td data-month="12" data-day="21">21</td>
                                </tr>
                                <tr>
                                    <td data-month="12" data-day="22">22</td>
                                    <td data-month="12" data-day="23">23</td>
                                    <td data-month="12" data-day="24">24</td>
                                    <td data-month="12" data-day="25">25</td>
                                    <td data-month="12" data-day="26">26</td>
                                    <td data-month="12" data-day="27">27</td>
                                    <td data-month="12" data-day="28" class="event-uncomplited">28</td>
                                </tr>
                                <tr>
                                    <td data-month="12" data-day="29">29</td>
                                    <td data-month="12" data-day="30">30</td>
                                    <td data-month="12" data-day="31">31</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="list">

                            <div id="accordion-1" role="tablist" aria-multiselectable="true" class="day-event"
                                data-month="12" data-day="2">
                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title">TODAY’S EVENTS</h6>
                                </div>
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne-1">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">9:00am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne-1"
                                                aria-expanded="true" aria-controls="collapseOne-1">
                                                Breakfast at the Agency<svg class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseOne-1" class="collapse" role="tabpanel">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>
                                        <div class="place inline-items">
                                            <svg class="olymp-add-a-place-icon">
                                                <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon">
                                                </use>
                                            </svg>
                                            <span>Daydreamz Agency</span>
                                        </div>

                                        <ul class="friends-harmonic inline-items">
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic5.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic10.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic7.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic8.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic2.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li class="with-text">
                                                Will Assist
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" role="tab" id="headingTwo-1">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">9:00am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo-1"
                                                aria-expanded="true" aria-controls="collapseTwo-1">
                                                Send the new “Olympus” project files to the Agency<svg
                                                    class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseTwo-1" class="collapse" role="tabpanel">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>
                                    </div>

                                </div>

                                <div class="card">
                                    <div class="card-header" role="tab" id="headingThree-1">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">6:30am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                href="#" aria-expanded="false">
                                                Take Querty to the Veterinarian
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="place inline-items">
                                        <svg class="olymp-add-a-place-icon">
                                            <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon"></use>
                                        </svg>
                                        <span>Daydreamz Agency</span>
                                    </div>
                                </div>

                                <a href="#" class="check-all">Check all your Events</a>
                            </div>

                            <div id="accordion-2" role="tablist" aria-multiselectable="true" class="day-event"
                                data-month="12" data-day="10">
                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title">TODAY’S EVENTS</h6>
                                </div>
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne-2">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">9:00am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne-2"
                                                aria-expanded="true" aria-controls="collapseOne-2">
                                                Breakfast at the Agency<svg class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseOne-2" class="collapse" role="tabpanel">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>
                                        <div class="place inline-items">
                                            <svg class="olymp-add-a-place-icon">
                                                <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon">
                                                </use>
                                            </svg>
                                            <span>Daydreamz Agency</span>
                                        </div>

                                        <ul class="friends-harmonic inline-items">
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic5.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic10.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic7.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic8.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic2.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li class="with-text">
                                                Will Assist
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <a href="#" class="check-all">Check all your Events</a>
                            </div>

                            <div id="accordion-3" role="tablist" aria-multiselectable="true" class="day-event"
                                data-month="12" data-day="15">
                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title">TODAY’S EVENTS</h6>
                                </div>
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne-3">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">9:00am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne-3"
                                                aria-expanded="true" aria-controls="collapseOne-3">
                                                Breakfast at the Agency<svg class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseOne-3" class="collapse" role="tabpanel">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>

                                        <div class="place inline-items">
                                            <svg class="olymp-add-a-place-icon">
                                                <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon">
                                                </use>
                                            </svg>
                                            <span>Daydreamz Agency</span>
                                        </div>

                                        <ul class="friends-harmonic inline-items">
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic5.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic10.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic7.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic8.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic2.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li class="with-text">
                                                Will Assist
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="card">
                                    <div class="card-header" role="tab" id="headingTwo-3">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">12:00pm</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo-3"
                                                aria-expanded="true" aria-controls="collapseTwo-3">
                                                Send the new “Olympus” project files to the Agency<svg
                                                    class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseTwo-3" class="collapse" role="tabpanel">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>
                                    </div>

                                </div>

                                <div class="card">
                                    <div class="card-header" role="tab" id="headingThree-3">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">6:30pm</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                href="#" aria-expanded="false">
                                                Take Querty to the Veterinarian
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="place inline-items">
                                        <svg class="olymp-add-a-place-icon">
                                            <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon"></use>
                                        </svg>
                                        <span>Daydreamz Agency</span>
                                    </div>
                                </div>

                                <a href="#" class="check-all">Check all your Events</a>
                            </div>

                            <div id="accordion-4" role="tablist" aria-multiselectable="true" class="day-event"
                                data-month="12" data-day="28">
                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title">TODAY’S EVENTS</h6>
                                </div>
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne-4">
                                        <div class="event-time">
                                            <span class="circle"></span>
                                            <time datetime="2004-07-24T18:18">9:00am</time>
                                            <a href="#" class="more"><svg class="olymp-three-dots-icon">
                                                    <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon">
                                                    </use>
                                                </svg></a>
                                        </div>
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne-4"
                                                aria-expanded="true" aria-controls="collapseOne-4">
                                                Breakfast at the Agency<svg class="olymp-dropdown-arrow-icon">
                                                    <use
                                                        xlink:href="svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon">
                                                    </use>
                                                </svg>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseOne-4" class="collapse" role="tabpanel"
                                        aria-labelledby="headingOne-4">
                                        <div class="card-body">
                                            Hi Guys! I propose to go a litle earlier at the agency to have breakfast and
                                            talk a little more about the new design project we have been working on.
                                            Cheers!
                                        </div>
                                        <div class="place inline-items">
                                            <svg class="olymp-add-a-place-icon">
                                                <use xlink:href="svg-icons/sprites/icons.svg#olymp-add-a-place-icon">
                                                </use>
                                            </svg>
                                            <span>Daydreamz Agency</span>
                                        </div>

                                        <ul class="friends-harmonic inline-items">
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic5.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic10.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic7.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic8.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/friend-harmonic2.jpg"
                                                        alt="friend">
                                                </a>
                                            </li>
                                            <li class="with-text">
                                                Will Assist
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <a href="#" class="check-all">Check all your Events</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ... end W-Calendar -->
            </div>


            <div class="ui-block rounded-[20px]">
                <div class="ui-block-title">
                    <h6 class="title">Pages You May Like</h6>
                    <a href="#" class="more"><svg class="olymp-three-dots-icon">
                            <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                        </svg></a>
                </div>

                <!-- W-Friend-Pages-Added -->

                <ul class="widget w-friend-pages-added notification-list friend-requests">
                    <?php
                    foreach ($spaces as $space) { ?>
                        <li class="inline-items">
                            <div class="author-thumb pt-[10px]">
                                <?php echo $space->getProfileImage()->render(30, ['class' => 'space-avatar', 'id' => 'space-account-image']);
                                ?>
                            </div>
                            <div class="notification-event">
                                <a href="http://localhost/social-media/index.php?r=space%2Fspace&cguid=<?= $space->guid ?>"
                                    class="h6 notification-friend"> <?php echo $space->name ?> </a>
                                <span class="chat-message-item"> <?php echo $space->description ?> </span>
                            </div>
                            <span class="notification-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="ADD TO YOUR FAVS">
                                <a href="#">
                                    <svg class="olymp-star-icon">
                                        <use xlink:href="svg-icons/sprites/icons.svg#olymp-star-icon"></use>
                                    </svg>
                                </a>
                            </span>

                        </li>
                        <?php
                    }
                    ?>
                    <li class="inline-items">
                        <a href="http://localhost/social-media/index.php?r=space%2Fspaces" class="text-center opacity-80">
                            More Spaces
                        </a>

                    </li>

                </ul>

                <!-- .. end W-Friend-Pages-Added -->
            </div>
        </aside>

        <!-- ... end Left Sidebar -->


        <!-- Right Sidebar -->

        <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-12">

            <div class="ui-block rounded-[20px]">
                <div class="ui-block-title">
                    <h6 class="title text-[14px] text-black">Friend Suggestions</h6>
                    <a href="#" class="more"><svg class="olymp-three-dots-icon">
                            <use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                        </svg></a>
                </div>
                <!-- W-Action -->
                <ul class="widget w-friend-pages-added notification-list friend-requests">

                    <?php
                    function getRandomElements($array, $count)
                    {
                        // Kiểm tra nếu số phần tử của mảng nhỏ hơn hoặc bằng số lượng cần lấy
                        if (count($array) <= $count) {
                            return $array; // Trả về toàn bộ mảng nếu nhỏ hơn hoặc bằng $count
                        }

                        // Lấy $count phần tử ngẫu nhiên từ mảng
                        $randomKeys = array_rand($array, $count);

                        // Nếu chỉ chọn 1 phần tử, array_rand() trả về 1 khóa, cần chuyển nó thành mảng
                        if (!is_array($randomKeys)) {
                            $randomKeys = [$randomKeys];
                        }

                        // Trả về các phần tử từ mảng gốc theo các khóa ngẫu nhiên
                        return array_intersect_key($array, array_flip($randomKeys));
                    }

                    $result = getRandomElements($this->params['users'], 5);
                    foreach ($result as $user) { ?>
                        <li class="inline-items" style="padding: 20px">
                            <div class="author-thumb mt-[10px]">
                                <?php echo Image::widget([
                                    'user' => $user,
                                    'link' => false,
                                    'width' => 30,
                                    'htmlOptions' => ['id' => 'user-account-image'],
                                    'showSelfOnlineStatus' => true,
                                ]);
                                ?>
                            </div>
                            <div class="notification-event ">
                                <a href="index.php?r=user%2Fprofile%2Fhome&cguid=<?= $user->guid ?>"
                                    class="h6 notification-friend"><?php echo $user->displayName; ?></a>
                                <span class="chat-message-item">8 Friends in Common</span>
                            </div>
                            <!-- <span class="notification-icon color-red"> -->
                            <!-- <a href="#" class="accept-request mt-[10px]">
                                    <span class="without-text">
                                        Follow
                                    </span>
                                </a>
                            </span>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="inline-items text-center hover:pointer">
                        <div class="text-[16px] text-black font-semibold items-center ">
                            <a class="opacity-60 text-[16px] text-black font-bold"
                                href="index.php?r=user%2Fpeople">More people</a>
                        </div>
                    </li>
                </ul>

                <!-- ... end W-Action -->
            </div>


        </aside>

        <!-- ... end Left Sidebar -->


        <!-- Right Sidebar -->

        <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-12">

        <div class="ui-block rounded-[20px]">
                <div class="ui-block-title flex justify-between">
                    <h6 class="title flex items-center">Spaces</h6>
                        <?php if ($canCreateSpace) : ?>
                        <li>
                            <div class="dropdown-footer">
                                <a href="#" class="btn btn-info py-[1px] px-3 text-[12px] bg-[]" data-action-click="ui.modal.load"
                                data-action-url="<?= Url::to(['/space/create/create']) ?>">
                                    <i class="fa fa-plus m-0 text-[10px]"></i>
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                </div>

                <!-- W-Friend-Pages-Added -->

                <ul class="widget w-friend-pages-added notification-list friend-requests">
                    <?php

                    $result_space = getRandomElements($spaces, 3);
                    foreach ($result_space as $space) { ?>
                        <li class="inline-items">
                            <div class="author-thumb pt-[10px]">
                                <?php echo $space->getProfileImage()->render(30, ['class' => 'space-avatar', 'id' => 'space-account-image']);
                                ?>
                            </div>
                            <div class="notification-event">
                                <a href="index.php?r=space%2Fspace&cguid=<?= $space->guid ?>"
                                    class="h6 notification-friend"> <?php echo $space->name ?> </a>
                                <span class="chat-message-item"> <?php echo $space->description ?> </span>
                            </div>
                            <span class="notification-icon" data-toggle="tooltip" data-placement="top"
                                data-original-title="ADD TO YOUR FAVS">
                                <a href="#">
                                    <svg class="olymp-star-icon">
                                        <use xlink:href="svg-icons/sprites/icons.svg#olymp-star-icon"></use>
                                    </svg>
                                </a>
                            </span>

                        </li>
                        <?php
                    }
                    ?>
                    <li class="inline-items">
                        <a href="index.php?r=space%2Fspaces" class="text-center text-[12px] text-center opacity-80">
                            <p class="text-center"> More Spaces</p>
                        </a>

                    </li>

                </ul>

                <!-- .. end W-Friend-Pages-Added -->
            </div>
            <div class="ui-block rounded-[20px] overflow-hidden">



                <!-- W-Activity-Feed -->

                <!-- <ul class="widget w-activity-feed notification-list">
                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar49-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Marina Polson</a> commented on Jason Mark’s <a
                                href="#" class="notification-link">photo.</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">2 mins ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar9-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Jake Parker </a> liked Nicholas Grissom’s <a
                                href="#" class="notification-link">status update.</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">5 mins ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar50-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Mary Jane Stark </a> added 20 new photos to her
                            <a href="#" class="notification-link">gallery album.</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">12 mins ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar51-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Nicholas Grissom </a> updated his profile <a
                                href="#" class="notification-link">photo</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar48-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Marina Valentine </a> commented on Chris
                            Greyson’s <a href="#" class="notification-link">status update</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar52-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Green Goo Rock </a> posted a <a href="#"
                                class="notification-link">status update</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar10-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Elaine Dreyfuss </a> liked your <a href="#"
                                class="notification-link">blog post</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">2 hours ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar10-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Elaine Dreyfuss </a> commented on your <a
                                href="#" class="notification-link">blog post</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">2 hours ago</time></span>
                        </div>
                    </li>

                    <li>
                        <div class="author-thumb">
                            <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/avatar53-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Bruce Peterson </a> changed his <a href="#"
                                class="notification-link">profile picture</a>.
                            <span class="notification-date"><time class="entry-date updated"
                                    datetime="2004-07-24T18:18">15 hours ago</time></span>
                        </div>
                    </li>
                </ul> -->

                <?= Sidebar::widget([
                    'widgets' => [
                        [
                            ActivityStreamViewer::class,
                            ['streamAction' => '/dashboard/dashboard/activity-stream'],
                        ]
                    ]
                ]);
                ?>
                <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>

                <!-- .. end W-Activity-Feed -->
            </div>


            <div class="ui-block">


                <!-- W-Action -->

                <!-- <div class="widget w-action">

                    <img src="<?= Yii::getAlias('@web') ?>/themes/HumHub/img/logo.png" alt="Olympus">
                    <div class="content">
                        <h4 class="title">OLYMPUS</h4>
                        <span>THE BEST SOCIAL NETWORK THEME IS HERE!</span>
                        <a href="01-LandingPage.html" class="btn btn-bg-secondary btn-md">Register Now!</a>
                    </div>
                </div> -->

                <!-- ... end W-Action -->
            </div>

        </aside>

        <!-- ... end Right Sidebar -->

    </div>
</div>

<?= Html::endContainer() ?>
