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
        $userId = Yii::$app->user->id;
        
        return new ActiveDataProvider([
            'query' => WikiPage::find()
                ->select([
                    'wiki_page.*',
                    'COALESCE(forum_vote.total_vote, 0) AS total_vote',
                    'COALESCE(vote.is_love, 0) AS is_love',
                    '(CASE 
                        WHEN content.updated_at >= DATE_SUB(NOW(), INTERVAL 2 DAY) THEN 1 
                        ELSE 0 
                    END) AS is_recent'
                ])
                ->leftJoin('forum_vote', 'wiki_page.id = forum_vote.forum_id')
                ->leftJoin('vote', 'wiki_page.id = vote.forum_id AND vote.user_id = :userId', [':userId' => $userId])
                ->orderBy([
                    'is_love' => SORT_DESC, // Ưu tiên bài user đã yêu thích trước
                    'content.updated_at' => SORT_DESC, // Tiếp theo là bài mới nhất
                    'total_vote' => SORT_DESC, // Sau đó là bài có nhiều vote
                    'wiki_page.id' => SORT_ASC // Đảm bảo thứ tự ổn định
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
    // $wikiPages11 = WikiPage::find()
    // ->select(['wiki_page.*', 'COALESCE(forum_vote.total_vote, 0) AS total_vote'])
    // ->leftJoin('forum_vote', 'wiki_page.id = forum_vote.forum_id')
    // ->orderBy(['total_vote' => SORT_DESC, 'wiki_page.id' => SORT_ASC])
    // ->asArray()
    // ->all();
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
