<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\wiki\controllers;

use humhub\modules\content\search\SearchRequest;
use humhub\modules\wiki\helpers\Url;
use humhub\modules\wiki\models\WikiPage;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class OverviewController
 * @package humhub\modules\wiki\controllers
 */
class OverviewController extends BaseController
{
    /**
     * @return $this|void|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $homePage = $this->getHomePage();
        if ($homePage !== null) {
            return $this->redirect(Url::toWiki($homePage));
        }

        return $this->redirect(Url::toOverview($this->contentContainer));
    }


    /**
     * @return OverviewController|string|\yii\console\Response|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionListCategories($hideSidebarOnSmallScreen = false)
    {
        if (!$this->hasPages()) {
            return $this->render('no-pages', [
                'canCreatePage' => $this->canCreatePage(),
                'createPageUrl' => $this->contentContainer->createUrl('/wiki/page/edit'),
                'contentContainer' => $this->contentContainer,
            ]);
        }

        $views = ['last-edited'];
        if (!$hideSidebarOnSmallScreen) {
            array_unshift($views, 'list-categories');
        }

        return $this->renderSidebarContent($views, [
            'contentContainer' => $this->contentContainer,
            'canCreate' => $this->canCreatePage(),
            'dataProvider' => $this->getLastEditedDataProvider(),
            'hideSidebarOnSmallScreen' => $hideSidebarOnSmallScreen,
        ]);
    }

    public function actionLastEdited()
    {
        return $this->actionListCategories(true);
    }

    private function getLastEditedDataProvider(): ActiveDataProvider
    {

        $wikiPages11 = WikiPage::find()
            ->select(['wiki_page.*', 'COALESCE(forum_vote.total_vote, 0) AS total_vote'])
            ->leftJoin('forum_vote', 'wiki_page.id = forum_vote.forum_id')
            ->orderBy(['total_vote' => SORT_DESC, 'wiki_page.id' => SORT_ASC])
            ->asArray()
            ->all();


        return new ActiveDataProvider([
            'query' => WikiPage::find()
            ->select([
                'wiki_page.*',
                'COALESCE(forum_vote.total_vote, 0) AS total_vote',
                '(CASE 
                    WHEN content.updated_at >= DATE_SUB(NOW(), INTERVAL 2 DAY) THEN 1 
                    ELSE 0 
                END) AS is_recent'
            ])
            ->leftJoin('forum_vote', 'wiki_page.id = forum_vote.forum_id')
            ->orderBy([
                'is_recent' => SORT_DESC, // Ưu tiên bài trong 2 ngày đầu tiên
                'content.updated_at' => SORT_DESC, // Sau đó sắp xếp theo ngày cập nhật mới nhất
                'total_vote' => SORT_DESC, // Cuối cùng là tổng vote
                'wiki_page.id' => SORT_ASC // Để đảm bảo thứ tự ổn định
            ])
            ->contentContainer($this->contentContainer)
            ->readable(),
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'attributes' => [
                    'title',
                    'updated_at' => [
                        'asc' => ['content.updated_at' => SORT_ASC],
                        'desc' => ['content.updated_at' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ],
            ],
        ]);
    }

    public function actionSearch($keyword)
    {
        $searchRequest = new SearchRequest([
            'contentType' => WikiPage::class,
            'contentContainer' => [$this->contentContainer->guid],
            'pageSize' => 10,
        ]);

        if ($searchRequest->load(Yii::$app->request->get(), '') && $searchRequest->validate()) {
            $resultSet = Yii::$app->getModule('content')->getSearchDriver()->searchCached($searchRequest, 30);
        } else {
            $resultSet = null;
        }

        return $this->renderSidebarContent('search', [
            'contentContainer' => $this->contentContainer,
            'resultSet' => $resultSet,
        ]);
    }

    public function actionUpdateFoldingState(int $categoryId, int $state)
    {
        $this->updateFoldingState($categoryId, $state);
    }
}
