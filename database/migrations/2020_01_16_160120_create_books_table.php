<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    DB::statement('
      CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "标题",
  `introduce` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "简介",
  `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "缩略图",
  `zhangjie` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "章节",
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "作者",
  `words_count` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "字数",
  `level` tinyint(3) unsigned NOT NULL 0 COMMENT "等级",
  `follow` int(10) unsigned NOT NULL 0 COMMENT "关注人数",
  `hits` bigint(20) unsigned NOT NULL 0 COMMENT "浏览次数",
  `status` tinyint(1) unsigned NOT NULL 1 COMMENT "状态",
  `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "来源",
  `from_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "来源网址",
  `from_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "来源网址hash,用来判断是否插入过",
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_fromhash_unique` (`from_hash`),
  KEY `books_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
      ');
    DB::statement('
     CREATE TABLE `books_chapter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `books_id` int(11) unsigned NOT NULL 0 COMMENT "ID",
  `chapter_index` int(11) unsigned NOT NULL 0 COMMENT "序号",
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "标题",
  `hits` bigint(20) unsigned NOT NULL 0 COMMENT "浏览次数",
  `status` tinyint(1) unsigned NOT NULL 1 COMMENT "状态",
  `from_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "来源链接",
  `from_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL "" COMMENT "来源链接hash值",
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_detail_fromhash_unique` (`from_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
      ');
    DB::statement('
      CREATE TABLE `books_content` (
  `id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT "小说内容",
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
      ');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('books');
    Schema::dropIfExists('books_chapter');
    Schema::dropIfExists('books_content');
  }
}
