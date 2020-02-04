<?php

namespace App\Http\Controllers;

use App\Jobs\BooksContentMultiJob;
use App\Models\Books\BooksChapterModel;
use App\Models\Books\BooksContentModel;
use App\Models\Books\CollectionRuleModel;
use App\Repositories\CollectionRule\BookRule;

class TestController extends Controller
{
    public function index()
    {

        $html = file_get_contents('https://www.biquge.lu/book/22005/450343705.html');
        $pattern = '/<script[\s\S]*?<\/script>/i';
//        preg_match_all($pattern,$html,$match);
//        dd($match);

        $html = preg_replace($pattern, '', $html);

        dd($html);


        dd(123);
        $arr = BooksContentModel::query()->select('id')
            ->where('id', '>=', 585)
            ->get()->pluck('id')->all();
        $urls = BooksChapterModel::query()->select('from_url')->whereIn('id', $arr)
            ->get()->pluck('from_url')->all();
//        dd($urls);
        $rule = CollectionRuleModel::query()->where('id', 4)->first();
        $bookRule = unserialize($rule->rule_json);
        dispatch(new BooksContentMultiJob($bookRule, $urls))->onQueue('Content');

        dd();

        /**
         * @var BookRule $bookRule
         */
//        $bookRule = unserialize($rule->rule_json);
//        dd($bookRule->replaceTags);

        $contentArr = BooksContentModel::query()->where('id', '=', 585)->get();

        foreach ($contentArr as $contentModel) {
            $content = $contentModel->content;
            foreach ($bookRule->replaceTags as $tag) {
                $content = preg_replace($tag[0], $tag[1], $content);
            }
            $contentModel->update(['content' => $content]);
        }

        $content = BooksContentModel::query()->where('id', 758)->first()->content;

        $pattern = '/<p>.+?(天才一秒记住本站地址).+?<\/p>/is';
        $content = preg_replace($pattern, '', $content);

        $pattern = '/<p class="content_detail">/';
        $content = preg_replace($pattern, '<p>    ', $content);

        $pattern = '/<br>/';
        $content = preg_replace($pattern, '', $content);

        dd($content);
    }
}
